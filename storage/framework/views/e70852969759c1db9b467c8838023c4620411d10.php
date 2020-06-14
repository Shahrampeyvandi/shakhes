<?php $__env->startSection('content'); ?>
<style>
    td input {
        width: 100px !important;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
        <h4> صورت های مالی و گزارشات</h4>
    </div>
    <div class="col-md-12 ">

        <h6 class="mt-4">انتخاب سهم: </h6>

        <select class="form-control text-right selectpicker" name="namad" required data-size="5" data-live-search="true"
            data-title="نام سهم" id="namad" data-width="100%">
            <?php $__currentLoopData = \App\Models\Namad\Namad::OrderBy('symbol','ASC')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($item->id); ?>"><?php echo e($item->symbol); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <div class="change-month">
            <h6 class="mt-4">شروع مالی سهم: </h6>
            <div class="row mt-3 ">
                <div class="form-group col-md-6">

                    <select id="begin_year" name="begin_year" class="form-control" id="recipient-name">

                        <option value="<?php echo e($last_year); ?>" selected><?php echo e($last_year); ?></option>
                        <option value="<?php echo e($new_year); ?>"><?php echo e($new_year); ?></option>




                    </select>
                </div>
                <div class="form-group col-md-6">

                    <select id="begin_month" name="begin_month" class="form-control" id="recipient-name">

                        <option selected value="1">فروردین</option>
                        <option value="2">اردیبهشت</option>
                        <option value="3">خرداد</option>
                        <option value="4">تیر</option>
                        <option value="5">مرداد</option>
                        <option value="6">شهریور</option>
                        <option value="7">مهر</option>
                        <option value="8">آبان</option>
                        <option value="9">آذر</option>
                        <option value="10">دی</option>
                        <option value="11">بهمن</option>
                        <option value="12">اسفند</option>



                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-3 show-details" style="display: none">
            <a href="" class="ml-3 monthly">مشاهده نمودار ماهانه</a>
            <a href="" class="ml-3 seasonal">مشاهده نمودار سه ماهه</a>

            <a href="" class="ml-3 yearly">مشاهده نمودار سالیانه</a>

            <a href="" class="ml-3 delete_reports">حذف کل اطلاعات</a>
        </div>

        <h6 class="mt-4">نوع اطلاعات</h6>


        <div class="d-block">

            <div class="row m-4">

                <div class="col-md-4 mb-3">
                    <div class="custom-control   custom-radio">
                        <input id="govahi_nano_meghyas_yes" name="type" type="radio" value="ماهانه"
                            class="custom-control-input" required checked>
                        <label class="custom-control-label" for="govahi_nano_meghyas_yes">ماهانه</label>
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <div class="custom-control custom-radio">
                        <input id="govahi_nano_meghyas_azmayeshgah" name="type" type="radio" value="سه ماهه"
                            class="custom-control-input" required>
                        <label class="custom-control-label" for="govahi_nano_meghyas_azmayeshgah">سه ماهه</label>
                    </div>
                </div>


                <div class="col-md-4 mb-3">
                    <div class="custom-control   custom-radio">
                        <input id="govahi_nano_meghyas_sanat" name="type" type="radio" value="سالیانه"
                            class="custom-control-input" required>
                        <label class="custom-control-label" for="govahi_nano_meghyas_sanat">سالیانه</label>
                    </div>
                </div>


            </div>

        </div>



        <div id="ajax-table">
            <form id="monthly-table" class="needs-validation" action="<?php echo e(route('MonyReport.Monthly')); ?>" method="post"
                enctype="multipart/form-data">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="type" id="type" value="ماهانه">
                <input type="hidden" name="n" id="n" value="">
                <input type="hidden" name="begin_month" id="" value="">
                <input type="hidden" name="begin_year" id="" value="">
                <div class="d-block">

                    <div class="row my-4">
                        <div class="table-responsive">
                            <table class="table table-bordered  ">
                                <thead>
                                    <tr>
                                        <th>سال</th>
                                        <th scope="col">فروردین</th>
                                        <th scope="col">اردیبهشت</th>
                                        <th scope="col">خرداد</th>
                                        <th scope="col">تیر</th>
                                        <th scope="col">مرداد</th>
                                        <th scope="col">شهریور</th>
                                        <th scope="col">مهر</th>
                                        <th scope="col">آبان</th>
                                        <th scope="col">آذر</th>
                                        <th scope="col">دی</th>
                                        <th scope="col">بهمن</th>
                                        <th scope="col">اسفند</th>


                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo e($new_year); ?></td>
                                        <td>
                                            <input type="text" name="1[<?php echo e($new_year); ?>]">
                                        </td>
                                        <td><input type="text" name="2[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="3[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="4[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="5[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="6[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="7[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="8[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="9[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="10[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="11[<?php echo e($new_year); ?>]"></td>
                                        <td><input type="text" name="12[<?php echo e($new_year); ?>]"></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo e($last_year); ?></td>
                                        <td>
                                            <input type="text" name="1[<?php echo e($last_year); ?>]">
                                        </td>
                                        <td><input type="text" name="2[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="3[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="4[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="5[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="6[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="7[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="8[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="9[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="10[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="11[<?php echo e($last_year); ?>]"></td>
                                        <td><input type="text" name="12[<?php echo e($last_year); ?>]"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <hr>
                <div class="container text-center">
                    <button class="btn btn-primary " type="submit">ثبت اطلاعات</button>
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

        $('#namad').change(function(){
            // check if namad has in database
            var value = $(this).val()
            var data = $('#begin_month').val()
            var type = $('input[name=type]:checked').val()
            var year = $('#begin_year').val()
            var monthly_url = "<?php echo e(route("BaseUrl")); ?>/moneyreports/showchart/"+value
            var yearly_url = "<?php echo e(route("BaseUrl")); ?>/yearlyreports/showchart/"+value
            var seasonal_url = "<?php echo e(route("BaseUrl")); ?>/seasonalreports/showchart/"+value
            var delete_url = "<?php echo e(route("BaseUrl")); ?>/namadreports/delete/"+value
          $.ajax({
            type:'post',
            url:'<?php echo e(route("getmoneyreportsdata")); ?>',
            data:{month:data,year:year,type:type,sahm:value},
            success:function(data){ 

                if(data['status'] == 'exist'){
                    $('.change-month').hide()
                    $('.show-details').show()
                    $('.show-details').children('.monthly').attr('href',monthly_url)
                    $('.show-details').children('.seasonal').attr('href',seasonal_url)
                    $('.show-details').children('.yearly').attr('href',yearly_url)
                    $('.show-details').children('.delete_reports').attr('href',delete_url)
                    
                }

                if(data['status'] == 'not exist'){
                    $('.change-month').show()
                    $('.show-details').hide()
                }

            $('#ajax-table').html(data['table'])
            }
        })








           
            $('#n').val(value)
        })

$('#begin_month').change(function(e){
  e.preventDefault();
 var data = $(this).val()
 var type = $('input[name=type]:checked').val()
 var year = $('#begin_year').val()
 var sahm = $('#sahm').val()

 $.ajax({

type:'post',
url:'<?php echo e(route("getmoneyreportsdata")); ?>',
data:{month:data,year:year,type:type,sahm:sahm},
success:function(data){ 
   $('#ajax-table').html(data['table'])
   }
 })
})


        $('input[name=type]').change(function(){
            // if($('#begin_month').val() == '' || $('#begin_year').val() == '')
            // {
            //     alert('لطفا ماه و سال مالی را وارد کنید')
            //     return false;
            // }
           
           
 var type = $(this).val()
 var data = $('#begin_month').val()
 var year = $('#begin_year').val()
 var sahm = $('#namad').val()

 $.ajax({

type:'post',
url:'<?php echo e(route("getmoneyreportsdata")); ?>',
data:{month:data,year:year,type:type,sahm:sahm},
success:function(data){ 
   $('#ajax-table').html(data['table'])
   }
 })
           
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
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\panel\resources\views/MoneyReports/Index.blade.php ENDPATH**/ ?>