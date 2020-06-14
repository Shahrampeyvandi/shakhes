<?php

namespace App\Http\Controllers;

use App\Models\clarification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ClarificationController extends Controller
{
    public function Index()
    {
        $clarifications = clarification::latest()->get();
        return view('Clarification.Index',compact('clarifications'));
    }


    public function Create()
    {
        return view('Clarification.create');

    }
    public function Insert(Request $request)
    {
        $capitalincrease = new clarification();
        $capitalincrease->namad_id = $request->namad;
        $capitalincrease->subject = $request->subject;
        $capitalincrease->publish_date = $this->convertDate($request->date);
        $capitalincrease->link_to_codal = $request->linkcodal;
        if ($capitalincrease->save()) {
            return redirect()->route('Clarifications');
        }else{
            return back()->withInput(Input::all());
        }

    }

    public function Delete(Request $request)
    {
        clarification::where('id',$request->id)->delete();
        return back();
    }
}
