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
        foreach ($namads as $namad) {
            $array[$namad->symbol] =  $this->get_from_cache($namad->id, $key);
        }
        asort($array);
        $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);
        if ($key == 'person_most_buy_sell') {
            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                $item['name'] = $namad->name;
                $item['symbol'] = $namad->symbol;
                $item['tedad'] = Cache::get($namad->id)['person_most_buy_sell'];
                $item['pl'] = Cache::get($namad->id)['final_price_value'];
                $item['final_price_percent'] = Cache::get($namad->id)['final_price_percent'];
                $item['tradeVol'] = Cache::get($namad->id)['tradevol'];
                $data[] = $item;
            }
        }

        return $data;
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
