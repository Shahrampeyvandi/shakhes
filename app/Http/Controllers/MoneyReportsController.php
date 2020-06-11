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
        $new_year = Jalalian::forge('now')->format('%Y');
        $last_year = Jalalian::forge('now')->subYears(1)->format('%Y');

        return view('MoneyReports.Index', compact([
            'new_year',
            'last_year'
        ]));
    }

    public function SubmitMonthly(Request $request)
    {
        if (is_null($request->sahm)) {
            return back();
        }

        $namad_data = Namad::where('id', $request->sahm)->first();
        if (count($namad_data->monthlyReports) == 0) {
            $namad_data->mahemali = $request->begin_month;
            $namad_data->update();
        }

        if (!is_null($namad_data)) {

            $monthly_data = $namad_data->monthlyReports;
            $seasonal_data = $namad_data->seasonalReports;

            $yearly_data = $namad_data->yearlyReports;
        }


        if ($request->type == 'ماهانه') {
            $months = $request->all(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']);


            foreach ($months as $key => $value) {

                foreach ($value as $key2 => $value2) {
                    if ($value2 !== null) {
                        if (NamadsMonthlyReport::where('namad_id', $request->sahm)->where('year', $key2)->where('month', $key)->count()) {

                            NamadsMonthlyReport::where('namad_id', $request->sahm)->where('year', $key2)->where('month', $key)
                                ->update([
                                    'value' => $value2
                                ]);
                        } else {

                            $monthly_report = new NamadsMonthlyReport();
                            $monthly_report->namad_id = $request->sahm;
                            $monthly_report->value = $value2;
                            $monthly_report->month = $key;
                            $monthly_report->year = $key2;
                            $monthly_report->save();
                        }
                    }
                }
            }
            return back();
        }
        if ($request->type == 'سه ماهه') {
            if (count($seasonal_data)) $seasonal_data->each->delete();
            $count = 1;
            foreach ($request->season as $key => $season) {

                $seasonal_report = new NamadsSeasonalReport();
                $seasonal_report->namad_id = $request->sahm;
                $seasonal_report->profit = !is_null($season['income']) ? $season['income'] : 0;
                $seasonal_report->loss = !is_null($season['gain']) ? $season['gain'] : 0;
                $seasonal_report->season = 'فصل ' . $request->num[$count] . ' سال ' . $request->year[$count] . '';
                $seasonal_report->save();
                $count++;
            }
            return back();
        }
        if ($request->type == 'سالیانه') {
            foreach ($request->year as $key => $year) {
                if (NamadsYearlyReport::where('namad_id', $request->sahm)->where('year', $key)->count()) {

                    NamadsYearlyReport::where('namad_id', $request->sahm)->where('year', $key)
                        ->update([
                            'profit' => !is_null($year['income']) ? $year['income'] : 0,
                            'loss' => !is_null($year['gain']) ? $year['gain'] : 0
                        ]);
                } else {
                    $seasonal_report = new NamadsYearlyReport();
                    $seasonal_report->namad_id = $request->sahm;
                    $seasonal_report->profit = !is_null($year['income']) ? $year['income'] : 0;
                    $seasonal_report->loss = !is_null($year['gain']) ? $year['gain'] : 0;
                    $seasonal_report->year = $key;
                    $seasonal_report->save();
                }
            }
            return back();
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



        return view('MoneyReports.show_monthly_chart', compact(['namad','months_label', 'years', 'array_values']));
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
       
        return view('MoneyReports.show_seasonal_chart', compact(['array','namad']));

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
       
        return view('MoneyReports.show_yearly_chart', compact(['array','namad']));

    }

    public function Delete($id)
    {
        NamadsMonthlyReport::where('namad_id',$id)->delete();
        NamadsYearlyReport::where('namad_id',$id)->delete();
        NamadsSeasonalReport::where('namad_id',$id)->delete();
        return back();

    }


}
