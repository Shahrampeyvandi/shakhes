<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;

class NamadsController extends Controller
{
    public function search(Request $request)
    {

        $key = $request->search;

        $namads =Namad::where('symbol','like', '%' . $key . '%')
        ->get();

        return response()->json($namads,200);
      
    }
}
