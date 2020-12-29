<?php

namespace App\Http\Controllers\Api;

use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use App\Models\Holding\Holding;
use App\Http\Controllers\Controller;
use App\Http\Resources\NamadResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\HoldingResource;

class MoneyReportsController extends Controller
{


    public function get_holding_data($id = null)
    {


        $arraym = [
            'time' => $this->get_current_date_shamsi() . '_' . date('H:i'),
        ];

        $holding_obj = Holding::where('namad_id', $id)->first();
        $namad = Namad::where('id', $id)->first();
        $array['namad']['symbol'] = $namad->symbol;
        $information = Cache::get($namad->id);
        if (isset($information['namad_status'])) {
            $array['namad']['namad_status'] = $information['namad_status'];
        } else {
            $array['namad']['namad_status'] = 'A';
        }

        $array['namad']['status'] = ((int)$holding_obj->getMarketValue() - (int)$holding_obj->portfoy)  > 0 ? 'green' : 'red';

        if (is_null($holding_obj)) {
            return response()->json(
                [
                    'data' => [],
                    'error' => true
                ],
                401
            );
        }

        $getnamadsdata = $holding_obj->showPercentNamads($holding_obj->id, $namad);


        $yesterday_portfoy = $holding_obj->portfoy;
        $array['yesterday_portfoy'] = (int)$yesterday_portfoy;
        $array['portfoy'] = (int)$holding_obj->getMarketValue();
        $array['marketvalue'] = $this->format($holding_obj->getMarketValue());
        $array['percent_change_porftoy'] = (int)$yesterday_portfoy !== 0 ?  number_format((((int)$holding_obj->getMarketValue() - (int)$yesterday_portfoy) / (int)$yesterday_portfoy) * 100, 2) : 0;
        $array['saham'] = $getnamadsdata;
        $array['color_status']  =  $array['percent_change_porftoy'] > 0 ? 'green' : 'red';

        $arraym['datasingle'] = $array;

        return response()->json(
            $arraym,
            200
        );
    }

    public function getHoldings()
    {

        // return Cache::get(856);

        $all = [];
        if (Cache::has('holding-data')) {

            $all = Cache::get('holding-data');
            return $this->JsonResponse($all, null, 200);
        }


        $collection = Holding::get()->sortByDesc(function ($item, $key) {
            return $item->portfoy;
        });

        //  usort($collection, function ($a, $b) {
        //     return $a['realPortfoy'] < $b['realPortfoy'];
        // });

        $all = HoldingResource::collection($collection);

        Cache::put('holding-data', $all, 60 * 10);
        $error = null;


        return $this->JsonResponse($all, $error, 200);
    }

    public function showHolding()
    {
        if (isset(request()->id)) {

            $row = Holding::whereId(request()->id)->first();
            if($row){

            
            $h = Namad::find($row->namad_id);
            $data = [
                'namad' => new NamadResource($h),
                'itemId' => $row->id,
                'realPortfoy' => (int)$row->getMarketValue(),
                'formatedPortfoy' => $row->format($row->getMarketValue()),
                'percentChangePorftoy' => $row->change_percent(),
                'Status' =>  $row->change_percent() > 0 ? '+' : '-',
                'countNamad' => count($row->namads),
            ];
            $data['namads'] = $row->namadsResource();
            $error = null;
        }else{
            $data = null;
            $error = 'شرکت سرمایه گذاری یافت نشد';
        }
        
            //  Cache::put('holding-data-'.request()->id, $all, 60 * 10);
        } else {
            $data = null;
            $error = 'خطا در دریافت اطلاعات';
        }
        return $this->JsonResponse($data,$error,200);
    }

