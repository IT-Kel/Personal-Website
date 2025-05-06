 <!-- Use the appropriate layout -->


<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <!-- Sidebar Section -->
        <div class="col-md-3">
            <div class="sidebar">
                <a href="<?php echo e(route('dashboard')); ?>">Home</a>
                <a href="<?php echo e(route('explore')); ?>">Explore</a>
                <a href="<?php echo e(route('feed')); ?>">Feed</a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms</a>
                <a href="<?php echo e(route('settings')); ?>" class="active">Settings</a>
                <a href="<?php echo e(route('profile.edit')); ?>" class="profile-btn">View Profile</a>
            </div>
        </div>

        
        <!-- Settings Section -->
        <div class="col-md-9">
            
            <div class="settings-section">
                <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

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
                <h2>Account Settings</h2>

                <!-- Change Password Form -->
                <div class="change-password">
                    <h3>Change Password</h3>
                    <form action="<?php echo e(route('settings.updatePassword')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>

               <!-- Delete Account Form -->
                <div class="delete-account mt-5">
                    <h3>Delete Account</h3>
                    <p class="text-danger">This action is permanent and cannot be undone. Once you delete your account, all your data will be erased.</p>

                    <!-- Confirmation Prompt -->
                    <div class="confirmation-prompt">
                        <label for="confirm-delete" class="form-check-label">
                            <input type="checkbox" id="confirm-delete" class="form-check-input">
                            I am sure I want to delete my account.
                        </label>
                    </div>

                    <!-- Delete Form -->
                    <form action="<?php echo e(route('settings.deleteAccount')); ?>" method="POST" id="delete-account-form" class="mt-3">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger" id="delete-button" disabled>Delete Account</button>
                    </form>
                </div>
<!-- JavaScript for Confirmation -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const confirmDeleteCheckbox = document.getElementById('confirm-delete');
    const deleteButton = document.getElementById('delete-button');

    // Enable button when checkbox is checked
    confirmDeleteCheckbox.addEventListener('change', function () {
        if (confirmDeleteCheckbox.checked) {
            deleteButton.removeAttribute('disabled'); // Enable the button
        } else {
            deleteButton.setAttribute('disabled', 'true'); // Disable the button
        }
    });
});

</script>
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



<?php echo $__env->make('layouts.profile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel Personal_Website\resources\views/settings.blade.php ENDPATH**/ ?>