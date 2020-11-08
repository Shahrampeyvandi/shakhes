@extends('layout.temp')
@section('content')

<div class="container-fluid ">
    <div class="card">
        <div class="card-body">
            <div class="card-title position-relative">
            <h5 class="text-center">{{isset($namad) ? 'ویرایش' : ''}} صورت های مالی {{$type ?? ''}} {{$namad->symbol ?? ''}}</h5>
            <a href="{{route('MoneyReports')}}" class="btn btn-primary back-link">بازگشت</a>
            <hr />
            </div>

            <div class="col-md-12 ">
                @if (!isset($namad))
                <h6 class="mt-4">انتخاب سهم: </h6>
                <select class="form-control js-example-basic-single" name="namad" id="namad" required dir="rtl">
                    @foreach (\App\Models\Namad\Namad::OrderBy('symbol','ASC')->get() as $item)
                    <option value="{{$item->id}}">{{$item->symbol}}</option>
                    @endforeach
                </select>

                <div class="change-month">
                    <h6 class="mt-4">شروع مالی سهم: </h6>
                    <div class="row mt-3 ">
                        <div class="form-group col-md-6">
                            <select id="begin_year" name="begin_year" class="form-control" id="recipient-name">
                                <option value="{{$last_year}}" selected>{{$last_year}}</option>
                                <option value="{{$new_year}}">{{$new_year}}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <select id="begin_month" name="begin_month" class="form-control" id="recipient-name">
                                <option value="1" {{isset($namad) && $namad->begin_month == 1 ? 'selected' : ''}}>
                                    فروردین</option>
                                <option value="2" {{isset($namad) && $namad->begin_month == 2 ? 'selected' : ''}}>
                                    اردیبهشت</option>
                                <option value="3" {{isset($namad) && $namad->begin_month == 3 ? 'selected' : ''}}>خرداد
                                </option>
                                <option value="4" {{isset($namad) && $namad->begin_month == 4 ? 'selected' : ''}}>تیر
                                </option>
                                <option value="5" {{isset($namad) && $namad->begin_month == 5 ? 'selected' : ''}}>مرداد
                                </option>
                                <option value="6" {{isset($namad) && $namad->begin_month == 6 ? 'selected' : ''}}>شهریور
                                </option>
                                <option value="7" {{isset($namad) && $namad->begin_month == 7 ? 'selected' : ''}}>مهر
                                </option>
                                <option value="8" {{isset($namad) && $namad->begin_month == 8 ? 'selected' : ''}}>آبان
                                </option>
                                <option value="9" {{isset($namad) && $namad->begin_month == 9 ? 'selected' : ''}}>آذر
                                </option>
                                <option value="10" {{isset($namad) && $namad->begin_month == 10 ? 'selected' : ''}}>دی
                                </option>
                                <option value="11" {{isset($namad) && $namad->begin_month == 11 ? 'selected' : ''}}>بهمن
                                </option>
                                <option value="12" {{isset($namad) && $namad->begin_month == 12 ? 'selected' : ''}}>
                                    اسفند</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{-- <div class="row mt-3 show-details" style="display: none">
                    <a href="" class="ml-3 monthly">مشاهده نمودار ماهانه</a>
                    <a href="" class="ml-3 seasonal">مشاهده نمودار سه ماهه</a>
                    <a href="" class="ml-3 yearly">مشاهده نمودار سالیانه</a>
                    <a href="" class="ml-3 delete_reports">حذف کل اطلاعات</a>
                </div> --}}

                <h6 class="mt-4">نوع اطلاعات</h6>


                <div class="d-block">
                    <div class="row m-4">
                        <div class="col-md-4 mb-3">
                            <div class="custom-control   custom-radio">
                                <input id="govahi_nano_meghyas_yes" name="type" type="radio" value="ماهانه"
                                    class="custom-control-input" required checked>
                                <label class="custom-control-label" for="govahi_nano_meghyas_yes">ماهانه</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="custom-control custom-radio">
                                <input id="govahi_nano_meghyas_azmayeshgah" name="type" type="radio" value="سه ماهه"
                                    class="custom-control-input" required>
                                <label class="custom-control-label" for="govahi_nano_meghyas_azmayeshgah">سه
                                    ماهه</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="custom-control   custom-radio">
                                <input id="govahi_nano_meghyas_sanat" name="type" type="radio" value="سالیانه"
                                    class="custom-control-input" required>
                                <label class="custom-control-label" for="govahi_nano_meghyas_sanat">سالیانه</label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div id="ajax-table">
                 
                    <?php
                    if (isset($type) && $type == 'فصلی') { ?>

                 <form id="monthly-table" class="needs-validation" action="<?php echo route('MonyReport.Monthly') ?>"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" id="type" value="سه ماهه">
                        <input type="hidden" name="sahm" id="sahm" value="{{$namad->id}}">
                        <div class="d-block">
                            <div class="row my-4">
                                <div class="" style="overflow-x: scroll !important;">
                                    <table class="table table-bordered  ">
                                        <thead>
                                            <tr>
                     <?php
                        if (count($first)) {
                            foreach ($first as $key => $data) {
                                echo '<th scope="col" colspan="2">سه ماه '. $data->season .' سال '. $data->year .' </th>';
                            }
                        } else {
                            echo '<th scope="col" colspan="2">سه ماه ابتدا</th>
                                            <th scope="col" colspan="2">سه ماه دوم</th>
                                            <th scope="col" colspan="2">سه ماه سوم</th>
                                            <th scope="col" colspan="2">سه ماه آخر</th>';
                        }

                echo  "</thead>
                            <tbody>
                            <tr>";
            $count = 1;
            for ($i = 0; $i < 4; $i++) {
                echo ' <td scope="col" colspan="2">
                            فصل 
                            <select style="display: inline;
                            width: 70px;" name="num[' . $count . '][]" class="form-control" id="exampleFormControlSelect2">
                            <option value="اول">اول</option>
                            <option value="دوم">دوم</option>
                            <option value="سوم">سوم</option>
                            <option value="چهارم">چهارم</option>
                        </select>
                        سال
                        <select style="display: inline;
                        width: 90px;" name="num[' . $count . '][]" class="form-control" id="exampleFormControlSelect2">
                        <option value="' . $new_year . '">' . $new_year . '</option>
                        <option value="' . $last_year . '">' . $last_year . '</option>
                    </select>
                            </td>';
                $count++;
                }


                echo '  </tr>
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
            if (count($first)) {
                $count = 1;
                
                foreach ($first as $key => $data) {
                    if ($data->number == $count) {
                       
                        echo ' <td>
                                     <input type="text" value="' . $data->profit . '" name="num[' . $count . '][]">
                                     </td>
                                     <td><input type="text" value="' . $data->loss . '" name="num[' . $count . '][]"></td>';
                    }else{
                        echo ' <td>
                            <input type="text" value="" name="num[' . $count . '][]">
                        </td>
                        <td><input type="text" value="" name="num[' . $count . '][]"></td>';
                    }

                    $count++;
                }
            } else {

                echo '  <td>
                                                    <input type="text" value=""
                                                        name="num[1][]">
                                                </td>
                                                <td><input type="text" value="" name="num[1][]">
                                                </td>
                                                <td>
                                                    <input type="text" value="0"
                                                        name="num[2][]">
                                                </td>
                                                <td><input type="text" value="0" name="num[2][]">
                                                </td>
                                                <td>
                                                    <input type="text" value="0"
                                                        name="num[3][]">
                                                </td>
                                                <td><input type="text" value="0" name="num[3][]">
                                                </td>
                                                <td>
                                                    <input type="text" value="0"
                                                        name="num[4][]">
                                                </td>
                                                <td><input type="text" value="0" name="num[4][]">
                                                </td>';
            }


            echo '</tr>
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
}elseif(isset($type) && $type == 'سالیانه') { 

echo '<form id="monthly-table" class="needs-validation" action="' . route('MonyReport.Monthly') . '" method="post"
        enctype="multipart/form-data">
        ' . csrf_field() . '
        <input type="hidden" name="type" id="type" value="سالیانه">
        <input type="hidden" name="sahm" id="sahm" value="' . $namad->id . '">
       
        <div class="d-block">
    
            <div class="row my-4">
                <div class="" style="overflow-x: scroll !important;">
                    <table class="table table-bordered  ">
                        <thead>
                            <tr>';
                                if (count($first)) {
                                foreach ($first as $key => $data) {
                               echo '<th scope="col" colspan="2">' . $data->year . '</th>';
                                }
                                } else {
    
                               echo ' <th scope="col" colspan="2">' . $fivelast_year . '</th>
                                <th scope="col" colspan="2">' . $fourlast_year . '</th>
                                <th scope="col" colspan="2">' . $threelast_year . '</th>
                                <th scope="col" colspan="2">' . $twolast_year . '</th>
                                <th scope="col" colspan="2">' . $last_year . '</th>';
                                }
                               echo '
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
                            <tr>';
                                if (count($first)) {
                                foreach ($first as $key => $data) {
                               echo '<td><input type="text" value="' . $data->profit . '"
                                        name="year[' . $data->year . '][income]"></td>
                                <td><input type="text" value="' . $data->loss . '" name="year[' . $data->year . '][gain]">
                                </td>';
                                }
                                } else {
    
                               echo '
                                <td><input type="text" name="year[' . $fivelast_year . '][income]"></td>
                                <td><input type="text" name="year[' . $fivelast_year . '][gain]"></td>
                                <td><input type="text" name="year[' . $fourlast_year . '][income]"></td>
                                <td><input type="text" name="year[' . $fourlast_year . '][gain]"></td>
                                <td><input type="text" name="year[' . $threelast_year . '][income]"></td>
                                <td><input type="text" name="year[' . $threelast_year . '][gain]"></td>
                                <td><input type="text" name="year[' . $twolast_year . '][income]"></td>
                                <td><input type="text" name="year[' . $twolast_year . '][gain]"></td>
                                <td><input type="text" name="year[' . $last_year . '][income]"></td>
                                <td><input type="text" name="year[' . $last_year . '][gain]"></td>';
                                }
    
    
                               echo '
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

}else{ ?>
    <form id="monthly-table" class="needs-validation" action="{{route('MonyReport.Monthly')}}" method="post"
        enctype="multipart/form-data">
        {{csrf_field()}}
    
        @isset($namad)
        <input type="hidden" name="sahm" id="sahm" value="{{$namad->id}}">
        <input type="hidden" name="type" id="type" value="{{$type}}">
        @else
        <input type="hidden" name="type" id="type" value="ماهانه">
        <input type="hidden" name="n" id="n" value="">
        <input type="hidden" name="begin_month" id="" value="">
        <input type="hidden" name="begin_year" id="" value="">
        @endisset
        <div class="d-block">
            <div class="row my-4">
                <div class="" style="overflow-x: scroll !important;">
                    <table class="table table-bordered  ">
                        <thead>
                            <tr>
                                <th>سال</th>
                                <th scope="col">فروردین</th>
                                <th scope="col">اردیبهشت</th>
                                <th scope="col">خرداد</th>
                                <th scope="col">تیر</th>
                                <th scope="col">مرداد</th>
                                <th scope="col">شهریور</th>
                                <th scope="col">مهر</th>
                                <th scope="col">آبان</th>
                                <th scope="col">آذر</th>
                                <th scope="col">دی</th>
                                <th scope="col">بهمن</th>
                                <th scope="col">اسفند</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$new_year}}</td>
                                <td>
                                    <input type="text" name="1[{{$new_year}}]" value="{{$first[1] ?? ''}}">
                                </td>
                                <td><input type="text" name="2[{{$new_year}}]" value="{{$first[2] ?? ''}}"></td>
                                <td><input type="text" name="3[{{$new_year}}]" value="{{$first[3] ?? ''}}"></td>
                                <td><input type="text" name="4[{{$new_year}}]" value="{{$first[4] ?? ''}}"></td>
                                <td><input type="text" name="5[{{$new_year}}]" value="{{$first[5] ?? ''}}"></td>
                                <td><input type="text" name="6[{{$new_year}}]" value="{{$first[6] ?? ''}}"></td>
                                <td><input type="text" name="7[{{$new_year}}]" value="{{$first[7] ?? ''}}"></td>
                                <td><input type="text" name="8[{{$new_year}}]" value="{{$first[8] ?? ''}}"></td>
                                <td><input type="text" name="9[{{$new_year}}]" value="{{$first[9] ?? ''}}"></td>
                                <td><input type="text" name="10[{{$new_year}}]" value="{{$first[10] ?? ''}}"></td>
                                <td><input type="text" name="11[{{$new_year}}]" value="{{$first[11] ?? ''}}"></td>
                                <td><input type="text" name="12[{{$new_year}}]" value="{{$first[12] ?? ''}}"></td>
                            </tr>
                            <tr>
                                <td>{{$last_year}}</td>
                                <td>
                                    <input type="text" name="1[{{$last_year}}]" value="{{$last[1] ?? ''}}">
                                </td>
                                <td><input type="text" name="2[{{$last_year}}]" value="{{$last[2] ?? ''}}"></td>
                                <td><input type="text" name="3[{{$last_year}}]" value="{{$last[3] ?? ''}}"></td>
                                <td><input type="text" name="4[{{$last_year}}]" value="{{$last[4] ?? ''}}"></td>
                                <td><input type="text" name="5[{{$last_year}}]" value="{{$last[5] ?? ''}}"></td>
                                <td><input type="text" name="6[{{$last_year}}]" value="{{$last[6] ?? ''}}"></td>
                                <td><input type="text" name="7[{{$last_year}}]" value="{{$last[7] ?? ''}}"></td>
                                <td><input type="text" name="8[{{$last_year}}]" value="{{$last[8] ?? ''}}"></td>
                                <td><input type="text" name="9[{{$last_year}}]" value="{{$last[9] ?? ''}}"></td>
                                <td><input type="text" name="10[{{$last_year}}]" value="{{$last[10] ?? ''}}"></td>
                                <td><input type="text" name="11[{{$last_year}}]" value="{{$last[11] ?? ''}}"></td>
                                <td><input type="text" name="12[{{$last_year}}]" value="{{$last[12] ?? ''}}"></td>
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
    </form>
    <?php
}
               echo '</div>

            </div>
        </div>
    </div>';
    ?>
