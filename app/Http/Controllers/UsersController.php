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
}
