<?php

namespace App\Http\Controllers;

use App\Models\clarification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ClarificationController extends Controller
{
    public function Index()
    {
        if (\request()->ajax()) {
            $res = [];

            $items = clarification::orderBy('created_at','desc')->paginate(10);
            foreach ($items as $key => $item) {
                $res[] = [
                    'id' => $item->id,
                    'subject' => $item->subject,
                    'symbol' => $item->namad->symbol,
                    'date' => $item->get_codal_date() . ' ' . $item->get_codal_time(),
                    'link' => $item->link_to_codal,
                ];
            }

            return response()->json([
                'recordsTotal' => $items->total(),
                'recordsFiltered' => $items->total(),
                'draw' => request()->input('draw'),
                'data' => $res,
            ]);
        }



        return view('Clarification.Index');
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
        } else {
            return back()->withInput(Input::all());
        }
    }

    public function Delete(Request $request)
    {
        clarification::where('id', $request->id)->delete();
        return back();
    }
}
