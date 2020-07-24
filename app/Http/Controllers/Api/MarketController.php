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

    public function getNamad()
    {

        $id = request()->id;
        $namad = Namad::find($id);
        if ($namad) {
            $inscode = $namad->inscode;
            $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=' . $inscode . '&c=57');
            $all = \strip_tags($crawler->html());

            $explode_all = explode(';', $all);
            $main_data = $explode_all[0];
            $buy_sell = $explode_all[4];
            $orders = $explode_all[2];

            $explode_orders = explode('@', $orders);
            $array['orders'][] = array('type' => 'buy', 'order' => '1', 'tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2]);
            $array['orders'][] = array('type' => 'buy', 'order' => '2', 'tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7]);
            $array['orders'][] = array('type' => 'buy', 'order' => '3', 'tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12]);
            $array['orders'][] = array('type' => 'sell', 'order' => '1', 'tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3]);
            $array['orders'][] = array('type' => 'sell', 'order' => '2', 'tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8]);
            $array['orders'][] = array('type' => 'sell', 'order' => '3', 'tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13]);

            $array['personbuy'] = explode(',', $buy_sell)[0];
            $array['legalbuy'] = explode(',', $buy_sell)[1];
            $array['personsell'] = explode(',', $buy_sell)[3];
            $array['legalsell'] = explode(',', $buy_sell)[4];
            $array['personbuycount'] = explode(',', $buy_sell)[5];
            $array['legalbuycount'] = explode(',', $buy_sell)[6];
            $array['personsellcount'] = explode(',', $buy_sell)[8];
            $array['legalsellcount'] = explode(',', $buy_sell)[9];

            $array['pl'] = explode(',', $main_data)[2];
            $array['pc'] = explode(',', $main_data)[3];
            $array['pf'] = explode(',', $main_data)[4];
            $array['py'] = explode(',', $main_data)[5];
            $array['pmax'] = explode(',', $main_data)[6];
            $array['pmin'] = explode(',', $main_data)[7];
            $array['pmin'] = explode(',', $main_data)[8];
            $array['tradevol'] = explode(',', $main_data)[9];
            $array['tradecash'] = explode(',', $main_data)[10];

            $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=151311&i=9211775239375291');
            $all = \strip_tags($crawler->html());
            $explode = \explode(',', $all);

            preg_match('/=\'?(\d+)/', $explode[25], $matches);
            $array['Inscode'] = count($matches) ? $matches[1] : '';
            preg_match('/=\'?(\d+)/', $explode[23], $matches);
            $array['flow'] = count($matches) ? $matches[1] : '';
            preg_match('/\'?(\d+)/', $explode[24], $matches);
            $array['ID'] = count($matches) ? $matches[1] : '';
            preg_match('/=\'?(\d+)/', $explode[26], $matches);
            $array['BaseVol'] = count($matches) ? $matches[1] : '';
            preg_match('/\'?(\d+)/', $explode[27], $matches);
            $array['EPS'] = count($matches) ? $matches[1] : '';

            preg_match('/=\'?(\d+)/', $explode[38], $matches);
            $array['minweek'] = count($matches) ? $matches[1] : '';
            preg_match('/=\'?(\d+)/', $explode[39], $matches);
            $array['maxweek'] = count($matches) ? $matches[1] : '';
            preg_match('/=\'?(\d+)/', $explode[42], $matches);
            $array['monthAVG'] = count($matches) ? $matches[1] : '';
            preg_match('/\'?(\d+)/', $explode[43], $matches);
            $array['groupPE'] = count($matches) ? $matches[1] : '';
            preg_match('/=\'?(\d+)/', $explode[44], $matches);
            $array['sahamShenavar'] = count($matches) ? $matches[1] : '';

            return response()->json($array, 200);
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
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(55) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(4)')->text();
            $array['percent_change'] =  $node->filter('tr:nth-of-type(55) td:nth-of-type(5) div')->text();
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(51) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(4)')->text();
            $array['percent_change'] =  $node->filter('tr:nth-of-type(51) td:nth-of-type(5) div')->text();
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(53) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(4)')->text();
            $array['percent_change'] =  $node->filter('tr:nth-of-type(53) td:nth-of-type(5) div')->text();
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(47) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(4)')->text();
            $array['percent_change'] =  $node->filter('tr:nth-of-type(47) td:nth-of-type(5) div')->text();
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(48) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(4)')->text();
            $array['percent_change'] =  $node->filter('tr:nth-of-type(48) td:nth-of-type(5) div')->text();
            $all[] = $array;
            $array['title'] = $node->filter('tr:nth-of-type(46) td')->text();
            $array['time'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(2)')->text();
            $array['last_val'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(3)')->text();
            $array['value_change'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(4)')->text();
            $array['percent_change'] =  $node->filter('tr:nth-of-type(46) td:nth-of-type(5) div')->text();
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
            return $information;
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
