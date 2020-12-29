@extends('layout.temp')
@section('content')
@include('Includes.Panel.Modal',['url'=>url('/clarification/delete')])

<div class="container-fluid panel-table mt-5">

    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
                <div class="wpb_wrapper py-3 d-flex justify-content-between">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                            class="title__divider__wrapper">شفاف سازی<span
                                class="line brk-base-bg-gradient-right"></span>
                        </span>
                    </h6>
                    <a href="{{route('Clarification.Create')}}" style="left:0;" class=" btn btn-success btn-sm m-0 ">افزودن</a>
                </div>

                <div style="overflow-x: auto;">
                    <table id="clarifications" class="table table-striped  table-bordered w-100">
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
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>


</div>


</div>
@endsection

@section('js')
<script src="{{asset('assets/vendors/dataTable/defaultConfig.js')}}"></script>
    <script>
        $(document).ready(function () {
        let users_table = $('#clarifications').DataTable({
        "columnDefs": [{
        "defaultContent": "-",
        "targets": "_all"
        }],
        "pageLength": 10,
        'ajax': {
        'url': '{{route('Clarifications')}}',
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
        "data": "subject",
        'orderable': true,
        'searchable': true,
        },
        {
        "data": "symbol",
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
        
        let links = ` 
        
            <a href="${data.link}"  
                class="text-primary ">
                لینک به کدال
            </a>
       `
        
        return ` ${links}`;
        }
        },
       
        
        {
        'data': null,
        'orderable': false,
        'searchable': false,
        'render': function (data, type, row, meta) {
        
        let links = ` <div class="btn-group" role="group" aria-label="">
   
            <a href="#" data-id="${data.id}" title="حذف" data-toggle="modal" data-target="#deleteModal"
                class="btn btn-sm btn-danger  ">
                <i class="fa fa-trash"></i>
            </a>
        </div>`
        
        return ` ${links}`;
        }
        }
        ],
        });

        })
    </script>
@endsection