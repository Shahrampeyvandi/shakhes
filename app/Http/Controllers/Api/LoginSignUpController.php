<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Member\Member;
use App\Models\ActivationCode;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginSignUpController extends Controller
{
    public function getcode(Request $request)
    {


        $code = ActivationCode::createCode($request->phone);
        if ($code == false) {
            $error = 'کد فعال سازی قبلا برای شما ارسال شده است. لطفا بعدا مجددا امتحان فرمایید';
            $data = null;
            $status = 200;
        } else {
            $data = $code->v_code;
            $error = null;
            $status = 200;
        }


        return $this->JsonResponse($data, $error, $status);
    }


    public function verify(Request $request)
    {
        // return Auth::guard('api')->user();

        $code = $request->code;
        $mobile = $request->phone;
        $activationCode_OBJ = ActivationCode::where('v_code', $code)->where('mobile', $mobile)->first();
        if ($activationCode_OBJ) {
            // check member 
            if ($member = Member::where('phone', $mobile)->first()) {
                $token =     Auth::guard('api')->login($member);
                return $this->JsonResponse($token, null, 200);
            } else {
                $member = new Member;
                $member->phone = $request->phone;
                if ($member->save()) {
                    $token =     Auth::guard('api')->login($member);
                    return $this->JsonResponse($token, null, 201);
                }
            }
        } else {
            $error = 'کد وارد شده اشتباه است';
            return $this->JsonResponse(null, $error, 200);
        }
    }

    public function register(Request $request)
    {

        $member = new Member;
        $member->fname = $request->fname;
        $member->lname = $request->lname;
        $member->phone = $request->phone;
        if ($member->save()) {
            $token =     Auth::guard('api')->login($member);
            return $this->JsonResponse($token, null, 201);
        } else {
            $error = 'خطا در ثبت نام';
           return $this->JsonResponse(null, $error, 200);
        }
    }

    public function me()
    {
        return $member = $this->token(request()->header('Authorization'));
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
