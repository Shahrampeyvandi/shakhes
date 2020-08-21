<?php $__env->startSection('content'); ?>
<style>
    td input {
        width: 50px !important;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
        <h4> افزایش سرمایه</h4>
    </div>
    <div class="col-md-12 ">
        <form action="<?php echo e(route('CapitalIncrease')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <h6 class="mt-4">انتخاب سهم: </h6>
            <select class="form-control text-right selectpicker" name="namad" required data-size="5"
                data-live-search="true" data-title="نام سهم" id="namad" data-width="100%">
                <?php $__currentLoopData = \App\Models\Namad\Namad::OrderBy('symbol','ASC')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($item->id); ?>"><?php echo e($item->symbol); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <h6 class="mt-4">سرمایه فعلی:  </h6>
            <div class="form-group">
                <input type="number" class="form-control" name="feli" id="feli"  />
            </div>
            <div class="row m-4 ajax-table">

            </div>

            <h6 class="mt-4">نوع افزایش سرمایه</h6>
            <div class="d-block">
                <div class="row m-4">
                    <div class="col-md-3 mb-3">
                        <div class="custom-control   custom-radio">
                            <input id="govahi_nano_meghyas_yes" name="type" type="radio" value="assets"
                                class="custom-control-input" required checked>
                            <label class="custom-control-label" for="govahi_nano_meghyas_yes">تجدید ارزیابی دارایی
                                ها</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="custom-control custom-radio">
                            <input id="govahi_nano_meghyas_azmayeshgah" name="type" type="radio" value="stored_gain"
                                class="custom-control-input" required>
                            <label class="custom-control-label" for="govahi_nano_meghyas_azmayeshgah">سود
                                انباشته</label>
                        </div>
                    </div>


                    <div class="col-md-3 mb-3">
                        <div class="custom-control   custom-radio">
                            <input id="govahi_nano_meghyas_sanat" name="type" type="radio" value="cash"
                                class="custom-control-input" required>
                            <label class="custom-control-label" for="govahi_nano_meghyas_sanat">آورده نقدی سهام
                                داران</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="custom-control   custom-radio">
                            <input id="increase" name="type" type="radio" value="compound" class="custom-control-input"
                                required>
                            <label class="custom-control-label" for="increase">افزایش سرمایه ترکیبی</label>
                        </div>
                    </div>


                </div>
                <div class="row percent-wrapper">
                    <div class="form-group col-md-6">

                        <input type="number" class="form-control" name="percent" id="percent"
                            placeholder="میزان افزایش سرمایه برحسب ریال" />
                    </div>
                </div>
                <div class="types-wrapper" style="display: none">
                    <div class="row wrapper-content">
                        <div class="form-group col-md-6">

                            <select name="typearray[]" class="form-control" id="exampleFormControlSelect2">
                                <option value="">باز کردن فهرست انتخاب</option>
                                <option value="assets">تجدید ارزیابی دارایی ها</option>
                                <option value="stored_gain">سود انباشته</option>
                                <option value="cash">آورده نقدی سهام داران</option>


                            </select>
                        </div>

                        <div class="form-group col-md-4">

                            <input type="number" class="form-control" name="percentarray[]" id=""
                                placeholder="مبلغ افزایش سرمایه" />
                        </div>

                    </div>


                    <div class="clone ">

                    </div>
                    <div class="clone-bottom mb-5">

                        <a href="#" class="">
                            مورد جدید
                            <i class="fa fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
                <div class="row ">
                    <div class="form-group col-md-6">
                        <select name="step" class="form-control" id="exampleFormControlSelect2">
                            <option value="پیشنهاد هیئت مدیره به مجمع عمومی فوق العاده">پیشنهاد هیئت مدیره به مجمع عمومی
                                فوق العاده</option>
                            <option value="اظهار نظر حسابرس و بازرس قانونی">اظهار نظر حسابرس و بازرس قانونی</option>
                            <option value="مدارک و مستندات درخواست افزایش سرمایه">مدارک و مستندات درخواست افزایش سرمایه
                            </option>
                            <option value="تمدید مهلت استفاده از مجوز افزایش سرمایه">تمدید مهلت استفاده از مجوز افزایش
                                سرمایه</option>
                            <option value="اصلاحیه">اصلاحیه</option>
                            <option value="دعوت به مجمع فوق العاده">دعوت به مجمع فوق العاده</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">

                                <span class="input-group-text">تاریخ ثبت: </span>

                            </div>
                            <input type="text" class="form-control datepicker-fa" placeholder="" id="date_mind_1"
                                aria-label="Small" name="date" aria-describedby="inputGroup-sizing-sm">

                        </div>
                    </div>

                </div>
                <div class="row ">

                    <div class="form-group col-md-12">
                        <label for="linkcodal" class="col-form-label">لینک به کدال: </label>
                        <input type="text" class="form-control" name="linkcodal" id="linkcodal"
                            placeholder="https://codal.ir">
                    </div>
                </div>
                <div class="row">
                    <div class="container text-center">
                        <button class="btn btn-primary " type="submit">ثبت اطلاعات</button>
                    </div>
                </div>

            </div>




        </form>

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
        $(document).on('click','.delete',function(e){
       
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
                url:'<?php echo e(route("CapitalIncrease.Delete")); ?>',
                 data:{_token:'<?php echo e(csrf_token()); ?>',id:value,},      
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
$('#namad').change(function(){
// check if namad has in database
var value = $(this).val()
$.ajax({
type:'post',
url:'<?php echo e(route("getCapitalIncreases")); ?>',
data:{sahm:value},
success:function(data){ 
if(data['count'] > 0 ){
    $('.ajax-table').html(data['list'])
}
}
})


})

        $(document).on('click','.clone-bottom',function(e){
  e.preventDefault()


  var originalDiv = $('.wrapper-content').first();
  
  var originalSelect = originalDiv.find('.selectpicker');
//   originalSelect.selectpicker('destroy').addClass('tmpSelect');

  let cloned = originalDiv.clone()
  let setval =100 - cloned.find('input[type=number]').val()
  
cloned.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
  cloned.prepend(`<div class="col-md-12"><a class="remove-link float-left" href="#" >
                                    <i class="fas fa-trash text-danger"></i>
                                </a></div>`)
                               
  $(this).prev('.clone').append(cloned)
 
 })

 
 $(document).on('click','.remove-link',function(e){
    e.preventDefault()
    $(this).parents('.wrapper-content').remove()
  
 })

     


$('input[name=type]').change(function(){
    var type = $(this).val()
    if(type == 'compound'){
        $('.percent-wrapper').find('#percent').val('')  
        $('.percent-wrapper').hide()
        $('.types-wrapper').show()
    }else{
        $('.clone').html('')  
        $("input[name='percentarray[]']").val('')
        $('.percent-wrapper').show()
        $('.types-wrapper').hide()
    }


           
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
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\panel\resources\views/CapitalIncrease/Index.blade.php ENDPATH**/ ?>