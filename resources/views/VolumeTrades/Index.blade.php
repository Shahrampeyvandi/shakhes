@extends('layout.temp')
@section('content')

<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
        <h4> افزایش حجم معاملات</h4>
    </div>
    <div class="col-md-12 ">
        <form action="{{route('CapitalIncrease')}}" method="post">
            @csrf
            <div class="form-group col-md-6">
                <h6 class="mt-4">تعیین میزان ضریب :  </h6>
            <input type="number" class="form-control" required>
           </div>
           <div class="form-group col-md-6">
        <button type="submit" class="btn btn-primary" >ذخیره</button>
       </div>
        </form>
    </div>
</div>
</div>
@endsection





