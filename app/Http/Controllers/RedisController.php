<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Goutte;
class RedisController extends Controller
{
    public function getmain(){
        //$redis = Redis::connection();
        $values = Redis::command('keys', ['*']);
        // dd($values);

        // foreach($values as $vla){
        //     $data = Redis::hgetall($vla);
        //     $data = json_decode(end($data), true);

        //     //dd($data);
        //     echo $data['l30'];
        //     echo '</br>';

        // }
        $allmarket = Redis::hgetall('IRB3TB630091')['13:42'];
        //$user = json_decode(end($user), true);
        return $allmarket;
        $all = [];
        foreach ($allmarket as $key => $item) {
            $item = [];

        }
       // echo $user;

       

    }

    public function shakhes()
    {
         $inscode = [
            '32097828799138957' => 'شاخص کل',
            '5798407779416661' => 'شاخص قیمت',
            '67130298613737946' => 'شاخص کل(هم وزن)',
            '8384385859414435' => 'شاخص قیمت(هم وزن)',
            '49579049405614711' => 'شاخص آزاد شناور',
            '62752761908615603' => 'شاخص بازار اول',
            '71704845530629737' => 'شاخص بازار دوم',
        ];

        $all = [];
        foreach ($inscode as $key => $name) {
            $array = [];

            $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=15131J&i=' . $key . '');
            
            $crawler->filter('#MainContent')->each(function ($node) use ($name, $array, $all) {
          
                $last_val = $node->filter('tr:contains("آخرین مقدار شاخص") td:nth-of-type(2)')->text();
                $high_val = $node->filter('tr:contains("بیشترین مقدار روز") td:nth-of-type(2)')->text();
                $low_val = $node->filter('tr:contains("کمترین مقدار روز") td:nth-of-type(2)')->text();
                $time = $node->filter('tr:contains("زمان انتشار") td:nth-of-type(2)')->text();
                $prev_val = $node->filter('.silver.tbl tr:nth-of-type(1) td:nth-of-type(2)')->text();

                $last_val = str_replace(',', '', $last_val);
                $prev_val = str_replace(',', '', $prev_val);
                $high_val = str_replace(',', '', $high_val);
                $low_val = str_replace(',', '', $low_val);

                $percent_change = ($last_val - $prev_val) * 100 / $prev_val;
                $array[$name] = $last_val;
             
              
                $all[] = $array;
                // DB::table('market')->insert([
                //     'name' => $name,
                //     'high_val' => $high_val,
                //     'low_val' => $low_val,
                //     'last_val' => $last_val,
                //     'time' => $time,
                //     'prev_val' => $prev_val,
                //     'change_val' => $last_val - $prev_val,
                //     'percent_change' => number_format((float) $percent_change, 2, '.', ''),
                // ]);
            });

        }
        return response()->json($all,200);
    }
}
