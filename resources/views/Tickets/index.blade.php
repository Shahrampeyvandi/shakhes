@extends('layout.temp')
@section('content')
{{-- @include('Includes.Panel.Modal',['url'=>url('/cp/delete')]) --}}

<div class="container-fluid panel-table mt-5">

    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
                <div class="wpb_wrapper py-3 d-flex justify-content-between">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                            class="title__divider__wrapper">پیام های کاربران<span
                                class="line brk-base-bg-gradient-right"></span>
                        </span>
                    </h6>

                </div>

                <div style="overflow-x: auto;">
                    <table id="example1" class="table table-striped  table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th> موضوع</th>
                                <th> متن</th>
                                <th> کاربر</th>
                                <th> تاریخ ارسال</th>
                                <th>وضعیت</th>
                                <th>پاسخ</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $key=>$cp)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    {{$cp->subject}}
                                </td>
                                <td>{!!$cp->content!!}</td>
                                <td>{{$cp->member->phone }}
                                </td>
                                <td>
                                    {{$cp->get_current_date_shamsi($cp->created_at)}}
                                </td>
                                <td>
                                    <span class="text-{{$cp->get_status()['alert']}}">
                                        {{$cp->get_status()['message']}}
                                    </span>
                                </td>
                                <td>
                                <a href="{{route('Panel.ShowTicket')}}?id={{$cp->id}}" class="btn btn-sm btn-primary">مشاهده</a>
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