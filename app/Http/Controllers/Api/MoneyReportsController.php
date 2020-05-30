<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;

class MoneyReportsController extends Controller
{
    public function getnamadmonthlyreports(Request $request)
    {

        $namad = Namad::find($request->id);
        $monthly_reports_years = $namad->monthlyReports->pluck('year');
        $array = [];
        foreach ($monthly_reports_years as $key => $year) {
            $monthly_reports = $namad->monthlyReports->where('year', $year);
            foreach ($monthly_reports as $key => $item) {
                switch ($item->month) {
                    case '1':
                        $fa = 'فروردین';
                        break;

                    case '2':
                        $fa = 'اردیبهشت';
                        break;
                    case '3':
                        $fa = 'خرداد';
                        break;
                    case '4':
                        $fa = 'تیر';
                        break;
                    case '5':
                        $fa = 'مرداد';
                        break;
                    case '6':
                        $fa = 'شهریور';
                        break;
                    case '7':
                        $fa = 'مهر';
                        break;
                    case '8':
                        $fa = 'آبان';
                        break;
                    case '9':
                        $fa = 'آذر';
                        break;
                    case '10':
                        $fa = 'دی';
                        break;
                    case '11':
                        $fa = 'بهمن';
                        break;
                    case '12':
                        $fa = 'اسفند';
                        break;
                }
                $array[$year][$fa] = $item->value;
            }
        }

        return response()->json(
            $array,
            200
        );
    }
    public function getnamadseasonalreports(Request $request)
    {
        $namad = Namad::find($request->id);
        $seasonal_reports = $namad->seasonalReports;
        $array = [];
        foreach ($seasonal_reports as $key => $season_data) {
                $array[$season_data->season]['profit'] = $season_data->profit;
                $array[$season_data->season]['loss'] = $season_data->loss;

        }
        return response()->json(
            $array,
            200
        );
    }
    public function getnamadyearlyreports(Request $request)
    {
        $namad = Namad::find($request->id);
        $yearlyreports = $namad->yearlyReports;
        $array = [];
        foreach ($yearlyreports as $key => $yearlyreport_data) {
                $array[$yearlyreport_data->year]['profit'] = $yearlyreport_data->profit;
                $array[$yearlyreport_data->year]['loss'] = $yearlyreport_data->loss;

        }
        return response()->json(
            $array,
            200
        );
    }
}
