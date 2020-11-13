<?php $__env->startSection('content'); ?>
<?php echo $__env->make('Includes.Panel.Modal',['url'=>url('/user/delete')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('Includes.Panel.Modals.user',['edit'=>true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="container-fluid ">
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-center">لیست حجم معاملات مشکوک</h5>
                <hr />
            </div>
            <div style="overflow-x: auto;">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th> نماد</th>
                            <th> قیمت </th>
                            <th>حجم معاملات </th>
                            <th>حجم معاملات ماهانه</th>
                            <th>ضریب</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        <?php $__currentLoopData = $volumetrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$vol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td> <?php echo e($key+1); ?> </td>
                            <td><?php echo e($vol->namad->symbol); ?></td>
                            <td><?php echo e(\Illuminate\Support\Facades\Cache::get($vol->namad->id)['pl']); ?></td>
                            <td><?php echo e(\Illuminate\Support\Facades\Cache::get($vol->namad->id)['tradevol']); ?></td>
                            <td><?php echo e(\Illuminate\Support\Facades\Cache::get($vol->namad->id)['monthAVG']); ?></td>
                            <td><?php echo e($vol->volume_ratio); ?></td>
                           
                            <td>
                                <div class="btn-group" role="group" aria-label="">
                                    <a href="#" data-id="<?php echo e($vol->id); ?>" title="حذف" data-toggle="modal"
                                        data-target="#userModal" class="btn btn-sm btn-primary  ">
                                        <i class="fa fa-calendar-day"></i>
                                    </a>
                                    <a href="#" data-id="<?php echo e($vol->id); ?>" title="حذف" data-toggle="modal"
                                        data-target="#deleteModal" class="btn btn-sm btn-danger  ">
                                        <i class="fa fa-trash"></i>
                                    </a> </div>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function(){
    $('.user-modal').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var user_id = button.data("id"); // Extract info from data-* attributes
    $.ajax({
    type:'post',
    url:mainUrl + '/user/get-data',
    cache: false,
    async: true,
    data:{user_id:user_id,_token:token},
    success:function(data){
      // console.log(data)
      $('#user_mobile').val(data.phone)
      $('#user_id').val(data.id)
      $('#date').val(data.date)
      $('#first_name').val(data.fname)
      $('#last_name').val(data.lname)
    }
  })
})
 
})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/VolumeTrades/Index.blade.php ENDPATH**/ ?>