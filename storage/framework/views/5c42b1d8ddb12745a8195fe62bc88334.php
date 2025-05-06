 <!-- Assuming you have a layout file -->

<?php $__env->startSection('content'); ?>
<div class="container-fluid main-container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="<?php echo e(route('dashboard')); ?>">Home</a>
        <a href="<?php echo e(route('explore')); ?>">Explore</a>
        <a href="<?php echo e(route('feed')); ?>" class="active">Feed</a>
        <a href="#">Terms</a>
        <a href="<?php echo e(route('settings')); ?>">Settings</a>
        <a href="<?php echo e(route('profile.show', auth()->id())); ?>" class="profile-btn">View Profile</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="profile-section">
            <h2><?php echo e($user->name); ?>'s Profile</h2>
            <div class="profile-details">
                <?php
                $profileImage = $user->profile_image
                    ? asset('storage/' . $user->profile_image)
                    : asset('assets/default-profile.jpg');
                ?>
                <img src="<?php echo e($profileImage); ?>" alt="<?php echo e(e($user->name)); ?>'s profile image" class="profile-img">
                <h3><?php echo e($user->name); ?></h3>
                <p><?php echo e($user->bio); ?></p>
            </div>
        </div>
        
        <div class="feed-section">
            <div class="posts">
                <h2><?php echo e($user->name); ?>'s Feed</h2>
        
                <?php if($posts->isEmpty()): ?>
                    <p><?php echo e($user->name); ?> has no posts yet.</p>
                <?php else: ?>
                    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $postUser = $post->user;
                            $postProfileImage = $postUser && $postUser->profile_image
                                ? asset('storage/' . $postUser->profile_image)
                                : asset('assets/default-profile.jpg');
                        ?>
        
                        <div class="post">
                            <div class="post-header">
                                <div class="user-info">
                                    <img src="<?php echo e($postProfileImage); ?>" alt="<?php echo e(e($postUser->name)); ?>'s profile image" class="profile-img" style="width:40px; height:40px; border-radius:50%;">
                                    <h4><?php echo e($postUser->name); ?></h4>
                                </div>
                                <!-- Post Options Menu (delete button, etc.) -->
                                <?php if($postUser->id == auth()->id()): ?>
                                    <div class="dropdown">
                                        <button class="dropbtn">⋮</button>
                                        <div class="dropdown-content">
                                            <form action="<?php echo e(route('posts.destroy', $post->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="delete-btn">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <p><?php echo e($post->content); ?></p>

                            <?php if($post->media): ?>
                                <?php
                                    $extension = pathinfo($post->media, PATHINFO_EXTENSION);
                                ?>
                                <?php if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                    <img src="<?php echo e(asset('storage/uploads/' . basename($post->media))); ?>" alt="Post Media" class="post-media" onclick="openImageModal(this.src)">
                                <?php elseif(in_array(strtolower($extension), ['mp4', 'webm', 'ogg', 'mkv'])): ?>
                                    <video controls class="post-media" aria-label="Post video media">
                                        <source src="<?php echo e(asset('storage/' . $post->media)); ?>" type="video/<?php echo e($extension); ?>">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php endif; ?>
                            <?php endif; ?>

                            <div id="imageModal" class="image-modal">
                                <span class="close-modal" onclick="closeImageModal()">✖</span>
                                <img id="modalImage" src="" alt="Full Image">
                            </div>

                            <div class="post-footer">
                                <div class="like-container">
                                    <button class="like-btn" id="like-button-<?php echo e($post->id); ?>" data-post-id="<?php echo e($post->id); ?>">
                                        <i class="fa-regular fa-heart"></i>
                                        <span class="like-count" id="like-count-<?php echo e($post->id); ?>"><?php echo e($post->usersWhoLiked()->count()); ?></span>
                                    </button>
                                </div>
                                <small><?php echo e($post->created_at->format('F j, Y, g:i a')); ?></small>
                            </div>

                            <div class="comments">
                                <button class="toggle-comments-btn">Show Comments</button> (<?php echo e($post->comments->count()); ?>)    

                                <form method="POST" action="<?php echo e(route('comments.store', ['postId' => $post->id])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <textarea name="content" placeholder="Write a comment..." required></textarea>
                                    <button type="submit">Post Comment</button>
                                </form>

                                <div class="comment-list" style="display: none;">
                                    <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="comment">
                                            <img src="<?php echo e(asset('storage/' . ($comment->user->profile_image ?? 'assets/default-profile.jpg'))); ?>" class="comment-profile-img">
                                            <div class="comment-details">
                                                <strong><?php echo e($comment->user->name); ?></strong>
                                                <p><?php echo e($comment->content); ?></p>
                                                <small><?php echo e($comment->created_at->diffForHumans()); ?></small>

                                                <form method="POST" action="<?php echo e(route('comments.reply', ['commentId' => $comment->id])); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="parent_id" value="<?php echo e($comment->id); ?>">
                                                    <textarea name="content" placeholder="Write a reply..." required></textarea>
                                                    <button type="submit">Reply</button>
                                                </form>

                                                <div class="reply-list">
                                                    <?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="comment reply">
                                                            <img src="<?php echo e(asset('storage/' . ($reply->user->profile_image ?? 'assets/default-profile.jpg'))); ?>" class="comment-profile-img">
                                                            <div class="comment-details">
                                                                <strong><?php echo e($reply->user->name); ?></strong>
                                                                <p><?php echo e($reply->content); ?></p>
                                                                <small><?php echo e($reply->created_at->diffForHumans()); ?></small>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Like Button functionality
        document.querySelectorAll('.like-btn').forEach(button => {
            button.addEventListener('click', function() {
                const postId = button.getAttribute('data-post-id');
                const likeCountElement = document.getElementById(`like-count-${postId}`);
                const currentLikeCount = parseInt(likeCountElement.innerText);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/post/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ post_id: postId })
                })
                    .then(response => response.json())
                    .then(data => {
                        likeCountElement.innerText = data.likes;

                        // Toggle the heart color based on whether the user liked the post
                        const icon = button.querySelector('i');
                        if (data.likedByUser) {
                            icon.classList.remove('fa-regular');
                            icon.classList.add('fa-solid');
                        } else {
                            icon.classList.remove('fa-solid');
                            icon.classList.add('fa-regular');
                        }
                    })
                    .catch(error => console.log('Error:', error));
            });
        });

        // Show/Hide Comments functionality
        const toggleCommentButtons = document.querySelectorAll('.toggle-comments-btn');
        toggleCommentButtons.forEach(button => {
            button.addEventListener('click', function() {
                const commentList = this.nextElementSibling.nextElementSibling; // Select the comment list
                if (commentList.style.display === 'none' || commentList.style.display === '') {
                    commentList.style.display = 'block';
                    this.textContent = 'Hide Comments';
                } else {
                    commentList.style.display = 'none';
                    this.textContent = 'Show Comments';
                }
            });
        });
    });

        // UNFOLLOW FUNCTIONALITY
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.unfollow-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('/unfollow', { // ✅ Updated URL (No ID in route)
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ user_id: userId }) // ✅ Send as JSON
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message === 'Unfollowed successfully!') {
                            document.getElementById(`followed-user-${userId}`).remove(); // ✅ Removes user from UI
                        } else {
                            console.error('Failed to unfollow:', data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });

    // Open Image Modal
    function openImageModal(src) {
        document.getElementById("modalImage").src = src;
        document.getElementById("imageModal").style.display = "flex";
    }

    // Close Image Modal
    function closeImageModal() {
        document.getElementById("imageModal").style.display = "none";
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.feed_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel Personal_Website\resources\views/user/feed.blade.php ENDPATH**/ ?>