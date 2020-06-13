<?php $__env->startSection('content'); ?>
<style>
    td input {
        width: 50px !important;
    }
</style>

<div class="container-fluid panel-table mt-5">
    
    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="wpb_wrapper py-3">
            <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                    class="title__divider__wrapper">افشای اطلاعات با اهمیت<span class="line brk-base-bg-gradient-right"></span>
            </span> <a href="<?php echo e(route('Disclosures.Create')); ?>" style="left:0;"
                    class=" btn btn-success btn-sm m-0 position-absolute">افزودن</a>
            </h6>
        </div>
       
        <div style="overflow-x: auto;">
            <table id="example1" class="table table-striped  table-bordered w-100">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th> موضوع</th>
                        <th> نماد</th>
                        <th> تاریخ گزارش</th>
                        <th> لینک کدال</th>
                        <th>عملیات</th>
    
                    </tr>
                </thead>
              <tbody>
                <?php $__currentLoopData = $disclosures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$disclosure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key+1); ?></td>
                    <td>
                       <?php echo e($disclosure->subject); ?>

                    </td>
                    <td><?php echo e($disclosure->namad->name); ?></td>
                     <td><?php echo e(\Morilog\Jalali\Jalalian::forge($disclosure->publish_date)->format('%B %d، %Y')); ?></td>
                <td>
                    <a href="<?php echo e($disclosure->link_to_codal); ?>" class="text-primary">لینک </a>
                </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="">
                        
                        <a data-id="<?php echo e($disclosure->id); ?>"
                                    class="delete text-white btn btn-rounded btn-danger btn-sm m-0"
                                    
                                    >حذف</a>
                            </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
        </div>
    
    </div>


</div>


</div>
<?php $__env->stopSection(); ?>




<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function(){
        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.delete').click(function(e){
                e.preventDefault()
                var value = $(this).data('id');
           swal({
            title: "آیا اطمینان دارید؟",
            text: "",
            icon: "warning",
			buttons: {
				confirm : 'بله',
				cancel : 'خیر'
			},
            dangerMode: true
        })
        .then(function(willDelete) {
            if (willDelete) {
                // ajax request
              $.ajax({
                type:'POST',
                url:'<?php echo e(url('/disclosures/delete')); ?>',
                 data:{_token:'<?php echo e(csrf_token()); ?>',id:value},
                 success:function(data){
                       setTimeout(()=>{
                        location.reload()
                       },1000)
               
                }
        })
            }
			else {
                swal("عملیات لغو شد", {
					icon: "error",
					button: "تایید"
				});
    		}
    	});

    })

})
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\panel\resources\views/Disclosures/Index.blade.php ENDPATH**/ ?>