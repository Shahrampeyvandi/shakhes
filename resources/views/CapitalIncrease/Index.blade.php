@extends('layout.temp')
@section('content')
{{-- @include('Includes.Panel.Modal',['url'=>url('/cp/delete')]) --}}

<div class="container-fluid panel-table mt-5">

    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
                <div class="wpb_wrapper py-3 d-flex justify-content-between">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                            class="title__divider__wrapper">افزایش سرمایه<span
                                class="line brk-base-bg-gradient-right"></span>
                        </span>
                    </h6>
                    <a href="#" style="left:0;" class=" btn btn-success btn-sm m-0 ">افزودن</a>
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
                            @foreach ($cps as $key=>$cp)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    {{$cp->description}}
                                </td>
                                <td>{{$cp->namad->symbol}}</td>
                                <td>{{$cp->get_codal_date() . ' ' . $cp->get_codal_time() }}
                                </td>
                                <td>
                                    <a href="{{$cp->link_to_codal}}" class="text-primary">لینک </a>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="">
                                      
                                        <a href="#" data-id="{{$cp->id}}" title="حذف" data-toggle="modal"
                                            data-target="#deleteModal" class="btn btn-sm btn-danger  ">
                                            <i class="fa fa-trash"></i>
                                        </a>
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


</div>
@endsection