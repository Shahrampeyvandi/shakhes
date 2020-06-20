<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VolumeTradesController extends Controller
{
    public function Index()
    {

        return view('VolumeTrades.Index');
    }
}
