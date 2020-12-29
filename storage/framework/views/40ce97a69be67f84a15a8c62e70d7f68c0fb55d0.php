<?php $__env->startSection('content'); ?>


<div class="container-fluid panel-table mt-5">

    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
                <div class="wpb_wrapper py-3 d-flex justify-content-between">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                            class="title__divider__wrapper">پیام های کاربران<span
                                class="line brk-base-bg-gradient-right"></span>
                        </span>
                    </h6>

                </div>

                <div style="overflow-x: auto;">
                    <table id="example1" class="table table-striped  table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th> موضوع</th>
                                <th> متن</th>
                                <th> کاربر</th>
                                <th> تاریخ ارسال</th>
                                <th>وضعیت</th>
                                <th>پاسخ</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$cp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($key+1); ?></td>
                                <td>
                                    <?php echo e($cp->subject); ?>

                                </td>
                                <td><?php echo $cp->content; ?></td>
                                <td><?php echo e($cp->member->phone); ?>

                                </td>
                                <td>
                                    <?php echo e($cp->get_current_date_shamsi($cp->created_at)); ?>

                                </td>
                                <td>
                                    <span class="text-<?php echo e($cp->get_status()['alert']); ?>">
                                        <?php echo e($cp->get_status()['message']); ?>

                                    </span>
                                </td>
                                <td>
                                <a href="<?php echo e(route('Panel.ShowTicket')); ?>?id=<?php echo e($cp->id); ?>" class="btn btn-sm btn-primary">مشاهده</a>
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


</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Tickets/index.blade.php ENDPATH**/ ?>