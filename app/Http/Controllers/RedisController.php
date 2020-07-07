<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    public function getmain(){
        //$redis = Redis::connection();
        $values = Redis::command('keys', ['*']);
        // dd($values);

        // foreach($values as $vla){
        //     $data = Redis::hgetall($vla);
        //     $data = json_decode(end($data), true);

        //     //dd($data);
        //     echo $data['l30'];
        //     echo '</br>';

        // }
        $allmarket = Redis::hgetall('IRB3TB630091')['13:42'];
        //$user = json_decode(end($user), true);
        return $allmarket;
        $all = [];
        foreach ($allmarket as $key => $item) {
            $item = [];

        }
       // echo $user;

       

    }
}
