<?php if(session()->has('Error')): ?>

    <div class="alert alert-danger ">
        
          <p class="m-3 fs-0-8 text-center"><?php echo e(session()->get('Error')); ?></p>
      
    </div>

<?php endif; ?>
<?php if($errors->any()): ?>
<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="alert alert-danger fs-0-8 p-2"><?php echo e($error); ?></div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\panel\resources\views/layout/errors.blade.php ENDPATH**/ ?>