<?php

namespace App\Http\Controllers;

use App\Models\Holding\Holding;
use App\Models\Namad\Namad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PortfoyController extends Controller
{
    public function Index()
    {

        $holdings = Holding::latest()->get();


        $array = [];
        foreach ($holdings as $key => $holding_obj) {
            $name = Namad::where('id', $holding_obj->namad_id)->first()->name;
            $getportfoy = Holding::GetPortfoyAndYesterdayPortfoy($holding_obj);
            // پرتفوی لحظه ای شرکت
            $array[$name]['portfoy'] = $getportfoy[0];
            // درصد تغییر پرتفوی
            $array[$name]['percent_change_porftoy'] = $getportfoy[1] == 0 ? 0 : ($getportfoy[0] - $getportfoy[1]) / $getportfoy[1];
            $array[$name]['namad_counts'] = count($holding_obj->namads);
            $array[$name]['id'] = $holding_obj->id;
        }


        return view('Portfoy.index', compact('array'));
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
        $total = 0;
        // جمع ارزش مالی شرکت از ضرب تعداد هرسهم شرکت در  آخرین قیمت سهم
        foreach ($request->namads as $key => $id) {
            
            $pl = Cache::get($id)['pl'];
            $total += $request->persent[$key] * $pl;
        }
        if(Holding::where('namad_id',$request->name)->first()) {
            $request->session()->flash('Error', 'شرکت از قبل ثبت شده است');
            return back();
        }

        $holding = new Holding();
        $holding->namad_id = $request->name;
        $holding->save();

        foreach ($request->namads as $key => $id) {
            if (!is_null($id) &&  DB::table('holdings_namads')
                ->whereNamad_id($id)
                ->whereHolding_id($holding->id)
                ->count() == 0
            ) {
                
                $last_price_value =  Cache::get($id)['pl'];
                $count =  $request->persent[$key] * $last_price_value;
                $percent = ($count * 100) / $total;
                $holding->namads()->attach($id, [
                    'amount_percent' => $percent,
                    'amount_value' =>  $request->persent[$key],
                    'change' => 0

                ]);
            }
        }

        $request->session()->flash('success', 'شرکت سرمایه گذاری با موفقیت ثبت شد');
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

    public function DeleteHoldingNamad(Request $request)
    {

        Holding::where('id', $request->holding)->first()->namads()->detach($request->id);
        $holding = Holding::where('id', $request->holding)->first();
        // بررسی دوباره درصد پرتفوی سهم ها
        $total = 0;
        foreach ($holding->namads as $key => $namad) {
            $amount_value =  DB::table('holdings_namads')
                ->whereNamad_id($namad->id)
                ->whereHolding_id($request->holding)->first()->amount_value;
            $last_price_value = Cache::get($namad->id)['final_price_value'];
            $total += (int)$amount_value * (int)$last_price_value;
        }



        foreach ($holding->namads as $key => $namad) {
            $amount_value =  DB::table('holdings_namads')
                ->whereNamad_id($namad->id)
                ->whereHolding_id($request->holding)->first()->amount_value;
            $count =  (int)$amount_value * (int)$last_price_value;
            $percent = ($count * 100) / $total;
            DB::table('holdings_namads')
                ->whereNamad_id($namad->id)
                ->whereHolding_id($request->holding)
                ->update([
                    'amount_percent' => $percent,
                ]);
        }




        return back();
    }
}
