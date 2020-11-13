<?php

namespace App\Http\Controllers;

use App\Models\Namad\Namad;
use App\Models\Namad\NamadsMonthlyReport;
use App\Models\Namad\NamadsSeasonalReport;
use App\Models\Namad\NamadsYearlyReport;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class MoneyReportsController extends Controller
{
    public function Index(Request $request)
    {

        $data['namads'] = Namad::has('monthlyReports')->orHas('yearlyReports')->orHas('seasonalReports')->orderBy('symbol')->get();
        return view('MoneyReports.Index', $data);
    }
    public function add(Request $request)
    {
        $data['new_year'] = Jalalian::forge('now')->format('%Y');
        $data['last_year'] = Jalalian::forge('now')->subYears(1)->format('%Y');
        if (isset(request()->id)) {
            $data['namad'] = Namad::find(request()->id);
        }
        if (isset(request()->t) && request()->t == 'ماهانه') {
            $first = $data['namad']->monthlyReports()->where('year', $data['new_year'])->orderBy('month', 'asc')->pluck('value', 'month')->toArray();
            $last = $data['namad']->monthlyReports()->where('year', $data['last_year'])->orderBy('month', 'asc')->pluck('value', 'month')->toArray();
            $data['first'] = $this->modify_month($first);
            $data['last'] = $this->modify_month($last);
        }
        if (isset(request()->t) && request()->t == 'فصلی') {
            $data['first'] = $data['namad']->seasonalReports()->orderBy('number', 'asc')->get();
        }
        if (isset(request()->t) && request()->t == 'سالیانه') {
            $data['new_year'] = Jalalian::forge('now')->format('%Y');
            $data['fivelast_year'] = Jalalian::forge('now')->subYears(5)->format('%Y');
            $data['fourlast_year'] = Jalalian::forge('now')->subYears(4)->format('%Y');
            $data['threelast_year'] = Jalalian::forge('now')->subYears(3)->format('%Y');
            $data['twolast_year'] = Jalalian::forge('now')->subYears(2)->format('%Y');
            $data['first'] = $data['namad']->yearlyReports()->orderBy('year', 'asc')->get();
        }

        //   dd($data);
        $data['type'] = request()->t;
        return view('MoneyReports.add', $data);
    }

    private function modify_month($array)
    {
        $keys = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12
        ];
        $count = 0;
        foreach ($array as $key => $value) {
            if (array_key_exists($keys[$count], $array)) {
            } else {
                $array[$keys[$count]] = 0;
            }
            $count++;
        }

        return $array;
    }
    private function modify_season($array)
    {
        $keys = [
            1,
            2,
            3,
            4
        ];
        $count = 0;
        foreach ($array as $key => $value) {
            if (array_key_exists($keys[$count], $array)) {
            } else {
                $array[$keys[$count]] = 0;
            }
            $count++;
        }

        return $array;
    }


    public function SubmitMonthly(Request $request)
    {
        // dd($request->all());
        if (is_null($request->sahm)) {
            return back();
        }

        $namad_data = Namad::where('id', $request->sahm)->first();
        if (count($namad_data->monthlyReports) == 0) {
            $namad_data->mahemali = $request->begin_month;
            $namad_data->update();
        }

        if (!is_null($namad_data)) {

        
            $seasonal_data = $namad_data->seasonalReports;

        
        }
        if ($request->type == 'ماهانه') {
            $months = $request->all(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']);
            $namad_data->monthlyReports()->delete();
            foreach ($months as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $value2 == null ? $value2 = 0 : $value2 = $value2;
                        $monthly_report = new NamadsMonthlyReport();
                        $monthly_report->namad_id = $request->sahm;
                        $monthly_report->value = $value2;
                        $monthly_report->month = $key;
                        $monthly_report->year = $key2;
                        $monthly_report->save();
                    }
                
            }
            return back()->with('success','اطلاعات ماهیانه با موفقیت ثبت شد');
        }
        if ($request->type == 'سه ماهه') {
            if (count($seasonal_data)) $seasonal_data->each->delete();
            $count = 1;
            foreach ($request->num as $key => $num) {
                $seasonal_report = new NamadsSeasonalReport();
                $seasonal_report->namad_id = $request->sahm;
                $seasonal_report->profit = $num[2];
                $seasonal_report->loss = $num[3];
                $seasonal_report->season = $num[0];
                $seasonal_report->number = $key;
                $seasonal_report->year = $num[1];
                $seasonal_report->save();
                $count++;
            }
           return back()->with('success','اطلاعات فصلی با موفقیت ثبت شد');
        }

        if ($request->type == 'سالیانه') {
            $namad_data->yearlyReports()->delete();
            foreach ($request->year as $key => $year) {
                
                    $seasonal_report = new NamadsYearlyReport();
                    $seasonal_report->namad_id = $request->sahm;
                    $seasonal_report->profit = !is_null($year['income']) ? $year['income'] : 0;
                    $seasonal_report->loss = !is_null($year['gain']) ? $year['gain'] : 0;
                    $seasonal_report->year = $key;
                    $seasonal_report->save();
                }
            
           return back()->with('success','اطلاعات سالیانه با موفقیت ثبت شد');
        }
    }
    public function SubmitSeasonly(Request $request)
    {

        $new_year = Jalalian::forge('now')->format('%Y');
        $last_year = Jalalian::forge('now')->subYears(1)->format('%Y');
        foreach ($request->newyear as $key => $newyear) {
            if (strlen(implode($request->newyear[$key])) == 0) {
                continue;
            }

            if ($key == 0) {
                $data['firstseasonnewyear']['income'] = $newyear['income'];
                $data['firstseasonnewyear']['gain'] = $newyear['gain'];
            }
            if ($key == 1) {
                $data['secondseasonnewyear']['income'] = $newyear['income'];
                $data['secondseasonnewyear']['gain'] = $newyear['gain'];
            }
            if ($key == 2) {
                $data['thirdseasonnewyear']['income'] = $newyear['income'];
                $data['thirdseasonnewyear']['gain'] = $newyear['gain'];
            }
            if ($key == 3) {
                $data['endseasonnewyear']['income'] = $newyear['income'];
                $data['endseasonnewyear']['gain'] = $newyear['gain'];
            }
        }
        foreach ($request->lastyear as $key => $lastyear) {
            if (strlen(implode($request->lastyear[$key])) == 0) {
                continue;
            }

            if ($key == 0) {
                $data['firstseasonlastyear']['income'] = $lastyear['income'];
                $data['firstseasonlastyear']['gain'] = $lastyear['gain'];
            }
            if ($key == 1) {
                $data['secondseasonlastyear']['income'] = $lastyear['income'];
                $data['secondseasonlastyear']['gain'] = $lastyear['gain'];
            }
            if ($key == 2) {
                $data['thirdseasonlastyear']['income'] = $lastyear['income'];
                $data['thirdseasonlastyear']['gain'] = $lastyear['gain'];
            }
            if ($key == 3) {
                $data['endseasonlastyear']['income'] = $lastyear['income'];
                $data['endseasonlastyear']['gain'] = $lastyear['gain'];
            }
        }
        dd($data);
    }

    public function ShowMonthlyChart($id)
    {
        $num_arrays = [
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12'

        ];
        $months_array = [
            'فروردین',
            'اردیبهشت',
            'خرداد',
            'تیر',
            'مرداد',
            'شهریور',
            'مهر',
            'آبان',
            'آذر',
            'دی',
            'بهمن',
            'اسفند',
            'فروردین',
            'اردیبهشت',
            'خرداد',
            'تیر',
            'مرداد',
            'شهریور',
            'مهر',
            'آبان',
            'آذر',
            'دی',
            'بهمن',
            'اسفند'

        ];
        $namad = Namad::where('id', $id)->first();
        $key = array_search($namad->mahemali, $num_arrays);
        $months =   array_slice($num_arrays, $key, 12);
        $months_label =   array_slice($months_array, $key, 12);

        $years =  array_unique($namad->monthlyReports->pluck('year')->toArray());
        $array_values = [];
        $count = 1;
        foreach ($years as $key => $year) {
            foreach ($months as $key2 => $month) {
                $obj =   $namad->monthlyReports()->where('year', $year)->where('month', $month)->first();
                if ($obj) {
                    $value = $obj->value;
                } else {
                    $value = 0;
                }
                $array_values[$count][] = $value;
            }
            $count++;
        }



        return view('MoneyReports.show_monthly_chart', compact(['namad', 'months_label', 'years', 'array_values']));
    }

    public function ShowSeasonalChart($id)
    {
        $namad = Namad::where('id', $id)->first();
        $seasonal_reports = $namad->seasonalReports;
        $array = [];
        foreach ($seasonal_reports as $key => $season_data) {
            $array[$season_data->season]['profit'] = $season_data->profit;
            $array[$season_data->season]['loss'] = $season_data->loss;
        }

        return view('MoneyReports.show_seasonal_chart', compact(['array', 'namad']));
    }
    public function ShowYearlyChart($id)
    {

        $namad = Namad::where('id', $id)->first();
        $yearlyreports = $namad->yearlyReports;
        $array = [];
        foreach ($yearlyreports as $key => $yearlyreport_data) {
            $array[$yearlyreport_data->year]['profit'] = $yearlyreport_data->profit;
            $array[$yearlyreport_data->year]['loss'] = $yearlyreport_data->loss;
        }

        return view('MoneyReports.show_yearly_chart', compact(['array', 'namad']));
    }

    public function Delete()
    {
        NamadsMonthlyReport::where('namad_id', request()->id)->delete();
        NamadsYearlyReport::where('namad_id', request()->id)->delete();
        NamadsSeasonalReport::where('namad_id', request()->id)->delete();
        return back();
    }
}
