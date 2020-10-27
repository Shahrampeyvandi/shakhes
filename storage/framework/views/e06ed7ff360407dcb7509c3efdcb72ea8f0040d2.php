<?php $__env->startSection('content'); ?>


<div class="card">
    <div class="card-title">
        <h5 class="text-center">ویرایش</h5>
        <hr>
    </div>
    <div class="card-body">
        <form id="add-plan" method="post" action="<?php echo e(route('Panel.EditPlan',$plan->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group col-md-6">
                             <label for="">نام اشتراک</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo e($plan->name); ?>"
                                placeholder="نام اشتراک">
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group form-inline col-md-4">
                            <label for="">مدت زمان اشتراک</label>
                            <div>
                                <input type="number" class="form-control  mx-2" name="time" id="time" placeholder=""
                                    value="<?php echo e($plan->days); ?>">
                                <span>روز</span>
                            </div>
                        </div>
                        <div class="form-group form-inline col-md-4">
                            <label for="">قیمت</label>
                            <div>

                                <input type="number" class="form-control  mx-2" name="price" id="price" placeholder=""
                                    value="<?php echo e($plan->price); ?>">
                                <span>تومان</span>
                            </div>
                        </div>
                         <div class="form-group form-inline col-md-4">
                                <label for="">تخفیف</label>
                                <div>

                                    <input type="number" class="form-control  mx-2" name="discount" id="discount"
                                        placeholder="" value="<?php echo e($plan->discount); ?>">
                                    <span>تومان</span>
                                </div>
                            </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="desc">توضیحات : </label>
                            <textarea class="form-control" name="desc" id="desc" cols="30"
                                rows="8"><?php echo $plan->description; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class=" btn btn-success text-white">ذخیره <i class="fas fa-edit"></i></button>


        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Plans/Edit.blade.php ENDPATH**/ ?>