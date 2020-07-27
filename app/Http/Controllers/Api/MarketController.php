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

        //  $array = [500,501,502,503,504,505,506];
        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=26014913469567886&c=57');
        $all = \strip_tags($crawler->html());

        $explode_all = explode(';', $all);
        $main_data = $explode_all[0];
        $buy_sell = $explode_all[4];
        $orders = $explode_all[2];

        $dailyReport = new NamadsDailyReport;
        $dailyReport->namad_id = 1;


        $explode_orders = explode('@', $orders);
        $array['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2]);
        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7]);
        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12]);

        $dailyReport->lastbuys = serialize($array['lastbuys']);

        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3]);
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8]);
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13]);

        $dailyReport->lastsells = serialize($array['lastsells']);
        $data['personbuy'] = explode(',', $buy_sell)[0];
        $data['legalbuy'] = explode(',', $buy_sell)[1];
        $data['personsell'] = explode(',', $buy_sell)[3];
        $data['legalsell'] = explode(',', $buy_sell)[4];
        $data['personbuycount'] = explode(',', $buy_sell)[5];
        $data['legalbuycount'] = explode(',', $buy_sell)[6];
        $data['personsellcount'] = explode(',', $buy_sell)[8];
        $data['legalsellcount'] = explode(',', $buy_sell)[9];



        foreach ($data as $key => $item) {
            if ((int)$item > 1000000 && (int)$item < 1000000000) {
                $array[$key] = number_format((int)$item / 1000000, 1) . "M";
            } elseif ((int)$item > 1000000000) {
                $array[$key] = number_format((int)$item / 1000000000, 1) . "B";
            } else {
                $array[$key] = (int)$item;
            }
        }


        if ($data['personbuy'] &&  $data['personbuycount'] &&  $data['personsell'] && $data['personsellcount']) {
            $array['person_buy_power'] = number_format((float)(($data['personbuy'] / $data['personbuycount']) / (($data['personbuy'] / $data['personbuycount']) + ($data['personsell'] / $data['personsellcount']))), 2, '.', '') * 100;
            $array['person_sell_power'] = number_format((float)(100 - $array['person_buy_power']), 2, '.', '');
        } else {
            $array['person_buy_power'] = 0;
            $array['person_sell_power'] = 0;
        }



        $totalbuy =  explode(',', $buy_sell)[0] + explode(',', $buy_sell)[1];
        $totalsell =  explode(',', $buy_sell)[3] + explode(',', $buy_sell)[4];

        if ($totalbuy && $buy_sell) {
            $array['percent_person_buy'] = number_format((float)((explode(',', $buy_sell)[0] * 100) / $totalbuy), 0, '.', '');
        } else {
            $array['percent_person_buy'] = 0;
        }
        if ($totalbuy && $buy_sell) {
            $array['percent_legal_buy'] = number_format((float)((explode(',', $buy_sell)[1] * 100) / $totalbuy), 0, '.', '');
        } else {
            $array['percent_legal_buy'] = 0;
        }

        if ($totalsell && $buy_sell) {
            $array['percent_person_sell'] = number_format((float)((explode(',', $buy_sell)[3] * 100) / $totalsell), 0, '.', '');
        } else {
            $array['percent_person_sell'] = 0;
        }
        if ($totalsell && $buy_sell) {
            $array['percent_legal_sell'] = number_format((float)((explode(',', $buy_sell)[4] * 100) / $totalsell), 0, '.', '');
        } else {

            $array['percent_legal_sell'] = 0;
        }





        $dailyReport->personbuy = $array['personbuy'];
        $dailyReport->legalbuy = $array['legalbuy'];
        $dailyReport->personsell = $array['personsell'];
        $dailyReport->legalsell = $array['legalsell'];
        $dailyReport->personbuycount = $array['personbuycount'];
        $dailyReport->legalbuycount = $array['legalbuycount'];
        $dailyReport->personsellcount = $array['personsellcount'];
        $dailyReport->legalsellcount = $array['legalsellcount'];

        $array['time']  = explode(',', $main_data)[0];
        $array['pl'] = explode(',', $main_data)[2];
        $array['pc'] = explode(',', $main_data)[3];
        $array['pf'] = explode(',', $main_data)[4];
        $array['py'] = explode(',', $main_data)[5];
        $array['pmin'] = explode(',', $main_data)[7];
        $array['pmax'] = explode(',', $main_data)[6];

        $array['tradecount'] = explode(',', $main_data)[8];


        $tradeVOL = explode(',', $main_data)[9];

        if ((int)$tradeVOL > 1000000 && (int)$tradeVOL < 1000000000) {
            $array['tradevol'] = number_format((int)$tradeVOL / 1000000, 1) . "M";
        } elseif ((int)$tradeVOL > 1000000000) {
            $array['tradevol'] = number_format((int)explode(',', $main_data)[10] / 1000000000, 1) . "B";
        } else {
            $array['tradevol'] = (int)$tradeVOL;
        }


        $tradeCASH = explode(',', $main_data)[10];
        if ((int)$tradeCASH > 1000000 && (int)$tradeCASH < 1000000000) {
            $array['tradecash'] =  number_format((int)$tradeCASH / 1000000, 1) . "M";
        } elseif ((int)$tradeCASH > 1000000000) {

            $array['tradecash'] =  number_format((int)$tradeCASH / 1000000000, 1) . "B";
        } else {
            $array['tradecash'] =  (int)$tradeCASH;
        }






        if ($array['pl'] && $array['py']) {

            $array['status'] =  ($array['pl'] - $array['py'])  > 0 ? 'green' : 'red';
        } else {
            $array['status'] = null;
        }
        $dailyReport->pl = $array['pl'];
        $dailyReport->pc = $array['pc'];
        $dailyReport->pf = $array['pf'];
        $dailyReport->py = $array['py'];

        $dailyReport->tradevol = $array['tradevol'];
        $dailyReport->tradecash = $array['tradecash'];


        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=151311&i=26014913469567886');
        $all = \strip_tags($crawler->html());
        $explode = \explode(',', $all);

        preg_match('/=\'?(\d+)/',  \explode(',', $all)[34], $matches);
        $array['maxrange'] =  count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/',  \explode(',', $all)[35], $matches);
        $array['minrange'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[23], $matches);
        $array['flow'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[24], $matches);
        $array['ID'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[26], $matches);
        $array['BaseVol'] =  count($matches) ? $matches[1] : '';

        preg_match('/=\'?(\d+)/', $explode[28], $matches);
        $array['TedadShaham'] =  count($matches) ? $matches[1] : '';






        if ($array['TedadShaham'] && $array['TedadShaham'] !== '' && $array['pl']) {
            $array['MarketCash'] = $array['TedadShaham'] * $array['pl'];
            if ((int)$array['MarketCash'] > 1000000 && (int)$array['MarketCash'] < 1000000000) {
                $array['MarketCash'] =  number_format((int)$array['MarketCash'] / 1000000, 1) . "M";
            } elseif ((int)$array['MarketCash'] > 1000000000) {

                $array['MarketCash'] =  number_format((int)$array['MarketCash'] / 1000000000, 1) . "B";
            } else {
                $array['MarketCash'] =  (int)$array['MarketCash'];
            }
        }


        if ($array['TedadShaham'] && $array['TedadShaham'] !== '') {

            if ((int)$array['TedadShaham'] > 1000000 && (int)$array['TedadShaham'] < 1000000000) {
                $array['TedadShaham'] =  number_format((int)$array['TedadShaham'] / 1000000, 1) . "M";
            } elseif ((int)$array['TedadShaham'] > 1000000000) {

                $array['TedadShaham'] =  number_format((int)$array['TedadShaham'] / 1000000000, 1) . "B";
            } else {
                $array['TedadShaham'] =  (int)$array['TedadShaham'];
            }
        }


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

        if (isset($array['pl']) && isset($array['py'])) {
            $array['final_price_value'] = $array['pl'];
            $array['final_price_percent'] = $array['py'] ?  abs(number_format((float)(($array['pl'] - $array['py']) * 100) / $array['py'], 2, '.', '')) : '';
            $array['last_price_change'] = abs($array['pl'] - $array['py']);
            $array['last_price_status'] = ($array['pl'] - $array['py']) > 0 ? '1' : '0';
        } else {
            $array['final_price_value'] = '0';
            $array['final_price_percent'] = '0';
            $array['last_price_change'] = '0';
            $array['last_price_status'] = '0';
        }

        $dailyReport->pmax = $array['pmax'];
        $dailyReport->pmin = $array['pmin'];
        $dailyReport->BaseVol = $array['BaseVol'];
        $dailyReport->EPS = $array['EPS'];
        $dailyReport->minweek = $array['minweek'];
        $dailyReport->maxweek = $array['maxweek'];
        $dailyReport->monthAVG = $array['monthAVG'];
        $dailyReport->groupPE = $array['groupPE'];
        $dailyReport->sahamShenavar = $array['sahamShenavar'];

        return response()->json($array, 200);
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

    public function search(Request $request)
    {


        $key = $request->search;
        $key = str_replace('ی','ي',$key);

        $namads = Namad::where('symbol', 'like', '%' . $key . '%')
            ->take(5)->get();

            $all=[];
            foreach($namads as $namad){
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
