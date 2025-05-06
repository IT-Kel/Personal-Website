

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Edit Post</h2>

    <form action="<?php echo e(route('posts.update', $post->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
    
        <div class="form-group">
            <label for="content">Post Content:</label>
            <textarea name="content" id="content" class="form-control" rows="4"><?php echo e(old('content', $post->content)); ?></textarea>
        </div>
    
        <div class="form-group">
            <label for="media">Post Media (Optional):</label>
            <input type="file" name="media" id="media" class="form-control">
        </div>
    
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
    
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.feed_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel Personal_Website\resources\views/posts/edit.blade.php ENDPATH**/ ?>