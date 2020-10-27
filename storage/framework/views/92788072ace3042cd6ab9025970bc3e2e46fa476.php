<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>شاخص - <?php echo e($education->title); ?></title>
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/bootstrap/RTL.css">
    <link rel="stylesheet" href="<?php echo e(route('BaseUrl')); ?>/vendor/FontAwesome/all.css">
</head>

<style>
    .desciption img{
        max-width: 100% !important;
        object-fit: cover;
    }
</style>

<body>
    <div class="container-fluid panel-table mt-5">
    <div class="card">
        <div class="card-body">
           <?php if($education->image): ?>
            <div>
                <img width="100%" src="<?php echo e(asset("pictures/$education->image")); ?>" alt="">
            </div>
           <?php endif; ?>
            <div class="desciption">
            <h3 class="<?php echo e($education->image ? 'mt-5' : 'mt-1'); ?> mb-3"><?php echo e($education->title); ?></h3>
                <?php echo $education->description; ?>

            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\xampp1\htdocs\shakhes\resources\views/Education/show.blade.php ENDPATH**/ ?>