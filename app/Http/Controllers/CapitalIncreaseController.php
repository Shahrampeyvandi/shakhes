<?php

namespace App\Http\Controllers;

use App\Models\CapitalIncrease\CapitalIncrease;
use App\Models\CapitalIncrease\CapitalIncreasePercents;
use Illuminate\Http\Request;

class CapitalIncreaseController extends Controller
{
    public function Index()
    {
        return view('CapitalIncrease.index');
    }
    public function Insert(Request $request)
    {
    
        $capitalincrease = new CapitalIncrease();
        $capitalincrease->namad_id = $request->namad;
        $capitalincrease->from = $request->type;
        $capitalincrease->step = $request->step;
        $capitalincrease->publish_date = $request->date;
        $capitalincrease->link_to_codal = $request->linkcodal;
        if ($capitalincrease->save()) {
           if ($capitalincrease->from == "compound") {
                foreach ($request->typearray as $key => $value) {
                    if(is_null($value)) continue;
                    $percents = new CapitalIncreasePercents();
                    $percents->capital_increase_id = $capitalincrease->id;
                    $percents->percent = $request->percentarray[$key];
                    $percents->type = $value;
                    $percents->save();
                }
           }else{
            $percents = new CapitalIncreasePercents();
            $percents->capital_increase_id = $capitalincrease->id;
            $percents->percent = $request->percent;
            $percents->type = $capitalincrease->from;
            $percents->save();
           }
        }
        return back();
    }
}
