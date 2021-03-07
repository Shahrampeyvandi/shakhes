<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\clarification;
use App\Http\Controllers\Controller;
use App\Http\Resources\NamadResource;
use Illuminate\Support\Facades\Cache;

class ClarificationController extends Controller
{
  public function getall()
  {

    if (isset(request()->section) && request()->section == 'mynamad') {
      $member = $this->token(request()->header('Authorization'));
      $namads = $member->namads->pluck('id')->toArray();
      $clarifications = clarification::whereIn('namad_id', $namads)->latest()->paginate(20);
    } else {

      if (isset(request()->namad) && request()->namad) {
        $clarifications = clarification::where('namad_id', request()->namad)->latest()->paginate(20);
      } else {
        $clarifications = clarification::latest()->paginate(20);
      }
    }

    $count = 1;
    $all = [];
    try {
      foreach ($clarifications as $key => $clarification_obj) {
        $namad_obj = $clarification_obj->namad;
        if ($namad_obj && Cache::has($namad_obj->id)) {
          $array['namad'] = new NamadResource($namad_obj);
          $array['newsId'] = $clarification_obj->id;
          $array['newsDate'] = $clarification_obj->get_codal_date();
          $array['newsTime'] = $clarification_obj->get_codal_time();
          $array['newsLink'] = $clarification_obj->link_to_codal;
          $array['newsText'] = $clarification_obj->subject;
          $array['isBookmarked'] = false;
          $all[] = $array;
          $count++;
        }
      }
      $error = null;
    } catch (\Throwable $th) {
      $error = 'خطا در دریافت اطلاعات از سرور';
    }
    $status = 200;
    return $this->JsonResponse($all, $error, $status);
  }
}
