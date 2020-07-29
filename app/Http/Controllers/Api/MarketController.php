<?php

namespace App\Http\Controllers\Api;

use Goutte;
use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Namad\NamadsDailyReport;

class MarketController extends Controller
{




    public function getNamad(Request $request)
    {

   

        $namad = Namad::find($request->id);
      
        if ($namad) {
            $information = Cache::get($namad->id);
            $information['symbol'] = $namad->symbol;
            $information['name'] = $namad->name;
            $information['id'] = $namad->id;
            $information['flow'] = $namad->flow;
            $information['time'] = date('g:i', strtotime($information['time']));
       
            if ($information['pl'] && $information['py']) {
                $information['final_price_value'] = $information['pl'];
                $information['final_price_percent'] = $information['py'] ?  abs(number_format((float)(($information['pl'] - $information['py']) * 100) / $information['py'], 2, '.', '')) : '';
                $information['last_price_change'] = abs($information['pl'] - $information['py']);
                $information['last_price_status'] = ($information['pl'] - $information['py']) > 0 ? '1' : '0';
            } else {
                $information['final_price_value'] = '';
                $information['final_price_percent'] = '';
                $information['last_price_change'] = '';
                $information['last_price_status'] = '';
            }

            return response()->json($information, 200);
            
        }
    }

