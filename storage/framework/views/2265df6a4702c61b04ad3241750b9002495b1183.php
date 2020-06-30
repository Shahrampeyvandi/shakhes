<?php $__env->startSection('content'); ?>

<div class="container-fluid panel-table mt-5">
   <div>
   <img width="100%" src="<?php echo e($education->image); ?>" alt="">
   </div>
   <div class="desciption">
   <h3 class="mt-5 mb-3"><?php echo e($education->title); ?></h3>
    <?php echo $education->description; ?>

   </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
       .desciption img{
            width:100% !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\panel\resources\views/Education/show.blade.php ENDPATH**/ ?>