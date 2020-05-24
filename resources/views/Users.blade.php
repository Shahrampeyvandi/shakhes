@extends('layout.temp')
@section('content')
<style>
   
    thead{
        background: #00a0ff;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
        <h5> کاربران</h5>
    </div>
    <div class="col-md-12 ">

  <div class="card">
    <div class="card-body">
     
      <div style="overflow-x: auto;">
        <table id="example1" class="table table-striped  table-bordered">
          <thead>
            <tr>
             
              <th>ردیف</th>
              <th>
                
                  نام
                
              </th>
              <th>
                
                  نام خانوادگی
                 
              </th>
              <th>
                
                  نام کاربری
                
              </th>
              <th>کد ملی</th>
              <th>ایمیل</th>
              <th>شماره موبایل</th>
              <th>پروفایل عکس</th>

            </tr>
          </thead>
          <tbody class="tbody">

            {{-- @foreach ($users as $key=>$user)
            <tr>
              <td>
                <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
                  <input data-id="{{$user->id}}" type="checkbox" id="user_{{ $key}}" name="customCheckboxInline1"
                    class="custom-control-input" value="1">
                  <label class="custom-control-label" for="user_{{$key}}"></label>
                </div>
              </td>
              <td> {{$key+1}} </td>
              <td>{{$user->user_firstname}}</td>
              <td>{{$user->user_lastname}}</td>
              <td>{{$user->user_username}}</td>
              <td>{{$user->user_responsibility}}</td>
              <td>{{$user->user_national_code}}</td>
              <td>{{$user->user_mobile}}</td>
              <td>
                @if ($user->user_prfile_pic !== '' && $user->user_prfile_pic !== null )
                <img width="75px" class="img-fluid " src=" {{asset("uploads/brokers/$user->user_prfile_pic")}} " />
                @else
                <img width="75px" class="img-fluid " src=" {{asset("Pannel/img/avatar.jpg")}} " />
                @endif
              </td>
            </tr>
            @endforeach --}}

          </tbody>
        </table>
      </div>
    </div>
  </div>

    </div>
</div>
@endsection


