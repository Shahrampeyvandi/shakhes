@extends('layout.temp')
@section('content')

<div class="container-fluid panel-table mt-5">
   <div>
   <img width="100%" src="{{$education->image}}" alt="">
   </div>
   <div class="desciption">
   <h3 class="mt-5 mb-3">{{$education->title}}</h3>
    {!! $education->description !!}
   </div>
</div>
@endsection

@section('css')
    <style>
       .desciption img{
            width:100% !important;
        }
    </style>
@endsection
