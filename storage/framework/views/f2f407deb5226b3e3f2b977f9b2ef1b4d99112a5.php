<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-center">افزودن اشتراک</h5>
                <hr />
            </div>
            <form id="add-plan" method="post" action="<?php echo e(route('Panel.AddPlan')); ?>">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" name="name" id="name" value=""
                                    placeholder="نام اشتراک">
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group form-inline col-md-4">
                                <label for="">مدت زمان اشتراک</label>
                                <div>
                                    <input type="number" class="form-control  mx-2" name="time" id="time" placeholder=""
                                        value="">
                                    <span>روز</span>
                                </div>
                            </div>
                            <div class="form-group form-inline col-md-4">
                                <label for="">قیمت</label>
                                <div>

                                    <input type="number" class="form-control  mx-2" name="price" id="price"
                                        placeholder="" value="">
                                    <span>تومان</span>
                                </div>
                            </div>
                            <div class="form-group form-inline col-md-4">
                                <label for="">تخفیف</label>
                                <div>

                                    <input type="number" class="form-control  mx-2" name="discount" id="discount"
                                        placeholder="" value="">
                                    <span>تومان</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="desc">توضیحات : </label>
                                <textarea class="form-control" name="desc" id="desc" cols="30" rows="8"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="desc">اطلاع رسانی به کاربران: </label>
                                <select class="custom-select" name="sendsms" id="sendsms">
                                    <option value="">لازم نیست</option>
                                    <option value="sms">پیامک</option>
                                    <option value="email">ایمیل</option>
                                    <option value="noty">نوتیفیکیشن</option>
                                </select>
                            </div>
                        </div>





                    </div>


                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">ثبت اشتراک </button>
                    </div>
                </div>
            </form>
            <hr>
            <div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<style>
    label.error {
        font-size: 12px;
        color: red;
        /* position: absolute; */
        /* top: -50px; */
        /* right: 70px; */
        margin-left: 50px;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Plans/add.blade.php ENDPATH**/ ?>