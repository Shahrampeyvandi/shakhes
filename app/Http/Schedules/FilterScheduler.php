<?php

namespace App\Http\Schedules;

use App\Http\Resources\NamadResource;
use Exception;
use App\Models\Namad\Namad;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\Jalalian;


class FilterScheduler
{


    public function __invoke()
    {

//        if (strtotime('08:30') < strtotime(date('H:i')) &&  strtotime('13:00') > strtotime(date('H:i'))) {
            $this->get_data();
//        }
    }


    public function get_data()
    {
        $filters_arr = [
            'person_most_buy_sell',
            'person_most_sell_buy',
            'most_cash_trade',
            'most_volume_trade',
            'most_person_buy',
            'most_person_sell',
            'most_legall_buy',
            'most_legall_sell',
            'power_person_buy',
            'power_person_sell'
        ];

        foreach ($filters_arr as $key => $value) {

                $this->get($value);
                // dd(Cache::get("$value"));


        }


        echo 'information stored ';
    }
    public function get($kilid)
    {

        if (Cache::has('namadlist')) {
            $namads = Cache::get('namadlist');
        } else {
            $namads = Namad::all();
            Cache::store()->put('namadlist', $namads, 86400); // 10 Minutes
        }

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
                    $item['namad'] = new NamadResource($namad);
                    // $item['first'] = $this->format((float)((float)Cache::get($namad->id)['N_personbuy'] / (float)Cache::get($namad->id)['personbuycount']));
                    // $item['second'] = $this->format((float)((float)Cache::get($namad->id)['N_personsell'] / (float)Cache::get($namad->id)['personsellcount']));
                    $item['first'] = isset(Cache::get($namad->id)['filter'][$kilid]) ?  number_format((float)Cache::get($namad->id)['filter'][$kilid], 0) : 0;
                    $data[] = $item;
                }
            }

             $this->send_json($kilid, $data);
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
                    $item['namad'] = new NamadResource($namad);
                    // $item['first'] = $this->format((float)((float)Cache::get($namad->id)['N_legalbuy'] / (float)Cache::get($namad->id)['legalbuycount']));
                    // $item['second'] = $this->format((float)((float)Cache::get($namad->id)['N_legalsell'] / (float)Cache::get($namad->id)['legalsellcount']));
                    $item['first'] = isset(Cache::get($namad->id)['filter'][$kilid]) ?  number_format((float)Cache::get($namad->id)['filter'][$kilid], 0) : 0;
                    $data[] = $item;
                }
            }

             $this->send_json($kilid, $data);
        }

        if ($kilid == 'most_cash_trade') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  Cache::get($namad->id) && isset(Cache::get($namad->id)['N_tradeCash']) ? Cache::get($namad->id)['N_tradeCash'] : 0;
            }
            asort($array);
            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                // return Cache::get($namad->id);
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['tradevol']) ? strval(Cache::get($namad->id)['tradevol']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['N_tradeCash']) ? $this->format(Cache::get($namad->id)['N_tradeCash'], 'fa') : '';
                $data[] = $item;
            }

             $this->send_json($kilid, $data);
        }

        if ($kilid == 'most_volume_trade') {
            foreach ($namads as $namad) {
                $array[$namad->symbol] =  Cache::get($namad->id) && isset(Cache::get($namad->id)['N_tradeVol']) ? Cache::get($namad->id)['N_tradeVol'] : 0;
            }
            asort($array);
            $symbols_array = array_slice(array_keys(array_reverse($array)), 0, 50);

            foreach ($symbols_array as $key => $symbol) {
                $namad = Namad::whereSymbol($symbol)->first();
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['tradecount']) ? strval(Cache::get($namad->id)['tradecount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['N_tradeVol']) ? $this->format(Cache::get($namad->id)['N_tradeVol'], 'fa') : '';


                $data[] = $item;
            }

             $this->send_json($kilid, $data);
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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['personbuycount']) ? strval(Cache::get($namad->id)['personbuycount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['personbuy']) ? $this->format(Cache::get($namad->id)['N_personbuy'], 'fa') : '';

                $data[] = $item;
            }

             $this->send_json($kilid, $data);
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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['personsellcount']) ? strval(Cache::get($namad->id)['personsellcount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['personsell']) ? $this->format(Cache::get($namad->id)['N_personsell'], 'fa') : '';

                $data[] = $item;
            }

             $this->send_json($kilid, $data);
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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['legalbuycount']) ? strval(Cache::get($namad->id)['legalbuycount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['legalbuy']) ? $this->format(Cache::get($namad->id)['N_legalbuy'], 'fa') : '';

                $data[] = $item;
            }

             $this->send_json($kilid, $data);
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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['legalsellcount']) ? strval(Cache::get($namad->id)['legalsellcount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['legalsell']) ? $this->format(Cache::get($namad->id)['N_legalsell'], 'fa') : '';

                $data[] = $item;
            }

             $this->send_json($kilid, $data);
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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['personbuycount']) ? strval(Cache::get($namad->id)['personbuycount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['N_personbuy']) ? $this->format((int)Cache::get($namad->id)['N_personbuy']) : 0;
                $item['first'] = isset(Cache::get($namad->id)['filter'][$kilid]) ? $this->format((float)Cache::get($namad->id)['filter'][$kilid], 'fa') : 0;

                $data[] = $item;
            }
             $this->send_json($kilid, $data);
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
                    $item['namad'] = new NamadResource($namad);
                    // $item['first'] = isset(Cache::get($namad->id)['personsellcount']) ? strval(Cache::get($namad->id)['personsellcount']) : '';
                    // $item['second'] =isset(Cache::get($namad->id)['personsellcount']) ? $this->format((int)Cache::get($namad->id)['N_personsell']) : 0;
                    $item['first'] = isset(Cache::get($namad->id)['filter'][$kilid]) ?  $this->format((float)Cache::get($namad->id)['filter'][$kilid], 'fa') : 0;
                    $data[] = $item;
                }
            }
             $this->send_json($kilid, $data);
        }
    }

    public function get_from_cache($id, $kilid)
    {
        try {
            if (array_key_exists('filter', Cache::get($id)) && isset(Cache::get($id)['filter'][$kilid])) {

                return Cache::get($id)['filter'][$kilid];
            } else {
                return 0;
            }
        } catch (Exception $e) {
        }
    }

    public function send_json($kilid, $data)
    {
        Cache::put($kilid, $data);
        \Log::info('Filter '.$kilid.' inserted at: '.date('Y-m-d H:i:s'));
//        return $this->JsonResponse($data, null, 200);
    }

    public function JsonResponse($data, $error, $status = 200)
    {
        return response()->json(
            [
                'data' => null,
                'responseDate' => Jalalian::forge('now')->format('Y/m/d'),
                'responseTime' => Jalalian::forge('now')->format('H:m'),
                'errorMessage' => $error
            ],
            $status
        );
    }

    public function format($number,$lang = 'en')
    {

        if ($number > 0 &&  $number < 1000000) {
            return number_format($number);
        } elseif ($number > 1000000 &&  $number < 1000000000) {
            $number = number_format($number / 1000000,2,'.','') + 0;
            if($lang == 'fa') {
                $label = ' میلیون';
            }else{
                $label = ' M';
            }
            return $number = number_format($number, 2) . $label;
        } elseif ($number > 1000000000) {
            $number =  number_format($number / 1000000000,2,'.','') + 0;
            if($lang == 'fa') {
                $label = ' میلیارد';
            }else{
                $label = ' B';
            }
            return  $number = number_format($number, 2) . $label;

        }
    }

}
