<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoneyReportsController extends Controller
{
    public function Index(Request $request)
    {
        return view('MoneyReports.Index');
    }
}
