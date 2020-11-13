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
        <table id="example1" class="table table-striped table-bordered">
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
              <th>عملیات</th>
            </tr>
          </thead>
          <tbody class="tbody">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td> <?php echo e($key+1); ?> </td>
              <td><?php echo e($user->fname); ?></td>
              <td><?php echo e($user->lname); ?></td>
              <td><?php echo e($user->phone); ?></td>
              <td><?php echo e($user->namads->count()); ?></td>
              <td>
                <?php if($user->avatar !== null ): ?>
                <img width="75px" class="img-fluid " src=" <?php echo e(asset("uploads/brokers/$user->avatar")); ?> " />
                <?php else: ?>
                <img width="75px" class="img-fluid " src=" <?php echo e(asset("assets/images/avatar.png")); ?> " />
                <?php endif; ?>
              </td>
              <td>
                <div class="btn-group" role="group" aria-label="">
                  <a href="#" data-id="<?php echo e($user->id); ?>" title="حذف" data-toggle="modal" data-target="#userModal"
                    class="btn btn-sm btn-primary  ">
                    <i class="fa fa-calendar-day"></i>
                  </a>
                  <a href="#" data-id="<?php echo e($user->id); ?>" title="حذف" data-toggle="modal" data-target="#deleteModal"
                    class="btn btn-sm btn-danger  ">
                    <i class="fa fa-trash"></i>
                  </a> </div>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
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
 
})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Users.blade.php ENDPATH**/ ?>