@extends('layout.temp')
@section('content')


<div class="container-fluid">
    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
            <div class="wpb_wrapper py-3">
                <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span class="title__divider__wrapper">پرتفوی روزانه شرکت ها<span class="line brk-base-bg-gradient-right"></span>
                    </span> <a href="{{route('Holding.Create')}}" style="left:21px;" class=" btn btn-success btn-sm m-0 position-absolute">افزودن</a>
                </h6>
            </div>
            <div style="overflow-x: auto;">
                <table id="example1" class="table table-striped  table-bordered w-100">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>نام شرکت</th>
                            <th>تعداد سهام</th>
                            <th> درصد تغییر پرتفوی </th>
                            <th> ارزش ریالی پرتفوی </th>
                            <th>عملیات</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($array as $key=>$holding)
                        @php
                        $count = 1;
                        @endphp
                        <tr>
                            <td>{{$count}}</td>
                            <td>
                                {{$key}}
                            </td>
                            <td>{{$holding['namad_counts']}}</td>
                            <td>{{$holding['percent_change_porftoy']}}</td>
                            <td>{{$holding['portfoy']}}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="">
                                    <a href="{{route('Holding.Namads',$holding['id'])}}" class=" btn btn-rounded btn-info btn-sm m-0">مشاهده</a>
                                    <a data-id="{{$holding['id']}}" class="delete text-white btn btn-rounded btn-danger btn-sm m-0">حذف</a>
                                </div>
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
                            url: '{{url(' / holding / delete ')}}',
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


        $('#exampleModal').on('shown.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('id') // Extract info from data-* attributes
            var modal = $(this)
            modal.find('#id').attr('value', recipient)
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