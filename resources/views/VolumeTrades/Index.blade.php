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
                <table id="dd" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th> نماد</th>
                            <th> قیمت </th>
                            <th> آخرین تغییر</th>
                            <th>حجم معاملات </th>
                            <th>حجم معاملات ماهانه</th>
                            <th>ضریب</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js')

    <script src="{{asset('assets/vendors/dataTable/defaultConfig.js')}}">

</script>
<script>
$(document).ready(function () {

let users_table = $('#dd').DataTable({
"columnDefs": [{
"defaultContent": "-",
"targets": "_all"
}],
"pageLength": 10,
'ajax': {
'url': '{{route('VolumeTrades')}}',
'type': 'GET',
"data": function (d, settings) {
var api = new $.fn.dataTable.Api(settings);
d.page = Math.min(
Math.max(0, Math.round(d.start / api.page.len())),
api.page.info().pages
) + 1;
}
},
'columns': [

{
"data": null,
"sortable": false,
render: function (data, type, row, meta) {
return meta.row + meta.settings._iDisplayStart + 1;
}
},
{
"data": "symbol",
'orderable': true,
'searchable': true,
},
{
"data": "pl",
'orderable': true,
'searchable': true,
},
{
'orderable': false,
'searchable': false,
'data': null,
'render': function (data, type, row, meta) {
if(data.status == 'green'){
var color = 'success';
}else{
var color = 'danger';
}

let span = ` <span class="text-${color}">
    ${data.final_price_percent}
</span>`

return ` ${span}`;
}
},
{
"data": "trade_vol",
'orderable': true,
'searchable': true,
},
{
"data": "month_avg",
'orderable': true,
'searchable': true,
},
{
"data": "ratio",
'orderable': true,
'searchable': true,
},
{
"data": "date",
'orderable': true,
'searchable': true,
},
{
'data': null,
'orderable': false,
'searchable': false,
'render': function (data, type, row, meta) {

let deletelink = ` <a href="#" data-id="${data.id}" title="حذف" data-toggle="modal" data-target="#deleteModal"
    class="btn btn-sm btn-danger  ">
    <i class="fa fa-trash"></i>
</a>`

return ` ${deletelink}`;
}
}
],
});

});



</script>

@endsection