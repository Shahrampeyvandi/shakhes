@extends('layout.temp')
@section('content')
<style>
    td input {
        width: 50px !important;
    }
</style>
{{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">اخطار</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
       <form id="delete" action="{{route('Holding.Delete')}}" method="post">
           @csrf
           @method('delete')
           <input type="hidden" name="id" id="id" value="">
        <div class="modal-body">
            آیا برای این کار مطمئن هستید؟
          </div>
          <div class="modal-footer">
            <a type="submit" class="btn btn-danger text-white">حذف!</a>
          </div>
       </form>
      </div>
    </div>
</div> --}}
<div class="container-fluid panel-table mt-5">
    
    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="wpb_wrapper py-3">
            <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                    class="title__divider__wrapper">پرتفوی روزانه شرکت ها<span class="line brk-base-bg-gradient-right"></span>
            </span> <a href="{{route('Holding.Create')}}" style="left:0;"
                    class=" btn btn-success btn-sm m-0 position-absolute">افزودن</a>
            </h6>
        </div>
       
        <div style="overflow-x: auto;">
            <table id="example1" class="table table-striped  table-bordered w-100">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام شرکت</th>
                        <th>تعداد سهام</th>
                        <th> ارزش پرتفوی</th>
                        <th> ارزش ریالی </th>
                        <th>عملیات</th>
    
                    </tr>
                </thead>
              <tbody>
                @foreach ($holdings as $key=>$holding)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>
                       {{$holding->name}}
                    </td>
                    <td>{{count($holding->namads)}}</td>
                    <td>0</td>
                    <td>0</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="">
                        <a href="{{route('Holding.Namads',$holding->id)}}"
                                    class=" btn btn-rounded btn-info btn-sm m-0">مشاهده</a>
                        <a data-id="{{$holding->id}}"
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
                url:'{{url('/holding/delete')}}',
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

        
$('#exampleModal').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('id') // Extract info from data-* attributes
  var modal = $(this)
  modal.find('#id').attr('value',recipient)
})

     






        })
</script>
@endsection


@section('css')
<style>
    .edit__profile__left {
        margin-top: 50px;
    }

    .fa__links {
        font-size: 20px;
        background: blue;
        color: white;
        padding: 5px;
        border-radius: 5px;
    }

    .fa-instagram {
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);

    }

    .fa-telegram {
        background: #285AEB;
    }

    .fa-whatsapp {
        background: #075e54;
    }

    .fa-linkedin {
        background: gray;
    }

    @media (min-width: 992px) {
        .modal-lg {
            width: 1000px !important;
        }
    }

    .badge {
        background-color: #74a1d0 !important;
    }
</style>
@endsection