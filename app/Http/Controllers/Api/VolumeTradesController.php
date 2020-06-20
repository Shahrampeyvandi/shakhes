<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VolumeTrade;
use App\Setting;

class VolumeTradesController extends Controller
{
    public function get($id = null)
    {
        if($id){
            $volume_trades = VolumeTrade::whereNamad_id($id)->get();
            
        }else{
            $volume_trades = VolumeTrade::latest()->get();
        }
         $all=[];
         foreach ($volume_trades as $key => $volume) {
             $array['symbol'] = $volume->namad->symbol;
             $array['name'] = $volume->namad->name;
             $array['price'] = $volume->namad->dailyReports()->latest()->first()->last_price_value;
             $array['trades_volume'] =  $volume->namad->dailyReports()->latest()->first()->trades_volume;
             $array['base_zarib'] = Setting::first()->trading_volume_ratio;
             $array['current_zarib'] = $volume->volume_ratio;
            $all[]= $array;
         }

       return response()->json(
        $all,
        200
      );
    }
}
