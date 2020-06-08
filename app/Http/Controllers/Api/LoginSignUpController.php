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

        $code = ActivationCode::createCode($request->mobile);
        if ($code == false) {
            return 'کد فعال سازی قبلا برای شما ارسال شده است. لطفا بعدا مجددا امتحان فرمایید';
        }


        return response()->json([
            'code' => $code,
            'error' => '',
        ], 200);
    }


    public function verify(Request $request)
    {
        $code = $request->code;
        $mobile = $request->mobile;
        $activationCode_OBJ = ActivationCode::where('v_code', $code)->where('mobile', $mobile)->first();
        if ($activationCode_OBJ) {

            // check member 
            if($member = Member::where('mobile',$mobile)->first()){
                $token = JWTAuth::fromUser($member);
                return response()->json([
                    'token'=>$token,
                    'error' => '',
                ], 200);
            }else{
                return response()->json([
                    'token'=>'',
                    'error' => '',
                ], 200);
            }

        } else {
            return response()->json([
                'error' => 'کد وارد شده اشتباه است',
            ], 401);
        }
    }

    public function register(Request $request)
    {

        $member = new Member;
        $member->fname = $request->fname;
        $member->lname = $request->lname;
        $member->mobile = $request->mobile;
        if($member->save()){
            $token = JWTAuth::fromUser($member);
            return response()->json([
                'code' => $token,
                'error' => '',
            ], 200);
        }else{
            return response()->json([
                'code' => '',
                'error' => 'خطا در ثبت نام',
            ], 401);
        }

       
    }


    // public function login(Request $request)
    // {
    //     if ($member = Member::where('mobile', $request->mobile)->first()) {


    //         if (Hash::check($request->password, $member->password)) {

    //             $token = JWTAuth::fromUser($member);
    //             return response()->json([
    //                 'code' => $token,
    //                 'error' => '',
    //             ], 200);
    //         } else {
    //             return response()->json(['error' => 'رمز عبور وارد شده اشتباه است'], 401);
    //         }
    //     } else {
    //         return response()->json(
    //             ['message' => 'َشماره وارد شده اشتباه است'],
    //             401
    //         );
    //     }
    // }
}
