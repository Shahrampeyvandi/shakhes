<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Holding\Holding;
use App\Models\Namad\Namad;
use Illuminate\Support\Facades\Cache;

class MoneyReportsController extends Controller
{


    public function get_holding_data($id = null)
    {
        $holding_obj = Holding::where('namad_id', $id)->first();
        $namad = Namad::where('id', $id)->first();
        if (is_null($holding_obj)) {
            return response()->json(
                [
                    'data' => [],
                    'error' => true
                ],
                401
            );
        }

        $array['arzeshbazar'] = Cache::get($namad->id)['MarketCash'];
        $getnamadsdata = $holding_obj->showPercentNamads($holding_obj->id, $namad);
        // پرتفوی لحظه ای شرکت
        $portfoy_array = Holding::GetPortfoyAndYesterdayPortfoy($holding_obj);
        // $array['portfoy'] = $portfoy_array[0];
        // $array['yesterday_portfoy'] = $portfoy_array[1];
        // درصد تغییر پرتفوی
        $array['percent_change_porftoy'] = $portfoy_array[1] == 0 ? 0 : ($portfoy_array[0] - $portfoy_array[1]) / $portfoy_array[1];
        $array['saham'] = $getnamadsdata;
        return response()->json(
            $array,
            200
        );
    }

    public function getHoldings()
    {
        $all = [
            'saat' => date('H:i'),
            'tarikh' => $this->get_current_date_shamsi()
        ];
        
        $collection = Holding::latest()->get();
        foreach ($collection as $key => $item) {
            $array = [];
           $cache =  Cache::get($item->namad_id);
            $namad = Namad::where('id', $item->namad_id)->first();
            $array['name'] = $cache ? $cache['symbol'] : '';
            $array['arzeshbazar'] = $cache ? $cache['MarketCash'] : '';
            $portfoy_array = Holding::GetPortfoyAndYesterdayPortfoy($item);
            $array['percent_change_porftoy'] = $portfoy_array[1] == 0 ? 0 : ($portfoy_array[0] - $portfoy_array[1]) / $portfoy_array[1];
            $array['namads_count'] = count($item->namads);
            $all['holdings'][] = $array;
        }

         return response()->json(
            $all,
            200
        );
    }

    public function check_if_holding($id)
    {

        $namad_obj =  Namad::whereId($id)->first();
        if (!is_null($namad_obj)) {
            $name = $namad_obj->name;
            // check if namad is holding
            $holding_obj = Holding::where('name', $namad_obj->id)->first();
            if (is_null($holding_obj)) {
                return response()->json(
                    [
                        'data' => [],
                        'error' => true
                    ],
                    401
                );
            }

            $getnamadsdata = $holding_obj->showPercentNamads($holding_obj->id);

            // پرتفوی لحظه ای شرکت
            $portfoy_array = Holding::GetPortfoyAndYesterdayPortfoy($holding_obj);
            $array['portfoy'] = $portfoy_array[0];
            // درصد تغییر پرتفوی
            $array['percent_change_porftoy'] = $portfoy_array[1] == 0 ? 0 : ($portfoy_array[0] - $portfoy_array[1]) / $portfoy_array[1];
            $array['saham'] = $getnamadsdata;
            return $array;
        } else {
            return response()->json(
                [
                    'error' => 'نماد مورد نظر پیدا نشد'
                ],
                401
            );
        }
    }


    public function getnamadmonthlyreports(Request $request)
    {

        $namad = Namad::find($request->id);
        $monthly_reports_years = $namad->monthlyReports->pluck('year')->toArray();

        $array = [];
        $count = 0;
        foreach (array_unique($monthly_reports_years) as $keys => $year) {
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

                //$array[$year][$fa] =  number_format($item->value,0,'.','');






                $array[$count]['value'] = number_format($item->value, 0, '.', '');
                $array[$count]['year'] = $year;
                $array[$count]['month'] = $fa;
                $count++;
            }
        }
        //$array['mahemali'] = $namad['mahemali'];

        return response()->json(
            ['data' => $array],
            200
        );
    }
    public function getnamadseasonalreports(Request $request)
    {


        $namad = Namad::find($request->id);
        $seasonal_reports = $namad->seasonalReports;
        $array = [];
        $count = 0;
        foreach ($seasonal_reports as $key => $season_data) {
            // $array[$season_data->season]['profit'] = $season_data->profit;
            // $array[$season_data->season]['loss'] = $season_data->loss;

            $array[$count]['profit'] = $season_data->profit;
            $array[$count]['loss'] = $season_data->loss;
            $array[$count]['season'] = $season_data->season;
            $count++;
        }
        return response()->json(
            ['data' => $array],
            200
        );
    }
    public function getnamadyearlyreports(Request $request)
    {

        $namad = Namad::find($request->id);
        $yearlyreports = $namad->yearlyReports;
        $array = [];
        $count = 0;
        foreach ($yearlyreports as $key => $yearlyreport_data) {

            $array[$count]['profit'] = $yearlyreport_data->profit;
            $array[$count]['loss'] = $yearlyreport_data->loss;
            $array[$count]['year'] = $yearlyreport_data->year;
            $count++;
        }
        return response()->json(
            ['data' => $array],
            200
        );
    }
}
