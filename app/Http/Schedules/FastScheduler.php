<?php

namespace App\Http\Schedules;

use Illuminate\Http\Request;
use Exception;

use Illuminate\Support\Facades\Cache;


class FastScheduler
{


    public function __invoke()
    {
        try {
            $this->get_data();
        } catch (Exception $e) {
        }
    }


    public function get_data()
    {


        do {
            try {
                $status = false;
                $ch = curl_init("http://www.tsetmc.com/tsev2/data/MarketWatchInit.aspx?h=0&r=0");
                curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_ENCODING, "");
                $result = curl_exec($ch);

                $datas = explode(';', $result);
            } catch (\Throwable $th) {
                $status = true;
                sleep(1);
            }
        } while ($status);


        foreach ($datas as $key => $row) {
            $dd = explode(',', $datas[$key]);
            if (count($dd) == 23) {
                Cache::put($dd[0], [
                    'symbol' => $dd[2],
                    'name' => $dd[3],
                    'pf' => $dd[5],
                    'pc' => $dd[6],
                    'pl' => $dd[7],
                    'py' => $dd[13],
                    'tradecount' => $dd[8],
                    'N_tradeVol' => $dd[9],
                    'N_tradecash' => $dd[10],
                    'TedadShaham' => $dd[21],
                    'EPS' => $dd[14],
                    'P/E' => $dd[14] ? number_format(($dd[6] / $dd[14]), 2, '.', '') : '',
                    'maxrange' => $dd[19],
                    'minrange' => $dd[20],
                    'final_price_value' => $dd[7],
                    'final_price_percent' => $dd[13] ?  abs(number_format((float)(($dd[7] - $dd[13]) * 100) / $dd[13], 2, '.', '')) : '',
                    'last_price_change' => abs($dd[7] - $dd[13]),
                    'last_price_status' => ($dd[7] - $dd[13]) > 0 ? '1' : '0',
                    'pc_change_percent' => isset($dd[13]) && $dd[13] !== 0 ?  abs(number_format((float)(($dd[6] - $dd[13]) * 100) / $dd[13], 2, '.', '')) : '',
                    'pf_change_percent' => isset($dd[5]) && $dd[13] !== 0 ?  abs(number_format((float)(($dd[5] - $dd[13]) * 100) / $dd[13], 2, '.', '')) : '',
                ], 60 * 5); // 5 minutes
            }
        }
        echo 'information stored ';
    }
}