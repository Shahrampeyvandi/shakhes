<?php

namespace App\Http\Controllers\Api;

use Goutte;
use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Http\Controllers\Controller;
use App\Http\Resources\NamadResource;
use Illuminate\Support\Facades\Cache;
use App\Models\Namad\NamadsDailyReport;
use App\MovingAverage;
use Carbon\Carbon;

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
            if (isset($information['namad_status'])) {
                $information['namad_status'] = $information['namad_status'];
            } else {
                $information['namad_status'] = 'A';
            }

            unset($information['lastsells']);
            unset($information['lastbuys']);
            unset($information['filter']);
            if (Cache::has('order' . $namad->id)) {
                $sefareshat = Cache::get('order' . $namad->id);
            } else {
                $sefareshat = [];
                do {
                    try {
                        $status = false;
                        $ch = curl_init("http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=$namad->inscode&c=57");
                        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_ENCODING, "");
                        $result = curl_exec($ch);
                    } catch (\Throwable $th) {
                        $status = true;
                        sleep(.5);
                    }
                } while ($status);

                $explode_all = explode(';', $result);
                $orders = $explode_all[2];
                if ($orders) {
                    $explode_orders = explode('@', $orders);
                    $explode_orders[1] = $this->format((int) $explode_orders[1]);
                    $sefareshat['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2], 'color' => $explode_orders[2] < $information['minrange'] ? 'gray' : 'black');
                    $explode_orders[6] = $this->format((int) $explode_orders[6]);
                    $sefareshat['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7], 'color' => $explode_orders[7] < $information['minrange'] ? 'gray' : 'black');
                    $explode_orders[11] = $this->format((int) $explode_orders[11]);
                    $sefareshat['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12], 'color' => $explode_orders[12] < $information['minrange'] ? 'gray' : 'black');

                    $explode_orders[4] = $this->format((int) $explode_orders[4]);
                    $sefareshat['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3], 'color' => $explode_orders[3] > $information['maxrange'] ? 'gray' : 'black');
                    $explode_orders[9] = $this->format((int) $explode_orders[9]);
                    $sefareshat['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8], 'color' => $explode_orders[8] > $information['maxrange'] ? 'gray' : 'black');
                    $explode_orders[14] = $this->format((int) $explode_orders[14]);
                    $sefareshat['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13], 'color' => $explode_orders[13] > $information['maxrange'] ? 'gray' : 'black');
                }

                Cache::store()->put('order' . $namad->id, $sefareshat, 12);
            }

            $result = array_merge($information, $sefareshat);

            return $this->JsonResponse($result, null, 200);
        }
    }

    public function bshackes()
    {

        if (Cache::has('bshakhes')) {

            return $this->JsonResponse(Cache::get('bshakhes'), null, 200);
        }

        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151315&Flow=1';
            $all = [];
            $crawler = Goutte::request('GET', $url);
            $crawler->filter('table')->each(function ($node) use (&$all) {

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
                $all[] = $array;







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
                $all[] = $array;

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
                $all[] = $array;

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
                $all[] = $array;


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

                $all[] = $array;
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
                $all[] = $array;


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
                $all[] = $array;
            });

            Cache::put('bshakhes', $all, 60 * 2);
            $error = null;
        } catch (\Throwable $th) {
            $error = 'در حال حاضر سرور با مشکل مواجه است';
            $all = [];
        }

        return $this->JsonResponse($all, $error, 200);
    }
    public function fshackes()
    {

        if (Cache::has('fshakhes')) {
            return $this->JsonResponse(Cache::get('fshakhes'), null, 200);
        }

        try {
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
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
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
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
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
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
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
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
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
                $array['percent_change'] = preg_replace('/\(|\)/', '', $array['percent_change']);
                if ($node->filter('tr:nth-of-type(4) td:nth-of-type(5) div.pn')->count()) {
                    $array['status'] = 'positive';
                }
                if ($node->filter('tr:nth-of-type(4) td:nth-of-type(5) div.mn')->count()) {
                    $array['status'] = 'negative';
                }
                $all[] = $array;
            });

            Cache::put('fshakhes', $all, 5000);
            $error = null;
        } catch (\Throwable $th) {
            $error = 'در حال حاضر سرور با مشکل مواجه است';
            $all = [];
        }
        return $this->JsonResponse($all, $error, 200);
    }

    public function bourseMostVisited()
    {
        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=MostVisited&Flow=1';
            $data = $this->getFromTSE($url, 'boursemosetvisit');
            $error = null;
        } catch (\Throwable $th) {
            $data = [];
            $error = 'خطا در دریافت اطلاعات';
        }
        return $this->JsonResponse($data, $error, 200);
    }

    public function farabourceMostVisited()
    {
        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=MostVisited&Flow=2';
            $data = $this->getFromTSE($url, 'faraboursemostvisit');

            $error = null;
        } catch (\Throwable $th) {
            $data = [];
            $error = 'خطا در دریافت اطلاعات';
        }
        return $this->JsonResponse($data, $error, 200);
    }

    public function bourseEffectInShakhes()
    {
        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151316&Flow=1';
            $data = $this->getEffect($url, 'bourseffectshakhes');
            $error = null;
        } catch (\Throwable $th) {
            $data = [];
            $error = 'خطا در دریافت اطلاعات';
        }
        return $this->JsonResponse($data, $error, 200);
    }
    public function farabourseEffectInShakhes()
    {
        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151316&Flow=2';
            $data = $this->getEffect($url, 'farabourseffectshakhes');
            $error = null;
        } catch (\Throwable $th) {
            $data = [];
            $error = 'خطا در دریافت اطلاعات';
        }
        return $this->JsonResponse($data, $error, 200);
    }

    public function bourseMostPriceIncreases()
    {
        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=PClosingTop&Flow=1';
            $data = $this->getFromTSE($url, 'boursepriceincrease');
            $error = null;
        } catch (\Throwable $th) {
            $data = [];
            $error = 'خطا در دریافت اطلاعات';
        }

        return $this->JsonResponse($data, $error, 200);
    }

    public function farabourseMostPriceIncreases()
    {
        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=PClosingTop&Flow=2';
            $data = $this->getFromTSE($url, 'faraboursepriceincrease');
            $error = null;
        } catch (\Throwable $th) {
            $data = [];
            $error = 'خطا در دریافت اطلاعات';
        }
        return $this->JsonResponse($data, $error, 200);
    }

    public function bourseMostPriceDecreases()
    {
        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=PClosingBtm&Flow=1';
            $data = $this->getFromTSE($url, 'boursepricedecrease');
            $error = null;
        } catch (\Throwable $th) {
            $data = [];
            $error = 'خطا در دریافت اطلاعات';
        }
        return $this->JsonResponse($data, $error, 200);
    }

    public function farabourseMostPriceDecreases()
    {
        try {
            $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=PClosingBtm&Flow=2';
            $data = $this->getFromTSE($url, 'faraboursepricedecrease');
            $error = null;
        } catch (\Throwable $th) {
            $data = [];
            $error = 'خطا در دریافت اطلاعات';
        }
        return $this->JsonResponse($data, $error, 200);
    }

    public function getEffect($url, $idd)
    {

        $information = Cache::get($idd);
        if ($information) {
            $collect = collect($information);
            return $result = $collect->paginate(20);
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
        $i = 0;
        foreach ($new as $key => $value) {
            $i++;
            if ($i == 60) break;
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
                        if (isset($information['namad_status'])) {
                            $dd['namad_status'] = $information['namad_status'];
                        } else {
                            $dd['namad_status'] = 'A';
                        }
                        $dd['effect'] = $value;

                        $ff[] = $dd;
                    }
                }
            }
        }

        Cache::store()->put($idd, $ff, 1000); // 10 Minutes
        $collect = collect($ff);
        $result = $collect->paginate(20);
        return $result;

        return response()->json(['data' => $ff], 200);
    }


    private function getFromTSE($url, $idd)
    {
        $information = Cache::get($idd);
        if ($information) {

            $collect = collect($information);
            $result = $collect->paginate(20);
            return $result;
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
        $i = 0;
        foreach ($array as $key => $inscode) {
            $i++;
            if ($i == 60) break;
            $namad = Namad::where('inscode', $inscode)->first();
            if ($namad) {
                $id = $namad->id;
                $information = Cache::get($id);
                // return $information;
                if (!is_null($information)) {
                    if (array_key_exists('pl', $information) && array_key_exists('py', $information)) {
                        $all[] = new NamadResource($namad);
                    }
                }
            }
        }

        Cache::store()->put($idd, $all, 60 * 3); // 3 Minutes
        $collect = collect($all);
        $result = $collect->paginate(20);
        return $result;
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
