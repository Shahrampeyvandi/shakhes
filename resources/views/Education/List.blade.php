@extends('layout.temp')
@section('content')

<div class="container-fluid ">
  <div class="card">
    <div class="card-body">
      <div class="card-title">
        <h5 class="text-center">آموزش ها</h5>
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

                <a href="{{route('Education.Show',$education->id)}}" class=" btn  btn-primary btn-sm m-0"><i
                    class="fa fa-eye"></i></a>
                <a href="{{route('Education.Add')}}?edit={{$education->id}}" class=" btn  btn-info btn-sm m-0"><i
                    class="fa fa-edit"></i></a>
                <a data-id="{{$education->id}}" class="delete text-white btn  btn-danger btn-sm m-0"><i
                    class="fa fa-trash"></i></a>

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