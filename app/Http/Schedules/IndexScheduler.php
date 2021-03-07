<?php

namespace App\Http\Schedules;

use Illuminate\Http\Request;
use Exception;
use App\Shakhes;
use Goutte;

use Illuminate\Support\Facades\Cache;


class IndexScheduler
{


    public function __invoke()
    {
        // if (date('H') >= 8 && date('H') <= 12) {
        try {


            if (strtotime('08:30') < strtotime(date('H:i')) &&  strtotime('13:00') > strtotime(date('H:i'))) {
                if($status = Cache::get('bazarstatus') == 'close') {

                }else{

                    $this->get_data();
                }
            }
        } catch (Exception $e) {
        }
        //   }
    }


    public function get_data()
    {
        // if (Cache::has('bshakhes')) {
        
        //   $this->save_in_db();
        //   echo 'cache has';
        //     return;
        // }

        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151315&Flow=1';
            $bourse = [];
            $crawler = Goutte::request('GET', $url);
            $crawler->filter('table')->each(function ($node) use (&$bourse) {

                $array['title'] = $node->filter('tr:nth-of-type(54) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(54) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(54) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $bourse[] = $array;







                $array['title'] = $node->filter('tr:nth-of-type(47) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(47) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(47) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $bourse[] = $array;

                $array['title'] = $node->filter('tr:nth-of-type(48) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(48) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(48) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $bourse[] = $array;

                $array['title'] = $node->filter('tr:nth-of-type(53) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(53) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(53) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $bourse[] = $array;


                $array['title'] = $node->filter('tr:nth-of-type(55) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(55) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(55) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }

                $bourse[] = $array;
                $array['title'] = $node->filter('tr:nth-of-type(51) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(3)')->text();

                $array['value_change'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(51) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(51) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $bourse[] = $array;


                $array['title'] = $node->filter('tr:nth-of-type(46) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(46) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(46) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $bourse[] = $array;
            });

            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151315&Flow=2';
            $farabourse = [];
            $crawler = Goutte::request('GET', $url);
            $crawler->filter('table')->each(function ($node) use (&$farabourse) {
                $array['title'] = $node->filter('tr:nth-of-type(5) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(5) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(5) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(5) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(5) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(5) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(5) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $farabourse[] = $array;


                $array['title'] = $node->filter('tr:nth-of-type(1) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(1) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(1) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(1) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(1) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(1) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(1) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $farabourse[] = $array;


                $array['title'] = $node->filter('tr:nth-of-type(2) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(2) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(2) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(2) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(2) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(2) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(2) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $farabourse[] = $array;



                $array['title'] = $node->filter('tr:nth-of-type(3) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(3) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(3) td:nth-of-type(3)')->text();
                $array['value_change'] =  $node->filter('tr:nth-of-type(3) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(3) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(3) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(3) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $farabourse[] = $array;


                $array['title'] = $node->filter('tr:nth-of-type(4) td')->text();
                $array['time'] =  $node->filter('tr:nth-of-type(4) td:nth-of-type(2)')->text();
                $array['last_val'] =  $node->filter('tr:nth-of-type(4) td:nth-of-type(3)')->text();

                $array['value_change'] =  $node->filter('tr:nth-of-type(4) td:nth-of-type(4)')->text();
                $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
                $array['percent_change'] =  $node->filter('tr:nth-of-type(4) td:nth-of-type(5) div')->text();
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(4) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(4) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $farabourse[] = $array;
            });

            Cache::put('fshakhes', $farabourse, 60 * 15);



            Cache::put('bshakhes', $bourse, 60 * 15);



            $error = null;

            $this->save_in_db();
        } catch (\Throwable $th) {
        }




        echo 'information stored ';
    }

    protected function save_in_db() {
        // echo Cache::get('bshakhes')[0]->last_value;
        foreach ($cashe = Cache::get('bshakhes') as $item) {
            
            $index = new Shakhes;
            $index->value = $item['last_val'];
            $index->time =  $item['time'];
            $index->title =  $item['title'];
            $index->value_change =  $item['value_change'];
            $index->percent_change =  $item['percent_change'];
            $index->status =  $item['status'];
            $index->market =  'bourse';
            $index->save();
        }
        foreach ($cashe = Cache::get('fshakhes') as $item) {
            $index = new Shakhes;
            $index->value = $item['last_val'];
            $index->time =  $item['time'];
            $index->title =  $item['title'];
            $index->value_change =  $item['value_change'];
            $index->percent_change =  $item['percent_change'];
            $index->status =  $item['status'];
            $index->market =  'farabourse';
            $index->save();
        }
    }
}
