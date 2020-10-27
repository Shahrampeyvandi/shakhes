@extends('layout.temp')

@section('content')

<div class="modal fade" id="deletePlan" tabindex="-1" role="dialog" aria-labelledby="deletePlanLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePlanLabel">اخطار</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                برای حذف این مورد مطمئن هستید؟
            </div>
            <div class="modal-footer">
                <form action="{{route('Panel.DeletePlan')}}" method="post">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="plan_id" id="plan_id" value="">
                    <button href="#" type="submit" class=" btn btn-danger text-white">حذف! </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h5 class="text-center">لیست تراکنش ها</h5>
            <hr>
        </div>
        <div style="">
            <table id="example1" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>شماره سفارش</th>
                        <th>زمان ثبت سفارش</th>
                        <th>وضعیت سفارش</th>
                        <th>کاربر</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymants as $key=>$item)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>{{$item->transaction_code}}</td>
                        <td>{{\Morilog\Jalali\Jalalian::forge($item->created_at)->format('%B %d، %Y')}}</td>
                        <td>
                            @if ($item->success == '1')
                            <span class="text-success">موفق</span>
                            @else
                            <span class="text-danger">ناموفق</span>
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
       
    </div>
</div>

@endsection

@section('js')
    <script>
            $('#deletePlan').on('shown.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var recipient = button.data('id')
            $('#plan_id').val(recipient)

    })
    </script>
@endsection
