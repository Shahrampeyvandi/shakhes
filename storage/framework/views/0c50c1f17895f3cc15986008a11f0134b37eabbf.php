<?php $sis = session()->get('user');?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>شاخص - پنل</title>
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/RTL.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/bootstrap-select.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/FontAwesome/all.css">
    <link rel="stylesheet" href="<?php echo e(route("BaseUrl")); ?>/datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/dataTable/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/assets/css/style.css">
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo $__env->yieldContent('css'); ?>
</head>

<body>

    <div class="wrapper">

        <div id="sidebarCo" class="sidebar">
            <div class="content-sidebar">

                <div class="sidebar-user">
                    <img src="<?php echo e(route('BaseUrl')); ?>/assets/images/avatar.png"
                    class="img-fluid rounded-circle mb-2" alt="User Image">
                    <div class="font-weight-bold">کاربر</div>

                </div>

                <ul class="sidebar-nav">
                   
                    <li class="sidebar-item active">
                    <a class="sidebar-link pr-4" href="<?php echo e(route('BaseUrl')); ?>">
                            <i class="align-middle mr-2 fas fa-fw fa-home ml-1"></i> <span
                                class="align-middle">داشبورد</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                    <a class="sidebar-link pr-4" href="<?php echo e(route('Users')); ?>">
                           
                            <img width="20px" src="<?php echo e(asset('assets/images/user.png')); ?>" alt=""> <span
                            class="align-middle">کاربران</span>
                        </a>
                    </li>


                   


                   


                  
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="<?php echo e(route('MoneyReports')); ?>">
                           
                        <img width="20px" src="<?php echo e(asset('assets/images/mali.png')); ?>" alt="">
                            <span
                                class="align-middle">
                                صورت های مالی و گزارشات</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="<?php echo e(route('PortfoyList')); ?>">
                             <img width="20px" src="<?php echo e(asset('assets/images/portfoy.png')); ?>" alt=""> <span
                                class="align-middle">
                                پرتفوی روزانه شرکت ها</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="<?php echo e(route('CapitalIncrease')); ?>">
                             <img width="20px" src="<?php echo e(asset('assets/images/sarmaye.png')); ?>" alt=""> <span
                                class="align-middle">
                                افزایش سرمایه</span>
                        </a>
                    </li>
                     <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="<?php echo e(route('Disclosures')); ?>">
                             <img width="20px" src="<?php echo e(asset('assets/images/sarmaye.png')); ?>" alt=""> <span
                                class="align-middle">
                                افشای اطلاعات با اهمیت</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="<?php echo e(route('Clarifications')); ?>">
                             <img width="20px" src="<?php echo e(asset('assets/images/shafafsazi.png')); ?>" alt=""> <span
                                class="align-middle">
                                شفاف سازی</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="<?php echo e(route('ContinuingPaterns')); ?>">
                             <img width="20px" src="<?php echo e(asset('assets/images/mali.png')); ?>" alt=""> <span
                                class="align-middle">
                                الگوهای ادامه دهنده و بازگشتی</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="#">
                             <img width="20px" src="<?php echo e(asset('assets/images/mali.png')); ?>" alt=""> <span
                                class="align-middle">
                                سیگنال های اندیکاتوری</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="#">
                             <img width="20px" src="<?php echo e(asset('assets/images/mali.png')); ?>" alt=""> <span
                                class="align-middle">
                                واگرایی مثبت</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="#">
                             <img width="20px" src="<?php echo e(asset('assets/images/mali.png')); ?>" alt=""> <span
                                class="align-middle">
                                نقاط حمایت</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="#">
                             <img width="20px" src="<?php echo e(asset('assets/images/mali.png')); ?>" alt=""> <span
                                class="align-middle">
                                افزایش حجم معاملات</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="#">
                             <img width="20px" src="<?php echo e(asset('assets/images/setting.png')); ?>" alt=""> <span
                                class="align-middle">
                                تنظیمات</span>
                        </a>
                    </li>
                   
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="#">
                             <img width="20px" src="<?php echo e(asset('assets/images/user.png')); ?>" alt=""> <span
                                class="align-middle">ویرایش
                                پروفایل</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link pr-4" href="#">
                             <img width="20px" src="<?php echo e(asset('assets/images/out.png')); ?>" alt=""> <span
                                class="align-middle">
                                خروج</span>
                        </a>
                    </li>
                </ul>


            </div>

        </div>

        <div class="main">
            <div class="head-main">
                <a href="#" class="btn-sidebar close-sidebar">
                    <i class="fa fa-times"></i>
                </a>
                <a href="#" class="btn-sidebar hamburger">
                    <i class="fa fa-bars"></i>
                </a>
                <a class="head-sidebar">
                    شاخص
                </a>
                <ul class="navbar-nav nav-custom mr-auto">
                
                    <li class="nav-item dropdown mx-3 ml-lg-2 ">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="userDropdown"
                            data-toggle="dropdown" aria-expanded="true">
                            <i class="align-middle fas fa-cog"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right " aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#"><i
                                    class="align-middle mr-1 fas fa-fw fa-user ml-2"></i>ویرایش پروفایل</a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"><i
                                    class="align-middle mr-1 fas fa-fw fa-arrow-alt-circle-right ml-2"></i>خروج از
                                حساب</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="content-main mt-5">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

        </div>
    </div>
    </div>

    <script src="<?php echo e(route('BaseUrl')); ?>/vendor/jquery/jquery-3.3.1.slim.min.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/vendor/jquery/jquery-3.4.1.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/vendor/jquery/popper.min.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/datepicker/bootstrap-datepicker.fa.min.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/bootstrap-select.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/vendor/dataTable/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/vendor/dataTable/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/vendor/dataTable/dataTables.responsive.min.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/vendor/dataTable/datatable.js"></script>
    <script src="<?php echo e(route('BaseUrl')); ?>/assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            $(".datepicker-fa").datepicker({
            changeMonth: true,
            changeYear: true
            });
        });
    </script>
    <?php echo $__env->yieldContent('js'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\panel\resources\views/layout/temp.blade.php ENDPATH**/ ?>