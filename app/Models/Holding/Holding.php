<?php

namespace App\Models\Holding;

use App\Models\Namad\Namad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Holding extends Model
{
    protected $with = 'namads';
    public function namads()
    {
        return $this->belongsToMany(Namad::class, 'holdings_namads')->withPivot(['amount_percent', 'amount_value', 'change']);
    }
    public function format($number,$lang = 'fa')
    {
       
        if ($number > 0 &&  $number < 1000000) {
            return number_format($number);
        } elseif ($number > 1000000 &&  $number < 1000000000) {
           $number = number_format($number / 1000000,2,'.','') + 0;
           if($lang == 'fa') {
               $label = ' میلیون';
           }else{
               $label = ' M';
           }
            return $number = number_format($number, 2) . $label;
        } elseif ($number > 1000000000) {
           $number =  number_format($number / 1000000000,2,'.','') + 0;
           if($lang == 'fa') {
               $label = ' میلیارد';
           }else{
               $label = ' B';
           }
            return  $number = number_format($number, 2) . $label;
            
        }
    }

    public static function GetPortfoyAndYesterdayPortfoy($holding_obj)
    {

        $count = 1;
        $portfoy = 0;
        $yesterday_portfoy = 0;
        foreach ($holding_obj->namads as $key => $namad) {


            // حساب کردن ارزش پرتفوی شرکت
            // today
            $last_price_value = Cache::get($namad->id)['pl'];
            $portfoy +=   (int)$namad->pivot->amount_value * $last_price_value;


            // yesterday
            $yesterday = $namad->dailyReports()->whereDate('created_at', date('Y-m-d', strtotime("-1 days")))->latest()->first();
            if (!is_null($yesterday)) {
                $last_price_value_yesterday =  $yesterday->pl;
            } else {
                $last_price_value_yesterday = 0;
            }
            $yesterday_portfoy +=  (int)$namad->pivot->amount_value * $last_price_value_yesterday;
            $count++;
        }

        return [$portfoy, $yesterday_portfoy];
    }

    public function showPercentNamads($holdingid, $namadobj)
    {


        $holding_namads =  DB::table('holdings_namads')->where('holding_id', $holdingid)->get();

        $all = [];
        $total = 0;

        // محاسبه جمع تعداد سهام
        foreach ($holding_namads as $key => $pivot) {
            $namad_ = Namad::where('id', $pivot->namad_id)->first();
            if ($namad_) {
                $pl = Cache::get($pivot->namad_id)['pc'];
                $total += $pivot->amount_value * $pl;
            }
        }

        // میزان تغییر و قیمت هر سهم
        foreach ($holding_namads as $key => $pivot) {
            $namad_ = Namad::where('id', $pivot->namad_id)->first();

            if ($namad_) {
                $count = $pivot->amount_value * Cache::get($pivot->namad_id)['pc'];
                $array['symbol'] = $namad_->symbol;
                $array['name'] = $namad_->name;
                $array['amount_percent'] = number_format((float)(($count * 100) / $total), 1, '.', '');
                $array['final_price_value'] = Cache::get($pivot->namad_id)['pc'];
                $array['last_price_percent'] = isset(Cache::get($pivot->namad_id)['pc_change_percent']) ? Cache::get($pivot->namad_id)['pc_change_percent'] : 0;
                $array['status'] = Cache::get($pivot->namad_id)['status'];
                $all[] = $array;
            }
        }

        return $all;
    }

    public function namadsResource()
    {
        $res = [];
        foreach ($this->namads()->get() as $key => $namad) {
            $res[] =  [
                'id' => $namad->id,
                'symbol' => Cache::get($namad->id)['symbol'],
                'name' => Cache::get($namad->id)['name'],
                'final_price_value' => Cache::get($namad->id)['final_price_value'],
                'final_price_percent' => Cache::get($namad->id)['final_price_percent'],
                'final_price_change' => Cache::get($namad->id)['last_price_change'],
                'final_price_status' => Cache::get($namad->id)['last_price_status'] ? '+' : '-',
                'namad_status' => Cache::get($namad->id)['namad_status'],
                'amount_value' => $this->format($namad->pivot->amount_value),
                'amount_percent' => $namad->pivot->amount_percent ? number_format($namad->pivot->amount_percent,2) : 0
            ];
        }

        return $res;
    }

    public function getMarketValue($index='pc')
    {
        if (Cache::has('marketvalue-'. $index . '-' . $this->id)) {
            return   Cache::get('marketvalue-'. $index . '-' . $this->id);
        }

        $namads = $this->namads()->get();
        $sum_market_value = [];
        foreach ($namads as $key => $namad) {
            $sum_market_value[] = (int)$namad->pivot->amount_value * (int)Cache::get($namad->id)[$index];
        }
        Cache::put('marketvalue-'. $index . '-' . $this->id,array_sum($sum_market_value),60*5);
        return array_sum($sum_market_value);
    }

    public function save_portfoy()
    {
        $portfoy = $this->getMarketValue();


        static::whereId($this->id)->update([
            'portfoy' => $portfoy
        ]);
    }

    public function change_percent()
    {

        $portfoy = $this->getMarketValue();
        $yesterday_portfoy = $this->getMarketValue('py');

       return $percent = number_format((($portfoy - $yesterday_portfoy) / $yesterday_portfoy) * 100, 2);
    }

    public function get_history_data($inscode, $days)
    {
        $array = [];
        $ch = curl_init("https://members.tsetmc.com/tsev2/data/InstTradeHistory.aspx?i=$inscode&Top=$days&A=0");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $result = curl_exec($ch);
        $day_data = explode(';', $result);
        foreach ($day_data as $key => $value) {
            $data = explode('@', $value);

            if (count($data) == 10) {
                $pl = substr($data[4], 0, -3);
                $pc = substr($data[3], 0, -3);


                $array[] = [
                    'pl' => $pl,
                    'pc' => $pc,

                ];
            }
        }
        return $array;
    }
}
