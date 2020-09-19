<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;
use App\Models\Holding\Holding;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class NamadsController extends Controller
{

    public function getalldata($id)
    {
        return Namad::whereId($id)->first()->dailyReports;
    }
    public function search(Request $request)
    {

        $key = $request->search;

        $namads = Namad::where('symbol', 'like', '%' . $key . '%')
            ->take(5)->get();

        return response()->json([
            'data' => $namads
        ], 200);
    }

    public function getnamad(Request $request)
    {
        $namad = Namad::find($request->id);
        if ($namad) {
            $information = Cache::get($namad->id);
            $information['symbol']=$namad->symbol;
            $information['name']=$namad->name;
            $information['id']=$namad->id;
            $information['flow']=$namad->flow;
            if(isset($information['pl'])){
                $information['status'] = $information['status'] ;
            }else{
                $information['status'] = 'red' ;
            }
            if (isset($information['namad_status'])) {
                $information['namad_status'] = $information['namad_status'];
            } else {
                $information['namad_status'] = 'A';
            }

            if(Holding::where('namad_id',$namad->id)->first()){
                $information['holding'] = 1;
            }else{
                $information['holding'] = 0;
            }
            $information['holding'] = 0;

            $result =  array_merge($information,$namad->getNamadNotifications());

            return response()->json($result, 200);
        } else {
            return response()->json('namad not found', 401);
        }
    }

    public function getAllNotifications()
    {
        $data = Namad::GetAllNotifications();
        return response()->json($data, 200);
    }
}
