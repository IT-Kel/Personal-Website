 <!-- or your base layout -->

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3">
            <div class="sidebar">
                <a href="<?php echo e(route('dashboard')); ?>" class="active">Home</a>
                <a href="<?php echo e(route('explore')); ?>">Explore</a>
                <a href="<?php echo e(route('feed')); ?>">Feed</a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms</a>
                <a href="<?php echo e(route('settings')); ?>">Settings</a>
                <a href="<?php echo e(route('profile.edit')); ?>" class="profile-btn">View Profile</a>
            </div>
        </div>

        

        <!-- MAIN CONTENT -->
        <div class="col-md-9">
            <div class="explore-content">
                <h2>Explore</h2>

                <div class="row">
                    <!-- All Users -->
                    <div class="col-md-12">
                        <h4>All Users</h4>
                        <?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $userProfileImage = $user->profile_image
                                ? asset('storage/' . $user->profile_image)
                                : asset('assets/default-profile.jpg');
                        ?>
                        <div class="card mb-2 p-2" id="user-<?php echo e($user->id); ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <!-- Link to user's feed (stalk) -->
                                    <a href="<?php echo e(route('user.feed', ['user' => $user->id])); ?>" class="d-flex align-items-center">
                                        <!-- Display user's profile picture -->
                                        <img src="<?php echo e($userProfileImage); ?>" alt="Profile Picture" class="img-fluid rounded-circle" width="40" height="40">
                                        <strong class="ml-2"><?php echo e($user->name); ?></strong>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- Modal for Terms and Conditions -->
 <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Introduction</h5>
                <p>These are the terms and conditions for using our website. By accessing or using our services, you agree to abide by these terms. Please read them carefully.</p>

                <h5>Use of the Website</h5>
                <p>Users are responsible for their own actions on this website. Misuse of the website, including posting inappropriate content or violating others' rights, is prohibited.</p>

                <h5>Privacy</h5>
                <p>We respect your privacy. Our privacy policy explains how we collect, use, and store your information.</p>

                <h5>Changes to Terms</h5>
                <p>We reserve the right to update these terms at any time. Any changes will be posted on this page.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.profile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel Personal_Website\resources\views/explore.blade.php ENDPATH**/ ?>