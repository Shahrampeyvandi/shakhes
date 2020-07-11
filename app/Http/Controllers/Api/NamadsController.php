<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'data' => $namads], 200);

    }

    public function getnamad(Request $request)
    {
        

   $last_minutes = Carbon::now()->subMinutes(3)->format('H:i');
        if (substr($last_minutes, -2, 1) == 0) {
            $last_minutes = str_replace(substr($last_minutes, -2, 1), '', $last_minutes);
        }

        
    //    return $redis_data =count(Redis::hkeys("ID"));
        /**
         * get all namad data in last minutes
         *  
         $all = [];
          $redis_data = Redis::hkeys("ID");
          
          foreach ($redis_data as $key => $item) {
            $all[] = json_decode(Redis::hget($item,$last_minutes),true);
        }
        return response()->json($all);

         */
        
         
        /**
         * 
         * get ir_code all namads
         *    
          $all = [];
          $redis_data = Redis::hkeys("ID");
          
          foreach ($redis_data as $key => $item) {
             
            $single = json_decode(Redis::hget($item,$last_minutes),true);
            if(isset($single["l18"])){
            $all[$item] = $single["l18"];
            }
            
        }
        return response()->json($all);
         */
    


       /**
        * 
        * get all time data for namad sort by time

         $all = [];
          $redis_data = Redis::hgetall("IRO1SEFH0001");
          ksort($redis_data);
          foreach ($redis_data as $key => $item) {
             $all[$key] = json_decode($item, true);
          }
          return response()->json($all);

          */
         


        $id = $request->id;
        $namad = Namad::find($id);
        $code = $namad->code;

       
        $redis_data = Redis::hgetall($code)[$last_minutes];
       $data_obj = json_decode($redis_data, true);

        if (is_null(($data_obj))) {
            return response()->json(['error' => 'نماد مورد نظر پیدا نشد', 'data' => []], 401);
        }
        $data['final_price_value'] = $data_obj["pl"];
        $data['final_price_percent'] = $data_obj["plp"];
        $data['last_price_status'] = $data_obj["plp"] > 0 ? '1' : '0';
        $data['holding'] = 0;
        //     $namad['final_price_value'] = $report=$namad->dailyReports()->latest()->first()  ? $namad->dailyReports()->latest()->first()->last_price_value : null;
        //     $namad['final_price_percent'] = $report=$namad->dailyReports()->latest()->first() ? $namad->dailyReports()->latest()->first()->final_price_percent : null;
        //     $namad['last_price_status'] = $report=$namad->dailyReports()->latest()->first() ? $namad->dailyReports()->latest()->first()->last_price_status : null;
        //     // check if holding

        //     if(Holding::where('name',$id)->first()){
        //         $namad['holding'] = 1;
        //     }else{
        //         $namad['holding'] = 0;

        //     }
        //     $namad['holding'] = 0;

        //   $all =  array_merge($namad->toArray(),$namad->getNamadNotifications());

        $namadnotifications = $namad->getNamadNotifications();

        return response()->json(array_merge($data, $namadnotifications), 200);

    }

    public function getAllNotifications()
    {
        $data = Namad::GetAllNotifications();
        return response()->json($data, 200);
    }

}
