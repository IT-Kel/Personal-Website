<!-- resources/views/layouts/profile.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/logo.png')); ?>" type="image/x-icon">
    <title>My Everyday - Profile</title>

    <!-- Include the profile-specific CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/profile.css')); ?>">

    <!-- Include Bootstrap CSS -->
    <link href="<?php echo e(asset('bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">

    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/fontawesome/css/all.min.css')); ?>">
    
     <!-- jQuery -->
     <script src="<?php echo e(asset('js/jquery-3.7.1.min.js')); ?>"></script>
     <script src="<?php echo e(asset('js/bootstrap.bundle.min.js')); ?>"></script>

    <!-- Include any additional JS libraries (if required) -->
    <script src="<?php echo e(asset('js/jQuery.js')); ?>"></script>
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body class="profile-page">
    <!-- Navbar specific to profile page -->
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
        <?php echo $__env->yieldContent('content'); ?> <!-- Profile page content will be inserted here -->
    </div>

    <script src="<?php echo e(asset('static/js/script.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Laravel Personal_Website\resources\views/layouts/profile.blade.php ENDPATH**/ ?>