<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;

class NamadsController extends Controller
{
    public function search(Request $request)
    {

        $key = $request->search;

        $namads =Namad::where('symbol','like', '%' . $key . '%')
        ->take(5)->get();

        return response()->json([
            'data'=>$namads],200);
      
    }

    public function getnamad(Request $request)
    {

        $namad =Namad::find($request->id);

        $namad['final_price_value'] = $namad->dailyReports()->latest()->first()->last_price_value;
        $namad['final_price_percent'] = $namad->dailyReports()->latest()->first()->final_price_percent;
        $namad['last_price_status'] = $namad->dailyReports()->latest()->first()->last_price_status;



        return response()->json($namad,200);
      
    }
}
