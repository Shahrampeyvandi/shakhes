<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function Index()
    {
        $notystatus = null;
        $notifications = [];
        return view('dashboard',compact(['notystatus','notifications']));
    }
}
