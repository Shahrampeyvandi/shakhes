<?php

namespace App\Http\Controllers\Api;

use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class FilterController extends Controller
{
    public function get($key)
    {
       
            $namads = Namad::all();

            $array = [];
            foreach ($namads as $namad) {
               $array[$namad->symbol] =  $this->get_from_cache($namad->id,$key);
            }
            asort($array);
            return array_keys(array_reverse($array));
        
    }
    public function get_from_cache($id,$key)        
    {
        if(array_key_exists('filter',Cache::get($id))){

            return Cache::get($id)['filter'][$key];
        }else{
            return 0;
        }

    }
}
