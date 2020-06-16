@extends('layout.temp')
@section('content')


<div class="row">
    <div class="col-md-12">
        <div class="card p-3">
            @include('layout.errors')
        <form id="upload-file" method="post" action="{{route('Disclosures.Create')}}" >
                @csrf
                <h5 class="mb-3">افزودن مورد جدید</h5>
                <div class="row ">
                    <div class="form-group col-md-6">
                        <label for="subject" class="col-form-label">نام سهم: </label>
                     <select class="form-control text-right selectpicker" name="namad"  data-size="5"
                     data-live-search="true" data-title="" id="namad" data-width="100%">
                     @foreach (\App\Models\Namad\Namad::OrderBy('symbol','ASC')->get() as $item)
                     <option value="{{$item->id}}">{{$item->symbol}}</option>
                     @endforeach
                 </select>
                    </div>
                    <div class="form-group col-md-6">

                        <label for="subject" class="col-form-label">گروه: </label>
                        <select required name="group" class="form-control" id="exampleFormControlSelect2">
                            <option value="a" selected>الف</option>
                            <option value="b">ب</option>
                          


                        </select>
                    </div>
 
                 </div>
                <div class="row mb-1">
                    <div class="form-group col-md-12">
                        <label for="subject" class="col-form-label">موضوع: </label>
                        <input type="text" class="form-control" name="subject" id="subject"
                            placeholder="" required>
                    </div>
                    

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">

                                <span class="input-group-text">تاریخ ثبت: </span>

                            </div>
                            <input type="text" class="form-control datepicker-fa" placeholder="" id="date_mind_1"
                             required   aria-label="Small" name="date" aria-describedby="inputGroup-sizing-sm">

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
            </form>
        </div>
        <br />
    </div>
</div>
@endsection


@section('js')
<script>
    $(document).ready(function(){
       
      

 $(document).on('click','.clone-bottom',function(e){
  e.preventDefault()


  var originalDiv = $('.wrapper-content').first();
  
  var originalSelect = originalDiv.find('.selectpicker');
//   originalSelect.selectpicker('destroy').addClass('tmpSelect');

  let cloned = originalDiv.clone()
  let setval =100 - cloned.find('input[type=number]').val()
  let sum = 100
  $('input[type=number]').each(function(){
      sum -= $(this).val()
  })
  cloned.find('input[type=number]').val(sum)
cloned.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
  cloned.prepend(`<div class="col-md-12"><a class="remove-link float-left" href="#" >
                                    <i class="fas fa-trash text-danger"></i>
                                </a></div>`)
                               
  $(this).prev('.clone').append(cloned)
  $('.selectpicker').selectpicker();
 })


 $(document).on('click','.remove-link',function(e){
    e.preventDefault()
    $(this).parents('.wrapper-content').remove()
  
 })



 });
</script>
@endsection