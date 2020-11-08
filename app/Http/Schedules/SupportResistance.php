<?php

namespace App\Http\Schedules;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\Namad\Namad;
use App\SupportResistance as AppSupportResistance;
use Illuminate\Http\Request;

use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Cache;


class SupportResistance extends Controller
{


    public function __invoke()
    {
        if (Cache::has('namadlist')) {
            $namads = Cache::get('namadlist');
        } else {
            $namads = Namad::all();
            Cache::store()->put('namadlist', $namads, 86400); // 10 Minutes
        }
        foreach ($namads as $key => $namad) {
            $this->get_data($namad);
        }
    }


    public function get_data($namad, $days = 100)
    {


        $data = [];

        // $namad = Namad::where('symbol', $symbol)->first();

        $cache = Cache::get($namad->id);
        $pl = $cache && isset($cache['pl']) ? (int)$cache['pl'] : null;
        $symbol =  $cache && isset($cache['pl']) ? $cache['symbol'] : null;
        if ($pl && $symbol) {
            $array = $this->get_history_data($namad->inscode, $days);
            $sum = 0;
            if (count($array)) {
                foreach ($array as $key => $row) {
                    $sum += (int)$row['pl'];
                }
                echo 'search for: ' . $symbol . PHP_EOL;
                $avg = $sum / count($array);
                $min_pl = $avg - (($avg * 5) / 100);
                $max_pl = $avg + (($avg * 5) / 100);
                if ($pl > $min_pl && $pl < $max_pl) {
                    $data[] = ['symbol' => $symbol, 'pl' => $pl, 'avg' => $avg];

                    if ($array[1]['pl'] > $pl && $array[2]['pl'] > $pl) {
                        $s = new AppSupportResistance();
                        $s->namad_id = $namad->id;
                        $s->symbol = $symbol;
                        $s->type = 'resistance';
                        $s->period = $days;
                        $s->save();
                        echo 'resistance for: ' . $symbol . PHP_EOL;
                    }
                    if ($array[1]['pl'] < $pl && $array[2]['pl'] < $pl) {
                        $s = new AppSupportResistance();
                        $s->namad_id = $namad->id;
                        $s->symbol = $symbol;
                        $s->type = 'support';
                        $s->period = $days;
                        $s->save();
                        echo 'support for: ' . $symbol . PHP_EOL;
                    }
                }
            }
        }
    }
}
