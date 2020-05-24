<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class AjaxController extends Controller
{
    public function getmoneyreportsdata(Request $request)
    {
      
        
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
       


        $key = array_search($request->month, $months_array); 
     


        

        
        $months =   array_slice($months_array, $key, 12);
        $new_year = Jalalian::forge('now')->format('%Y');
        $fivelast_year = Jalalian::forge('now')->subYears(5)->format('%Y');
        $fourlast_year = Jalalian::forge('now')->subYears(4)->format('%Y');
        $threelast_year = Jalalian::forge('now')->subYears(3)->format('%Y');
        $twolast_year = Jalalian::forge('now')->subYears(2)->format('%Y');
       
        $last_year = Jalalian::forge('now')->subYears(1)->format('%Y');
        $next_year = Jalalian::forge('now')->addYears(1)->format('%Y');
if($request->type == "ماهانه"){
    
        $table = '<form id="monthly-table" class="needs-validation" action="'.route('MonyReport.Monthly').'" method="post" enctype="multipart/form-data">
        '.csrf_field().'
        <input type="hidden" name="type" id="type" value="'.$request->type.'">
        <input type="hidden" name="sahm" id="sahm" value="'.$request->sahm.'">
        <input type="hidden" name="begin_month" id="" value="'.$request->month.'">
        <input type="hidden" name="begin_year" id="" value="'.$request->year.'">
        <div class="d-block">

            <div class="row my-4">
                <div class="table-responsive">
                    <table class="table table-bordered  ">
                        <thead>
                            <tr>
                                <th>سال</th>
                                <th scope="col">'.$months[0].'</th>
                                <th scope="col">'.$months[1].'</th>
                                <th scope="col">'.$months[2].'</th>
                                <th scope="col">'.$months[3].'</th>
                                <th scope="col">'.$months[4].'</th>
                                <th scope="col">'.$months[5].'</th>
                                <th scope="col">'.$months[6].'</th>
                                <th scope="col">'.$months[7].'</th>
                                <th scope="col">'.$months[8].'</th>
                                <th scope="col">'.$months[9].'</th>
                                <th scope="col">'.$months[10].'</th>
                                <th scope="col">'.$months[11].'</th>


                        </thead>
                        <tbody>
                            <tr>
                                <td>'.$new_year.'</td>
                                <td>
                                    <input type="text" name="farvardin[]">
                                </td>
                                <td><input type="text" name="ordi[]"></td>
                                <td><input type="text" name="khordad[]"></td>
                                <td><input type="text" name="tir[]"></td>
                                <td><input type="text" name="mordad[]"></td>
                                <td><input type="text" name="shahrivar[]"></td>
                                <td><input type="text" name="mehr[]"></td>
                                <td><input type="text" name="aban[]"></td>
                                <td><input type="text" name="azar[]"></td>
                                <td><input type="text" name="dey[]"></td>
                                <td><input type="text" name="bahman[]"></td>
                                <td><input type="text" name="esfand[]"></td>
                            </tr>
                           ';
                    if($request->year !== $new_year){
                        $table .=' <tr>
                        <td>'.$last_year.'</td>
                        <td>
                            <input type="text" name="farvardin[]">
                        </td>
                        <td><input type="text" name="ordi[]"></td>
                        <td><input type="text" name="khordad[]"></td>
                        <td><input type="text" name="tir[]"></td>
                        <td><input type="text" name="mordad[]"></td>
                        <td><input type="text" name="shahrivar[]"></td>
                        <td><input type="text" name="mehr[]"></td>
                        <td><input type="text" name="aban[]"></td>
                        <td><input type="text" name="azar[]"></td>
                        <td><input type="text" name="dey[]"></td>
                        <td><input type="text" name="bahman[]"></td>
                        <td><input type="text" name="esfand[]"></td>
                    </tr>';
                    }
                        $table .='</tbody>
                    </table>
                </div>

            </div>
        </div>
        <hr>
        <div class="container text-center">
            <button class="btn btn-primary " type="submit">ثبت اطلاعات</button>
        </div>
    </form>';
     }
     if($request->type == "سه ماهه"){
        $season_array = [
            "فروردین $last_year",
            "اردیبهشت $last_year",
            "خرداد $last_year",
            "تیر $last_year",
            "مرداد $last_year",
            "شهریور $last_year",
            "مهر $last_year",
            "آبان $last_year",
            "آذر $last_year",
            "دی $last_year",
            "بهمن $last_year",
            "اسفند $last_year",
            "فروردین $new_year",
            "اردیبهشت $new_year",
            "خرداد $new_year",
            "تیر $new_year",
            "مرداد $new_year",
            "شهریور $new_year",
            "مهر $new_year",
            "آبان $new_year",
            "آذر $new_year",
            "دی $new_year",
            "بهمن $new_year",
            "اسفند $new_year",
            "فروردین $next_year",
            "اردیبهشت $next_year",
            "خرداد $next_year",
            "تیر $next_year",
            "مرداد $next_year",
            "شهریور $next_year",
            "مهر $next_year",
            "آبان $next_year",
            "آذر $next_year",
            "دی $next_year",
            "بهمن $next_year",
            "اسفند $next_year",
           

        ];
        $key = array_search($request->month.' '.$request->year, $season_array); 
        $first_season = $request->month.' '.$request->year.' تا '.$season_array[$key+2]; 
        $second_season = $season_array[$key+3].' تا '.$season_array[$key+5];
        $third_season = $season_array[$key+6].' تا '.$season_array[$key+8];
        $end_season = $season_array[$key+9].' تا '.$season_array[$key+11];
        
        $table = '<form id="monthly-table" class="needs-validation" action="'.route('MonyReport.Monthly').'" method="post" enctype="multipart/form-data">
        '.csrf_field().'
        <input type="hidden" name="type" id="type" value="'.$request->type.'">
        <input type="hidden" name="sahm" id="sahm" value="'.$request->sahm.'">
        <input type="hidden" name="begin_month" id="" value="'.$request->month.'">
        <input type="hidden" name="begin_year" id="" value="'.$request->year.'">
        <div class="d-block">

            <div class="row my-4">
                <div class="table-responsive">
                    <table class="table table-bordered  ">
                        <thead>
                            <tr>
                                
                                <th scope="col" colspan="2">'. $first_season .'</th>
                                <th scope="col" colspan="2">'. $second_season .'</th>
                                <th scope="col" colspan="2">'. $third_season .'</th>
                                <th scope="col" colspan="2">'. $end_season .'</th>
                               


                        </thead>
                        <tbody>
                        <tr>
                               
                        <td> درآمد</td>
                        <td>سود</td>
                        <td> درآمد</td>
                        <td>سود</td>
                        <td> درآمد</td>
                        <td>سود</td>
                        <td> درآمد</td>
                        <td>سود</td>
                       
                        
                    </tr>
                            <tr>
                               
                                <td>
                                    <input type="text" name="season['.$first_season.'][income]">
                                </td>
                                <td><input type="text" name="season['.$first_season.'][gain]"></td>
                                <td><input type="text" name="season['.$second_season.'][income]"></td>
                                <td><input type="text" name="season['.$second_season.'][gain]"></td>
                                <td><input type="text" name="season['.$third_season.'][income]"></td>
                                <td><input type="text" name="season['.$third_season.'][gain]"></td>
                                <td><input type="text" name="season['.$end_season.'][income]"></td>
                                <td><input type="text" name="season['.$end_season.'][gain]"></td>
                                
                            </tr>
                           
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <hr>
        <div class="container text-center">
            <button class="btn btn-primary " type="submit">ثبت اطلاعات</button>
        </div>
    </form>';
     }
     if($request->type == "سالیانه"){
        $table = '<form id="monthly-table" class="needs-validation" action="'.route('MonyReport.Monthly').'" method="post" enctype="multipart/form-data">
        '.csrf_field().'
        <input type="hidden" name="type" id="type" value="'.$request->type.'">
        <input type="hidden" name="sahm" id="sahm" value="'.$request->sahm.'">
        <input type="hidden" name="begin_month" id="" value="'.$request->month.'">
        <input type="hidden" name="begin_year" id="" value="'.$request->year.'">
        <div class="d-block">

            <div class="row my-4">
                <div class="table-responsive">
                    <table class="table table-bordered  ">
                        <thead>
                            <tr>
                                
                                <th scope="col" colspan="2">'.$fivelast_year.'</th>
                                <th scope="col" colspan="2">'.$fourlast_year.'</th>
                                <th scope="col" colspan="2">'.$threelast_year.'</th>
                                <th scope="col" colspan="2">'.$twolast_year.'</th>
                                <th scope="col" colspan="2">'.$last_year.'</th>
                               


                        </thead>
                        <tbody>
                        <tr>
                               
                        <td> درآمد</td>
                        <td>سود</td>
                        <td> درآمد</td>
                        <td>سود</td>
                        <td> درآمد</td>
                        <td>سود</td>
                        <td> درآمد</td>
                        <td>سود</td>
                        <td> درآمد</td>
                        <td>سود</td>
                       
                        
                    </tr>
                            <tr>
                                
                              
                                <td><input type="text" name="year['.$fivelast_year.'][income]"></td>
                                <td><input type="text" name="year['.$fivelast_year.'][gain]"></td>
                                <td><input type="text" name="year['.$fourlast_year.'][income]"></td>
                                <td><input type="text" name="year['.$fourlast_year.'][gain]"></td>
                                <td><input type="text" name="year['.$threelast_year.'][income]"></td>
                                <td><input type="text" name="year['.$threelast_year.'][gain]"></td>
                                <td><input type="text" name="year['.$twolast_year.'][income]"></td>
                                <td><input type="text" name="year['.$twolast_year.'][gain]"></td>
                                <td><input type="text" name="year['.$last_year.'][income]"></td>
                                <td><input type="text" name="year['.$last_year.'][gain]"></td>
                                
                            </tr>
                            
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <hr>
        <div class="container text-center">
            <button class="btn btn-primary " type="submit">ثبت اطلاعات</button>
        </div>
    </form>';
     }
        return response()->json($table,200);

    }

    
}
