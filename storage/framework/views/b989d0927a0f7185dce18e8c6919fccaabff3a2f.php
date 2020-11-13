<?php $__env->startSection('content'); ?>

<div class="container-fluid ">
    <div class="card">
        <div class="card-body">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="text-center"><?php echo e(isset($edu) ? 'ویرایش' : 'افزودن'); ?> آموزش</h5>
                    <hr />
                </div>
                <form id="upload-file" method="post" action="<?php echo e(route('Education.Add')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-8">
                            <?php if(isset($edu)): ?>
                            <input type="hidden" name="edu" value="<?php echo e($edu->id); ?>">
                            <?php endif; ?>
                            <h6 class="card-title">عنوان </h6>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <input required type="text" class="form-control" name="title" id="title"
                                placeholder="" value="<?php echo e($edu->title ?? ''); ?>">
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="desc">توضیحات : </label>
                                    <textarea class="form-control" name="desc" id="desc" cols="30" rows="8"><?php echo $edu->description ?? ''; ?></textarea>
                                </div>

                            </div>

                          <?php if(isset($edu)): ?>
                              <div class="row">
                                    <div class="col-md-6">
                                        <img width="100%" src="<?php echo e(asset("$edu->image")); ?>" alt="">
                                    </div>
                                </div>
                          <?php endif; ?>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="form-row">
                                        
                                        <div class="col-md-3">
                                            <label for=""> پوستر : </label>
                                        </div>
                                    
                                        <div class="col-md-9">
                                            <input type="file" name="image" class="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 right-side">
                            <div class="cat">
                                <h6 class="card-title">دسته بندی ها: </h6>
                                <input type="text" class="form-control mb-2" name="category" id="category"
                                    placeholder="جدید">
                                <button class="btn btn-outline-primary btn-sm mb-2"
                                    onclick="addCategory(event)">افزودن</button>
                                <div class="cat-wrapper">
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="<?php echo e($key+1); ?>" name="category" value="<?php echo e($category->name); ?>"
                                            class="custom-control-input"
                                            <?php if(isset($edu)): ?>
                                            <?php if($edu->category_id == $category->id): ?> checked <?php endif; ?>
                                            <?php else: ?> 
                                            <?php if(($key+1)==1): ?> checked <?php endif; ?>
                                            <?php endif; ?>
                                            
                                             >
                                        <label class="custom-control-label" for="<?php echo e($key+1); ?>"><?php echo e($category->name); ?></label>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">شماره قسمت</label>
                                    <input type="number" class="form-control" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">تایید </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('vendor/ckeditor/ckeditor.js')); ?>"></script>
<script>
    CKEDITOR.replace('desc', {
        contentsLangDirection: 'rtl',
        extraPlugins: 'uploadimage',
        filebrowserUploadUrl: '<?php echo e(route('UploadImage')); ?>?type=file',
        imageUploadUrl: '<?php echo e(route('UploadImage')); ?>?type=image',
    });

    function addCategory(event) {
        event.preventDefault()
        let val = $(event.target).prev().val();
        let id = Math.random();
        let wrapper = $(event.target).next();
        wrapper.append(`
         <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" 
                            id="${id}" 
                            name="category"
                            value="${val}"
                             class="custom-control-input">
                            <label class="custom-control-label" for="${id}">${val}</label>
                        </div>
         `);

    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Education/add.blade.php ENDPATH**/ ?>