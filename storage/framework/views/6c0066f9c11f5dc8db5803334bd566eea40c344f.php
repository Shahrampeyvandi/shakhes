<?php $__env->startSection('content'); ?>


<div class="container-fluid panel-table mt-5">

    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
                <div class="wpb_wrapper py-3 d-flex justify-content-between">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                            class="title__divider__wrapper">مشاهده تیکت <span
                                class="line brk-base-bg-gradient-right"></span>
                        </span>
                    </h6>

                </div>

                <div>
                    <h4><?php echo e($ticket->subject); ?></h4>
                    <div>
                        <?php echo $ticket->content; ?>

                    </div>
                </div>
                <br>
                <br>
                <form id="" method="post" action="<?php echo e(route('Panel.AnswerTicket')); ?>">
                    <?php echo csrf_field(); ?>
                   <?php if(isset($ticket)): ?>
                       <input type="hidden" name="id" value="<?php echo e($ticket->id); ?>">
                   <?php endif; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                               <div class="form-group col-md-12">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                        name="suspended"
                                        <?php echo e(isset($ticket) && $ticket->status == 'suspended' ? 'checked' : ''); ?>

                                        id="customSwitch" >
                                        <label class="custom-control-label" for="customSwitch">حالت معلق</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="desc">پاسخ</label>
                                    <textarea class="form-control" name="text" id="text" cols="30" rows="8"><?php if(isset($ticket)): ?><?php echo $ticket->answer; ?><?php endif; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class=" btn btn-success text-white">ارسال <i class="fas fa-edit"></i>
                    </button>


                </form>
            </div>
        </div>

    </div>


</div>


</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.temp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Tickets/show.blade.php ENDPATH**/ ?>