@extends('layout.temp')
@section('content')
@include('Includes.Panel.Modal',['url'=> route('Reports.Delete')])

<div class="container-fluid">
    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
                <div class="wpb_wrapper py-3">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                            class="title__divider__wrapper">صورت های مالی و گزارشات<span
                                class="line brk-base-bg-gradient-right"></span>
                        </span> <a href="{{route('MoneyReports.Add')}}" style="left:21px;"
                            class=" btn btn-success btn-sm m-0 position-absolute">افزودن</a>
                    </h6>
                </div>
                <div style="overflow-x: auto;">
                    <table id="example1" class="table table-striped  table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>نام</th>
                                <th>اطلاعات ماهانه</th>
                                <th> اطلاعات فصلی </th>
                                <th> اطلاعات سالیانه</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($namads as $key=>$namad)
                            @php
                            $count = 1;
                            @endphp
                            <tr>
                                <td>{{$count}}</td>
                                <td>
                                    {{$namad->symbol}}
                                </td>
                                <td><a href="{{route('MoneyReports.Add')}}?id={{$namad->id}}&t=ماهانه"
                                        class="text-primary"><i class="fa fa-edit"></i></a> 
                                    @if (count($namad->monthlyReports))
                                        <i class="fa fa-check text-success"></i>
                                    @endif   
                                    </td>
                                <td><a href="{{route('MoneyReports.Add')}}?id={{$namad->id}}&t=فصلی"
                                        class="text-primary"><i class="fa fa-edit"></i></a>
                                        @if (count($namad->seasonalReports))
                                        <i class="fa fa-check text-success"></i>
                                        @endif
                                    </td>
                                <td><a href="{{route('MoneyReports.Add')}}?id={{$namad->id}}&t=سالیانه"
                                        class="text-primary"><i class="fa fa-edit"></i></a>
                                        @if (count($namad->yearlyReports))
                                        <i class="fa fa-check text-success"></i>
                                        @endif
                                    </td>
                                <td>
                                   <a href="#" data-id="{{$namad->id}}" title="حذف" data-toggle="modal" data-target="#deleteModal"
                                    class="btn btn-sm btn-danger   m-2">
                                    <i class="fa fa-trash"></i>
                                </a>
                                </td>
                            </tr>
                            @php
                            $count++;
                            @endphp
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
                            url: mainUrl + '/holding/delete',
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


  