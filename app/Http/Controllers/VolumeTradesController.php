<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataTableVolumeTrade;
use App\Models\VolumeTrade;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Cache;

class VolumeTradesController extends Controller
{
    public function Index()
    {
      
        if (\request()->ajax()) {
        
            $items = VolumeTrade::with('namad')->orderBy('updated_at','desc')->paginate(10);
           
            
         
        
            return response()->json([
                'recordsTotal' => $items->total(),
                'recordsFiltered' => $items->total(),
                'draw' => request()->input('draw'),
                'data' => DataTableVolumeTrade::collection($items),
            ]);
          
        }
      return view('VolumeTrades.Index');
    }

      public function Delete(Request $request)
    {
        $user = VolumeTrade::find($request->id);
        $user->delete();
        

        return back()->with('success', 'مورد با موفقیت حذف شد!!');
    }
}
