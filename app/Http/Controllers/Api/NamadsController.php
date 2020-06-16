<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Holding\Holding;
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
        if(is_null(($namad))){
            return response()->json(['error'=>'نماد مورد نظر پیدا نشد','data'=>[]],401);
        }
        $array['final_price_value'] = $report=$namad->dailyReports()->latest()->first()  ? $namad->dailyReports()->latest()->first()->last_price_value : null;
        $array['final_price_percent'] = $report=$namad->dailyReports()->latest()->first() ? $namad->dailyReports()->latest()->first()->final_price_percent : null;
        $array['last_price_status'] = $report=$namad->dailyReports()->latest()->first() ? $namad->dailyReports()->latest()->first()->last_price_status : null;
        // check if holding

        if(Holding::where('name',$request->id)->first()){
            $array['holding'] = true;
        }else{
            $array['holding'] = false;

        }

      $all =  array_merge($array,$namad->getNamadNotifications());

        return response()->json(['data'=>$all,'error'=>false],200);
      
    }

    public function getAllNotifications()
    {
        $data =  Namad::GetAllNotifications();
        return response()->json(['data'=>$data,'error'=>false],200);
    }
  

}
