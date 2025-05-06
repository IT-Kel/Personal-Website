<!DOCTYPE html>
<html lang="en">
<head>
    <?php if(Auth::check()): ?>
        <script>window.location.href = "<?php echo e(route('dashboard')); ?>";</script>
    <?php endif; ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/logo.png')); ?>" type="image/x-icon">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/fontawesome/css/all.min.css')); ?>">
    
    <!-- Bootstrap 5.2.3 CSS -->
    <link href="<?php echo e(asset('bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">

    <title>My Everyday</title>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="<?php echo e(asset('assets/logo.png')); ?>" alt="Avatar Logo" style="width:40px;" class="rounded-pill">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">My Everyday</a>
                    </li>
                </ul>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#authModal" title="Login">
                    <i class="fa-solid fa-user"></i>
                </button>
            </div>
        </div>
    </nav> 

    <!-- Main Content -->
    <div class="container mt-6 d-flex align-items-center position-relative" style="height: 80vh;">
        <div class="col-md-5 animate-on-load">
            <h2>Welcome to My Everyday</h2>
        <p>A private space where you can capture your thoughts, memories, and moments—just for you. Whether it's a daily reflection, a photo that sparks joy, or a note to your future self, My Everyday is your personal archive of life’s journey. Unlike social media, this is your private sanctuary—no likes, no comments, just your story.</p>
        <p><strong>Start documenting your everyday moments now.</strong></p>
        </div>
        <div class="floating-book">
            <img src="<?php echo e(asset('assets/book.png')); ?>" alt="Floating Book">
        </div>
    </div>

    <!-- Authentication Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Login</h5>
                    <button type="button" class="btn btn-light border-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-user"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="authTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Register</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="authTabsContent">
                        <!-- Login Form -->
                        <div class="tab-pane fade show active" id="login" role="tabpanel">
                            <form action="<?php echo e(route('login')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Log in</button>
                            </form>
                        </div>

                        <!-- Register Form -->
                        <div class="tab-pane fade" id="register" role="tabpanel">
                            <form action="<?php echo e(route('register')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="register-name" class="form-label">Fullname:</label>
                                    <input type="text" class="form-control" id="register-name" name="name" placeholder="Enter Name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="register-address" class="form-label">Address:</label>
                                    <input type="text" class="form-control" id="register-address" name="address" placeholder="Enter Address" required>
                                </div>
                                <div class="mb-3">
                                    <label for="register-email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="register-email" name="email" placeholder="Enter Email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="register-password" class="form-label">Password:</label>
                                    <input type="password" class="form-control" id="register-password" name="password" placeholder="Enter Password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm-password" class="form-label">Confirm Password:</label>
                                    <input type="password" class="form-control" id="confirm-password" name="password_confirmation" placeholder="Confirm Password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="profile-image" class="form-label">Profile Image:</label>
                                    <input type="file" class="form-control" id="profile-image" name="profile_image" accept="image/*">
                                </div>
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?php echo e(session('success')); ?>

                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if(session('error')): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?php echo e(session('error')); ?>

                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if($errors->any()): ?>
                                    <div class="alert alert-danger">
                                        <ul>
                                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($error); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <button type="submit" class="btn btn-success w-100">Register</button>
                            </form>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS & JQuery -->
    <script src="<?php echo e(asset('js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jQuery.js')); ?>"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
            let urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('showModal')) {
                document.getElementById('messageContent').innerText = decodeURIComponent(urlParams.get('message'));
                messageModal.show();
            }
        });

            window.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.floating-book').style.opacity = '1';
            document.querySelector('.floating-book').style.transform = 'translateY(0)';
        });
    </script>

    <!-- Modal for messages -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="messageContent"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Laravel Personal_Website\resources\views/index.blade.php ENDPATH**/ ?>