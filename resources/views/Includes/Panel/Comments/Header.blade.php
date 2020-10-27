
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">اخطار</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                موارد علامت زده شده حذف شوند؟
            </div>
            <div class="modal-footer">
                <form action="" method="post">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="comment_id" id="comment_id" value="">
                    <a href="#" class="delete-comment btn btn-danger text-white">حذف! </a>
                </form>
            </div>
        </div>
    </div>
</div>


@include('Includes.Panel.Comments.commentsmenu')

<div class="card">
    <div class="container_icon card-body d-flex justify-content-end">
        <div class="delete-edit" style="display:none;">
            <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="order-delete   m-2">
                <span class="__icon bg-danger">
                    <i class="fa fa-trash"></i>
                </span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h5 class="text-center">مدیریت دیدگاه ها</h5>
            <hr>
        </div>
    
        <table id="example1" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th></th>
                    <th>ردیف</th>
                    <th> نویسنده </th>
                    <th>دیدگاه</th>
                    <th>در پاسخ به</th>
                    <th>عملیات</th>


                </tr>
            </thead>

            <tbody>