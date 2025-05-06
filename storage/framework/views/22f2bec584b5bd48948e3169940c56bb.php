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
                <a href="#">Terms</a>
                <a href="<?php echo e(route('settings')); ?>" class="active">Settings</a>
                <a href="<?php echo e(route('profile.edit')); ?>" class="profile-btn">View Profile</a>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="col-md-9">
            <div class="profile-section">
                <h2><?php echo e($user->name); ?>'s Profile</h2>

                <!-- Conditionally Display Profile or Edit Form -->
                <?php if(auth()->user()->id == $user->id): ?>
                    <!-- Display Profile Info or Editable Form -->
                    <div class="profile-details">
                        <div class="profile-img-container">
                            <img src="<?php echo e(asset('storage/' . ($user->profile_image ?? 'assets/default-profile.jpg'))); ?>" alt="Profile Image" class="profile-img">
                        </div>
                        <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                        <p><strong>Bio:</strong> <?php echo e($user->bio ?? 'No bio available.'); ?></p>

                        <!-- Edit Form for Logged-in User -->
                        <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="address">Address (optional)</label>
                                <textarea name="address" id="address" class="form-control" rows="3"><?php echo e(old('address', $user->address)); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="bio">Bio (optional)</label>
                                <textarea name="bio" id="bio" class="form-control" rows="3"><?php echo e(old('bio', $user->bio)); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="profile_image">Profile Image</label>
                                <input type="file" name="profile_image" id="profile_image" class="form-control-file">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Message if Viewing Another User's Profile -->
                    <p>You are viewing another user's profile.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.profile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel Personal_Website\resources\views/profile/show.blade.php ENDPATH**/ ?>