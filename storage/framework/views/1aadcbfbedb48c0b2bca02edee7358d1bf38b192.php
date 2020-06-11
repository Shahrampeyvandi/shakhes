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
            class="title__divider__wrapper">لیست سهام شرکت <?php echo e($holding->name); ?><span class="line brk-base-bg-gradient-right"></span>
            </span>
            </h6>
        </div>
       
        <div style="overflow-x: auto;">
            <table id="example1" class="table table-striped  table-bordered w-100">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام سهام</th>
                        <th>نماد سهام</th>
                        <th> بازار</th>
                        <th> درصد پرتفوی</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
    
                    </tr>
                </thead>
              <tbody>
                <?php $__currentLoopData = $holding->namads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$namad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key+1); ?></td>
                    <td>
                       <?php echo e($namad->name); ?>

                    </td>
                    
                    <td><?php echo e($namad->symbol); ?></td>
                    <td><?php echo e($namad->market); ?></td>
                    <td>
                        <span class="btn btn-info">% 
                        <?php echo e(\DB::table('holdings_namads')
                        ->whereNamad_id($namad->id)
                        ->whereHolding_id($holding->id)
                        ->first()->amount_percent); ?> 
                        </span>
                    </td>
                    <td>
                        <?php if($namad->dailyReports()->latest()->first() && $namad->dailyReports()->latest()->first()->last_price_status == 'up' ): ?>
                            
                        <span class="btn btn-success"><?php echo e($namad->dailyReports()->latest()->first() ? $namad->dailyReports()->latest()->first()->last_price_percent : 0); ?> %</span>
                        <?php else: ?>
                        <span class="btn btn-danger"><?php echo e($namad->dailyReports()->latest()->first() ? $namad->dailyReports()->latest()->first()->last_price_percent : 0); ?> %</span>

                        <?php endif; ?>
                    </td>
                    <td>
                    <a data-id="<?php echo e($namad->id); ?>" data-holding="<?php echo e($holding->id); ?>"
                            class="delete text-white btn btn-rounded btn-danger btn-sm m-0"
                            
                            >حذف</a>
                            <a data-id="<?php echo e($namad->id); ?>" 
                                class="edit text-white btn btn-rounded btn-primary btn-sm m-0"
                                
                                >ویرایش</a>
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
                var holding = $(this).data('holding');
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
                url:'<?php echo e(url('/holding/namad/delete')); ?>',
                 data:{_token:'<?php echo e(csrf_token()); ?>',id:value,holding:holding},
        
                      
                 
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

        
$('#exampleModal').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('id') // Extract info from data-* attributes
  var modal = $(this)
  modal.find('#id').attr('value',recipient)
})

     






        })
</script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('css'); ?>
<style>
    .edit__profile__left {
        margin-top: 50px;
    }

    .fa__links {
        font-size: 20px;
        background: blue;
        color: white;
        padding: 5px;
        border-radius: 5px;
    }

    .fa-instagram {
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);

    }

    .fa-telegram {
        background: #285AEB;
    }

    .fa-whatsapp {
        background: #075e54;
    }

    .fa-linkedin {
        background: gray;
    }

    @media (min-width: 992px) {
        .modal-lg {
            width: 1000px !important;
        }
    }

    .badge {
        background-color: #74a1d0 !important;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\panel\resources\views/Portfoy/shownamads.blade.php ENDPATH**/ ?>