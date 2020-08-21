<?php

namespace App\Models\Holding;

use App\Models\Namad\Namad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Holding extends Model
{
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
            $last_price_value = Cache::get($namad->id)['pl'] ;
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

    public function showPercentNamads($holdingid,$namadobj)
    {
       
        
       $holding_namads =  DB::table('holdings_namads')->where('holding_id',$holdingid)->get();
       
        $all = [];
        $total = 0;

        // محاسبه جمع تعداد سهام
        foreach ($holding_namads as $key => $pivot) {
             $namad_ = Namad::where('id', $pivot->namad_id)->first();
            $pl = Cache::get($namad_->id)['pl'];
            $total += $pivot->amount_value * $pl;
        }

        // میزان تغییر و قیمت هر سهم
        foreach ($holding_namads as $key => $pivot) {
            $namad_ = Namad::where('id', $pivot->namad_id)->first();
            $count = $pivot->amount_value * Cache::get($namad_->id)['pl'];
            $array['symbol'] = $namad_->symbol;
            $array['name'] = $namad_->name;
            $array['amount_percent'] = number_format((float)(($count * 100) / $total), 1, '.', '');
            $array['price'] = Cache::get($namad_->id)['pl'];
            $array['change'] = Cache::get($namad_->id)['final_price_percent'];
            $all[] = $array;
        }

        return $all;
    }
}
