<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivationCode;
use App\Models\Member\Member;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginSignUpController extends Controller
{
    public function getcode(Request $request)
    {
        if(Member::where('mobile',$request->mobile)->first()){
            return response()->json(['message' => 'کاربر با این شماره از قبل وجود دارد!']
            , 400);
        }
        

        $code = ActivationCode::createCode($request->mobile);
        if ($code == false) {
            return response()->json(['message' => 'کد فعال سازی برای شما ارسال شده است لطفا 15 دقیقه دیگر تلاش نمایید']
            , 400);
        }
        return response()->json([
            
            'error' => '',
        ], 200);


    }
    public function verify(Request $request)
    {
    $activationCode_OBJ = ActivationCode::where('v_code', $request->code)->first();
    if ($activationCode_OBJ) {
        return response()->json([
            
            'error' => '',
        ], 200);
    }


    return response()->json([
            
        'error' => true,
    ], 400);
    }

    public function register(Request $request)
    {

        $user = Member::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'password'=> $request->password,
            'avatar' => $request->avatar,
           

        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json([
            'code' => $token,
            'error' => '',
        ], 200);
    }


    public function login(Request $request)
    {
        if ($member = Member::where('mobile', $request->mobile)->first()) {


            if (Hash::check($request->password, $member->password)) {
                
                $token = JWTAuth::fromUser($member);
                return response()->json([
                    'code' => $token,
                    'error' => '',
                ], 200);
            }else{
                return response()->json(['error' => 'رمز عبور وارد شده اشتباه است'], 401);
            }
        }else{
            return response()->json(['message' => 'َشماره وارد شده اشتباه است']
            , 401);
        }
       
    }
}
