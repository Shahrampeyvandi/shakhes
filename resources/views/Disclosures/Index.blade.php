@extends('layout.temp')
@section('content')

<div class="container-fluid panel-table mt-5">
    <div class="card">
        <div class="card-body">
            <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
                <div class="wpb_wrapper py-3">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span class="title__divider__wrapper">افشای اطلاعات با اهمیت<span class="line brk-base-bg-gradient-right"></span>
                        </span> <a href="{{route('Disclosures.Create')}}" style="left:0;" class=" btn btn-success btn-sm m-0 position-absolute">افزودن</a>
                    </h6>
                </div>
                <div style="overflow-x: auto;">
                    <table id="example1" class="table table-striped  table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th> موضوع</th>
                                <th> نماد</th>
                                <th> تاریخ گزارش</th>
                                <th> لینک کدال</th>
                                <th>عملیات</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($disclosures as $key=>$disclosure)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    {{$disclosure->subject}}
                                </td>
                                <td>
                                    @if ($disclosure->namad)

                                    {{$disclosure->namad->name}}
                                    @endif
                                </td>
                                <td>{{\Morilog\Jalali\Jalalian::forge($disclosure->publish_date)->format('%B %d، %Y')}}</td>
                                <td>
                                    <a href="{{$disclosure->link_to_codal}}" class="text-primary">لینک </a>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="">
                                        {{-- <a href="#"
                                    class=" btn btn-rounded btn-info btn-sm m-0">مشاهده</a> --}}
                                        <a data-id="{{$disclosure->id}}" class="delete text-white btn btn-rounded btn-danger btn-sm m-0">حذف</a>
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
    $(document).ready(function() {
        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.delete').click(function(e) {
            e.preventDefault()
            var value = $(this).data('id');
            swal({
                    title: "آیا اطمینان دارید؟",
                    text: "",
                    icon: "warning",
                    buttons: {
                        confirm: 'بله',
                        cancel: 'خیر'
                    },
                    dangerMode: true
                })
                .then(function(willDelete) {
                    if (willDelete) {
                        // ajax request
                        $.ajax({
                            type: 'POST',
                            url: '{{url(' / disclosures / delete ')}}',
                            data: {
                                _token: '{{csrf_token()}}',
                                id: value
                            },
                            success: function(data) {
                                setTimeout(() => {
                                    location.reload()
                                }, 1000)

                            }
                        })
                    } else {
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