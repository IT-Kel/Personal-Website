

<?php $__env->startSection('content'); ?>
    <div class="container-fluid main-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <a href="<?php echo e(route('dashboard')); ?>" class="active">Home</a>
            <a href="<?php echo e(route('explore')); ?>">Explore</a>
            <a href="<?php echo e(route('feed')); ?>">Feed</a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms</a>
            <a href="<?php echo e(route('settings')); ?>" >Settings</a>
            <a href="<?php echo e(route('profile.edit')); ?>" class="profile-btn">View Profile</a>
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


  

    <!-- MAIN CONTENT -->
    <div class="content">
    <!-- Flash Messages -->
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

  <!-- Post Box -->
    <div class="post-box">
        <form action="<?php echo e(route('post.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <textarea name="content" placeholder="Share your ideas" required></textarea>
            <input type="file" name="image" accept="image/*" aria-label="Upload an image">
            <button type="submit">Share</button>
        </form>
    </div>

<!-- Post Feed -->
<div class="post-feed">
    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $postUser = $post->user;
            $profileImage = $postUser && $postUser->profile_image
                ? asset('storage/' . $postUser->profile_image)
                : asset('assets/default-profile.jpg');
        ?>

        <div class="post">
            <div class="post-header">
                <img src="<?php echo e($profileImage); ?>" alt="<?php echo e($postUser ? e($postUser->name) : 'Unknown User'); ?>'s profile image" class="profile-img">
                <strong><?php echo e($postUser ? e($postUser->name) : 'Unknown User'); ?></strong>
            </div>

            <p><?php echo e(nl2br(e($post->content))); ?></p>

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

        <!-- Image Modal -->
        <div id="imageModal" class="image-modal">
            <span class="close-modal" onclick="closeImageModal()">✖</span>
            <img id="modalImage" src="" alt="Full Image">
        </div>

        <div class="post-footer">
            <!-- Like Button with Count (Fixed Alignment) -->
            <div class="like-container">
                <button class="like-btn" id="like-button-<?php echo e($post->id); ?>" data-post-id="<?php echo e($post->id); ?>">
                    <i class="fa-regular fa-heart"></i>
                    <span class="like-count" id="like-count-<?php echo e($post->id); ?>"><?php echo e($post->usersWhoLiked()->count()); ?></span>
                </button>
            </div>
            <small><?php echo e($post->created_at->format('F j, Y, g:i a')); ?></small>
        </div>

