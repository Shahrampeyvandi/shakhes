<?php $__env->startSection('content'); ?>
<?php echo $__env->make('Includes.Panel.Modal',['url'=>url('/user/delete')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('Includes.Panel.Modals.user',['edit'=>true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="container-fluid ">
  <div class="card">
    <div class="card-body">
      <div class="card-title">
        <h5 class="text-center">مدیریت کاربران</h5>
        <hr />
      </div>
      <div style="overflow-x: auto;">
        <table id="users" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ردیف</th>
              <th>
                نام
              </th>
              <th>
                نام خانوادگی
              </th>
              <th>شماره موبایل</th>
              <th>تعداد سهام</th>
              <th>پروفایل عکس</th>
              <th>اشتراک</th>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('assets/vendors/dataTable/defaultConfig.js')); ?>"></script>
<script>
  $(document).ready(function(){
    $('.user-modal').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var user_id = button.data("id"); // Extract info from data-* attributes
    $.ajax({
    type:'post',
    url:mainUrl + '/user/get-data',
    cache: false,
    async: true,
    data:{user_id:user_id,_token:token},
    success:function(data){
      // console.log(data)
      $('#user_mobile').val(data.phone)
      $('#user_id').val(data.id)
      $('#date').val(data.date)
      $('#first_name').val(data.fname)
      $('#last_name').val(data.lname)
    }
  })
})

let users_table = $('#users').DataTable({
"columnDefs": [{
"defaultContent": "-",
"targets": "_all"
}],
"pageLength": 10,
'ajax': {
'url': '',
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
"data": "fname",
'orderable': true,
'searchable': true,
},
{
"data": "lname",
'orderable': true,
'searchable': true,
},

{
"data": "mobile",
'orderable': true,
'searchable': true,
},
{
"data": "namads",
'orderable': true,
'searchable': true,
},
{
'orderable': false,
'searchable': false,
'data': null,
'render': function (data, type, row, meta) {
let image = ` <img style="width:60px" src="${data.avatar}">
`
return ` ${image}`;
}
},
{
"data": "has_plan",
'orderable': true,
'searchable': true,
},

{
'data': null,
'orderable': false,
'searchable': false,
'render': function (data, type, row, meta) {

let links = ` <div class="btn-group" role="group" aria-label="">
  <a href="#" data-id="${data.id}" title="ویرایش کاربر" data-toggle="modal" data-target="#userModal"
    class="btn btn-sm btn-primary  ">
    <i class="fa fa-calendar-day"></i>
  </a>
  <a href="#" data-id="${data.id}" title="حذف" data-toggle="modal" data-target="#deleteModal"
    class="btn btn-sm btn-danger  ">
    <i class="fa fa-trash"></i>
  </a> </div>`

return ` ${links}`;
}
}
],
});
 
})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Users.blade.php ENDPATH**/ ?>