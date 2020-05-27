<?php

namespace App\Http\Controllers;

use App\Models\Holding\Holding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfoyController extends Controller
{
    public function Index()
    {

        $holdings = Holding::latest()->get();

        return view('Portfoy.index', compact('holdings'));
    }
    public function CreateHolding()
    {
        return view('Portfoy.createholding');
    }

    public function InsertHolding(Request $request)
    {
        
        if (is_null($request->name)) {
            $request->session()->flash('Error', 'نام شرکت را وارد نمایید');
            return back();
        }
        $holding = new Holding();
        $holding->name = $request->name;
        if ($holding->save()) {
            foreach ($request->namads as $key => $namad) {
                
                if (!is_null($namad) &&  DB::table('holdings_namads')
                ->whereNamad_id($namad)
                ->whereHolding_id($holding->id)
                ->count() == 0 ) {
                    $holding->namads()->attach($namad, ['amount_percent' => $request->persent[$key], 'amount_value' => 0, 'change' => 0]);
                }
            }
        }
        return redirect()->route('PortfoyList');
    }
    public function DeleteHolding(Request $request)
    {

        Holding::where('id', $request->id)->first()->namads()->detach();
        Holding::where('id', $request->id)->delete();

        return back();
    }

    public function ShowNamads($id)
    {

        $holding =  Holding::where('id', $id)->first();

        return view('Portfoy.shownamads', compact('holding'));
    }
}
