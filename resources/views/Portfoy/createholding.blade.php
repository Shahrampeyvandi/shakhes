@extends('layout.temp')
@section('content')


<div class="row">
    <div class="col-md-12">
        <div class="card p-3">
            
        <form id="upload-file" method="post" action="{{route('Holding.Create')}}" >
                @csrf
          
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="name" class="col-form-label"><span class="text-danger">*</span> نام شرکت: </label>
                        <select class="form-control js-example-basic-single" name="name"   id="name" >
                        @foreach (\App\Models\Namad\Namad::OrderBy('symbol','ASC')->get() as $item)
                        <option value="{{$item->id}}">{{$item->symbol}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="row wrapper-content">
                   <div class="form-group col-md-8">
                    <select class="form-control js-example-basic-single" name="namads[]"   id="namads[]" >
                    @foreach (\App\Models\Namad\Namad::OrderBy('symbol','ASC')->get() as $item)
                    <option value="{{$item->id}}">{{$item->symbol}}</option>
                    @endforeach
                </select>
                   </div>
                   <div class="form-group col-md-4">
                 
                    <input type="number" class="form-control" name="persent[]" id="" placeholder="تعداد" />
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