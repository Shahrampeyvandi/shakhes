@extends('layout.temp')
@section('content')

<div class="container-fluid panel-table mt-5">

    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
                <div class="wpb_wrapper py-3">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                            class="title__divider__wrapper">لیست سهام شرکت {{$holding->name}}<span
                                class="line brk-base-bg-gradient-right"></span>
                        </span>
                    </h6>
                </div>

                <div style="overflow-x: auto;">
                    <table id="example1" class="table table-striped  table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>نام سهام</th>
                                <th>نماد سهام</th>
                                <th> بازار</th>
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
                                <td>{{$namad->market}}</td>
                                <td>
                                    <span class="btn btn-sm btn-info">%
                                        {{\DB::table('holdings_namads')
                                    ->whereNamad_id($namad->id)
                                    ->whereHolding_id($holding->id)
                                    ->first()->amount_percent}}
                                    </span>
                                </td>
                                <td>
                                    @if (Illuminate\Support\Facades\Cache::get($namad->id)['last_price_status'])
                                    <span
                                        class="text-success">{{Illuminate\Support\Facades\Cache::get($namad->id)['final_price_percent']}}
                                        %</span>
                                    @else
                                    <span
                                        class="text-danger">{{Illuminate\Support\Facades\Cache::get($namad->id)['final_price_percent']}}
                                        %</span>

                                    @endif
                                </td>
                                <td>
                                    <a data-id="{{$namad->id}}" data-holding="{{$holding->id}}"
                                        class="delete text-white btn btn-rounded btn-danger btn-sm m-0">حذف</a>
                                    <a data-id="{{$namad->id}}"
                                        class="edit text-white btn btn-rounded btn-primary btn-sm m-0">ویرایش</a>
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

        
$('#exampleModal').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('id') // Extract info from data-* attributes
  var modal = $(this)
  modal.find('#id').attr('value',recipient)
})

        })
</script>
@endsection

