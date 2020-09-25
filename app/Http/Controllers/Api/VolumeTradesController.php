<?php

namespace App\Http\Controllers\Api;

use App\Setting;
use Carbon\Carbon;
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
            $volume_trades = VolumeTrade::whereNamad_id($id)->paginate(20);
        } else {
            $volume_trades = VolumeTrade::whereDate('created_at', Carbon::today())->paginate(20);
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
            $collection = VolumeTrade::where('namad_id', $namad->id)->paginate(20);

        } else {
            $collection = VolumeTrade::latest()->paginate(20);
            $all = [
                'time' => $this->get_current_date_shamsi().'_'.date('H:i'),
                ];
        }


       
        $list = [];
        foreach ($collection as $key => $obj) {
            $array=[];
            $namad = Namad::where('id', $obj->namad_id)->first();
            $array['namad'] = Cache::get($obj->namad_id);
            $array['namad']['symbol'] = $namad->symbol;
            $array['namad']['name'] = $namad->name;
            $array['mothAVG'] = $this->show_with_symbol($obj->month_avg);
            $array['vol'] = $this->show_with_symbol($obj->trade_vol);
            $array['ratio'] = $obj->volume_ratio;
            $array['new'] = $obj->new();
            $array['publish_date'] = substr($obj->created_at,0,10);
            $list[] = $array;
        }
        if ($id !== null) {
            return response()->json(['data'=>$list], 200);

        }else{
            $all['data']=$list;

            return response()->json($all, 200);

        }


    }
}
