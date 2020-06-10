<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Member\Member;
use App\Models\Namad\Namad;
use Tymon\JWTAuth\Facades\JWTAuth;

class MembersDataController extends Controller
{


  public function personaldata(Request $request)
  {
    $member = $this->token($request->header('Authorization'));
    $array['fname'] = $member->fname;
    $array['lname'] = $member->lname;
    $array['mobile'] = $member->phone;


    return response()->json(
      $array,
      200
    );
  }


  public function namads(Request $request)
  {
    $member = $this->token($request->header('Authorization'));
    $namads_array = $member->namads;


    foreach ($namads_array as $key => $namad_data) {
      $array[$namad_data->symbol]['final_price_value'] = $namad_data->dailyReports()->latest()->first()->last_price_value;
      $array[$namad_data->symbol]['final_price_percent'] = $namad_data->dailyReports()->latest()->first()->final_price_percent;
    }

    return response()->json(
      $array,
      200
    );
  }


  public function add(Request $request)
  {
    $member = $this->token($request->header('Authorization'));
    $namad_id = $request->id;
    $namad_obj = Namad::whereId($namad_id)->first();
    $namad_daily_report = $namad_obj->dailyReports->first();
    $price = !is_null($namad_daily_report) ? $namad_daily_report->last_price_value : 0;

    $member->namads()->attach($namad_id, ['amount' => 0, 'profit_loss_percent' => 0,'price'=>$price]);
      return response()->json(
        [
          'error' => ''
        ],
        200
      );
   
  }
}
