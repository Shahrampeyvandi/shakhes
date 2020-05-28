<?php

namespace App\Http\Controllers\Ajax;
use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class MoneyReportController extends Controller
{
    public function getmoneyreportsdata(Request $request)
    {
       
     
        $namad_data = Namad::where('id',$request->sahm)->first();
        if(!is_null($namad_data)){
            
            $monthly_data = $namad_data->monthlyReports;
            $seasonal_data = $namad_data->seasonalReports;
            $yearly_data = $namad_data->yearlyReports;
           
        }else{
 
            $monthly_data = [];
            $seasonal_data = [];
            $yearly_data = [];
        }
        if(count($monthly_data)){ $status = 'exist';}else{$status = 'not exist';} 
        
        
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
       


        $key = array_search('فروردین', $months_array); 
     


        

        
        $months =   array_slice($months_array, $key, 12);
        $num_months = array_slice($num_arrays, $key, 12);
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
                                        <input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',1)->where('year',$new_year)->first()) ? $first->value : '').'" name="'.$num_months[0].'['.$new_year.']">
                                        </td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',2)->where('year',$new_year)->first()) ? $first->value : '').'" name="'.$num_months[1].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',3)->where('year',$new_year)->first()) ? $first->value : '').'" name="'.$num_months[2].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',4)->where('year',$new_year)->first()) ? $first->value : '').'" name="'.$num_months[3].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',5)->where('year',$new_year)->first()) ? $first->value : '').'" name="'.$num_months[4].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',6)->where('year',$new_year)->first()) ? $first->value : '').'" name="'.$num_months[5].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',7)->where('year',$new_year)->first()) ? $first->value : '').'" name="'.$num_months[6].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',8)->where('year',$new_year)->first()) ? $first->value : '').'" name="'.$num_months[7].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',9)->where('year',$new_year)->first()) ? $first->value : '').'" name="'.$num_months[8].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',10)->where('year',$new_year)->first()) ? $first->value : '').'"" name="'.$num_months[9].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',11)->where('year',$new_year)->first()) ? $first->value : '').'"" name="'.$num_months[10].'['.$new_year.']"></td>
                                        <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',12)->where('year',$new_year)->first()) ? $first->value : '').'"" name="'.$num_months[11].'['.$new_year.']"></td>
                            </tr>
                           ';
                    if($request->year !== $new_year){
                        $table .=' <tr>
                        <td>'.$last_year.'</td>
                        <td>
                        <input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',1)->where('year',$last_year)->first()) ? $first->value : '').'" name="'.$num_months[0].'['.$last_year.']">
                    </td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',2)->where('year',$last_year)->first()) ? $first->value : '').'" name="'.$num_months[1].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',3)->where('year',$last_year)->first()) ? $first->value : '').'" name="'.$num_months[2].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',4)->where('year',$last_year)->first()) ? $first->value : '').'" name="'.$num_months[3].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',5)->where('year',$last_year)->first()) ? $first->value : '').'" name="'.$num_months[4].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',6)->where('year',$last_year)->first()) ? $first->value : '').'" name="'.$num_months[5].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',7)->where('year',$last_year)->first()) ? $first->value : '').'" name="'.$num_months[6].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',8)->where('year',$last_year)->first()) ? $first->value : '').'" name="'.$num_months[7].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',9)->where('year',$last_year)->first()) ? $first->value : '').'" name="'.$num_months[8].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',10)->where('year',$last_year)->first()) ? $first->value : '').'"" name="'.$num_months[9].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',11)->where('year',$last_year)->first()) ? $first->value : '').'"" name="'.$num_months[10].'['.$last_year.']"></td>
                    <td><input type="text" value="'.(!empty($monthly_data) && !is_null($first = $monthly_data->where('month',12)->where('year',$last_year)->first()) ? $first->value : '').'"" name="'.$num_months[11].'['.$last_year.']"></td>
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
                            <tr>';
                           if(count($seasonal_data)){
                            foreach ($seasonal_data->take(4) as $key => $data) {
                                $table .='<th scope="col" colspan="2">'. $data->season .'</th>'
                                ;
                             }
                           }else{
                                $table .='<th scope="col" colspan="2">'. $first_season .'</th>
                                <th scope="col" colspan="2">'. $second_season .'</th>
                                <th scope="col" colspan="2">'. $third_season .'</th>
                                <th scope="col" colspan="2">'. $end_season .'</th>';
                            }
                              
                       $table .=' </thead>
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
                            <tr>';
                            if(count($seasonal_data)){
                                foreach ($seasonal_data->take(4) as $key => $data) {
                                    $table .=' <td>
                                    <input type="text" value="'.$data->profit.'" name="season['.$data->season.'][income]">
                                </td>
                                <td><input type="text" value="'.$data->loss.'" name="season['.$data->season.'][gain]"></td>'
                                    ;
                                 }
                               }else{
                               
                                $table .='<td>
                                    <input type="text" name="season['.$first_season.'][income]">
                                </td>
                                <td><input type="text" name="season['.$first_season.'][gain]"></td>
                                <td><input type="text" name="season['.$second_season.'][income]"></td>
                                <td><input type="text" name="season['.$second_season.'][gain]"></td>
                                <td><input type="text" name="season['.$third_season.'][income]"></td>
                                <td><input type="text" name="season['.$third_season.'][gain]"></td>
                                <td><input type="text" name="season['.$end_season.'][income]"></td>
                                <td><input type="text" name="season['.$end_season.'][gain]"></td>';
                               }

                                
                               $table .='</tr>
                           
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
                            <tr>';
                            if(count($yearly_data)){
                                foreach ($yearly_data->take(5) as $key => $data) {
                                    $table .='<th scope="col" colspan="2">'.$data->year.'</th>'
                                    ;
                                 }
                               }else{
                                
                                $table .=' <th scope="col" colspan="2">'.$fivelast_year.'</th>
                                <th scope="col" colspan="2">'.$fourlast_year.'</th>
                                <th scope="col" colspan="2">'.$threelast_year.'</th>
                                <th scope="col" colspan="2">'.$twolast_year.'</th>
                                <th scope="col" colspan="2">'.$last_year.'</th>';
                               }
                               


                        $table .='</thead>
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
                            <tr>';
                            if(count($yearly_data)){
                                foreach ($yearly_data->take(5) as $key => $data) {
                                    $table .='<td><input type="text" value="'.$data->profit.'" name="year['.$data->year.'][income]"></td>
                                    <td><input type="text" value="'.$data->loss.'" name="year['.$data->year.'][gain]"></td>'
                                    ;
                                 }
                               }else{
                                
                                $table .=' 
                                
                              
                                <td><input type="text" name="year['.$fivelast_year.'][income]"></td>
                                <td><input type="text" name="year['.$fivelast_year.'][gain]"></td>
                                <td><input type="text" name="year['.$fourlast_year.'][income]"></td>
                                <td><input type="text" name="year['.$fourlast_year.'][gain]"></td>
                                <td><input type="text" name="year['.$threelast_year.'][income]"></td>
                                <td><input type="text" name="year['.$threelast_year.'][gain]"></td>
                                <td><input type="text" name="year['.$twolast_year.'][income]"></td>
                                <td><input type="text" name="year['.$twolast_year.'][gain]"></td>
                                <td><input type="text" name="year['.$last_year.'][income]"></td>
                                <td><input type="text" name="year['.$last_year.'][gain]"></td>';
                               }

                                
                            $table .='</tr>
                            
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
        return response()->json(['table'=>$table,'status'=>$status],200);

    }

    
}
