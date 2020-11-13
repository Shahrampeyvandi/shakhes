<?php

namespace App\Http\Controllers;

use App\Models\Member\Member;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function Index(Request $request)
    {
      
       $users = Member::latest()->get();
        return view('Users',compact('users'));
    }

    public function Delete(Request $request)
    {
        $user = Member::find($request->id);
        $user->namads()->detach();
        $user->delete();

        return back()->with('success', 'کاربر با موفقیت حذف شد!');
    }
      public function Edit(Request $request)
    {
    
        $user = Member::find($request->user_id);
        $user->phone = $request->user_mobile;
        $user->fname = $request->user_name;
        $user->lname = $request->user_family;
        $user->subscribe = $request->date;
        $user->update();

        return back()->with('success', 'کاربر با موفقیت ویرایش شد');
    }

    public function get_data()
    {
        $user = Member::find(request()->user_id);
        $resources = [
            'id' => $user->id,
            'phone' => $user->phone,
            'fname' => $user->fname,
            'lname' => $user->lname,
            'date' => \Morilog\Jalali\Jalalian::forge($user->subscribe)->format('Y/m/d') 
        ];
        
        return response()->json($resources,200);
    }
}
