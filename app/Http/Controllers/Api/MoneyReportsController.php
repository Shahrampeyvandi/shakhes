<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Holding\Holding;
use App\Models\Namad\Namad;

class MoneyReportsController extends Controller
{



    public function check_if_holding($id)
    {
        /**
         * 
         * شرکت سرمایه کذاری به این صورت هست که از توی پنل خودش انتخاب میکنه که این نماد جزو شرکت های سرمایه گزاریه 
         * یعنی از توی پنل باید درست کنم مه اگه انتخابش کرد بیاد توی تیبل هلدینگ ها 
         * پس ما اینجا چک میکنیم که اگه شرکت سرمایه گزاری بود دیگه صورت های مالیشو نمیاریم 
         * 
         * اوکیه همینجوری؟؟
         * 
         */
        // Change the line below to your timezone!
        date_default_timezone_set('Asia/Tehran');
      
        $namad_obj =  Namad::whereId($id)->first();
        if (!is_null($namad_obj)) {
            $name = $namad_obj->name;
            // check if namad is holding
            $holding_obj = Holding::where('name', $name)->first();

            if (is_null($holding_obj)) return null;



            $count = 1;
            $portfoy = 0;
            $yesterday_portfoy = 0;
           
            foreach ($holding_obj->namads as $key => $namad) {
                
                $array[$count]['symbol'] = $namad->symbol;
                $array[$count]['name'] = $namad->name;
                $array[$count]['amount_percent'] = $namad->pivot->amount_percent;
                $array[$count]['amount_value'] = $namad->pivot->amount_value;
                $array[$count]['change'] = $namad->pivot->change;

                // حساب کردن ارزش پرتفوی شرکت
                
                // today
                $last_price_value = count($namad->dailyReports) ? $namad->dailyReports()->latest()->first()->last_price_value : 0;
                $portfoy +=  $array[$count]['amount_value'] * $last_price_value ;
              
                // yesterday
                $yesterday = $namad->dailyReports()->whereDate('created_at', date('Y-m-d',strtotime("-1 days")))->latest()->first();
               if(!is_null($yesterday)) {
                   $last_price_value_yesterday =  $yesterday->last_price_value;
               }else{
                $last_price_value_yesterday = 0;
               }
               $yesterday_portfoy += $array[$count]['amount_value'] * $last_price_value_yesterday ;


                $count++;
            }
            // پرتفوی لحظه ای شرکت
            $array['portfoy'] = $portfoy;
            // درصد تغییر پرتفوی
            $array['percent_change_porftoy'] = $yesterday_portfoy == 0 ? 0 : ($portfoy - $yesterday_portfoy) / $yesterday_portfoy;

            


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

        $check_holding = $this->check_if_holding($request->id);
        if ($check_holding) {
            return response()->json(
                $check_holding,
                200
            );
        }

        // else

        $namad = Namad::find($request->id);
        $monthly_reports_years = $namad->monthlyReports->pluck('year');
        $array = [];
        $count = 1;
        foreach ($monthly_reports_years as $keys => $year) {
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
                $array[$count]['value'] = number_format($item->value,0,'.','');
                $array[$count]['year'] = $year;
                $array[$count]['month'] = $fa;

            }
            $count++;
        }

        return response()->json(
           ['data'=>$array],
            200
        );
    }
    public function getnamadseasonalreports(Request $request)
    {


        $namad = Namad::find($request->namad_id);
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


        $namad = Namad::find($request->namad_id);
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
