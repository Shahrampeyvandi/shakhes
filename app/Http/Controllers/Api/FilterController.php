<?php

namespace App\Http\Controllers\Api;

use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FilterResource;
use Exception;
use Illuminate\Support\Facades\Cache;

class FilterController extends Controller
{
    public function get($kilid)
    {




        if (Cache::has($kilid)) {
            return Cache::get($kilid);
        }
        
        $namads = Namad::all();
        $array = [];
        $data = [
            'time' => $this->get_current_date_shamsi(),
        ];


        if ($kilid == 'person_most_buy_sell' || $kilid == 'person_most_sell_buy') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  $this->get_from_cache($namad->id, $kilid);
            }

            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return $this->format((float)((float)Cache::get($namad->id)['personsell'] / (float)Cache::get($namad->id)['personsellcount']));
                if (isset(Cache::get($namad->id)['filter'])) {

                    $item['name'] = $namad->name;
                    $item['symbol'] = $namad->symbol;
                    $item['first'] = $this->format((float)((float)Cache::get($namad->id)['personbuy'] / (float)Cache::get($namad->id)['personbuycount']));
                    $item['second'] = $this->format((float)((float)Cache::get($namad->id)['personsell'] / (float)Cache::get($namad->id)['personsellcount']));
                    $item['third'] = isset(Cache::get($namad->id)['filter']['person_most_buy_sell']) ?  number_format((float)Cache::get($namad->id)['filter']['person_most_buy_sell'], 0) : 0;
                    $data['data'][] = $item;
                }
            }

            return $this->send_json($kilid, $data);
        }

        if ($kilid == 'legal_most_buy_sell' || $kilid == 'legal_most_sell_buy') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  $this->get_from_cache($namad->id, $kilid);
            }

            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return $this->format((float)((float)Cache::get($namad->id)['personsell'] / (float)Cache::get($namad->id)['personsellcount']));
                if (isset(Cache::get($namad->id)['filter'])) {
                    $item['name'] = $namad->name;
                    $item['symbol'] = $namad->symbol;
                    $item['second'] = $this->format((float)((float)Cache::get($namad->id)['legalbuy'] / (float)Cache::get($namad->id)['legalbuycount']));
                    $item['first'] = $this->format((float)((float)Cache::get($namad->id)['legalsell'] / (float)Cache::get($namad->id)['legalsellcount']));
                    $item['third'] = isset(Cache::get($namad->id)['filter']['legal_most_buy_sell']) ?  number_format((float)Cache::get($namad->id)['filter']['person_most_buy_sell'], 0) : 0;
                    $data['data'][] = $item;
                }
            }

            return $this->send_json($kilid, $data);
        }
        if ($kilid == 'most_cash_trade') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  Cache::get($namad->id) && isset(Cache::get($namad->id)['N_tradecash']) ? Cache::get($namad->id)['N_tradecash'] : 0;
            }
            asort($array);
            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['name'] = $namad->name;
                $item['symbol'] = $namad->symbol;
                $item['first'] = isset(Cache::get($namad->id)['tradevol']) ? Cache::get($namad->id)['tradevol'] : '';
                $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? Cache::get($namad->id)['final_price_percent'] : '';
                $item['third'] = isset(Cache::get($namad->id)['tradecash']) ? Cache::get($namad->id)['tradecash'] : '';
                $data['data'][] = $item;
            }

            return $this->send_json($kilid, $data);
        }

        if ($kilid == 'most_volume_trade') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  Cache::get($namad->id) && isset(Cache::get($namad->id)['N_tradeVol']) ? Cache::get($namad->id)['N_tradeVol'] : 0;
            }
            asort($array);
            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();

                $item['name'] = $namad->name;
                $item['symbol'] = $namad->symbol;
                $item['first'] = isset(Cache::get($namad->id)['tradecount']) ? Cache::get($namad->id)['tradecount'] : '';
                $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? Cache::get($namad->id)['final_price_percent'] : '';
                $item['third'] = isset(Cache::get($namad->id)['N_tradeVol']) ? $this->format(Cache::get($namad->id)['N_tradeVol']) : '';
                $item['status'] = Cache::get($namad->id)['status'];

                $data['data'][] = $item;
            }

            return $this->send_json($kilid, $data);
        }
        if ($kilid == 'most_person_buy') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  isset(Cache::get($namad->id)['personbuycount']) ? Cache::get($namad->id)['personbuycount'] : '';
            }
            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['name'] = $namad->name;
                $item['symbol'] = $namad->symbol;
                $item['first'] = isset(Cache::get($namad->id)['personbuycount']) ? Cache::get($namad->id)['personbuycount'] : '';
                $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? Cache::get($namad->id)['final_price_percent'] : '';
                $item['third'] = isset(Cache::get($namad->id)['personbuy']) ? Cache::get($namad->id)['personbuy'] : '';
                $item['status'] = Cache::get($namad->id)['status'];
                $data['data'][] = $item;
            }

            return $this->send_json($kilid, $data);
        }

        if ($kilid == 'power_person_buy') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] = isset(Cache::get($namad->id)['N_personbuy']) ? (float)Cache::get($namad->id)['N_personbuy'] : 0;
            }
            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['name'] = $namad->name;
                $item['symbol'] = $namad->symbol;
                $item['first'] = isset(Cache::get($namad->id)['personbuycount']) ? Cache::get($namad->id)['personbuycount'] : '';
               // $item['secondsecond'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] =  $this->format((int)Cache::get($namad->id)['N_personbuy']);
                $item['second'] = $this->format((int)Cache::get($namad->id)['N_personbuy']);
                $item['status'] = Cache::get($namad->id)['status'];
                $data['data'][] = $item;
            }
            return $this->send_json($kilid, $data);
        }
    }
    public function get_from_cache($id, $kilid)
    {
        try {
            if (array_key_exists('filter', Cache::get($id))) {

                return Cache::get($id)['filter'][$kilid];
            } else {
                return 0;
            }
        } catch (Exception $e) {
        }
    }

    public function send_json($kilid, $data)
    {
        Cache::store()->put($kilid, $data, 1000); // 10 Minutes
        return response()->json($data, 200);
    }
}
