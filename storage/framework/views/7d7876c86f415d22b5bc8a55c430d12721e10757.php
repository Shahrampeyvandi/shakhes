<?php $__env->startSection('content'); ?>
<style>
   
    thead{
        background: #00a0ff;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
        <h5> کاربران</h5>
    </div>
    <div class="col-md-12 ">

  <div class="card">
    <div class="card-body">
     
      <div style="overflow-x: auto;">
        <table id="example1" class="table table-striped  table-bordered">
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



<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\panel\resources\views/Users.blade.php ENDPATH**/ ?>