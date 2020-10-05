<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function Login()
    {

        if (Auth::guard('admin')->check()) {
            return redirect()->route('BaseUrl');
        }


        return view('login');
    }

    public function Verify(Request $request)
    {


        $admin = Admin::where('mobile', $request->mobile)->first();
        if ($admin) {
            if (Hash::check($request->password, $admin->password)) {

                if ($request->has('rememberme')) {
                    Auth::guard('admin')->Login($admin, true);
                } else {
                    Auth::guard('admin')->Login($admin);
                }

                return redirect()->route('BaseUrl');
            } else {
                $request->session()->flash('Error', 'رمز عبور وارد شده صحیح نمیباشد');
                return back();
            }
        } else {
            $request->session()->flash('Error', 'شماره ای که وارد کرده اید اشتباه است');
            return back();
        }
    }

    public function Logout()
    {
        Auth::guard('admin')->Logout();
        return Redirect::route('login');
    }
}
