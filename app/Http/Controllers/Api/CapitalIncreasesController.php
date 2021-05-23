<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NamadResource;
use App\Models\CapitalIncrease\CapitalIncrease;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\Jalalian;

class CapitalIncreasesController extends Controller
{
  public function getall()
  {
    if (isset(request()->section) && request()->section == 'mynamad') {
      $member = $this->token(request()->header('Authorization'));
      $namads = $member->namads->pluck('id')->toArray();
      $capitalincreases_array = CapitalIncrease::whereIn('namad_id', $namads)->latest()->paginate(5);
    } else {
       if (isset(request()->namad) && request()->namad) {
        $capitalincreases_array = CapitalIncrease::where('namad_id', request()->namad)->latest()->paginate(5);
      } else {
        $capitalincreases_array = CapitalIncrease::latest()->paginate(5);
      }
    }
    $count = 1;
    $all = [];
    try {
      foreach ($capitalincreases_array as $key => $capitalincrease_obj) {
        $namad_obj = $capitalincrease_obj->namad;
        if ($namad_obj && Cache::has($namad_obj->id)) {
          $array['namad'] = new NamadResource($namad_obj);
          $array['newsId'] = $capitalincrease_obj->id;
          $array['newsDate'] = $capitalincrease_obj->get_codal_date();
          $array['newsTime'] = $capitalincrease_obj->get_codal_time();
          $array['newsLink'] = $capitalincrease_obj->link_to_codal;
          $array['newsText'] = $capitalincrease_obj->description;
          $array['extra'] = $capitalincrease_obj->pdf_link;
          $array['seen'] = false;
          $array['isBookmarked'] = false;
          $all[] = $array;
          $count++;
        }
      }
      $status = 200;
      $error = null;
    } catch (\Throwable $th) {
      $error = 'خطا در دریافت اطلاعات از سرور';
    }
    return $this->JsonResponse($all, $error, $status);
  }
}
