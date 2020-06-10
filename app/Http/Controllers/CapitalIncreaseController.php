<?php

namespace App\Http\Controllers;

use App\Models\CapitalIncrease\CapitalIncrease;
use App\Models\CapitalIncrease\CapitalIncreasePercents;
use Illuminate\Http\Request;

class CapitalIncreaseController extends Controller
{
    public function Index()
    {
        return view('CapitalIncrease.Index');
    }
    public function Insert(Request $request)
    {
        
        $capitalincrease = new CapitalIncrease();
        $capitalincrease->namad_id = $request->namad;
        $capitalincrease->from = $request->type;
        $capitalincrease->step = $request->step;
        $capitalincrease->publish_date = $this->convertDate($request->date);
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

    public function Delete(Request $request)
    {

       $model = CapitalIncrease::find($request->id);
        if($model->delete()){
            CapitalIncreasePercents::where('capital_increase_id',$request->id)->delete();
        }

        return back();
    }
}
