<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CapitalIncrease\CapitalIncrease;

class CapitalIncreasesController extends Controller
{
    public function getall()
    {
    
  
  
      $capitalincreases_array = CapitalIncrease::latest()->get();
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
