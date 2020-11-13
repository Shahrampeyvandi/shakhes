<?php

namespace App\Http\Controllers;

use App\Models\VolumeTrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VolumeTradesController extends Controller
{
    public function Index()
    {
        $data['volumetrades'] = VolumeTrade::with('namad')->orderBy('updated_at','desc')->get();
        return view('VolumeTrades.Index',$data);
    }

      public function Delete(Request $request)
    {
        $user = VolumeTrade::find($request->id);
        $user->delete();

        return back()->with('success', 'مورد با موفقیت حذف شد!!');
    }
}