    public function bshackes()
    {

        if (Cache::has('bshakhes')) {

            return response()->json([
                'data' => Cache::get('bshakhes'),
            ], 200);
        }

        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151315&Flow=1';
        $all = [];
        $crawler = Goutte::request('GET', $url);
        $crawler->filter('table')->each(function ($node) use (&$all) {



            $array['title'] = $node->filter('tr:nth-of-type(53) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(53) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(53) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;


            $array['title'] = $node->filter('tr:nth-of-type(54) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(54) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(54) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;

            $array['title'] = $node->filter('tr:nth-of-type(47) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(47) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(47) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;

            $array['title'] = $node->filter('tr:nth-of-type(48) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(48) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(48) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;

            $array['title'] = $node->filter('tr:nth-of-type(55) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(55) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(55) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }

            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(51) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(3)')->text();

            $array['value_change'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(51) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(51) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;


            $array['title'] = $node->filter('tr:nth-of-type(46) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(46) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(46) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;
        });

        Cache::put('bshakhes', $all, 5000);

        return response()->json([
            'data' => $all,
        ], 200);
    }
    public function fshackes()
    {

        if (Cache::has('fshakhes')) {

            return response()->json([
                'data' => Cache::get('fshakhes'),
            ], 200);
        }

        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151315&Flow=2';
        $all = [];
        $crawler = Goutte::request('GET', $url);
        $crawler->filter('table')->each(function ($node) use (&$all) {
            $array['title'] = $node->filter('tr:nth-of-type(5) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(5) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(5) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(5) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(5) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(5) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(5) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;


            $array['title'] = $node->filter('tr:nth-of-type(1) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(1) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(1) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(1) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(1) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(1) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(1) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;


            $array['title'] = $node->filter('tr:nth-of-type(2) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(2) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(2) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(2) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(2) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(2) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(2) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;



            $array['title'] = $node->filter('tr:nth-of-type(3) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(3) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(3) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(3) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(3) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(3) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(3) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;


            $array['title'] = $node->filter('tr:nth-of-type(4) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(4) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(4) td:nth-of-type(3)')->text();

            $array['value_change'] =  $node->filter('tr:nth-of-type(4) td:nth-of-type(4)')->text();
            $array['value_change'] = preg_replace('/\(|\)/', '', $array['value_change']);
            $array['percent_change'] =  $node->filter('tr:nth-of-type(4) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(4) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(4) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;
        });

        Cache::put('fshakhes', $all, 5000);

        return response()->json([
            'data' => $all,
        ], 200);
    }

    public function bourseMostVisited()
    {

        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=MostVisited&Flow=1';
        return $this->getFromTSE($url, 'boursemosetvisit');
    }

    public function farabourceMostVisited()
    {
        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=MostVisited&Flow=2';
        return $this->getFromTSE($url, 'faraboursemostvisit');
    }

    public function bourseEffectInShakhes()
    {
        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151316&Flow=1';



        return $this->getEffect($url, 'bourseffectshakhes');
    }
    public function farabourseEffectInShakhes()
    {
        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151316&Flow=2';
        return $this->getEffect($url, 'farabourseffectshakhes');
    }

    public function bourseMostPriceIncreases()
    {
        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=PClosingTop&Flow=1';
        return $this->getFromTSE($url, 'boursepriceincrease');
    }

    public function farabourseMostPriceIncreases()
    {
        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=PClosingTop&Flow=2';
        return $this->getFromTSE($url, 'faraboursepriceincrease');
    }

    public function bourseMostPriceDecreases()
    {
        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=PClosingBtm&Flow=1';
        return $this->getFromTSE($url, 'boursepricedecrease');
    }

    public function farabourseMostPriceDecreases()
    {
        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=PClosingBtm&Flow=2';
        return $this->getFromTSE($url, 'faraboursepricedecrease');
    }

    public function getEffect($url, $idd)
    {

        $information = Cache::get($idd);
        if ($information) {
            return response()->json(['data' => $information], 200);
        }


        $crawler = Goutte::request('GET', $url);
        $array = [];
        $data = [];
        $all = [];
        $crawler->filter('td:nth-of-type(1) a')->each(function ($node) use (&$array, &$all) {

            $symbol = $node->attr('href');
            if ($symbol) {
                $parse = parse_url($symbol);
                parse_str($parse['query'], $query);
                $inscode = $query['i'];
            } else {
                $inscode = '';
            }

            $array[] = $inscode;
        });

        $crawler->filter('td div')->each(function ($node) use (&$data, &$array, &$all) {

            $effect = $node->text();
            // return $effect;
            $data[] = $effect;
        });

        foreach ($array as $key => $value) {

            $data[$key] = preg_replace('/\(|\)/', '', $data[$key]);
            $new[$value] = $data[$key];
        }


        $ff = [];
        foreach ($new as $key => $value) {

            $namad = Namad::where('inscode', $key)->first();
            if ($namad) {
                $id = $namad->id;
                $information = Cache::get($id);
                // return $information;
                if (!is_null($information)) {
                    if (array_key_exists('pl', $information) && array_key_exists('py', $information)) {
                        $pl = $information['pl'];
                        $py = $information['py'];
                        $dd['id'] = $namad->id;
                        if ($pl && $py) {
                            $dd['symbol'] = $namad->symbol;
                            $dd['name'] = $namad->name;
                            $dd['final_price_value'] = $pl;
                            $percent = (($pl - $py) * 100) / $py;
                            $percent =  number_format((float)$percent, 2, '.', '');
                            $dd['final_price_percent'] = $percent;
                            $dd['last_price_change'] = $pl - $py;
                            $dd['last_price_status'] = ($pl - $py) > 0 ? '1' : '0';
                        } else {
                            $dd['symbol'] = $namad->symbol;
                            $dd['name'] = $namad->name;
                            $dd['final_price_value'] = '';
                            $dd['final_price_percent'] = '';
                            $dd['last_price_change'] = '';
                            $dd['last_price_status'] = '';
                        }
                        $dd['effect'] = $value;

                        $ff[] = $dd;
                    }
                }
            }
        }
        Cache::store()->put($idd, $ff, 1000); // 10 Minutes
        return response()->json(['data' => $ff], 200);
    }


    private function getFromTSE($url, $idd)
    {
        $information = Cache::get($idd);
        if ($information) {
            return response()->json(['data' => $information], 200);
        }

        $crawler = Goutte::request('GET', $url);
        $array = [];
        $all = [];
        $crawler->filter('td:nth-of-type(1) a')->each(function ($node) use (&$array, &$all) {

            $symbol = $node->attr('href');
            if ($symbol) {
                $parse = parse_url($symbol);
                parse_str($parse['query'], $query);
                $inscode = $query['i'];
            } else {
                $inscode = '';
            }

            $array[] = $inscode;
            // get symbol data from redis with $inscode's

        });
        //  return sort($array);
        // return $array;
        $all = [];
        foreach ($array as $key => $inscode) {
            $namad = Namad::where('inscode', $inscode)->first();
            if ($namad) {
                $id = $namad->id;
                $information = Cache::get($id);
                // return $information;
                if (!is_null($information)) {
                    if (array_key_exists('pl', $information) && array_key_exists('py', $information)) {
                        $pl = $information['pl'];
                        $py = $information['py'];
                        $data['id'] = $namad->id;
                        if ($pl && $py) {
                            $data['symbol'] = $namad->symbol;
                            $data['name'] = $namad->name;
                            $data['final_price_value'] = $pl;
                            $percent = (($pl - $py) * 100) / $py;
                            $percent =  number_format((float)$percent, 2, '.', '');
                            $data['final_price_percent'] = $percent;
                            $data['last_price_change'] = $pl - $py;
                            $data['last_price_status'] = ($pl - $py) > 0 ? '1' : '0';
                        } else {
                            $data['symbol'] = $namad->symbol;
                            $data['name'] = $namad->name;
                            $data['final_price_value'] = '';
                            $data['final_price_percent'] = '';
                            $data['last_price_change'] = '';
                            $data['last_price_status'] = '';
                        }

                        $all[] = $data;
                    }
                }
            }
        }

        Cache::store()->put($idd, $all, 100); // 10 Minutes
        return response()->json(['data' => $all], 200);
    }

    public function search(Request $request)
    {


        $key = $request->search;
        $key = str_replace('ی', 'ي', $key);

        $namads = Namad::where('symbol', 'like', '%' . $key . '%')
            ->take(5)->get();

        $all = [];
        foreach ($namads as $namad) {
            $id = $namad->id;
            $information = Cache::get($id);
            // return $information;
            if (!is_null($information)) {
                if (array_key_exists('pl', $information) && array_key_exists('py', $information)) {
                    $pl = $information['pl'];
                    $py = $information['py'];
                    $data['id'] = $namad->id;
                    if ($pl && $py) {
                        $data['symbol'] = $namad->symbol;
                        $data['name'] = $namad->name;
                        $data['final_price_value'] = $pl;
                        $percent = (($pl - $py) * 100) / $py;
                        $percent =  number_format((float)$percent, 2, '.', '');
                        $data['final_price_percent'] = $percent;
                        $data['last_price_change'] = $pl - $py;
                        $data['last_price_status'] = ($pl - $py) > 0 ? '1' : '0';
                    } else {
                        $data['symbol'] = $namad->symbol;
                        $data['name'] = $namad->name;
                        $data['final_price_value'] = '';
                        $data['final_price_percent'] = '';
                        $data['last_price_change'] = '';
                        $data['last_price_status'] = '';
                    }

                    $all[] = $data;
                }
            }
        }
        return response()->json([
            'data' => $all
        ], 200);
    }
}
