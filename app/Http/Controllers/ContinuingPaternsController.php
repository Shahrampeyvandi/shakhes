<?php

namespace App\Http\Controllers;

use App\Models\Namad\Namad;
use App\Models\Paterns\ContinuingPatern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ContinuingPaternsController extends Controller
{
    public function Index()
    {
        $paterns = ContinuingPatern::latest()->get();
        return view('Paterns.continuing_paterns',compact('paterns'));
    }

    public function Create()
    {
        return view('Paterns.continuing_paterns_create');

    }
    public function Insert(Request $request)
    {

         $namad_name = Namad::whereId($request->namad)->first();
         if ($request->hasFile('picture')) {
            $destinationPath = "pictures/continuing_paterns/$namad_name->symbol";
            $picextension = $request->file('picture')->getClientOriginalExtension();
            $fileName = date("Y-m-d") . '_' . time() . '.' . $picextension;
            $request->file('picture')->move($destinationPath, $fileName);
            $picPath = "$destinationPath/$fileName";
           
        } else {
            $picPath = '';
        }
        $capitalincrease = new ContinuingPatern();
        $capitalincrease->namad_id = $request->namad;
        $capitalincrease->name = $request->name;
        $capitalincrease->picture = $picPath;
        $capitalincrease->type = $request->type;
        if ($capitalincrease->save()) {
            return redirect()->route('ContinuingPaterns');
        }
    }

    public function Delete(Request $request)
    {
       $model = ContinuingPatern::find($request->id);
       if($model->picture){
           File::delete(public_path($model->picture));
       }
       $model->delete();
        return back();

    }


}
