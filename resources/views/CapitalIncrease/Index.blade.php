@extends('layout.temp')
@section('content')
<style>
    td input {
        width: 50px !important;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
        <h4> افزایش سرمایه</h4>
    </div>
    <div class="col-md-12 ">
        <form action="{{route('CapitalIncrease')}}" method="post">
            @csrf
            <h6 class="mt-4">انتخاب سهم: </h6>
            <select class="form-control text-right selectpicker" name="namad" required data-size="5"
                data-live-search="true" data-title="نام سهم" id="namad" data-width="100%">
                @foreach (\App\Models\Namad\Namad::OrderBy('symbol','ASC')->get() as $item)
                <option value="{{$item->id}}">{{$item->symbol}}</option>
                @endforeach
            </select>



            <div class="row m-4 ajax-table">

            </div>




            <h6 class="mt-4">نوع افزایش سرمایه</h6>
            <div class="d-block">
                <div class="row m-4">
                    <div class="col-md-3 mb-3">
                        <div class="custom-control   custom-radio">
                            <input id="govahi_nano_meghyas_yes" name="type" type="radio" value="assets"
                                class="custom-control-input" required checked>
                            <label class="custom-control-label" for="govahi_nano_meghyas_yes">تجدید ارزیابی دارایی
                                ها</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="custom-control custom-radio">
                            <input id="govahi_nano_meghyas_azmayeshgah" name="type" type="radio" value="stored_gain"
                                class="custom-control-input" required>
                            <label class="custom-control-label" for="govahi_nano_meghyas_azmayeshgah">سود
                                انباشته</label>
                        </div>
                    </div>


                    <div class="col-md-3 mb-3">
                        <div class="custom-control   custom-radio">
                            <input id="govahi_nano_meghyas_sanat" name="type" type="radio" value="cash"
                                class="custom-control-input" required>
                            <label class="custom-control-label" for="govahi_nano_meghyas_sanat">آورده نقدی سهام
                                داران</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="custom-control   custom-radio">
                            <input id="increase" name="type" type="radio" value="compound" class="custom-control-input"
                                required>
                            <label class="custom-control-label" for="increase">افزایش سرمایه ترکیبی</label>
                        </div>
                    </div>


                </div>
                <div class="row percent-wrapper">
                    <div class="form-group col-md-6">

                        <input type="number" class="form-control" name="percent" id="percent"
                            placeholder="میزان افزایش سرمایه برحسب ریال" />
                    </div>
                </div>
                <div class="types-wrapper" style="display: none">
                    <div class="row wrapper-content">
                        <div class="form-group col-md-6">

                            <select name="typearray[]" class="form-control" id="exampleFormControlSelect2">
                                <option value="">باز کردن فهرست انتخاب</option>
                                <option value="assets">تجدید ارزیابی دارایی ها</option>
                                <option value="stored_gain">سود انباشته</option>
                                <option value="cash">آورده نقدی سهام داران</option>


                            </select>
                        </div>
                        <div class="form-group col-md-4">

                            <input type="number" class="form-control" name="percentarray[]" id=""
                                placeholder="میزان افزایش سرمایه برحسب ریال" />
                        </div>

                    </div>


                    <div class="clone ">

                    </div>
                    <div class="clone-bottom mb-5">

                        <a href="#" class="">
                            مورد جدید
                            <i class="fa fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
                <div class="row ">
                    <div class="form-group col-md-6">

                        <select required name="step" class="form-control" id="exampleFormControlSelect2">
                            <option value="">مرحله</option>
                            <option value="اول">اول</option>
                            <option value="دوم">دوم</option>
                            <option value="سوم">سوم</option>


                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">

                                <span class="input-group-text">تاریخ ثبت: </span>

                            </div>
                            <input type="text" class="form-control datepicker-fa" placeholder="" id="date_mind_1"
                                aria-label="Small" name="date" aria-describedby="inputGroup-sizing-sm">

                        </div>
                    </div>

                </div>
                <div class="row ">

                    <div class="form-group col-md-12">
                        <label for="linkcodal" class="col-form-label">لینک به کدال: </label>
                        <input type="text" class="form-control" name="linkcodal" id="linkcodal"
                            placeholder="https://codal.ir">
                    </div>
                </div>
                <div class="row">
                    <div class="container text-center">
                        <button class="btn btn-primary " type="submit">ثبت اطلاعات</button>
                    </div>
                </div>

            </div>




        </form>

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

$('#namad').change(function(){
// check if namad has in database
var value = $(this).val()
$.ajax({
type:'post',
url:'{{route("getCapitalIncreases")}}',
data:{sahm:value},
success:function(data){ 
if(data['count'] > 0 ){
    $('.ajax-table').html(data['list'])
}
}
})


})

        $(document).on('click','.clone-bottom',function(e){
  e.preventDefault()


  var originalDiv = $('.wrapper-content').first();
  
  var originalSelect = originalDiv.find('.selectpicker');
//   originalSelect.selectpicker('destroy').addClass('tmpSelect');

  let cloned = originalDiv.clone()
  let setval =100 - cloned.find('input[type=number]').val()
  
cloned.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
  cloned.prepend(`<div class="col-md-12"><a class="remove-link float-left" href="#" >
                                    <i class="fas fa-trash text-danger"></i>
                                </a></div>`)
                               
  $(this).prev('.clone').append(cloned)
 
 })

 
 $(document).on('click','.remove-link',function(e){
    e.preventDefault()
    $(this).parents('.wrapper-content').remove()
  
 })

     


$('input[name=type]').change(function(){
    var type = $(this).val()
    if(type == 'compound'){
        $('.percent-wrapper').find('#percent').val('')  
        $('.percent-wrapper').hide()
        $('.types-wrapper').show()
    }else{
        $('.clone').html('')  
        $("input[name='percentarray[]']").val('')
        $('.percent-wrapper').show()
        $('.types-wrapper').hide()
    }


           
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