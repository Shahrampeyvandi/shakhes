<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CapitalIncrease\CapitalIncrease;
use App\Models\clarification;
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
      $array[$namad_data->symbol]['market'] = $namad_data->market;
      $array[$namad_data->symbol]['amount'] = $namad_data->pivot->amount;
      $array[$namad_data->symbol]['profit_loss_percent'] = $namad_data->pivot->profit_loss_percent;
      $array[$namad_data->symbol]['price'] = $namad_data->pivot->price;
    }

    return response()->json(
      $array,
      200
    );
  }


  public function add(Request $request)
  {
    $member = $this->token($request->header('Authorization'));
    $namad_id = $request->namad_id;
    $namad_obj = Namad::whereId($namad_id)->first();
    $namad_daily_report = $namad_obj->dailyReports->first();
    $price = !is_null($namad_daily_report) ? $namad_daily_report->last_price_value : 0;

    $member->namads()->attach($namad_id, ['amount' => 0, 'profit_loss_percent' => 0, 'price' => $price]);
    return response()->json(
      [
        'error' => ''
      ],
      200
    );
  }

  public function getclarifications()
  {
    $member = $this->token(request()->header('Authorization'));

    $member_namads = $member->namads->pluck('id')->toArray();
    $clarifications_array = clarification::whereIn('namad_id', $member_namads)->latest()->get();
    $count = 1;
    foreach ($clarifications_array as $key => $clarification_obj) {
      $array[$count]['namad'] = $clarification_obj->namad->name;
      $array[$count]['subject'] = $clarification_obj->subject;
      $array[$count]['publish_date'] = $clarification_obj->publish_date;
      $array[$count]['link_to_codal'] = $clarification_obj->link_to_codal;
      $array[$count]['new'] = $clarification_obj->new;

      $count++;
    }
    return response()->json(
      $array,
      200
    );
  }

  public function read_clarification(Request $request)
  {
    clarification::whereId($request->clarification_id)->update(
      [
        'new' => 0
      ]
    );
    return response()->json(
      ['error' => ''],
      200
    );
  }

  public function getcapitalincreases()
  {
    $member = $this->token(request()->header('Authorization'));

    $member_namads = $member->namads->pluck('id')->toArray();
    $capitalincreases_array = CapitalIncrease::whereIn('namad_id', $member_namads)->latest()->get();
    $count = 1;
    foreach ($capitalincreases_array as $key => $capitalincrease_obj) {
      $array[$count]['namad'] = $capitalincrease_obj->namad->name;
      $array[$count]['step'] = $capitalincrease_obj->step;
      // چک میشود اگر افزایش سرمایه ترکیبی باشد
      if ($capitalincrease_obj->from == 'compound') {
        foreach ($capitalincrease_obj->amounts as $key => $item) {
          $array[$count]['from'][$item->type] = $item->percent;
        }
      } else {

        $array[$count]['from'] = $capitalincrease_obj->from;
      }

      $array[$count]['publish_date'] = $capitalincrease_obj->publish_date;
      $array[$count]['link_to_codal'] = $capitalincrease_obj->link_to_codal;
      $array[$count]['description'] = $capitalincrease_obj->description;
      $array[$count]['new'] = $capitalincrease_obj->new;

      $count++;
    }
    return response()->json(
      $array,
      200
    );
  }
}
