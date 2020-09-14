<?php

namespace App\Http\Controllers\Api;

use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FilterResource;
use Illuminate\Support\Facades\Cache;

class FilterController extends Controller
{
    public function get($key)
    {


        $namads = Namad::all();
        $array = [];
        $data = [
            'time' => $this->get_current_date_shamsi() . '_' . date('H:i'),
        ];
 

        if ($key == 'person_most_buy_sell' || $key == 'person_most_sell_buy' || $key == 'legal_most_buy_sell' || $key == 'legal_most_sell_buy') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  $this->get_from_cache($namad->id, $key);
            }
            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['name'] = $namad->name;
                $item['symbol'] = $namad->symbol;
                $item['buy_avg'] = $this->format((float)((float)Cache::get($namad->id)['personbuy'] / (float)Cache::get($namad->id)['personbuycount']));
                $item['sell_avg'] = $this->format((float)((float)Cache::get($namad->id)['personsell'] / (float)Cache::get($namad->id)['personsellcount']));
                $item['nesbat'] = number_format((float)Cache::get($namad->id)['filter']['person_most_buy_sell'], 0);
                $data['data'][] = $item;
            }
            return $data;
        }
        if ($key == 'most_cash_trade') {
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
                $item['tradevol'] = isset(Cache::get($namad->id)['tradevol']) ? Cache::get($namad->id)['tradevol'] : '';
                $item['pl'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['final_price_percent'] = isset(Cache::get($namad->id)['final_price_percent']) ? Cache::get($namad->id)['final_price_percent'] : '';
                $item['tradecash'] = isset(Cache::get($namad->id)['tradecash']) ? Cache::get($namad->id)['tradecash'] : '';
                $data['data'][] = $item;
            }

            return $data;
        }

        if ($key == 'most_volume_trade') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  Cache::get($namad->id) && isset(Cache::get($namad->id)['N_tradeVol']) ? Cache::get($namad->id)['N_tradeVol'] : 0;
            }
            asort($array);
            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                return Cache::get($namad->id);
                $item['name'] = $namad->name;
                $item['symbol'] = $namad->symbol;
                $item['tradecount'] = isset(Cache::get($namad->id)['tradecount']) ? Cache::get($namad->id)['tradecount'] : '';
                $item['pl'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['final_price_percent'] = isset(Cache::get($namad->id)['final_price_percent']) ? Cache::get($namad->id)['final_price_percent'] : '';
                $item['tradevol'] = isset(Cache::get($namad->id)['tradevol']) ? Cache::get($namad->id)['tradevol'] : '';
                $data['data'][] = $item;
            }

            return $data;
        }
        if ($key == 'most_person_buy') {
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
                $item['personbuycount'] = isset(Cache::get($namad->id)['personbuycount']) ? Cache::get($namad->id)['personbuycount'] : '';
                $item['pl'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['personbuy'] = isset(Cache::get($namad->id)['personbuy']) ? Cache::get($namad->id)['personbuy'] : '';
                $data['data'][] = $item;
            }

            return $data;
        }

        if ($key == 'power_person_buy') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  $this->get_from_cache($namad->id, $key);
            }
            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['name'] = $namad->name;
                $item['symbol'] = $namad->symbol;
                $item['personbuycount'] = isset(Cache::get($namad->id)['personbuycount']) ? Cache::get($namad->id)['personbuycount'] : '';
                $item['pl'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['buy_avg'] =  isset(Cache::get($namad->id)['N_personbuy']) && Cache::get($namad->id)['N_personbuy'] > 0 ? $this->format((int)Cache::get($namad->id)['N_personbuy'] / Cache::get($namad->id)['personbuycount']) : 0;
                $data['data'][] = $item;
            }

            return $data;
        }
    }
    public function get_from_cache($id, $key)
    {
        if (array_key_exists('filter', Cache::get($id))) {

            return Cache::get($id)['filter'][$key];
        } else {
            return 0;
        }
    }
}
