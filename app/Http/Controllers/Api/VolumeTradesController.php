<?php

namespace App\Http\Controllers\Api;

use App\Setting;
use Carbon\Carbon;
use App\Models\Namad\Namad;
use App\Models\VolumeTrade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VolumeTradeResource;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\Jalalian;

class VolumeTradesController extends Controller
{
    public function get($id = null)
    {
        // VolumeTrade::whereDate('created_at','<',Carbon::now()->subMonth())->delete();

        try {
            if ($id) {
                $volume_trades = VolumeTrade::whereNamad_id($id)->paginate(20);
            } else {

                if (Cache::has('volume-trades')) {
                    return $this->JsonResponse(Cache::get('volume-trades'), null, 200);
                }

                if (Cache::has('bazarstatus') && Cache::get('bazarstatus') == 'close') {
                    $orderby = 'volume_ratio';
                } else {
                    $orderby = 'updated_at';
                }

                if (isset(request()->ratio)) {
                    if (VolumeTrade::whereDate('created_at', Carbon::today())->count()) {
                        $volume_trades = VolumeTrade::whereDate('created_at', Carbon::today())->orderBy($orderby, 'desc')
                            ->where('volume_ratio', '>=', request()->ratio)
                            ->get();
                    } else {
                        $volume_trades = VolumeTrade::orderBy($orderby, 'desc')
                            ->where('volume_ratio', '>=', request()->ratio)
                            ->take(40)->get();
                    }
                } else {
                    if (VolumeTrade::whereDate('created_at', Carbon::today())->count()) {
                       
                        $volume_trades = VolumeTrade::whereDate('created_at', Carbon::today())->orderBy($orderby, 'desc')->get();
                    } else {
                       
                        $volume_trades = VolumeTrade::orderBy($orderby, 'desc')->take(40)->get();
                    }
                }
            }



            $all = VolumeTradeResource::collection($volume_trades);
            if (!$id) {

                Cache::put('volume-trades', $all, 60);
            }

            $status = 200;
            $error = null;
        } catch (\Exception $th) {
            $all = [];
            $error = 'خطا در دریافت اطلاعات';
            $status = 200;
        }


        return $this->JsonResponse($all, $error, $status);
    }

    public function VolumeTradeIncease($id = null)
    {
        if ($id !== null) {
            $namad = Namad::where('id', $id)->first();
            $collection = VolumeTrade::where('namad_id', $namad->id)->paginate(20);
        } else {

            if (Cache::has('bazarstatus') && Cache::get('bazarstatus') == 'close') {
                $orderby = 'volume_ratio';
            } else {
                $orderby = 'updated_at';
            }
            $collection = VolumeTrade::where('created_at', '>=', Carbon::today()->toDateString())->orderBy($orderby, 'desc')->paginate(20);
            $all = [
                'time' => $this->get_current_date_shamsi() . '_' . date('H:i'),
            ];
        }




        $list = [];
        foreach ($collection as $key => $obj) {
            $array = [];
            $namad = Namad::where('id', $obj->namad_id)->first();
            $array['namad'] = Cache::get($obj->namad_id);
            $array['namad']['symbol'] = $namad->symbol;
            $array['namad']['id'] = $namad->id;
            $array['namad']['name'] = $namad->name;
            $array['mothAVG'] = $this->show_with_symbol($obj->month_avg);
            $array['vol'] = $this->show_with_symbol($obj->trade_vol);
            $array['ratio'] = $obj->volume_ratio;
            $array['new'] = $obj->new();
            $array['publish_date'] = substr($obj->created_at, 0, 10);
            $list[] = $array;
        }
        if ($id !== null) {
            return response()->json(['data' => $list], 200);
        } else {
            $all['data'] = $list;

            return response()->json($all, 200);
        }
    }
}