    public function getfinancial($id)
    {
        $all = [
            'time' => $this->get_current_date_shamsi() . '_' . date('H:i'),
        ];

        $namad = Namad::where('id', $id)->first();
        $all['notification1'] = '200';
        if (count($namad->monthlyReports) == 0 || count($namad->seasonalReports) == 0 || count($namad->yearlyReports) == 0) {
            $all['notification1'] = '404';
        }
        $cache =  Cache::get($namad->id);
        $cache['symbol'] = $namad->symbol;
        $cache['name'] = $namad->name;

        $all['datasingle'] = $cache;



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
            $array['percent_change_porftoy'] = $portfoy_array[1] == 0 ? 0 : number_format(($portfoy_array[0] - $portfoy_array[1]) / $portfoy_array[1], 1, '.');
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

        try {
            $namad = Namad::find($request->id);
            $monthly_reports_years = $namad->monthlyReports()->pluck('year')->toArray();

            if (count($monthly_reports_years)) {
                if (count(array_unique($monthly_reports_years)) == 2) {
                    $setOne = $namad->monthlyReports()->where('year', $monthly_reports_years[0])->orderBy('month')->get();
                    $setTwo = $namad->monthlyReports()->where('year', $monthly_reports_years[1])->orderBy('month')->get();
                    $data['info'][] = ['setOneTitle' => $monthly_reports_years[0], 'setTwoTitle' => $monthly_reports_years[1]];
                } else {
                    $setOne = $namad->monthlyReports()->where('year', $monthly_reports_years[0])->get();
                    $data['info'][] = ['setOneTitle' => $monthly_reports_years[0]];
                }
            } else {
                return $this->JsonResponse(null, 'اطلاعاتی برای این نماد ثبت نشده است', 200);
            }

            if (isset($setOne) && isset($setTwo)) {
                foreach ($setOne as $key => $item) {
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

                    $data['dataSet'][] = ['setOne' => $item->value, 'setTwo' => $setTwo[$key]['value'], 'xAxis' => $fa];
                }
            } else {
                foreach ($setOne as $key => $item) {
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
                    $data['dataSet'][] = ['setOne' => $item->value, 'xAxis' => $fa];
                }
            }





            $error = null;
        } catch (\Throwable $th) {
            $data = null;
            $error = 'خطا در دریافت اطلاعات از سرور';
        }

        return $this->JsonResponse($data, $error, 200);
    }
    public function getnamadseasonalreports(Request $request)
    {


        try {
            $namad = Namad::find($request->id);
            $seasonal_reports = $namad->seasonalReports()->orderBy('number', 'desc')->take(4)->get();
            $array = [];
            if (count($seasonal_reports)) {
                $count = 0;
                foreach ($seasonal_reports as $key => $season_data) {
                    // $array[$season_data->season]['profit'] = $season_data->profit;
                    // $array[$season_data->season]['loss'] = $season_data->loss;
                    $array[$count]['setOne'] = $season_data->profit;
                    $array[$count]['setTwo'] = $season_data->loss;
                    $array[$count]['xAxis'] = $season_data->get_label();
                    $data['dataSet'] = $array;
                    $count++;
                }
                $data['info'][] = ['setOneTitle' => 'درآمد', 'setTwoTitle' => 'سود'];
                $error = null;
            } else {
                $data = null;
                $error = 'اطلاعاتی برای این نماد ثبت نشده است';
            }
        } catch (\Throwable $th) {
            $error = 'خطا در دریافت اطلاعات از سرور';
            $data = [];
        }
        return $this->JsonResponse($data, $error, 200);
    }
    public function getnamadyearlyreports(Request $request)
    {

        try {
            $namad = Namad::find($request->id);
            $yearlyreports = $namad->yearlyReports()->orderBy('year', 'desc')->get();

            $array = [];
            $data = [];
            if (count($yearlyreports)) {
                $count = 0;
                foreach ($yearlyreports as $key => $yearlyreport_data) {
                    $array[$count]['setOne'] = $yearlyreport_data->profit;
                    $array[$count]['setTwo'] = $yearlyreport_data->loss;
                    $array[$count]['xAxis'] = $yearlyreport_data->year;
                    $data['dataSet'] = $array;
                    $count++;
                }
                $data['info'][] = ['setOneTitle' => 'درآمد', 'setTwoTitle' => 'سود'];
                $error = null;
            } else {
                $data = null;
                $error = 'اطلاعاتی برای این نماد ثبت نشده است';
            }
        } catch (\Throwable $th) {
            $error = 'خطا در دریافت اطلاعات از سرور';
            $data = [];
        }
        return $this->JsonResponse($data, $error, 200);
    }
}
