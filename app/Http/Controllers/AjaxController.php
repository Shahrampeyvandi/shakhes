<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class AjaxController extends Controller
{
    public function getmoneyreportsdata(Request $request)
    {
        $array = [
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
        

        $key = array_search($request->month, $array); 
        $months =   array_slice($array, $key, 12);
        $new_year = Jalalian::forge('now')->format('%Y');
        $last_year = Jalalian::forge('now')->subYears(1)->format('%Y');

        $table = '<form id="monthly-table" class="needs-validation" action="'.route('MonyReport.Monthly').'" method="post" enctype="multipart/form-data">
        '.csrf_field().'
        <input type="hidden" name="type" id="type" value="'.$request->type.'">
        <input type="hidden" name="sahm" id="sahm" value="'.$request->sahm.'">
        <input type="hidden" name="begin_month" id="" value="'.$request->month.'">
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
                            <tr>
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

        return response()->json($table,200);

    }

    public function getmoneyreportseasonaldata(Request $request)
    {
        $new_year = Jalalian::forge('now')->format('%Y');
        $last_year = Jalalian::forge('now')->subYears(1)->format('%Y');

        $table = '<form id="monthly-table" class="needs-validation" action="'.route('MonyReport.Monthly').'" method="post" enctype="multipart/form-data">
        '.csrf_field().'
        <input type="hidden" name="type" id="type" value="'.$request->type.'">
        <input type="hidden" name="sahm" id="sahm" value="'.$request->sahm.'">
        <input type="hidden" name="begin_month" id="" value="'.$request->month.'">
        <div class="d-block">

            <div class="row my-4">
                <div class="table-responsive">
                    <table class="table table-bordered  ">
                        <thead>
                            <tr>
                                <th>سال</th>
                                <th scope="col" colspan="2">سه ماه اول</th>
                                <th scope="col" colspan="2">سه ماه دوم</th>
                                <th scope="col" colspan="2">سه ماه سوم</th>
                                <th scope="col" colspan="2">سه ماه پایانی</th>
                               


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
                                
                            </tr>
                            <tr>
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

        return response()->json($table,200);


        
    }
}
