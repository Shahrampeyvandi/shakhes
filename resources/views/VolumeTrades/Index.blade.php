@extends('layout.temp')
@section('content')
@include('Includes.Panel.Modal',['url'=>url('/volumetrades/delete')])
@include('Includes.Panel.Modals.user',['edit'=>true])
<div class="container-fluid ">
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-center">لیست حجم معاملات مشکوک</h5>
                <hr />
            </div>
            <div style="overflow-x: auto;">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th> نماد</th>
                            <th> قیمت </th>
                            <th>حجم معاملات </th>
                            <th>حجم معاملات ماهانه</th>
                            <th>ضریب</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach ($volumetrades as $key=>$vol)
                        <tr>
                            <td> {{$key+1}} </td>
                            <td>{{$vol->namad->symbol}}</td>
                            <td>{{\Illuminate\Support\Facades\Cache::get($vol->namad->id)['pl']}}</td>
                            <td>{{\Illuminate\Support\Facades\Cache::get($vol->namad->id)['tradevol']}}</td>
                            <td>{{\Illuminate\Support\Facades\Cache::get($vol->namad->id)['monthAVG']}}</td>
                            <td>{{$vol->volume_ratio}}</td>
                           
                            <td>
                                <div class="btn-group" role="group" aria-label="">
                                    <a href="#" data-id="{{$vol->id}}" title="حذف" data-toggle="modal"
                                        data-target="#userModal" class="btn btn-sm btn-primary  ">
                                        <i class="fa fa-calendar-day"></i>
                                    </a>
                                    <a href="#" data-id="{{$vol->id}}" title="حذف" data-toggle="modal"
                                        data-target="#deleteModal" class="btn btn-sm btn-danger  ">
                                        <i class="fa fa-trash"></i>
                                    </a> </div>
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

