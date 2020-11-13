@extends('layout.temp')
@section('content')
@include('Includes.Panel.Modal',['url'=>url('/user/delete')])
@include('Includes.Panel.Modals.user',['edit'=>true])
<div class="container-fluid ">
  <div class="card">
    <div class="card-body">
      <div class="card-title">
        <h5 class="text-center">مدیریت کاربران</h5>
        <hr />
      </div>
      <div style="overflow-x: auto;">
        <table id="example1" class="table table-striped table-bordered">
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
              <th>تعداد سهام</th>
              <th>پروفایل عکس</th>
              <th>عملیات</th>
            </tr>
          </thead>
          <tbody class="tbody">
            @foreach ($users as $key=>$user)
            <tr>
              <td> {{$key+1}} </td>
              <td>{{$user->fname}}</td>
              <td>{{$user->lname}}</td>
              <td>{{$user->phone}}</td>
              <td>{{$user->namads->count()}}</td>
              <td>
                @if ($user->avatar !== null )
                <img width="75px" class="img-fluid " src=" {{asset("uploads/brokers/$user->avatar")}} " />
                @else
                <img width="75px" class="img-fluid " src=" {{asset("assets/images/avatar.png")}} " />
                @endif
              </td>
              <td>
                <div class="btn-group" role="group" aria-label="">
                  <a href="#" data-id="{{$user->id}}" title="حذف" data-toggle="modal" data-target="#userModal"
                    class="btn btn-sm btn-primary  ">
                    <i class="fa fa-calendar-day"></i>
                  </a>
                  <a href="#" data-id="{{$user->id}}" title="حذف" data-toggle="modal" data-target="#deleteModal"
                    class="btn btn-sm btn-danger  ">
                    <i class="fa fa-trash"></i>
                  </a> </div>
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

@section('js')
<script>
  $(document).ready(function(){
    $('.user-modal').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var user_id = button.data("id"); // Extract info from data-* attributes
    $.ajax({
    type:'post',
    url:mainUrl + '/user/get-data',
    cache: false,
    async: true,
    data:{user_id:user_id,_token:token},
    success:function(data){
      // console.log(data)
      $('#user_mobile').val(data.phone)
      $('#user_id').val(data.id)
      $('#date').val(data.date)
      $('#first_name').val(data.fname)
      $('#last_name').val(data.lname)
    }
  })
})
 
})
</script>
@endsection