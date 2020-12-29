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
        ini_set('max_execution_time', 300);

        // $namad_id = request()->id;
        // $days = request()->days;


        // dd((7150 * 100) / 6);
        // $h = Holding::where('namad_id', 698)->first();
        // dd(Cache::get('portfoy-' . $h->id));
        // dd((int)Cache::get(107)['pc'],$h->namads()->get(), (int)$h->getMarketValue(), (int)$h->portfoy, number_format((((int)$h->getMarketValue() - (int)$h->portfoy) / (int)$h->portfoy) * 100, 2));


        $holdings = Holding::latest()->get();
        $array = [];
        foreach ($holdings as $key => $holding_obj) {
            $namad = Namad::where('id', $holding_obj->namad_id)->first();
            $name = $namad->name;
            // $getportfoy = Holding::GetPortfoyAndYesterdayPortfoy($holding_obj);
            $array[$name]['portfoy'] = $this->format($holding_obj->getMarketValue());
            $array[$name]['percent_change_porftoy'] = $holding_obj->change_percent();
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
            $total += $request->persent[$key] * (int)$pl;
        }
        if (Holding::where('namad_id', $request->name)->first()) {
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

                $last_price_value =  (int)Cache::get($id)['pl'];
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
        $this->calculate($holding);
        return redirect()->route('Holding.Namads', $holding->id);
    }

    public function calculate($holding)
    {
        // dd($holding->namads()->get());
        $total = 0;
        foreach ($holding->namads()->get() as $key => $namad) {
            $amount_value =  $namad->pivot->amount_value;

            $last_price_value = Cache::get($namad->id)['pc'];
            $total += (int)$amount_value * (int)$last_price_value;
        }
        // dd($total);

        foreach ($holding->namads()->get() as $key => $namad) {
            $amount_value = $namad->pivot->amount_value;
            $last_price_value = Cache::get($namad->id)['pc'];
            $count =  (int)$amount_value * (int)$last_price_value;
            $percent = ($count * 100) / $total;
            $namad->pivot->amount_percent = $percent;
            $namad->pivot->save();
        }
    }


    function AddNewNamad()
    {
        // dd(request()->all());

        $holding = Holding::find(request()->id);
        $holding->namads()->attach(request()->namad, [
            'amount_percent' => 0,
            'amount_value' =>  request()->persent,
            'change' => 0

        ]);



        $this->calculate($holding);
        return redirect()->route('Holding.Namads', $holding->id);
    }
}
