@extends('layout.temp')
@section('content')
<style>
    td input {
        width: 50px !important;
    }
</style>

<div class="container-fluid panel-table mt-5">

    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="wpb_wrapper py-3">
            <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                    class="title__divider__wrapper">الگوهای ادامه دهنده و بازگشتی<span
                        class="line brk-base-bg-gradient-right"></span>
                </span> <a href="{{route('ContinuingPaterns.Create')}}" style="left:0;"
                    class=" btn btn-success btn-sm m-0 position-absolute">افزودن</a>
            </h6>
        </div>
        <div style="overflow-x: auto;">
            <table id="example1" class="table table-striped  table-bordered w-100">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th> نام الگو</th>
                        <th>نوع</th>
                        <th> نماد</th>
                        <th> تاریخ</th>
                        <th> تصویر</th>
                        <th> عملیات</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($paterns as $key=>$patern)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>
                            {{$patern->name}}
                        </td>
                        <td>
                            {{$patern->type == 'asc' ? 'صعودی' : 'نزولی'}}
                        </td>
                        <td>{{$patern->namad->name}}</td>
                        <td>{{\Morilog\Jalali\Jalalian::forge($patern->created_at)->format('%B %d، %Y')}}</td>
                        <td>
                            <a href="{{asset($patern->picture)}}" target="_blank" class="text-primary">
                                <img src="{{asset($patern->picture)}}" alt=" {{$patern->name}}" width="200px">
                            </a>
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="">
                                <a href="#" class=" btn btn-rounded btn-info btn-sm m-0">ویرایش</a>
                                <a data-id="{{$patern->id}}"
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
@endsection
@section('js')
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click','.delete',function(e){
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
                url:'{{url('/continuingpatern/delete')}}',
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