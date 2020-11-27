<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CapitalIncrease\CapitalIncrease;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\Jalalian;

class CapitalIncreasesController extends Controller
{
  public function getall()
  {

    $capitalincreases_array = CapitalIncrease::latest()->paginate(20);
    $count = 1;
    $all = [];
   try {
      foreach ($capitalincreases_array as $key => $capitalincrease_obj) {
      $namad_obj = $capitalincrease_obj->namad;
      $cache = Cache::get($namad_obj->id);
      $array['namad'] = [
        'id' => $namad_obj->id,
        'symbol'=>$cache['symbol'],
        'name'=>$cache['name'],
        'final_price_value' =>$cache['final_price_value'],
        'final_price_percent' => $cache['final_price_percent'],
        'final_price_change' => $cache['last_price_change'],
         'final_price_status' => $cache['last_price_status'] ? '+' : '-', 
      ];
      $array['newsId'] = $capitalincrease_obj->id;
      $array['newsDate'] = Jalalian::forge($capitalincrease_obj->publish_date)->format('Y/m/d');
      $array['newsLink'] = $capitalincrease_obj->link_to_codal;
      $array['newsText'] = $capitalincrease_obj->description;
      $array['isBookmarked'] = false;
      $all[] = $array;
      $count++;
    }
    $status = 200;
    $error = null;
   } catch (\Throwable $th) {
     $error = 'خطا در دریافت اطلاعات از سرور';
   }
    return $this->JsonResponse($all,$error,$status);
  }
}
