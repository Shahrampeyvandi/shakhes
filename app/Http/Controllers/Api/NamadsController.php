<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;
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

            return response()->json($information, 200);
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
