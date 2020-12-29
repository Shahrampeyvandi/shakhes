<?php

namespace App\Http\Controllers;

use App\Models\Member\Member;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function Index(Request $request)
    {
      
       
         if (\request()->ajax()) {
             $res = [];
        
            $items = Member::orderBy('created_at','desc')->paginate(10);
           foreach ($items as $key => $item) {
               $res [] = [
                   'id' => $item->id,
                   'fname' => $item->fname,
                   'lname' => $item->lname,
                   'mobile' => $item->phone,
                   'namads' => $item->namads->count(),
                   'avatar' => $item->avatar ?  asset("uploads/brokers/$item->avatar") : asset("assets/images/avatar.png"),
                   'has_plan' => $item->get_plan() ? $item->get_plan() : 'ندارد',
               ];
           }
            
            return response()->json([
                'recordsTotal' => $items->total(),
                'recordsFiltered' => $items->total(),
                'draw' => request()->input('draw'),
                'data' => $res,
            ]);
          
        }
        return view('Users');
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
        $user->subscribe = $this->convertDate($request->date);
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
