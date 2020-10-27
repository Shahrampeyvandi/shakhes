@extends('layout.temp')
@section('content')

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
                {{-- <a href="#"
                            class=" btn btn-rounded btn-info btn-sm m-0">مشاهده</a> --}}
                <a data-id="{{$user->id}}"
                            class="delete text-white btn btn-rounded btn-danger btn-sm m-0"
                            
                            >حذف</a>
                    </div>
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
      $.ajaxSetup({

          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('.delete').click(function(e){
              e.preventDefault()
              var value = $(this).data('id');
         swal({
          title: "آیا اطمینان دارید؟",
          text: "",
          icon: "warning",
    buttons: {
      confirm : 'بله',
      cancel : 'خیر'
    },
          dangerMode: true
      })
      .then(function(willDelete) {
          if (willDelete) {
              // ajax request
            $.ajax({
              type:'POST',
              url:'{{url('/user/delete')}}',
               data:{_token:'{{csrf_token()}}',id:value},
               success:function(data){
                     setTimeout(()=>{
                      location.reload()
                     },1000)
             
              }
      })
          }
    else {
              swal("عملیات لغو شد", {
        icon: "error",
        button: "تایید"
      });
      }
    });

  })

})
</script>
@endsection
