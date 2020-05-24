@extends('layout.temp')
@section('content')
<style>
    td input {
        width: 50px !important;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
        <h4> صورت های مالی و گزارشات</h4>
    </div>
    <div class="col-md-12 ">

        <h6 class="mt-4">انتخاب سهم: </h6>

        <select class="form-control text-right selectpicker" name="sahm" required data-size="5" data-live-search="true"
            data-title="نام سهم" id="sahm" data-width="100%">
            <option value="شپنا">شپنا</option>
            <option value="ذوب">ذوب</option>
        </select>

        <h6 class="mt-4">شروع مالی سهم: </h6>
        <div class="row mt-3 change-month">
            <div class="form-group col-md-6">

                <select id="begin_year" name="begin_year" class="form-control" id="recipient-name">
                    <option value="">باز کردن فهرست انتخاب</option>
                    <option value="{{$new_year}}">{{$new_year}}</option>
                    <option value="{{$last_year}}">{{$last_year}}</option>




                </select>
            </div>
            <div class="form-group col-md-6">

                <select id="begin_month" name="begin_month" class="form-control" id="recipient-name">
                    <option value="">باز کردن فهرست انتخاب</option>
                    <option value="فروردین">فروردین</option>
                    <option value="اردیبهشت">اردیبهشت</option>
                    <option value="خرداد">خرداد</option>
                    <option value="تیر">تیر</option>
                    <option value="مرداد">مرداد</option>
                    <option value="شهریور">شهریور</option>
                    <option value="مهر">مهر</option>
                    <option value="آبان">آبان</option>
                    <option value="آذر">آذر</option>
                    <option value="دی">دی</option>
                    <option value="بهمن">بهمن</option>
                    <option value="اسفند">اسفند</option>



                </select>
            </div>
        </div>

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
                        <label class="custom-control-label" for="govahi_nano_meghyas_azmayeshgah">سه ماهه</label>
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



        <div id="ajax-table">
            <form id="monthly-table" class="needs-validation" action="{{route('MonyReport.Monthly')}}" method="post"
                enctype="multipart/form-data">
                {{csrf_field()}}
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
                                        <td>{{$last_year}}</td>
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
        </div>









    </div>


</div>


</div>
@endsection




@section('js')
<script>
    $(document).ready(function(){
        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

$('#begin_month').change(function(e){
  e.preventDefault();
 var data = $(this).val()
 var type = $('input[name=type]:checked').val()
 var year = $('#begin_year').val()
 var sahm = $('#sahm').val()

 $.ajax({

type:'post',
url:'{{route("getmoneyreportsdata")}}',
data:{month:data,year:year,type:type,sahm:sahm},
success:function(data){ 
   $('#ajax-table').html(data)
   }
 })
})


        $('input[name=type]').change(function(){
            if($('#begin_month').val() == '' || $('#begin_year').val() == '')
            {
                alert('لطفا ماه و سال مالی را وارد کنید')
                return false;
            }
           
           
 var type = $(this).val()
 var data = $('#begin_month').val()
 var year = $('#begin_year').val()
 var sahm = $('#sahm').val()

 $.ajax({

type:'post',
url:'{{route("getmoneyreportsdata")}}',
data:{month:data,year:year,type:type,sahm:sahm},
success:function(data){ 
   $('#ajax-table').html(data)
   }
 })
           
            })
           



        })
</script>
@endsection


@section('css')
<style>
    .edit__profile__left {
        margin-top: 50px;
    }

    .fa__links {
        font-size: 20px;
        background: blue;
        color: white;
        padding: 5px;
        border-radius: 5px;
    }

    .fa-instagram {
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);

    }

    .fa-telegram {
        background: #285AEB;
    }

    .fa-whatsapp {
        background: #075e54;
    }

    .fa-linkedin {
        background: gray;
    }

    @media (min-width: 992px) {
        .modal-lg {
            width: 1000px !important;
        }
    }

    .badge {
        background-color: #74a1d0 !important;
    }
</style>
@endsection