<!-- Comment Section -->
<div class="comments">
    <!-- Show/Hide Comments Button -->
    <button class="toggle-comments-btn">Show Comments </button>
    (<?php echo e($post->comments->count()); ?>)

    <!-- Comment Form -->
    <form method="POST" action="<?php echo e(route('comments.store', ['postId' => $post->id])); ?>">
        <?php echo csrf_field(); ?>
        <textarea name="content" placeholder="Write a comment..." required></textarea>
        <button type="submit">Post Comment</button>
    </form>

    <!-- Comment List (Initially hidden) -->
    <div class="comment-list" style="display: none;">
        <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="comment">
                <img src="<?php echo e(asset('storage/' . ($comment->user->profile_image ?? 'assets/default-profile.jpg'))); ?>"
                     alt="Profile Image" class="comment-profile-img">
                
                <div class="comment-details">
                    <strong><?php echo e($comment->user->name); ?></strong>
                    <p><?php echo e($comment->content); ?></p>
                    <small><?php echo e($comment->created_at->diffForHumans()); ?></small>

                    <!-- Three-dot menu for actions (Delete and Reply) -->
                    <div class="dropdown">
                        <button class="dropbtn">⋮</button>
                        <div class="dropdown-content">
                            <?php if(auth()->id() == $comment->user_id): ?>
                                <!-- Delete Comment Form -->
                                <form action="<?php echo e(route('comments.destroy', $comment->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="delete-btn">Delete Comment</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Reply Form -->
                    <form method="POST" action="<?php echo e(route('comments.reply', ['commentId' => $comment->id])); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="parent_id" value="<?php echo e($comment->id); ?>">
                        <textarea name="content" placeholder="Write a reply..." required></textarea>
                        <button type="submit">Reply</button>
                    </form>

                    <!-- Display Replies (nested comments) -->
                    <div class="reply-list">
                        <?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="comment reply">
                                <img src="<?php echo e(asset('storage/' . ($reply->user->profile_image ?? 'assets/default-profile.jpg'))); ?>"
                                     alt="Profile Image" class="comment-profile-img">
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
</div>

 

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                            // Update the like count
                            likeCountElement.innerText = data.likes;

                            // Toggle the heart color based on whether the user liked the post
                            if (data.likedByUser) {
                                button.querySelector('i').classList.remove('fa-regular');
                                button.querySelector('i').classList.add('fa-solid');
                            } else {
                                button.querySelector('i').classList.remove('fa-solid');
                                button.querySelector('i').classList.add('fa-regular');
                            }
                        })
                        .catch(error => console.log('Error:', error));
                    });
                });
            });



            //hide and show comments
            document.addEventListener("DOMContentLoaded", function() {
                    const toggleCommentButtons = document.querySelectorAll('.toggle-comments-btn');

                    toggleCommentButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const commentList = this.nextElementSibling.nextElementSibling; // Select the comment list (it’s after the button and the form)
                            if (commentList.style.display === 'none' || commentList.style.display === '') {
                                commentList.style.display = 'block';
                                this.textContent = 'Hide Comments'; // Change button text when comments are shown
                            } else {
                                commentList.style.display = 'none';
                                this.textContent = 'Show Comments'; // Change button text when comments are hidden
                            }
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





<div class="right-sidebar">
    <div class="search-box">
        <input type="text" id="searchUser" placeholder="Search users...">
        <button>Search</button>
        <div id="searchResults"></div>
    </div>

    <div class="who-to-follow">
        <h4>Suggested to follow</h4>
        <?php $__currentLoopData = $usersToFollow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $followUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="follow-suggestion">
                <p><?php echo e($followUser->name); ?></p>
                <button class="follow-btn" data-id="<?php echo e($followUser->id); ?>">
                    Follow
                </button>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- Add jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    $(".follow-btn").click(function () {
        let button = $(this);
        let userId = button.data("id");
        let isFollowing = button.text() === "Unfollow"; // Check current button text

        let url = isFollowing ? "<?php echo e(route('unfollow')); ?>" : "<?php echo e(route('follow')); ?>";
        let token = "<?php echo e(csrf_token()); ?>";

        $.ajax({
            url: url,
            type: "POST",
            data: { user_id: userId, _token: token },
            success: function (response) {
                if (isFollowing) {
                    button.text("Follow"); // Change button text
                } else {
                    button.closest(".follow-suggestion").fadeOut("slow", function () {
                        $(this).remove(); // Remove from "Who to Follow" list
                    });
                }
            },
            error: function (xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
});

$(document).ready(function () {
        $('#searchUser').on('keyup', function () {
            let query = $(this).val(); // Get the search query

            if (query.length > 0) {
                $.ajax({
                    url: '<?php echo e(route('search.users')); ?>',  // Route for search
                    type: 'GET',
                    data: { query: query },
                    success: function (data) {
                        // Clear previous results
                        $('#searchResults').empty();

                        // Display the search results
                        if (data.length > 0) {
                            data.forEach(user => {
                                $('#searchResults').append(
                                    `<p><a href="<?php echo e(url('/profile')); ?>/${user.id}">${user.name}</a></p>`
                                );
                            });
                        } else {
                            $('#searchResults').append('<p>No users found</p>');
                        }
                    },
                    error: function (xhr) {
                        console.error('Search failed', xhr);
                    }
                });
            } else {
                // If the query is empty, clear the results
                $('#searchResults').empty();
            }
        });
    });
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel Personal_Website\resources\views/dashboard.blade.php ENDPATH**/ ?>