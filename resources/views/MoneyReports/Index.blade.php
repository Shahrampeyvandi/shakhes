@extends('layout.temp')
@section('content')
<style>
    td input{
        width: 50px !important;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
        <h4> صورت های مالی و گزارشات</h4>
    </div>
    <div class="col-md-12 ">
        
        <form class="needs-validation" action="" method="post" enctype="multipart/form-data" novalidate>
            {{csrf_field()}}
            <div class="col-md-12 my-3">
                <label for="title">انتخاب سهم:  </label>
                <select class="form-control text-right selectpicker" name="RFP_applicant" required data-size="5"
                    data-live-search="true" data-title="نام سهم" id="state_list" data-width="100%">
                    <option value="شپنا">شپنا</option>
                    <option value="ذوب">ذوب</option>
                </select>
            </div>

            <div class="container">

               

                <h6 class="mt-4">نوع اطلاعات</h6>


                <div class="d-block">

                    <div class="row m-4">

                        <div class="col-md-4 mb-3">
                            <div class="custom-control   custom-radio">
                                <input id="govahi_nano_meghyas_yes" name="govahi_nano_meghyas" type="radio"
                                    value="ماهانه" class="custom-control-input" required>
                                <label class="custom-control-label" for="govahi_nano_meghyas_yes">ماهانه</label>
                            </div>
                        </div>


                        <div class="col-md-4 mb-3">
                            <div class="custom-control custom-radio">
                                <input id="govahi_nano_meghyas_azmayeshgah" name="govahi_nano_meghyas" type="radio"
                                    value="سه ماهه" class="custom-control-input" required>
                                <label class="custom-control-label"
                                    for="govahi_nano_meghyas_azmayeshgah">سه ماهه</label>
                            </div>
                        </div>


                        <div class="col-md-4 mb-3">
                            <div class="custom-control   custom-radio">
                                <input id="govahi_nano_meghyas_sanat" name="govahi_nano_meghyas" type="radio"
                                    value="سالیانه" class="custom-control-input" required>
                                <label class="custom-control-label" for="govahi_nano_meghyas_sanat">سالیانه</label>
                            </div>
                        </div>
                      
                       
                    </div>
                </div>
               

               


               


               


                <div class="d-block">

                    <div class="row m-4">
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
                                        <td>1399</td>
                                        <td >
                                            <input type="text">
                                        </td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                       </tr>
                                       <tr>
                                        <td>1398</td>
                                        <td >
                                            <input type="text">
                                        </td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
                                        <td ><input type="text"></td>
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
        </form>

    </div>


  </div>
@endsection




@section('js')
    
@endsection


@section('css')
    <style>

    .edit__profile__left{
       margin-top:50px;
     }
     .fa__links{
        font-size: 20px;
    background: blue;
    color: white;
    padding: 5px;
    border-radius: 5px;
     }
     .fa-instagram{
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);

     }

     .fa-telegram{
         background: #285AEB;
     }

     .fa-whatsapp{
        background: #075e54;
     }

     .fa-linkedin{
         background: gray;
     }
     @media (min-width: 992px){
.modal-lg {
    width: 1000px !important;
}
     }

 .badge{
    background-color: #74a1d0 !important;
 }
    </style>
@endsection
