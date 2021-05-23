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

    /**
     * list of index page
     *
     * @return void
     */
    public function list()
    {
        $arr = [
            ['en'=>'most_cash_trade' ,'fa'=> 'بیشترین ارزش معامله','icon'=>'gold'],
            ['en'=>'most_volume_trade' ,'fa'=> 'بیشترین حجم معامله','icon'=>'gold'],
            ['en'=>'power_person_buy' ,'fa'=> 'قویترین خریداران حقیقی','icon'=>'green'],
            ['en'=>'power_person_sell' ,'fa'=> 'قویترین فروشندگان حقیقی','icon'=>'red'],
            ['en'=>'person_most_buy_sell' ,'fa'=> 'بالاترین نسبت های خرید به فروش حقیقی','icon'=>'green'],
            ['en'=>'person_most_sell_buy' ,'fa'=> 'بالاترین نسبت های فروش به خرید حقیقی','icon'=>'red'],
            ['en'=>'most_person_buy' ,'fa'=> 'بیشترین خرید حقیقی','icon'=>'green'],
            ['en'=>'most_person_sell' ,'fa'=> 'بیشترین فروش حقیقی','icon'=>'red'],
            ['en'=>'most_legall_buy' ,'fa'=> 'بیشترین خرید حقوقی','icon'=>'green'],
            ['en'=>'most_legall_sell' ,'fa'=> 'بیشترین فروش حقوقی','icon'=>'red']
        ];

        try {
            foreach ($arr as  $value) {
                $cache = Cache::get($value['en']);

                 if ($cache) {
                     $slice =  array_slice($cache, 0, 3);
                     $data = [];
                     $data['title'] = $value['fa'];
                     $data['key'] = $value['en'];
                     $count = 1;
                     foreach ($slice as  $item) {

                             $data['namads'][] = [
                                 'name' => $item['namad']->symbol,
                                 'icon' => asset('assets/images/filter-icons/'.$value['icon'].'-'.$count.'.png')
                             ];
                             $count++;

                     }
                     $arrr[] = $data;
                 }
             }
             $error = null;

        } catch (\Throwable $th) {
            $error = 'خطای سرور لطفا بعدا تلاش کنید';
            $errr = null;
        }

        return $this->JsonResponse($arrr, $error, 200);
    }

    public function get($kilid)
    {

        if (Cache::has($kilid)) {
            return $this->JsonResponse(Cache::get($kilid), null, 200);
        }

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
                    $item['namad'] = new NamadResource($namad);
                    // $item['first'] = $this->format((float)((float)Cache::get($namad->id)['N_legalbuy'] / (float)Cache::get($namad->id)['legalbuycount']));
                    // $item['second'] = $this->format((float)((float)Cache::get($namad->id)['N_legalsell'] / (float)Cache::get($namad->id)['legalsellcount']));
                    $item['first'] = isset(Cache::get($namad->id)['filter'][$kilid]) ?  number_format((float)Cache::get($namad->id)['filter'][$kilid], 0) : 0;
                    $data[] = $item;
                }
            }

            return $this->send_json($kilid, $data);
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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['tradecount']) ? strval(Cache::get($namad->id)['tradecount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['N_tradeVol']) ? $this->format(Cache::get($namad->id)['N_tradeVol'], 'fa') : '';


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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['personbuycount']) ? strval(Cache::get($namad->id)['personbuycount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['personbuy']) ? $this->format(Cache::get($namad->id)['N_personbuy'], 'fa') : '';

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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['personsellcount']) ? strval(Cache::get($namad->id)['personsellcount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['personsell']) ? $this->format(Cache::get($namad->id)['N_personsell'], 'fa') : '';

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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['legalbuycount']) ? strval(Cache::get($namad->id)['legalbuycount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['legalbuy']) ? $this->format(Cache::get($namad->id)['N_legalbuy'], 'fa') : '';

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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['legalsellcount']) ? strval(Cache::get($namad->id)['legalsellcount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['pl']) ? Cache::get($namad->id)['pl'] : '';
                // $item['secondsecond'] = isset(Cache::get($namad->id)['final_price_percent']) ? strval(Cache::get($namad->id)['final_price_percent']) : '';
                $item['first'] = isset(Cache::get($namad->id)['legalsell']) ? $this->format(Cache::get($namad->id)['N_legalsell'], 'fa') : '';

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
                $item['namad'] = new NamadResource($namad);
                // $item['first'] = isset(Cache::get($namad->id)['personbuycount']) ? strval(Cache::get($namad->id)['personbuycount']) : '';
                // $item['second'] = isset(Cache::get($namad->id)['N_personbuy']) ? $this->format((int)Cache::get($namad->id)['N_personbuy']) : 0;
                $item['first'] = isset(Cache::get($namad->id)['filter'][$kilid]) ? $this->format((float)Cache::get($namad->id)['filter'][$kilid], 'fa') : 0;

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
                    $item['namad'] = new NamadResource($namad);
                    // $item['first'] = isset(Cache::get($namad->id)['personsellcount']) ? strval(Cache::get($namad->id)['personsellcount']) : '';
                    // $item['second'] =isset(Cache::get($namad->id)['personsellcount']) ? $this->format((int)Cache::get($namad->id)['N_personsell']) : 0;
                    $item['first'] = isset(Cache::get($namad->id)['filter'][$kilid]) ?  $this->format((float)Cache::get($namad->id)['filter'][$kilid], 'fa') : 0;
                    $data[] = $item;
                }
            }
            return $this->send_json($kilid, $data);
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
         Cache::store()->put($kilid, $data, 60 * 5);
        return $this->JsonResponse($data, null, 200);
    }
}
