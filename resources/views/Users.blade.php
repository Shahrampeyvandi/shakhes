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
             
              <th>شماره موبایل</th>
              <th>پروفایل عکس</th>

            </tr>
          </thead>
          <tbody class="tbody">

            @foreach ($users as $key=>$user)
            <tr>
              
              <td> {{$key+1}} </td>
              <td>{{$user->fname}}</td>
              <td>{{$user->lname}}</td>
              <td>{{$user->phone}}</td>
            
              <td>
                @if ($user->avatar !== '' && $user->avatar !== null )
                <img width="75px" class="img-fluid " src=" {{asset("uploads/brokers/$user->avatar")}} " />
                @else
                <img width="75px" class="img-fluid " src=" {{asset("Pannel/img/avatar.jpg")}} " />
                @endif
              </td>
            </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
  </div>

    </div>
</div>
@endsection


