<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>شاخص - ورود</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/css/mdb.min.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/assets/css/style.css">
</head>
<body>
    <div class=" pt-5" style=" display: flex; justify-content: center; align-items: center;">
        <div class="login-wrap">
            <div class="head-login">
            <img src="<?php echo e(asset('assets/images/logo-shakhes.jpg')); ?>" style="width: 100px" alt="shakhes-logo">
            </div>
            <div class="login-html">
                <div class="login-form">
                    <form action="<?php echo e(route('login')); ?>" method="post">
                        <?php echo e(csrf_field()); ?>

                        <div class="sign-up-htm">
                            <div class="group">
                                <label for="address_email" class="label my-3">موبایل</label>
                                <input id="address_email" type="number" name="mobile" value="<?php echo e(old('mobile')); ?>"
                                    class="input mb-3">
                            </div>
                            <div class="group">
                                <label for="address_email" class="label my-3">رمز عبور</label>
                                <input id="address_email" type="password" name="password" class="input mb-3">
                            </div>
                            <div class="group mt-5">
                                <input type="submit" class="button p-2" value="تایید">
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html><?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/login.blade.php ENDPATH**/ ?>