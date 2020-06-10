<?php $__env->startSection('content'); ?>


<div class="row">
    <div class="col-md-12">
        <div class="card p-3">
            <?php echo $__env->make('layout.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <form id="upload-file" method="post" action="<?php echo e(route('ContinuingPaterns.Create')); ?>"  enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <h5 class="mb-3">افزودن مورد جدید</h5>
                <div class="row ">
                    <div class="form-group col-md-6">
                        <label for="subject" class="col-form-label">نام سهم: </label>
                     <select class="form-control text-right selectpicker" name="namad"  data-size="5"
                     data-live-search="true" data-title="" id="namad" data-width="100%" required>
                     <?php $__currentLoopData = \App\Models\Namad\Namad::OrderBy('symbol','ASC')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <option value="<?php echo e($item->id); ?>"><?php echo e($item->symbol); ?></option>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 </select>
                    </div>
                   
                    <div class="form-group col-md-6">
                        <label for="subject" class="col-form-label">نوع: </label>
                        <select class="form-control text-right " name="type"  data-size="5"
                       id="type" data-width="100%">
                       
                        <option value="asc">صعودی</option>
                        <option value="desc">نزولی</option>
   
                    </select>
                       </div>
                 </div>
                <div class="row mb-1">
                    <div class="form-group col-md-6">
                        <label for="name" class="col-form-label" >نام الگو:  </label>
                        <input type="text" class="form-control"  name="name" id="name"
                            placeholder="" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="picture" class="col-form-label">تصویر:   </label>
                        <input type="file" class="form-control" name="picture" id="picture"
                            placeholder="">
                    </div>
                </div>
                
              
                <div class="row">
                    <div class="container text-center">
                        <button class="btn btn-primary " type="submit">ثبت اطلاعات</button>
                    </div>
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
  let setval =100 - cloned.find('input[type=number]').val()
  let sum = 100
  $('input[type=number]').each(function(){
      sum -= $(this).val()
  })
  cloned.find('input[type=number]').val(sum)
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
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\panel\resources\views/Paterns/continuing_paterns_create.blade.php ENDPATH**/ ?>