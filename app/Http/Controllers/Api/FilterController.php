<?php

namespace App\Http\Controllers\Api;

use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FilterResource;
use App\Http\Resources\NamadResource;
use Exception;
use Illuminate\Support\Facades\Cache;

class FilterController extends Controller
{
    public function get($kilid)
    {

        if (Cache::has($kilid)) {
           
            
            return $this->JsonResponse(Cache::get($kilid),null,200);
        }

        $namads = Namad::all();
        
        $array = [];

        $data = [];


        if ($kilid == 'person_most_buy_sell' || $kilid == 'person_most_sell_buy') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  $this->get_from_cache($namad->id, $kilid);
            }
            
            asort($array);
            // return $array;
            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                if (isset(Cache::get($namad->id)['filter'])) {
                    $item['namad'] =new NamadResource($namad);
                    $item['first'] = $this->format((float)((float)Cache::get($namad->id)['N_personbuy'] / (float)Cache::get($namad->id)['personbuycount']));
                    $item['second'] = $this->format((float)((float)Cache::get($namad->id)['N_personsell'] / (float)Cache::get($namad->id)['personsellcount']));
                    $item['third'] = isset(Cache::get($namad->id)['filter'][$kilid]) ?  number_format((float)Cache::get($namad->id)['filter'][$kilid], 0) : 0;
                    $data[] = $item;
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
                    $item['namad'] =new NamadResource($namad);
                    $item['first'] = $this->format((float)((float)Cache::get($namad->id)['N_legalbuy'] / (float)Cache::get($namad->id)['legalbuycount']));
                    $item['second'] = $this->format((float)((float)Cache::get($namad->id)['N_legalsell'] / (float)Cache::get($namad->id)['legalsellcount']));
                    $item['third'] = isset(Cache::get($namad->id)['filter'][$kilid]) ?  number_format((float)Cache::get($namad->id)['filter'][$kilid], 0) : 0;
                    $data[] = $item;
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
                 $item['namad'] =new NamadResource($namad);
                $item['first'] = isset(Cache::get($namad->id)['tradevol']) ? strval(Cache::get($namad->id)['tradevol']) : '';
                $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['third'] = isset(Cache::get($namad->id)['tradecash']) ? Cache::get($namad->id)['tradecash'] : '';
                $data[] = $item;
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
                $item['namad'] =new NamadResource($namad);
                $item['first'] = isset(Cache::get($namad->id)['tradecount']) ? strval(Cache::get($namad->id)['tradecount']) : '';
                $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['third'] = isset(Cache::get($namad->id)['N_tradeVol']) ? $this->format(Cache::get($namad->id)['N_tradeVol']) : '';
             

                $data[] = $item;
            }

            return $this->send_json($kilid, $data);
        }
        if ($kilid == 'most_person_buy') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  isset(Cache::get($namad->id)['N_personbuy']) ? Cache::get($namad->id)['N_personbuy'] : '';
            }
            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['namad'] =new NamadResource($namad);
                $item['first'] = isset(Cache::get($namad->id)['personbuycount']) ? strval(Cache::get($namad->id)['personbuycount']) : '';
                $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['third'] = isset(Cache::get($namad->id)['personbuy']) ? Cache::get($namad->id)['personbuy'] : '';
             
                $data[] = $item;
            }

            return $this->send_json($kilid, $data);
        }

        if ($kilid == 'most_person_sell') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  isset(Cache::get($namad->id)['N_personsell']) ? Cache::get($namad->id)['N_personsell'] : '';
            }
            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['namad'] =new NamadResource($namad);
                $item['first'] = isset(Cache::get($namad->id)['personsellcount']) ? strval(Cache::get($namad->id)['personsellcount']) : '';
                $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['third'] = isset(Cache::get($namad->id)['personsell']) ? Cache::get($namad->id)['personsell'] : '';
             
                $data[] = $item;
            }

            return $this->send_json($kilid, $data);
        }

        if ($kilid == 'most_legall_buy') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  isset(Cache::get($namad->id)['N_legalbuy']) ? Cache::get($namad->id)['N_legalbuy'] : '';
            }
            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['namad'] =new NamadResource($namad);
                $item['first'] = isset(Cache::get($namad->id)['legalbuycount']) ? strval(Cache::get($namad->id)['legalbuycount']) : '';
                $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['third'] = isset(Cache::get($namad->id)['legalbuy']) ? Cache::get($namad->id)['legalbuy'] : '';
               
                $data[] = $item;
            }

            return $this->send_json($kilid, $data);
        }

        if ($kilid == 'most_legall_sell') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  isset(Cache::get($namad->id)['N_legalsell']) ? Cache::get($namad->id)['N_legalsell'] : '';
            }
            asort($array);

            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['namad'] =new NamadResource($namad);
                $item['first'] = isset(Cache::get($namad->id)['legalsellcount']) ? strval(Cache::get($namad->id)['legalsellcount']) : '';
                $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['third'] = isset(Cache::get($namad->id)['legalsell']) ? Cache::get($namad->id)['legalsell'] : '';
              
                $data[] = $item;
            }

            return $this->send_json($kilid, $data);
        }

        if ($kilid == 'power_person_buy') {
            foreach ($namads as $namad) {
  
                $array[$namad->symbol] =  $this->get_from_cache($namad->id, $kilid);
            }
            foreach ($array as $array_item) {
                if ($array_item == 0) {
                    unset($array_item);
                }
            }

            asort($array);
          
            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);
            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['namad'] =new NamadResource($namad);
                $item['first'] = isset(Cache::get($namad->id)['personbuycount']) ? strval(Cache::get($namad->id)['personbuycount']) : '';
                $item['second'] = isset(Cache::get($namad->id)['N_personbuy']) ? $this->format((int)Cache::get($namad->id)['N_personbuy']) : 0;
                $item['third'] =isset(Cache::get($namad->id)['filter'][$kilid]) ? $this->format((float)Cache::get($namad->id)['filter'][$kilid]) : 0;
                
                $data[] = $item;
            }
            return $this->send_json($kilid, $data);
        }

          if ($kilid == 'power_person_sell') {
            foreach ($namads as $namad) {
  
                $array[$namad->symbol] =  $this->get_from_cache($namad->id, $kilid);
            }
            foreach ($array as $array_item) {
                if ($array_item == 0) {
                    unset($array_item);
                }
            }

            asort($array);
            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);
            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                if (Cache::get($namad->id)) {
                    $item['namad'] =new NamadResource($namad);
                    $item['first'] = isset(Cache::get($namad->id)['personsellcount']) ? strval(Cache::get($namad->id)['personsellcount']) : '';
                    $item['second'] =isset(Cache::get($namad->id)['personsellcount']) ? $this->format((int)Cache::get($namad->id)['N_personsell']) : 0;
                    $item['third'] =isset(Cache::get($namad->id)['filter'][$kilid]) ?  $this->format((float)Cache::get($namad->id)['filter'][$kilid], 0) : 0;
                    $data[] = $item;
                }
            }
            return $this->send_json($kilid, $data);
        }
    }

    public function get_from_cache($id, $kilid)
    {
        try {
            if (array_key_exists('filter', Cache::get($id)) && isset(Cache::get($id)['filter'][$kilid]) ) {

                return Cache::get($id)['filter'][$kilid];
            } else {
                return 0;
            }
        } catch (Exception $e) {
        }
    }

    public function send_json($kilid, $data)
    {
        Cache::store()->put($kilid, $data, 60 * 5); 
        return $this->JsonResponse($data,null,200);
      
    }
}