@endsection

 @section('js')
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#namad').change(function(){
            // check if namad has in database
            var value = $(this).val()
            var data = $('#begin_month').val()
            var type = $('input[name=type]:checked').val()
            var year = $('#begin_year').val()
            var monthly_url = "{{route("BaseUrl")}}/moneyreports/showchart/"+value
            var yearly_url = "{{route("BaseUrl")}}/yearlyreports/showchart/"+value
            var seasonal_url = "{{route("BaseUrl")}}/seasonalreports/showchart/"+value
            var delete_url = "{{route("BaseUrl")}}/namadreports/delete/"+value
          $.ajax({
            type:'post',
            url:mainUrl + '/moneyreports/getdata',
            data:{month:data,year:year,type:type,sahm:value,_token:token},
            success:function(data){ 

                if(data['status'] == 'exist'){
                    $('.change-month').hide()
                    $('.show-details').show()
                    $('.show-details').children('.monthly').attr('href',monthly_url)
                    $('.show-details').children('.seasonal').attr('href',seasonal_url)
                    $('.show-details').children('.yearly').attr('href',yearly_url)
                    $('.show-details').children('.delete_reports').attr('href',delete_url)
                    
                }

                if(data['status'] == 'not exist'){
                    $('.change-month').show()
                    $('.show-details').hide()
                }

            $('#ajax-table').html(data['table'])
            }
        })

            $('#n').val(value)
        })

    $('#begin_month').change(function(e){
    e.preventDefault();
    var data = $(this).val()
    var type = $('input[name=type]:checked').val()
    var year = $('#begin_year').val()
    var sahm = $('#sahm').val()

    $.ajax({
    type:'post',
    url:'{{route("getmoneyreportsdata")}}',
    data:{month:data,year:year,type:type,sahm:sahm,_token:token},
    success:function(data){ 
    $('#ajax-table').html(data['table'])
    }
    })
    })


        $('input[name=type]').change(function(){
            // if($('#begin_month').val() == '' || $('#begin_year').val() == '')
            // {
            //     alert('لطفا ماه و سال مالی را وارد کنید')
            //     return false;
            // }
           
           
        var type = $(this).val()
        var data = $('#begin_month').val()
        var year = $('#begin_year').val()
        var sahm = $('#namad').val()

        $.ajax({
        type:'post',
        url:'{{route("getmoneyreportsdata")}}',
        data:{month:data,year:year,type:type,sahm:sahm,_token:token},
        success:function(data){ 
        $('#ajax-table').html(data['table'])
        }
        })
                
            })
           



        })
                                                </script>
                                                @endsection


 