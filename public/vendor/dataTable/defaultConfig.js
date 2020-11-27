function dataTableHidePagination(Settings) {
    if (Settings._iDisplayLength > Settings.fnRecordsDisplay()) {
        $(Settings.nTableWrapper).find('.dataTables_paginate').hide();
    }
}

function dataTableLanguage(option) {
    return {
        "paginate": {
            "next": "بعدی",
            "previous": "قبلی",
        },
        "search": "",
        "emptyTable": "داده ای یافت نشد.",
        "processing": "درحال پردازش ...",
        "searchPlaceholder": option.searchPlaceholder
    };
}

function dataTableRowNumber() {
    return {
        'orderable': false,
        'searchable': false,
        'data': null,
        'render': function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    }
}

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.extend(true, $.fn.dataTable.defaults, {
        "pageLength": 10,
        'processing': true,
        'serverSide': true,
        "info": false,
        "lengthChange": false,
        "fnDrawCallback": (Settings) => {
            dataTableHidePagination(Settings)
        }
    });
});
