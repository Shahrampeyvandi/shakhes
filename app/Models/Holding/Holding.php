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

    public static function GetPortfoyAndYesterdayPortfoy($holding_obj)
    {

        $count = 1;
        $portfoy = 0;
        $yesterday_portfoy = 0;
        foreach ($holding_obj->namads as $key => $namad) {


            // حساب کردن ارزش پرتفوی شرکت
            // today
            $last_price_value = Cache::get($namad->id)['pl'];
            $portfoy +=   $namad->pivot->amount_value * $last_price_value;


            // yesterday
            $yesterday = $namad->dailyReports()->whereDate('created_at', date('Y-m-d', strtotime("-1 days")))->latest()->first();
            if (!is_null($yesterday)) {
                $last_price_value_yesterday =  $yesterday->pl;
            } else {
                $last_price_value_yesterday = 0;
            }
            $yesterday_portfoy +=  $namad->pivot->amount_value * $last_price_value_yesterday;
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

    public function getMarketValue()
    {
        $namads = $this->namads;
        $sum_market_value = 0;
        foreach ($namads as $key => $namad) {
            $sum_market_value += $namad->pivot->amount_value * (int)Cache::get($namad->id)['pc'];
        }
        return $sum_market_value;
    }

    public function save_portfoy()
    {
        $portfoy = $this->getMarketValue();
        static::whereId($this->id)->update([
            'portfoy' => $portfoy
        ]);
    }
}
