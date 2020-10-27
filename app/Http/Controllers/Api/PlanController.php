<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;

class PlanController extends Controller
{
    public function all()
    {
        $plans = Plan::where('active',1)->orderBy('days','asc')->get();
        return response()->json(PlanResource::collection($plans));

    }
}
