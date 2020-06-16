<?php

namespace App\Http\Controllers;

use App\Models\Namad\Disclosures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DisclosuresController extends Controller
{
    public function Index()
    {
        $disclosures = Disclosures::latest()->get();
        return view('Disclosures.Index',compact('disclosures'));
    }


    public function Create()
    {
        return view('Disclosures.create');

    }
    public function Insert(Request $request)
    {
        
        $capitalincrease = new Disclosures();
        $capitalincrease->namad_id = $request->namad;
        $capitalincrease->subject = $request->subject;
        $capitalincrease->group = $request->group;
        $capitalincrease->publish_date = $this->convertDate($request->date);
        $capitalincrease->link_to_codal = $request->linkcodal;
        if ($capitalincrease->save()) {
            return redirect()->route('Disclosures');
        }else{
            return back()->withInput(Input::all());
        }

    }

    public function Delete(Request $request)
    {
        Disclosures::where('id',$request->id)->delete();
        return back();
    }
}
