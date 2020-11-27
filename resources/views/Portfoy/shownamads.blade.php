@extends('layout.temp')
@section('content')
@include('Includes.Panel.Modals.add-namad-portfoy')

<div class="container-fluid panel-table mt-5">

    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
                <div class="wpb_wrapper py-3 d-flex justify-content-between">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                            class="title__divider__wrapper">لیست سهام شرکت {{$holding->name}}<span
                                class="line brk-base-bg-gradient-right"></span>
                            </span>
                        </h6>
                    <a href="" class="btn btn-sm btn-success" data-id="{{$holding->id}}" data-toggle="modal" data-target="#namadModal">افزودن نماد جدید</a>           
                </div>

                <div style="overflow-x: auto;">
                    <table id="example1" class="table table-striped  table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>نام سهام</th>
                                <th>نماد سهام</th>
                                <th> ارزش سهم شرکت</th>
                                <th> درصد پرتفوی</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($holding->namads as $key=>$namad)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    {{$namad->name}}
                                </td>

                                <td>{{$namad->symbol}}</td>
                          
                                <td>
                                    <span class="btn btn-sm btn-info">%
                                        {{$namad->pivot->amount_percent}}
                                    </span>
                                </td>
                                <td>
                                    {{$namad->pivot->amount_value}}
                                </td>
                                <td>
                                    @if (Cache::get($namad->id)['last_price_status'])
                                    <span
                                        class="text-success">{{Cache::get($namad->id)['final_price_percent']}}
                                        %</span>
                                    @else
                                    <span
                                        class="text-danger">{{Cache::get($namad->id)['final_price_percent']}}
                                        %</span>

                                    @endif
                                </td>
                                <td>
                                    <a data-id="{{$namad->id}}" data-holding="{{$holding->id}}"
                                        class="delete text-white btn  btn-danger btn-sm m-0"><i class="fa fa-trash"></i></a>
                                    <a data-id="{{$namad->id}}"
                                        class="edit text-white btn  btn-primary btn-sm m-0"><i class="fa fa-edit"></i></a>
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
                var holding = $(this).data('holding');
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
                url:'{{url('/holding/namad/delete')}}',
                 data:{_token:'{{csrf_token()}}',id:value,holding:holding},
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

        
$('#namadModal').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('id') // Extract info from data-* attributes
  var modal = $(this)
  modal.find('#id').attr('value',recipient)
})

        })
</script>
@endsection

