<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\clarification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ClarificationController extends Controller
{
    public function getall()
    {
       $clarifications = clarification::latest()->get();
    $count = 1;
    $all = [];
   try {
      foreach ($clarifications as $key => $clarification_obj) {
      $namad_obj = $clarification_obj->namad;
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
      $array['newsId'] = $clarification_obj->id;
      $array['newsDate'] = Jalalian::forge($clarification_obj->publish_date)->format('Y/m/d');
      $array['newsLink'] = $clarification_obj->link_to_codal;
      $array['newsText'] = $clarification_obj->description;
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
