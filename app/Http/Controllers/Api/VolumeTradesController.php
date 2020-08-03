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
            $all[] = $array;
        }

        return response()->json(
            $all,
            200
        );
    }

    public function VolumeTradeIncease($id)
    {

        $namad = Namad::where('id', $id)->first();
        if ($namad) {

            $information = Cache::get($namad->id);
            $monthAVG = $information['N_monthAVG'];
            $tradevol = $information['N_tradevol'];

            $zarib = $tradevol / $monthAVG;
            if ($zarib > 4) {
                VolumeTrade::create(['namad_id' => $id, 'trade_vol' => $tradevol, 'month_avg' => $monthAVG, 'volume_ratio' => $zarib]);
            }

            return response()->json($information, 200);
        }
    }
}
