<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">اخطار</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                برای حذف این مورد مطمئن هستید؟
            </div>
            <div class="modal-footer">
                <form action="<?php echo e($url); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($method)): ?>
                    <?php echo method_field($method); ?>
                    <?php endif; ?>
                    <input type="hidden" name="id" id="id" value="">
                    <button href="#" type="submit" class=" btn btn-danger text-white">حذف! </button>
                </form>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Includes/Panel/Modal.blade.php ENDPATH**/ ?>