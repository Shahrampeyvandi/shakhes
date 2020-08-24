<?php

namespace App\Http\Controllers\Api;

use App\Setting;
use App\Models\Namad\Namad;
use App\Models\VolumeTrade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class VolumeTradesController extends Controller
{
    public function get($id = null)
    {
        if ($id) {
            $volume_trades = VolumeTrade::whereNamad_id($id)->get();
        } else {
            $volume_trades = VolumeTrade::latest()->get();
        }
        $all = [];
        foreach ($volume_trades as $key => $volume) {
            $array['symbol'] = $volume->namad->symbol;
            $array['name'] = $volume->namad->name;
            $array['price'] = $volume->namad->dailyReports()->latest()->first()->last_price_value;
            $array['trades_volume'] =  $volume->namad->dailyReports()->latest()->first()->trades_volume;
            $array['base_zarib'] = Setting::first()->trading_volume_ratio;
            $array['current_zarib'] = $volume->volume_ratio;
            $array['publish_date'] = $volume->created_at;
            $all[] = $array;
        }

        return response()->json(
           ['data'=> $all],
            200
        );
    }

    public function VolumeTradeIncease($id = null)
    {
        if ($id !== null) {
            $namad = Namad::where('id', $id)->first();
            $collection = VolumeTrade::where('namad_id', $namad->id)->get();
        } else {
            $collection = VolumeTrade::latest()->get();
        }

        $all = [
            'saat' => date('H:i'),
            'tarikh' => $this->get_current_date_shamsi()
            ];
        $array = [];
        foreach ($collection as $key => $obj) {
            $array['namad'] = Cache::get($obj->id);
            $array['mothAVG'] = $this->show_with_symbol($obj->month_avg);
            $array['vol'] = $this->show_with_symbol($obj->trade_vol);
            $array['ratio'] = $obj->volume_ratio;
            $array['new'] = $obj->new();
            $array['publish_date'] = $obj->created_at;
            $all[] = $array;
        }

        return response()->json(['data'=>$all], 200);
    }
}
