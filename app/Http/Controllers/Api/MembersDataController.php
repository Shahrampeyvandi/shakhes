<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Member\Member;
use Tymon\JWTAuth\Facades\JWTAuth;

class MembersDataController extends Controller
{
     public function namads(Request $request)
     {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $member = Member::where('phone', $mobile)->first();
    
        $namads_array = $member->namads;

        

      

        return response()->json(
            $namads_array,
            200
        );
     }
}
