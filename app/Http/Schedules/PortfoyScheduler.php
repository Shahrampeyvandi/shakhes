<?php

namespace App\Http\Schedules;

use App\Models\Holding\Holding;
use App\Models\Member\Member;
use Illuminate\Http\Request;
use Exception;
use App\Models\Namad\Namad;

use Illuminate\Support\Facades\Cache;


class PortfoyScheduler
{


    public function __invoke()
    {
        // if (date('H') >= 8 && date('H') <= 12) {
        try {

            $holdings = Holding::all();
            foreach ($holdings as $key => $holding) {

                $namads = $holding->namads()->get();
                $yesterday_portfoy = 0;
                $portfoy = 0;
                foreach ($namads as $key => $namad) {
                    $inscode = $namad->inscode;
                    $array = $this->get_data($inscode, 2);
                    if (count($array)) {
                        $py = (int)$array[1]['pc'];
                        $pc = (int)$array[0]['pc'];
                        $portfoy += $pc * $namad->pivot->amount_value;
                        $yesterday_portfoy += $py * $namad->pivot->amount_value;
                    }
                }
                
               if (count($array)) {
                    $percent = number_format((($portfoy - $yesterday_portfoy) / $yesterday_portfoy) * 100, 1);

                Cache::put('holding-' . $holding->id, [
                    'marketvalue' => $portfoy,
                    'change_percent' => $percent
                ]);
                echo 'holding ' . $holding->id . PHP_EOL;
               }
            }
            //  dd($percent,$portfoy,$yesterday_portfoy);

        } catch (Exception $e) {
        }
        //   }
    }


    public function get_data($inscode, $days)
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
            if (count($data) == 10 && isset($data[4]) && $data[3]) {
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
