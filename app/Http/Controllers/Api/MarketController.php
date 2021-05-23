<?php

namespace App\Http\Controllers\Api;

use Goutte;
use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Http\Controllers\Controller;
use App\Http\Resources\CapitalIncreaseResource;
use App\Http\Resources\ClarificationResource;
use App\Http\Resources\NamadResource;
use Illuminate\Support\Facades\Cache;
use App\Models\Namad\NamadsDailyReport;
use App\MovingAverage;
use App\Shakhes;
use Carbon\Carbon;

class MarketController extends Controller
{

    public function get_distributes()
    {
        try {
            $dist_arr = [];
            if (Cache::has('namad_status_counts')) {
                $status_count = Cache::get('namad_status_counts');
                return $this->JsonResponse($status_count, null, 200);
            }

            if (Cache::has('namadlist')) {
                $namads = Cache::get('namadlist');
                // dd($namads);
            } else {
                $namads =  \App\Models\Namad\Namad::all();
                Cache::store()->put('namadlist', $namads, 60 * 60 * 12); // 12 hours
            }


            foreach ($namads as $key => $namad) {
                if (Cache::has($namad->id)) {
                    $dist_arr[] = ['percent' => Cache::get($namad->id)['final_price_percent'], 'status' => Cache::get($namad->id)['status']];
                }
            }

            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (4 < $value['percent'] && $value['percent'] <= 5 && $value['status'] == 'green') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'green', 'range' => '4~5%'
            ];

            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (3 < $value['percent'] && $value['percent'] <= 4 && $value['status'] == 'green') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'green', 'range' => '3~4%'
            ];

            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (2 < $value['percent'] && $value['percent'] <= 3 && $value['status'] == 'green') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'green', 'range' => '2~3%'
            ];

            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (1 < $value['percent'] && $value['percent'] <= 2 && $value['status'] == 'green') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'green', 'range' => '1~2%'
            ];

            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (0 < $value['percent'] && $value['percent'] <= 1 && $value['status'] == 'green') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'green', 'range' => '0~1%'
            ];

            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (0 == $value['percent']) {
                        return true;
                    }
                    return false;
                })),
                'status' => 'red', 'range' => '0'
            ];




            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (0 < $value['percent'] && $value['percent'] <= 1 && $value['status'] == 'red') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'red', 'range' => '0~1%'
            ];
            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (1 < $value['percent'] && $value['percent'] <= 2 && $value['status'] == 'red') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'red', 'range' => '1~2%'
            ];

            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (2 < $value['percent'] && $value['percent'] <= 3 && $value['status'] == 'red') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'red', 'range' => '2~3%'
            ];

            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (3 < $value['percent'] && $value['percent'] <= 4 && $value['status'] == 'red') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'red', 'range' => '3~4%'
            ];

            $status_count[] = [
                'count' => count(array_filter($dist_arr, function ($value) {
                    if (4 < $value['percent'] && $value['percent'] <= 5 && $value['status'] == 'red') {
                        return true;
                    }
                    return false;
                })),
                'status' => 'red', 'range' => '4~5%'
            ];



            Cache::put('namad_status_counts', $status_count, 60 * 10);
        } catch (\Throwable $th) {
            //throw $th;
            $status_count = null;
//            return [];
        }
        // dd($dist_arr);

