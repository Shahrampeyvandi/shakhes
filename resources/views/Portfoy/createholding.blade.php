@extends('layout.temp')
@section('content')


<div class="row">
    <div class="col-md-12">
        <div class="card p-3">
            @include('layout.errors')
        <form id="upload-file" method="post" action="{{route('Holding.Create')}}" >
                @csrf
                {{-- <div class="form-group ">
                    <label for=""><span class="text-danger">*</span> نوع : </label>
                    <select name="slider_type" id="slider-type" class="form-control  custom-select">
                        <option value="header_slideshow" >header slider</option>
                        <option value="footer_slideshow" >client slider</option>
                    </select>
                </div> --}}
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="name" class="col-form-label"><span class="text-danger">*</span> نام شرکت: </label>
                        <input type="text" class="form-control" name="name" id="name" />
                    </div>
                </div>
                <div class="row wrapper-content">
                   <div class="form-group col-md-8">
                    <select class="form-control text-right selectpicker" name="namads[]"  data-size="5"
                    data-live-search="true" data-title="نام سهم" id="namads[]" data-width="100%">
                    @foreach (\App\Models\Namad\Namad::OrderBy('symbol','ASC')->get() as $item)
                    <option value="{{$item->id}}">{{$item->symbol}}</option>
                    @endforeach
                </select>
                   </div>
                   <div class="form-group col-md-4">
                 
                    <input type="number" class="form-control" name="persent[]" id="" placeholder="درصد پرتفوی" />
                </div>

                </div>

                
                <div class="clone ">

                </div>
                <div class="clone-bottom">

                    <a href="#" class="">
                        مورد جدید
                        <i class="fa fa-plus-circle"></i>
                    </a>
                </div>
                <hr>
                <div class="container text-center">
                    <button class="btn btn-primary " type="submit">ثبت اطلاعات</button>
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
  cloned.find('input[type=number]').val('')
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