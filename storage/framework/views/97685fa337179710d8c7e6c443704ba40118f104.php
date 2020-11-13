<div class="modal fade user-modal" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo e(isset($edit) && $edit ? 'ویرایش' : 'ثبت'); ?> کاربر</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="user--form" method="post" action="<?php echo e(route('User.Insert')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($edit) && $edit): ?>
        <?php echo method_field('PUT'); ?>
        <?php endif; ?>
        <input type="hidden" name="user_id" id="user_id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" style="display: flex;align-items: center;justify-content: center;">
              <div class="profile-img">
                <div class="chose-img">
                  <input type="file" class="btn-chose-img" id="user_profile" name="user_profile"
                    title="نوع فایل میتواند png , jpg  باشد">
                </div>
                <img
                  style="border-radius: 50%;object-fit: contain; background: #fff; max-width: 100%; height: 100%; width: 100%;"
                  src="<?php echo e(asset('assets/images/temp_logo.jpg')); ?>" alt="">
                <p class="text-chose-img" style="position: absolute;top: 44%;left: 14%;font-size: 13px;">انتخاب
                  پروفایل</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_name" class="col-form-label"><span class="text-danger">*</span> نام: </label>
              <input type="text" class="form-control" name="user_name" id="user_name">
            </div>
            <div class="form-group col-md-6">
              <label for="user_family" class="col-form-label"><span class="text-danger">*</span> نام خانوادگی:</label>
              <input type="text" class="form-control" name="user_family" id="user_family">
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_email" class="col-form-label">ایمیل:</label>
              <input type="text" class="form-control" name="user_email" id="user_email">
            </div>
            <div class="form-group col-md-6">
              <label for="user_mobile" class="col-form-label"><span class="text-danger">*</span> موبایل:</label>
              <input type="number" class="form-control" name="user_mobile" id="user_mobile">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="date" class="col-form-label"><span class="text-danger">*</span> اشتراک</label>
              <input type="text" id="date" name="date" autocomplete="off"
                class="form-control text-right date-picker-shamsi" />
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary btn--submit">ذخیره</button>
        </div>
      </form>
    </div>
  </div>
</div><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Includes/Panel/Modals/user.blade.php ENDPATH**/ ?>