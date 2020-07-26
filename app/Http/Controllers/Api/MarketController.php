<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;
use Goutte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\Jalalian;

class MarketController extends Controller
{



    public function search(Request $request)
    {
        $key = $request->search;

        $namads = Namad::where('symbol', 'like', '%' . $key . '%')
            ->take(5)->get();
        return response()->json([
            'data' => $namads
        ], 200);
    }

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
            // if ($information['pl'] && $information['py']) {

            //     $information['final_price_value'] = $information['pl'];
            //     $information['final_price_percent'] = $information['py'] ?  abs(number_format((float)(($information['pl'] - $information['py']) * 100) / $information['py'], 2, '.', '')) : '';
            //     $information['last_price_change'] = abs($information['pl'] - $information['py']);
            //     $information['last_price_status'] = ($information['pl'] - $information['py']) > 0 ? '1' : '0';
            // } else {
            //     $information['final_price_value'] = '';
            //     $information['final_price_percent'] = '';
            //     $information['last_price_change'] = '';
            //     $information['last_price_status'] = '';
            // }

            return response()->json($information, 200);
        } else {
            return response()->json('namad not found', 401);
        }
    }

    public function shackes()
    {

        if (Cache::has('shakhes')) {

            return response()->json([
                'data' => Cache::get('shakhes'),
            ], 200);
        }

        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151315&Flow=1';
        $all = [];
        $crawler = Goutte::request('GET', $url);
        $crawler->filter('table')->each(function ($node) use (&$all) {

            $array['title'] = $node->filter('tr:nth-of-type(54) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(4)')->text();
            $array['percent_change'] =  $node->filter('tr:nth-of-type(54) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(54) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(54) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(55) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(4)')->text();
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
            $array['percent_change'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(51) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(51) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(53) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(4)')->text();
            $array['percent_change'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(53) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(53) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(47) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(4)')->text();
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
            $array['percent_change'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(48) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(48) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(46) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(4)')->text();
            $array['percent_change'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(5) div')->text();
            if ($node->filter('tr:nth-of-type(46) td:nth-of-type(5) div.pn')->count()) {
                $array['status'] = 'positive';
            }
            if ($node->filter('tr:nth-of-type(46) td:nth-of-type(5) div.mn')->count()) {
                $array['status'] = 'negative';
            }
            $all[] = $array;
        });

        Cache::put('shakhes', $all, 5000);

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
        return $this->getFromTSE($url, 'bourseffectshakhes');
    }
    public function farabourseEffectInShakhes()
    {
        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151316&Flow=2';
        return $this->getFromTSE($url, 'farabourseffectshakhes');
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
}
