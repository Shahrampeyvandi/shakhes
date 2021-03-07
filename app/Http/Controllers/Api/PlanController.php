<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;

class PlanController extends Controller
{
    public function list()
    {
        try {
            $plans = Plan::where('active', 1)->orderBy('days', 'asc')->get();
            $data = PlanResource::collection($plans);
            $error = null;
        } catch (\Throwable $th) {
            $data = null;
            $error = 'خطا در دریافت اطلاعات از سرور';
        }
        return $this->JsonResponse($data, $error, 200);
    }
}
