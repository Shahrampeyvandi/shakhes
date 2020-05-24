<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function Index(Request $request)
    {
      
       
        return view('Users');
    }
}
