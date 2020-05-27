<?php

namespace App\Http\Controllers;

use App\Models\Namad\Namad;
use App\Models\Namad\NamadMonthlyReport;
use App\Models\Namad\NamadSeasonalReport;
use App\Models\Namad\NamadYearlyReport;
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


        $namad_data = Namad::where('id', $request->sahm)->first();
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
                        if (NamadMonthlyReport::where('namad_id', $request->sahm)->where('year', $key2)->where('month', $key)->count()) {

                            NamadMonthlyReport::where('namad_id', $request->sahm)->where('year', $key2)->where('month', $key)
                                ->update([
                                    'value' => $value2
                                ]);
                        } else {

                            $monthly_report = new NamadMonthlyReport();
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
            if ($seasonal_data) $seasonal_data->delete();
            foreach ($request->season as $key => $season) {

                $seasonal_report = new NamadSeasonalReport();
                $seasonal_report->namad_id = $request->sahm;
                $seasonal_report->profit = !is_null($season['income']) ? $season['income'] : 0;
                $seasonal_report->loss = !is_null($season['gain']) ? $season['gain'] : 0;
                $seasonal_report->season = $key;
                $seasonal_report->save();
            }
            return back();
        }
        if ($request->type == 'سالیانه') {
            foreach ($request->year as $key => $year) {
                if (NamadYearlyReport::where('namad_id', $request->sahm)->where('year', $key)->count()) {

                    NamadYearlyReport::where('namad_id', $request->sahm)->where('year', $key)
                        ->update([
                            'profit' => !is_null($year['income']) ? $year['income'] : 0,
                            'loss' =>!is_null($year['gain']) ? $year['gain'] : 0
                        ]);
                } else {
                    $seasonal_report = new NamadYearlyReport();
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
}
