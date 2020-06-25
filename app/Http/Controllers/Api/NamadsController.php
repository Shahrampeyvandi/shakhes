<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Holding\Holding;
use App\Models\Namad\Namad;

class NamadsController extends Controller
{



    public function getalldata($id)
    {
       return Namad::whereId($id)->first()->dailyReports;
    }
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

        $id=$request->id;
        $namad =Namad::find($id);
        if(is_null(($namad))){
            return response()->json(['error'=>'نماد مورد نظر پیدا نشد','data'=>[]],401);
        }
        $namad['final_price_value'] = $report=$namad->dailyReports()->latest()->first()  ? $namad->dailyReports()->latest()->first()->last_price_value : null;
        $namad['final_price_percent'] = $report=$namad->dailyReports()->latest()->first() ? $namad->dailyReports()->latest()->first()->final_price_percent : null;
        $namad['last_price_status'] = $report=$namad->dailyReports()->latest()->first() ? $namad->dailyReports()->latest()->first()->last_price_status : null;
        // check if holding

        if(Holding::where('name',$id)->first()){
            $namad['holding'] = 1;
        }else{
            $namad['holding'] = 0;

        }
        $namad['holding'] = 0;


      $all =  array_merge($namad->toArray(),$namad->getNamadNotifications());

        return response()->json($all,200);
      
    }

    public function getAllNotifications()
    {
        $data =  Namad::GetAllNotifications();
        return response()->json($data,200);
    }
  

}
