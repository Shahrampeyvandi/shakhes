<?php

namespace App\Http\Controllers;

use App\Models\Namad\NamadMonthlyReport;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class MoneyReportsController extends Controller
{
    public function Index(Request $request)
    {
        $new_year = Jalalian::forge('now')->format('%Y');
        $last_year = Jalalian::forge('now')->subYears(1)->format('%Y');
       
        return view('MoneyReports.Index',compact([
            'new_year',
            'last_year'
        ]));
    }

    public function SubmitMonthly(Request $request)
    {

      if ($request->type == 'ماهانه') {
        $months = $request->all(['1','2','3','4','5','6','7','8','9','10','11','12']);
        
       
        foreach ($months as $key => $value) {
        
         foreach ($value as $key2 => $value2) {
            if($value2 !== null){
             $monthly_report = new NamadMonthlyReport();
             $monthly_report->namad_id = 1;
             $monthly_report->value = $value2;
             $monthly_report->month = (new Jalalian($key2, $key, 28))->toString();
             $monthly_report->save();
            }
         }
        }
        return back();
      }
      if ($request->type == 'سه ماهه') {

      }
      if ($request->type == 'سالیانه') {
          
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

            if($key == 0) {
                $data['firstseasonnewyear']['income'] = $newyear['income'];
                $data['firstseasonnewyear']['gain'] = $newyear['gain'];
            }
            if($key == 1) {
                $data['secondseasonnewyear']['income'] = $newyear['income'];
                $data['secondseasonnewyear']['gain'] = $newyear['gain'];
            }
            if($key == 2) {
                $data['thirdseasonnewyear']['income'] = $newyear['income'];
                $data['thirdseasonnewyear']['gain'] = $newyear['gain'];
            }
            if($key == 3) {
                $data['endseasonnewyear']['income'] = $newyear['income'];
                $data['endseasonnewyear']['gain'] = $newyear['gain'];
            }



        }
        foreach ($request->lastyear as $key => $lastyear) {
            if (strlen(implode($request->lastyear[$key])) == 0) {
                continue;
            }

            if($key == 0) {
                $data['firstseasonlastyear']['income'] = $lastyear['income'];
                $data['firstseasonlastyear']['gain'] = $lastyear['gain'];
            }
            if($key == 1) {
                $data['secondseasonlastyear']['income'] = $lastyear['income'];
                $data['secondseasonlastyear']['gain'] = $lastyear['gain'];
            }
            if($key == 2) {
                $data['thirdseasonlastyear']['income'] = $lastyear['income'];
                $data['thirdseasonlastyear']['gain'] = $lastyear['gain'];
            }
            if($key == 3) {
                $data['endseasonlastyear']['income'] = $lastyear['income'];
                $data['endseasonlastyear']['gain'] = $lastyear['gain'];
            }



        }
        dd($data);

    }
}
