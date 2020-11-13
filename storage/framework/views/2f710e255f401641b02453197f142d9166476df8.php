<?php $__env->startSection('content'); ?>

<div class="container-fluid ">
  <div class="card">
    <div class="card-body">
      <div class="card-title">
        <h5 class="text-center">آموزش ها</h5>
        <hr />
      </div>
      <div style="overflow-x: auto;">
        <table id="example1" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ردیف</th>
              <th>
                نام
              </th>
              <th>
                دسته بندی
              </th>
              <th>
                تعداد بازدید
              </th>
              <th>عملیات</th>
            </tr>
          </thead>
          <tbody class="tbody">

            <?php $__currentLoopData = $educations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$education): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td> <?php echo e($key+1); ?> </td>
              <td><?php echo e($education->title); ?></td>
              <td><?php echo e($education->category->name); ?></td>
              <td><?php echo e($education->views); ?></td>
              <td>

                <a href="<?php echo e(route('Education.Show',$education->id)); ?>" class=" btn  btn-primary btn-sm m-0"><i
                    class="fa fa-eye"></i></a>
                <a href="<?php echo e(route('Education.Add')); ?>?edit=<?php echo e($education->id); ?>" class=" btn  btn-info btn-sm m-0"><i
                    class="fa fa-edit"></i></a>
                <a data-id="<?php echo e($education->id); ?>" class="delete text-white btn  btn-danger btn-sm m-0"><i
                    class="fa fa-trash"></i></a>

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
              url:'<?php echo e(url('/education/delete')); ?>',
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
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Education/List.blade.php ENDPATH**/ ?>