//        return $status_count;
         return $this->JsonResponse($status_count,null,200);

    }

    public function filterFunction($value)
    {
        if ($value > 1) {
            return true;
        }
        return false;
    }


    public function index_values()
    {

        if (isset(request()->type)) {
            if (request()->type == 'bourse') {
                $url = "http://www.tsetmc.com/tsev2/chart/data/Index.aspx?Top=2&i=32097828799138957&t=value";
                $type = 'bourse';
            } elseif (request()->type == 'farabourse') {
                $url = 'http://www.tsetmc.com/tsev2/chart/data/Index.aspx?i=43685683301327984&t=value';
                $type = 'farabourse';
            } elseif (request()->type == 'equivalent') {
                $url = 'http://www.tsetmc.com/tsev2/chart/data/Index.aspx?i=67130298613737946&t=value';
                $type = 'bourse';
            } else {

                $url = "http://www.tsetmc.com/tsev2/chart/data/Index.aspx?Top=2&i=32097828799138957&t=value";
                $type = 'bourse';
            }
        } else {
            $url = "http://www.tsetmc.com/tsev2/chart/data/Index.aspx?Top=2&i=32097828799138957&t=value";
            $type = 'bourse';
        }

        if ($type == 'bourse') {
            $bourse = Shakhes::where('title', 'شاخص كل')->latest()->first();
            $data[] = [
                'title' => 'شاخص کل',
                'value' => $bourse ? $bourse->value : '',
                'change' => $bourse ? $bourse->value_change : '',
                'percent' => $bourse ? $bourse->percent_change : '',
                'status' => $bourse ? ($bourse->status == 'positive' ? '+' : '-') : '',
                'link' => route('BaseUrl') . '/api/market/index/shakhes-status?type=bourse'
            ];

            $equivalent = Shakhes::where('title', 'شاخص كل (هم وزن)')->latest()->first();
            $data[] = [
                'title' => 'شاخص كل (هم وزن)',
                'value' => $equivalent ? $equivalent->value : '',
                'change' => $equivalent ? $equivalent->value_change : '',
                'percent' => $equivalent ? $equivalent->percent_change : '',
                'status' => $equivalent ? ($equivalent->status == 'positive' ? '+' : '-') : '',
                'link' => route('BaseUrl') . '/api/market/index/shakhes-status?type=equivalent'
            ];
        }

        if ($type == 'farabourse') {
            $bourse = Shakhes::where('title', 'بازار اول فرابورس')->latest()->first();
            $data[] = [
                'title' => 'شاخص فرابورس',
                'value' => $bourse ? $bourse->value : '',
                'change' => $bourse ? $bourse->value_change : '',
                'percent' => $bourse ? $bourse->percent_change : '',
                'status' => $bourse ? ($bourse->status == 'positive' ? '+' : '-') : '',
                'link' => route('BaseUrl') . '/api/market/index/shakhes-status?type=farabourse'
            ];
        }

        return $this->JsonResponse($data, null, 200);
    }


    public function index_chart()
    {

        //    return $data['distributes'] = $this->get_distributes();


        try {

            if (isset(request()->type)) {
                if (request()->type == 'bourse') {
                    $url = "http://www.tsetmc.com/tsev2/chart/data/Index.aspx?Top=2&i=32097828799138957&t=value";
                    $type = 'bourse';
                } elseif (request()->type == 'farabourse') {
                    $url = 'http://www.tsetmc.com/tsev2/chart/data/Index.aspx?i=43685683301327984&t=value';
                    $type = 'farabourse';
                } elseif (request()->type == 'equivalent') {
                    $url = 'http://www.tsetmc.com/tsev2/chart/data/Index.aspx?i=67130298613737946&t=value';
                    $type = 'bourse';
                } else {

                    $url = "http://www.tsetmc.com/tsev2/chart/data/Index.aspx?Top=2&i=32097828799138957&t=value";
                    $type = 'bourse';
                }
            } else {
                $url = "http://www.tsetmc.com/tsev2/chart/data/Index.aspx?Top=2&i=32097828799138957&t=value";
                $type = 'bourse';
            }

            $days = request()->months ? (int)request()->months * 30 : 7;
            $date = Jalalian::forge('now')->subDays($days)->getTimestamp();
            $chart_arr = [];
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            $result = curl_exec($ch);
            $day_data = explode(';', $result);
            foreach (array_reverse($day_data) as $item) {

                $y = explode('/', explode(',', $item)[0])[0];
                $m = explode('/', explode(',', $item)[0])[1] < 10 ? '0' . intval(explode('/', explode(',', $item)[0])[1]) : explode('/', explode(',', $item)[0])[1];
                $d = explode('/', explode(',', $item)[0])[2] < 10 ? '0' . intval(explode('/', explode(',', $item)[0])[2]) : explode('/', explode(',', $item)[0])[2];

                $chart_arr[] = ['yAxis' => implode('/', [$y, $m, $d]), 'xAxis' => explode(',', $item)[1]];

                if (Jalalian::fromFormat('Y/m/d', implode('/', [$y, $m, $d]))->getTimestamp() < $date) break;

                $error = null;
            }

            $data = $chart_arr;

            // $data['distributes'] = $this->get_distributes();

        } catch (\Throwable $th) {
            //throw $th;
            $error = 'خطای سرور';
            $chart_arr = null;
        }

        return $this->JsonResponse($data, $error, 200);
    }

    public function chart()
    {
        try {
            $namad = Namad::find(request()->namad);
            $inscode = $namad->inscode;
            $days = request()->months ? (int)request()->months * 30 : 7;





            // if (Cache::has("chartdata-$inscode-$days")) {
            //     $chartdata = Cache::get("chartdata-$inscode-$days");
            // } else {

                $data = $this->get_history_data($inscode, $days);
                foreach ($data as $key => $item) {
                    $axis['xAxis'] = $item['pc'];
                    $axis['open'] = $item['pf'];
                    $axis['close'] = $item['pl'];
                    $axis['high'] = $item['pmax'];
                    $axis['low'] = $item['pmin'];
                    $axis['yAxis'] = $item['date'];
                    $chartdata[] = $axis;
                }

                Cache::put("chartdata-$inscode-$days", $chartdata, 60 * 60 * 12);
            // }
            $error = null;
        } catch (\Throwable $th) {
            $chartdata = null;
            $error = 'خطا در دریافت اطلاعات از سرور';
        }


        return $this->JsonResponse($chartdata, $error, 200);
    }


    public function getNamad(Request $request)
    {

        $member = $this->token(request()->header('Authorization'));
        $namad = Namad::find($request->id);
        try {
            if ($namad) {

                $information['id'] = $request->id;
                $information['time'] = date('g:i', strtotime($information['time']));

                $information['final_price_change'] = $information['last_price_change'];
                $information['final_price_status'] = $information['last_price_status'] ? '+' : '-';

                $information['person_buy_power'] = strval($information['person_buy_power']);
                $information['pc_change_percent'] = strval($information['pc_change_percent']);
                $information['pc_status'] = (int)$information['pc'] > (int)$information['py'] ? '+' : '-';
                $information['pl_change_percent'] = strval($information['final_price_percent']);
                $information['pl_change_val'] = strval($information['last_price_change']);
                $information['pf_change_percent'] = strval($information['pf_change_percent']);
                $information['pf_status'] = (int)$information['pc'] > (int)$information['py'] ? '+' : '-';
                $information['pl_status'] = (int)$information['pl'] > (int)$information['py'] ? '+' : '-';
                $information['pmin_status'] = (int)$information['pmin'] > (int)$information['py'] ? '+' : '-';
                $information['pmax_status'] = (int)$information['pmax'] > (int)$information['py'] ? '+' : '-';
                $information['tradeVol'] = $this->format($information['N_tradeVol'], 'fa');
                $information['tradeCash'] = $this->format($information['N_tradeCash'], 'fa');
                $information['marketCash'] = $this->format((int)$information['TedadSaham'] * (int)$information['pc'], 'fa');
                $information['tedadSaham'] = $this->format($information['TedadSaham'], 'fa');
                $information['TedadSaham'] = $this->format($information['TedadSaham'], 'fa');
                $information['monthAVG'] = $this->format($information['N_monthAVG'], 'fa');
                $information['notifications_count'] = 0;
                $information['flow'] = $information['flow'] == '1' ? 'بورس' : 'فرابورس';

                unset($information['last_price_change']);
                unset($information['last_price_status']);
                unset($information['lastsells']);
                unset($information['lastbuys']);
                unset($information['N_personbuy']);
                unset($information['N_legalbuy']);
                unset($information['N_personsell']);
                unset($information['N_legalsell']);
                unset($information['personbuy']);
                unset($information['personsell']);
                unset($information['legalbuy']);
                unset($information['legalsell']);
                unset($information['personbuycount']);
                unset($information['personsellcount']);
                unset($information['legalsellcount']);
                unset($information['legalbuycount']);
                unset($information['person_buy_power']);
                unset($information['person_sell_power']);
                unset($information['percent_person_buy']);
                unset($information['percent_legal_buy']);
                unset($information['percent_person_sell']);
                unset($information['percent_legal_sell']);
                unset($information['ID']);


                    $data = [];
                    do {
                        try {
                            $status = false;
                            // $ch = curl_init("http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=$namad->inscode&c=57");
                            // curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
                            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            // curl_setopt($ch, CURLOPT_ENCODING, "");
                            $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=' . $namad->inscode . '&c=57');
                            $all = \strip_tags($crawler->html());

                            $result = $all;
                        } catch (\Throwable $th) {
                            $status = true;
                            sleep(.1);
                        }
                    } while ($status);

                    $explode_all = explode(';', $result);

                    $orders = $explode_all[2];
                    if ($orders) {
                        $explode_orders = explode('@', $orders);
                        $explode_orders[1] = $this->format((int) $explode_orders[1], 'en');
                        $data['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2], 'color' => $explode_orders[2] < $information['minRange'] ? 'gray' : 'black');
                        $explode_orders[6] = $this->format((int) $explode_orders[6], 'en');
                        $data['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7], 'color' => $explode_orders[7] < $information['minRange'] ? 'gray' : 'black');
                        $explode_orders[11] = $this->format((int) $explode_orders[11], 'en');
                        $data['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12], 'color' => $explode_orders[12] < $information['minRange'] ? 'gray' : 'black');
                        $explode_orders[16] = $this->format((int) $explode_orders[16], 'en');
                        $data['lastbuys'][] = array('tedad' => explode(',', $explode_orders[15])[1], 'vol' => $explode_orders[16], 'price' => $explode_orders[17], 'color' => $explode_orders[17] < $information['minRange'] ? 'gray' : 'black');
                        $explode_orders[21] = $this->format((int) $explode_orders[21], 'en');
                        $data['lastbuys'][] = array('tedad' => explode(',', $explode_orders[20])[1], 'vol' => $explode_orders[21], 'price' => $explode_orders[22], 'color' => $explode_orders[22] < $information['minRange'] ? 'gray' : 'black');

                        $explode_orders[4] = $this->format((int) $explode_orders[4], 'en');
                        $data['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3], 'color' => $explode_orders[3] > $information['maxRange'] ? 'gray' : 'black');
                        $explode_orders[9] = $this->format((int) $explode_orders[9], 'en');
                        $data['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8], 'color' => $explode_orders[8] > $information['maxRange'] ? 'gray' : 'black');
                        $explode_orders[14] = $this->format((int) $explode_orders[14], 'en');
                        $data['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13], 'color' => $explode_orders[13] > $information['maxRange'] ? 'gray' : 'black');
                        $explode_orders[19] = $this->format((int) $explode_orders[19], 'en');
                        $data['lastsells'][] = array('tedad' => explode(',', $explode_orders[20])[0], 'vol' => $explode_orders[19], 'price' => $explode_orders[18], 'color' => $explode_orders[18] > $information['maxRange'] ? 'gray' : 'black');
                        $explode_orders[24] = $this->format((int) $explode_orders[24], 'en');
                        $data['lastsells'][] = array('tedad' => explode(',', $explode_orders[25])[0], 'vol' => $explode_orders[24], 'price' => $explode_orders[23], 'color' => $explode_orders[23] > $information['maxRange'] ? 'gray' : 'black');

                    }

                    $main_data = $explode_all[0];
                    $pl = explode(',', $main_data)[2];
                    $pc = explode(',', $main_data)[3];
                    $pf = explode(',', $main_data)[4];
                    $py = explode(',', $main_data)[5];
                    $information['time']  = explode(',', $main_data)[0];
                    $information['final_price_value'] = number_format($pl);
                    $information['final_price_change'] = abs($pl - $py);
                    $information['final_price_status'] = ($pl - $py) > 0 ? '+' : '-';
                    $information['final_price_percent'] = $py ?  abs(number_format((float)(($pl - $py) * 100) / $py, 2, '.', '')) : '';
                    $information['pl_change_percent'] = strval($information['final_price_percent']);
                    $information['pl_change_val'] = strval($information['final_price_change']);
                    $information['pl_status'] = $information['final_price_status'];

                    // dd(explode(',', $buy_sell)[0] );

                    $buy_sell = $explode_all[4];
                    $data['N_personbuy'] = $buy_sell ?  explode(',', $buy_sell)[0] : 0;
                    $data['N_legalbuy'] = $buy_sell ? explode(',', $buy_sell)[1] : 0;
                    $data['N_personsell'] = $buy_sell ? explode(',', $buy_sell)[3] : 0;
                    $data['N_legalsell'] = $buy_sell ? strval(explode(',', $buy_sell)[4]) : 0;
                    $data['personbuycount'] = $buy_sell ? strval(explode(',', $buy_sell)[5]) : 0;
                    $data['legalbuycount'] = $buy_sell ? strval(explode(',', $buy_sell)[6]) : 0;
                    $data['personsellcount'] = $buy_sell ? strval(explode(',', $buy_sell)[8]) : 0;
                    $data['legalsellcount'] = $buy_sell ? strval(explode(',', $buy_sell)[9]) : 0;
                    $data['personbuy'] = $this->format($data['N_personbuy'], 'en');
                    $data['personsell'] = $this->format($data['N_personsell'], 'en');
                    $data['legalbuy'] = $this->format($data['N_legalbuy'], 'en');
                    $data['legalsell'] = $this->format($data['N_legalsell'], 'en');

                    if ($data['N_personbuy'] &&  $data['personbuycount'] &&  $data['N_personsell'] && $data['personsellcount']) {
                        $data['person_buy_power'] = strval(number_format((float)(($data['N_personbuy'] / $data['personbuycount']) / (($data['N_personbuy'] / $data['personbuycount']) + ($data['N_personsell'] / $data['personsellcount']))), 2, '.', '') * 100);
                        $data['person_sell_power'] = number_format((float)(100 - $data['person_buy_power']), 0, '.', '');
                        Cache::put('person_buy_power',$data['person_buy_power']);
                        Cache::put('person_sell_power',$data['person_sell_power']);
                    } else {
                        $data['person_buy_power'] = Cache::get('person_buy_power');
                        $data['person_sell_power'] = Cache::get('person_sell_power');
                    }

                    $totalbuy = $buy_sell ? explode(',', $buy_sell)[0] + explode(',', $buy_sell)[1] : 0;
                    $totalsell = $buy_sell ? explode(',', $buy_sell)[3] + explode(',', $buy_sell)[4] : 0;

                    if ($totalbuy && $buy_sell) {
                        $data['percent_person_buy'] = number_format((float)((explode(',', $buy_sell)[0] * 100) / $totalbuy), 0, '.', '');
                        Cache::put('percent_person_buy',$data['percent_person_buy']);
                    } else {
                        $data['percent_person_buy'] = Cache::get('percent_person_buy');
                    }

                    if ($totalbuy && $buy_sell) {
                        $data['percent_legal_buy'] = number_format((float)((explode(',', $buy_sell)[1] * 100) / $totalbuy), 0, '.', '');
                        Cache::put('percent_legal_buy',$data['percent_legal_buy']);
                    } else {
                        $data['percent_legal_buy'] = Cache::get('percent_legal_buy');
                    }

                    if ($totalsell && $buy_sell) {
                        $data['percent_person_sell'] = number_format((float)((explode(',', $buy_sell)[3] * 100) / $totalsell), 0, '.', '');
                        Cache::put('percent_person_sell',$data['percent_person_sell']);
                    } else {
                        $data['percent_person_sell'] =Cache::get('percent_person_sell');
                    }

                    if ($totalsell && $buy_sell) {
                        $data['percent_legal_sell'] = number_format((float)((explode(',', $buy_sell)[4] * 100) / $totalsell), 0, '.', '');
                        Cache::put('percent_legal_sell',$data['percent_legal_sell']);
                    } else {
                        $data['percent_legal_sell'] = Cache::get('percent_legal_sell');
                    }

                $result = array_merge($information, $data);
                Cache::put('namad_data_all',$result,10);
                return $this->JsonResponse($result, null, 200);
            }
        } catch (\Throwable $th) {
            $result = null;
            $error = 'خطا در دریافت اطلاعات از سرور';
        }
    }


    public function getCodal(Request $request)
    {

            $pageNumber = $request->page ?? 1;

            $namad = Namad::find($request->query('namad_id'));
            $url = "https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=-1&Childs=true&CompanyState=-1&CompanyType=-1&Consolidatable=true&IsNotAudited=false&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber={$pageNumber}&Publisher=false&Symbol={$namad->symbol}&TracingNo=-1&search=true";
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            $err = curl_error($ch);
            $result = json_decode($result, true);
            curl_close($ch);
            $all=[];
            if ($result) {
                foreach ($result['Letters'] as $key => $item) {
                    $data['namad'] =
                        ['id' => $namad->id,
                        'symbol' => Cache::get($namad->id)['symbol'],
                        'name' => Cache::get($namad->id)['name'],
                        'final_price_value' => Cache::get($namad->id)['final_price_value'],
                        'final_price_percent' => Cache::get($namad->id)['final_price_percent'],
                        'final_price_change' => Cache::get($namad->id)['last_price_change'],
                        'final_price_status' => Cache::get($namad->id)['last_price_status'] ? '+' : '-',
                ];
                    $data['newsId'] = 1;
                    $data['newsDate'] = explode(' ',$item['PublishDateTime'])[0];
                    $data['newsTime'] = explode(':',explode(' ',$item['PublishDateTime'])[1])[0] . ':' . explode(':',explode(' ',$item['PublishDateTime'])[1])[1];
                    $data['publish_at'] = date('Y-m-d');
                    $data['newsLink'] = 'https://www.codal.ir/' . $item['Url'];
                    $data['newsText'] = $item['Title'];
                    $data['extra'] = $item['PdfUrl'] ? 'https://www.codal.ir/' . $item['PdfUrl'] : null;
                    $data['isBookmarked'] = false;
                    $data['seen'] = Carbon::parse(date('Y-m-d'))->isToday() ? false : true;
                    $all[] = $data;
                }

            }




        return $this->JsonResponse($all, null, 200);
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

            Cache::put('bshakhes', $all, 60 * 10);
            $error = null;
        } catch (\Throwable $th) {
            $error = 'در حال حاضر سرور مشغول است لطفا مجددا امتحان کنید';
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

            Cache::put('fshakhes', $all, 60 * 5);
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

            return $collect = collect($information);
            //  $result = $collect->paginate(20);
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
                        $dd['namad'] = new NamadResource($namad);
                        $dd['effect'] = (float)$value;

                        $ff[] = $dd;
                    }
                }
            }
        }

        Cache::store()->put($idd, $ff, 60 * 5); // 10 Minutes
        $collect = collect($ff);
        // $result = $collect->paginate(20);
        return $collect;

        // return response()->json(['data' => $ff], 200);
    }


    private function getFromTSE($url, $idd)
    {
        $information = Cache::get($idd);
        if ($information) {
            $collect = collect($information);
            // $result = $collect->paginate(20);
            return $collect;
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

        Cache::store()->put($idd, $all, 60 * 5); // 5 Minutes
        $collect = collect($all);
        // $result = $collect->paginate(20);
        return $collect;
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
