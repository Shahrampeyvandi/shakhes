<?php $__env->startSection('content'); ?>


<div class="row">
    <div class="col-md-12">
        <div class="card p-3">
            <?php echo $__env->make('layout.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <form id="upload-file" method="post" action="<?php echo e(route('Holding.Create')); ?>" >
                <?php echo csrf_field(); ?>
                
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="name" class="col-form-label"><span class="text-danger">*</span> نام شرکت: </label>
                        <select class="form-control text-right selectpicker" name="name"  data-size="5"
                        data-live-search="true" data-title="نام شرکت سرمایه گذاری" id="name" data-width="100%">
                        <?php $__currentLoopData = \App\Models\Namad\Namad::OrderBy('symbol','ASC')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->symbol); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    </div>
                </div>
                <div class="row wrapper-content">
                   <div class="form-group col-md-8">
                    <select class="form-control text-right selectpicker" name="namads[]"  data-size="5"
                    data-live-search="true" data-title="نام سهم" id="namads[]" data-width="100%">
                    <?php $__currentLoopData = \App\Models\Namad\Namad::OrderBy('symbol','ASC')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->symbol); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                   </div>
                   <div class="form-group col-md-4">
                 
                    <input type="number" class="form-control" name="persent[]" id="" placeholder="تعداد" />
                </div>

                </div>

                
                <div class="clone ">

                </div>
                <div class="clone-bottom">

                    <a href="#" class="">
                        مورد جدید
                        <i class="fa fa-plus-circle"></i>
                    </a>
                </div>
                <hr>
                <div class="container text-center">
                    <button class="btn btn-primary " type="submit">ثبت اطلاعات</button>
                </div>
            </form>
        </div>
        <br />
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function(){
       
      

 $(document).on('click','.clone-bottom',function(e){
  e.preventDefault()


  var originalDiv = $('.wrapper-content').first();
  
  var originalSelect = originalDiv.find('.selectpicker');
//   originalSelect.selectpicker('destroy').addClass('tmpSelect');

  let cloned = originalDiv.clone()
 
cloned.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
  cloned.prepend(`<div class="col-md-12"><a class="remove-link float-left" href="#" >
                                    <i class="fas fa-trash text-danger"></i>
                                </a></div>`)
                               
  $(this).prev('.clone').append(cloned)
  $('.selectpicker').selectpicker();
 })


 $(document).on('click','.remove-link',function(e){
    e.preventDefault()
    $(this).parents('.wrapper-content').remove()
  
 })



 });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\panel\resources\views/Portfoy/createholding.blade.php ENDPATH**/ ?>