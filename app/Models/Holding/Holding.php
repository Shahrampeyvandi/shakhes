<?php

namespace App\Models\Holding;

use Illuminate\Database\Eloquent\Model;
use App\Models\Namad\Namad;
use Illuminate\Support\Facades\DB;

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

            // $array[$name][$count]['symbol'] = $namad->symbol;
            // $array[$name][$count]['name'] = $namad->name;
            // $array[$name][$count]['amount_percent'] = $namad->pivot->amount_percent;
            //  $array[$name][$count]['amount_value'] = $namad->pivot->amount_value;
            // $array[$name][$count]['change'] = $namad->pivot->change;

            // حساب کردن ارزش پرتفوی شرکت

            // today
            $last_price_value = count($namad->dailyReports) ? $namad->dailyReports()->latest()->first()->last_price_value : 0;
            $portfoy +=   $namad->pivot->amount_value * $last_price_value;

            // yesterday
            $yesterday = $namad->dailyReports()->whereDate('created_at', date('Y-m-d', strtotime("-1 days")))->latest()->first();
            if (!is_null($yesterday)) {
                $last_price_value_yesterday =  $yesterday->last_price_value;
            } else {
                $last_price_value_yesterday = 0;
            }
            $yesterday_portfoy +=  $namad->pivot->amount_value * $last_price_value_yesterday;


            $count++;
        }

        return [$portfoy, $yesterday_portfoy];
    }

    public function showPercentNamads($id)
    {
        
        $holding_namads =  DB::table('holdings_namads')->whereHolding_id($id)->get();
        $all=[];
        foreach ($holding_namads as $key => $namad) {
            $id = $this->name;
            $name = Namad::where('id',$id)->first()->name;
            $array['name'] = $name;
            $array['amount_percent'] = $namad->amount_percent;
            $array['amount_value'] = $namad->amount_value;
            $array['change'] = $namad->change;
            $all[]=$array;
        }

        return $all;
    }
}
