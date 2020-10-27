<?php $__env->startSection('content'); ?>

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
                <form action="<?php echo e(route('Panel.DeletePlan')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('delete'); ?>
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
            <h5 class="text-center">لیست اشتراک ها</h5>
            <hr>
        </div>
        <div style="">
            <table id="example1" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th> عنوان </th>
                        <th>قیمت</th>
                        <th>تعداد روز</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>

                        <td style="width: 30px"><?php echo e($key+1); ?></td>
                        <td>
                            <a href="#" class="text-primary"><?php echo e($plan->name); ?></a>
                        </td>
                        <td><?php echo e($plan->price); ?> تومان</td>

                        <td class="text-info"><?php echo e($plan->days); ?></td>
                        <td class="text-center">
                            <a href="<?php echo e(route('Panel.EditPlan',$plan)); ?>" class="btn btn-sm btn-info"><i
                                    class="fa fa-edit"></i></a>
                            <a href="#" data-id="<?php echo e($plan->id); ?>" title="حذف " data-toggle="modal"
                                data-target="#deletePlan" class="btn btn-sm btn-danger   m-2">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </tbody>
            </table>
        </div>
       
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
            $('#deletePlan').on('shown.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var recipient = button.data('id')
            $('#plan_id').val(recipient)

    })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Plans/list.blade.php ENDPATH**/ ?>