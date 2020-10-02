<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;
use App\Models\Holding\Holding;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Prophecy\Call\Call;

class NamadsController extends Controller
{

    public function getalldata($id)
    {
        return Namad::whereId($id)->first()->dailyReports;
    }
    public function search(Request $request)
    {

        $key = $request->search;

        $namads = Namad::where('symbol', 'like', '%' . $key . '%')
            ->take(5)->get();

        return response()->json([
            'data' => $namads
        ], 200);
    }

    public function getnamad(Request $request)
    {
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

            $result =  array_merge($information, $namad->getNamadNotifications());

            return response()->json($result, 200);
        } else {
            return response()->json('namad not found', 401);
        }
    }

    public function getAllNotifications()
    {
        $data = Namad::GetAllNotifications();
        return response()->json($data, 200);
    }

    public function show($id)
    {

        if (Cache::has('data-' . $id)) {
            return response()->json(['data' => Cache::get('data-' . $id), 'error' => false]);
        }

        $namad = Namad::find($id);
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
            $array['pmin'] = Cache::get($id)['pmin'];
            $array['pmax'] = Cache::get($id)['pmin'];
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
            $array['flow'] = Cache::get($id)['flow'];
            $array['ID'] = Cache::get($id)['ID'];
            $array['BaseVol'] =  Cache::get($id)['BaseVol'];
            $array['status'] =  ($array['pl'] - $array['py'])  > 0 ? 'green' : 'red';
            $array['personbuy'] = Cache::get($id)['personbuy'];
            $array['legalbuy'] = Cache::get($id)['legalbuy'];
            $array['personsell'] = Cache::get($id)['personsell'];
            $array['legalsell'] = Cache::get($id)['legalsell'];
            $array['personbuycount'] = Cache::get($id)['personbuycount'];
            $array['legalbuycount'] = Cache::get($id)['legalbuycount'];
            $array['personsellcount'] = Cache::get($id)['personsellcount'];
            $array['legalsellcount'] = Cache::get($id)['legalsellcount'];
            $array['person_buy_power'] = Cache::get($id)['person_buy_power'];
            $array['person_sell_power'] = Cache::get($id)['person_sell_power'];
            $array['percent_legal_buy'] = Cache::get($id)['percent_legal_buy'];
            $array['percent_person_sell'] = Cache::get($id)['percent_person_sell'];
            $array['percent_legal_sell'] = Cache::get($id)['percent_legal_sell'];

            Cache::put('data-' . $id, $array, 5);  // 5 seconds
            return response()->json(['data' => $array, 'error' => false]);
        }
    }
}
