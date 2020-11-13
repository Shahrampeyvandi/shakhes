<?php if(session()->has('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo e(session()->get('success')); ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>
<?php if(session()->has('Error')): ?>
<div class="alert alert-error alert-dismissible fade show" role="alert">
    <?php echo e(session()->get('Error')); ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>
<?php if($errors->has('errors')): ?>
<div class="error"><?php echo e($errors->first('errors')); ?></div>
<?php endif; ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Includes/Panel/alerts.blade.php ENDPATH**/ ?>