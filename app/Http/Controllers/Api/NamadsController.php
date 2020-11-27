<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Prophecy\Call\Call;
use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\Holding\Holding;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class NamadsController extends Controller
{
    public $cache_time = 60;

    public function getalldata($id)
    {
        return Namad::whereId($id)->first()->dailyReports;
    }
    public function search(Request $request)
    {
        $member = $this->token(request()->header('Authorization'));
        $key = $request->search;
        $res = [];
        try {
            if ($key) {

                $namads = Namad::where('symbol', 'like', '%' . $key . '%')
                    ->take(5)->get();
                if (count($namads) == 0) {
                    $error = 'موردی یافت نشد';
                } else {
                    foreach ($namads as $key => $namad) {
                        $res[] = [
                            'id' => $namad->id,
                            'symbol' => $namad->symbol,
                            'name' => $namad->name,
                            'isSelected' => $member->check_if_has_namad($namad->id)
                        ];
                    }
                    $error = null;
                }
            }
            $status = 200;
        } catch (\Throwable $th) {
            $error = 'خطا در دریافت اطلاعات از سرور';
        }
         return $this->JsonResponse($res,$error,$status);
    }

    public function getnamad(Request $request)
    {
        $member = $this->token(request()->header('Authorization'));
        $namad = Namad::find($request->id);
        if ($namad) {
            $information = Cache::get($namad->id);
            $information['symbol'] = $namad->symbol;
            $information['name'] = $namad->name;
            $information['id'] = $namad->id;
            $information['flow'] = $namad->flow;
            if (isset($information['pl'])) {
                $information['status'] = $information['status'];
            } else {
                $information['status'] = 'red';
            }
            if (isset($information['namad_status'])) {
                $information['namad_status'] = $information['namad_status'];
            } else {
                $information['namad_status'] = 'A';
            }

            if (Holding::where('namad_id', $namad->id)->first()) {
                $information['holding'] = 1;
            } else {
                $information['holding'] = 0;
            }
            $information['holding'] = 0;

            $result =  array_merge($information, $namad->getUserNamadNotifications($member));

            return response()->json($result, 200);
        } else {
            return response()->json('Namad not found', 401);
        }
    }

    public function getHomeNotifications()
    {
        $member = $this->token(request()->header('Authorization'));

        $my_notif = $member->get_notifications();
        $common_notif = $this->get_home_notifications($member);
        $common_notif['notifications'][] = [
            "pk" => 1,
            "title" => "mynamads",
            "count" => $my_notif['count']
        ];

        $all_notif = array_merge(["my_namad" => $my_notif['my_namads']], $common_notif);
        return $this->JsonResponse($all_notif,null,200);
        // return response()->json($all_notif, 200);
    }

    public function show($id)
    {

        if (Cache::has('data-' . $id)) {
            return response()->json(['data' => Cache::get('data-' . $id), 'error' => false]);
        }

        $namad = Namad::find($id);
        $old_cache = Cache::get($id);
        if (!$namad) {
            return response()->json(['data' => null, 'error' => true], 200);
        }

        $inscode = $namad->inscode;
        if ($inscode) {

            $cache = Cache::get($inscode);

            if ($cache) {
                $cache = Cache::get($inscode);
            } else {
                $cache = Cache::get($id);
            }

            do {
                try {
                    $status = false;
                    $ch = curl_init("http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=$inscode&c=57");
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
                $array['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2], 'color' => $explode_orders[2] < $cache['minrange'] ? 'gray' : 'black');
                $explode_orders[6] = $this->format((int) $explode_orders[6]);
                $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7], 'color' => $explode_orders[7] < $cache['minrange'] ? 'gray' : 'black');
                $explode_orders[11] = $this->format((int) $explode_orders[11]);
                $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12], 'color' => $explode_orders[12] < $cache['minrange'] ? 'gray' : 'black');

                $explode_orders[4] = $this->format((int) $explode_orders[4]);
                $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3], 'color' => $explode_orders[3] > $cache['maxrange'] ? 'gray' : 'black');
                $explode_orders[9] = $this->format((int) $explode_orders[9]);
                $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8], 'color' => $explode_orders[8] > $cache['maxrange'] ? 'gray' : 'black');
                $explode_orders[14] = $this->format((int) $explode_orders[14]);
                $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13], 'color' => $explode_orders[13] > $cache['maxrange'] ? 'gray' : 'black');
            }


            $array['name'] = $cache['name'];
            $array['symbol'] = $cache['symbol'];
            $array['pl'] = $cache['pl'];
            $array['pc'] = $cache['pc'];
            $array['pf'] = $cache['pf'];
            $array['py'] = $cache['py'];
            $array['pmin'] = $old_cache['pmin'];
            $array['pmax'] = $old_cache['pmin'];
            $array['tradecount'] = $cache['tradecount'];
            $array['N_tradeVol'] =  $this->format($cache['N_tradeVol']);
            $array['N_tradecash'] =  $this->format($cache['N_tradecash']);
            $array['EPS'] = $cache['EPS'];
            $array['P/E'] = $cache['P/E'];
            $array['TedadShaham'] = $cache['TedadShaham'];
            $array['final_price_value'] = $cache['final_price_value'];
            $array['final_price_percent'] = $cache['final_price_percent'];
            $array['last_price_change'] = $cache['last_price_change'];
            $array['last_price_status'] = $cache['last_price_status'];
            $array['pc_change_percent'] = $cache['pc_change_percent'];
            $array['pf_change_percent'] = $cache['pf_change_percent'];
            $array['flow'] = $old_cache['flow'];
            $array['ID'] = $old_cache['ID'];
            $array['BaseVol'] = $old_cache['BaseVol'];
            $array['status'] =  ($array['pl'] - $array['py'])  > 0 ? 'green' : 'red';
            $array['personbuy'] = $old_cache['personbuy'];
            $array['legalbuy'] = $old_cache['legalbuy'];
            $array['personsell'] = $old_cache['personsell'];
            $array['legalsell'] = $old_cache['legalsell'];
            $array['personbuycount'] = $old_cache['personbuycount'];
            $array['legalbuycount'] = $old_cache['legalbuycount'];
            $array['personsellcount'] = $old_cache['personsellcount'];
            $array['legalsellcount'] = $old_cache['legalsellcount'];
            $array['person_buy_power'] = $old_cache['person_buy_power'];
            $array['person_sell_power'] = $old_cache['person_sell_power'];
            $array['percent_legal_buy'] = $old_cache['percent_legal_buy'];
            $array['percent_person_sell'] = $old_cache['percent_person_sell'];
            $array['percent_legal_sell'] = $old_cache['percent_legal_sell'];

            Cache::put('data-' . $id, $array, 5);  // 5 seconds
            return response()->json(['data' => $array, 'error' => false]);
        }
    }

    public function namad_history_data()
    {
        $namad_id = request()->id;
        $days = request()->days;

        $inscode = Namad::find($namad_id)->inscode;

        $array = $this->get_history_data($inscode, $days);

        $reversed = array_reverse($array);

        return response()->json(['data' => $reversed], 200);
        return response()->json(['data' => $array], 200);
    }
    public function support_resistance()
    {

        //  $namad_id = request()->id;
        $days = request()->days;
        if (isset(request()->namad)) {

            $namads[]  = Namad::find(request()->namad)->symbol;
        } else {
            if (Cache::has('namadlist')) {
                $namads = Cache::get('namadlist');
            } else {
                $namads = Namad::all();
                Cache::store()->put('namadlist', $namads, 86400); // 10 Minutes
            }
        }

        $data = [];
        foreach ($namads as $key => $namad) {
            $namad = Namad::where('symbol', $namad)->first();

            $cache = Cache::get($namad->id);
            $pl = (int)$cache['pl'];
            $symbol = $cache['symbol'];
            $array = $this->get_history_data($namad->inscode, $days);
            $sum = 0;
            if (count($array)) {
                foreach ($array as $key => $row) {
                    $sum += (int)$row['pl'];
                }
                $avg = $sum / count($array);
                $min_pl = $avg - (($avg * 10) / 100);
                $max_pl = $avg + (($avg * 10) / 100);
                if ($pl > $min_pl && $pl < $max_pl) {
                    $data[] = ['symbol' => $symbol, 'pl' => $pl, 'avg' => $avg];
                }
            }
        }


        return response()->json($data, 200);
    }
}
