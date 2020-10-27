<?php $__env->startSection('content'); ?>

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
                
                <a data-id="<?php echo e($user->id); ?>"
                            class="delete text-white btn btn-rounded btn-danger btn-sm m-0"
                            
                            >حذف</a>
                    </div>
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
      $.ajaxSetup({

          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('.delete').click(function(e){
              e.preventDefault()
              var value = $(this).data('id');
         swal({
          title: "آیا اطمینان دارید؟",
          text: "",
          icon: "warning",
    buttons: {
      confirm : 'بله',
      cancel : 'خیر'
    },
          dangerMode: true
      })
      .then(function(willDelete) {
          if (willDelete) {
              // ajax request
            $.ajax({
              type:'POST',
              url:'<?php echo e(url('/user/delete')); ?>',
               data:{_token:'<?php echo e(csrf_token()); ?>',id:value},
               success:function(data){
                     setTimeout(()=>{
                      location.reload()
                     },1000)
             
              }
      })
          }
    else {
              swal("عملیات لغو شد", {
        icon: "error",
        button: "تایید"
      });
      }
    });

  })

})
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Users.blade.php ENDPATH**/ ?>