@extends('layout.temp')
@section('content')
<style>
    thead {
        background: #00a0ff;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
        <h5>آموزش ها</h5>
    </div>
    <div class="col-md-12 ">
        <div class="card">
            <div class="card-body">
                <div style="overflow-x: auto;">
                    <table id="example1" class="table table-striped  table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>
                                    نام
                                </th>
                                <th>
                                    دسته بندی
                                </th>
                                <th>
                                    تعداد بازدید
                                </th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">

                            @foreach ($educations as $key=>$education)
                            <tr>

                                <td> {{$key+1}} </td>
                                <td>{{$education->title}}</td>
                                <td>{{$education->category->name}}</td>

                                <td>{{$education->views}}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="">
                                        {{-- <a href="#"
                            class=" btn btn-rounded btn-info btn-sm m-0">مشاهده</a> --}}
                                        <a data-id="{{$education->id}}"
                                            class="delete text-white btn btn-rounded btn-danger btn-sm m-0">حذف</a>
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
              url:'{{url('/education/delete')}}',
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