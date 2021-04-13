<?php

namespace App\Http\Schedules;

use Exception;
use App\Models\Namad\Namad;

use Illuminate\Support\Facades\Cache;


class FilterScheduler
{


    public function __invoke()
    {

        if (strtotime('08:30') < strtotime(date('H:i')) &&  strtotime('13:00') > strtotime(date('H:i'))) {
            $this->get_data();
        }
    }


    public function get_data()
    {
        $filters_arr = [
            'person_most_buy_sell',
            'person_most_sell_buy',
            'most_cash_trade',
            'most_volume_trade',
            'most_person_buy',
            'most_person_sell',
            'most_legall_buy',
            'most_legall_sell',
            'power_person_buy',
            'power_person_sell'
        ];

        foreach ($filters_arr as $key => $value) {
            try {

                $ch = curl_init("http://localhost/shakhes/public/api/filter/$value");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_ENCODING, "");
                $result = curl_exec($ch);

                Cache::put("$value", json_decode($result)->data);
                // dd(Cache::get("$value"));
                \Log::error('Filter inserted');
            } catch (\Throwable $th) {
            }
        }


        echo 'information stored ';
    }
}
