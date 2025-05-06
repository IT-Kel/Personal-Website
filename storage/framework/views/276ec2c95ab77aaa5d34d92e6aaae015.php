<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/logo.png')); ?>" type="image/x-icon">
    <title>My Everyday</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">

     
    <!-- jQuery -->
    <script src="<?php echo e(asset('js/jquery-3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap.bundle.min.js')); ?>"></script>

    <link href="<?php echo e(asset('bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fontawesome/css/all.min.css')); ?>">
    <script src="<?php echo e(asset('js/jQuery.js')); ?>"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">My Everyday</a>
            <div class="d-flex">
                <a href="<?php echo e(route('logout')); ?>" class="nav-link text-light"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="<?php echo e(asset('static/js/script.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Laravel Personal_Website\resources\views/layouts/app.blade.php ENDPATH**/ ?>