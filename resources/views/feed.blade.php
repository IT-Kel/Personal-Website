@extends('layouts.feed_layout') <!-- Assuming you have a layout file -->

@section('content')
<div class="container-fluid main-container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="{{ route('dashboard') }}">Home</a>
        <a href="{{ route('explore') }}">Explore</a>
        <a href="{{ route('feed') }}" class="active">Feed</a>
        <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms</a>
        <a href="{{ route('settings') }}">Settings</a>
        <a href="{{ route('profile.edit') }}" class="profile-btn">View Profile</a>
    </div>

    

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="profile-section">
            <h2>Your Profile</h2>
            <div class="profile-details">
                @php
                $authUser = auth()->user();
                $profileImage = $authUser->profile_image
                    ? asset('storage/' . $authUser->profile_image)
                    : asset('assets/default-profile.jpg');
            @endphp
                 <img src="{{ $profileImage }}" alt="{{ e($authUser->name) }}'s profile image" class="profile-img">
                <h3>{{ auth()->user()->name }}</h3>
                <p>{{ auth()->user()->bio }}</p>
                <a href="{{ route('profile.edit') }}">Edit Profile</a>
            </div>
        </div>

            
        <div class="right-sidebar">
            <div class="followed">
                <h3>Followed Users</h3>
                <ul>
                    @foreach(auth()->user()->followedUsers as $user)
                    <div class="followed-users" id="followed-user-{{ $user->id }}">
                        <p>
                            <a href="{{ route('profile.show', $user->id) }}">{{ $user->name }}</a>
                            <button class="unfollow-btn" data-user-id="{{ $user->id }}">Unfollow</button>
                        </p>
                    </div>
                    @endforeach
                </ul>
            </div>
        </div>
        
        
        <div class="feed-section">
            <div class="posts">
                <h2>Your Feed</h2>
        
                @if($posts->isEmpty())
                    <p>You have no posts yet.</p>
                @else
                    @foreach($posts as $post)
                        @php
                            $postUser = $post->user;
                            $postProfileImage = $postUser && $postUser->profile_image
                                ? asset('storage/' . $postUser->profile_image)
                                : asset('assets/default-profile.jpg');
                        @endphp
        
                        @if($postUser->id == auth()->id()) <!-- Check if the post is from the logged-in user -->
                            <div class="post">
                                <div class="post-header">
                                    <div class="user-info">
                                    <img src="{{ $postProfileImage }}" alt="{{ e($postUser->name) }}'s profile image" class="profile-img" style="width:40px; height:40px; border-radius:50%;">
                                    <h4>{{ $postUser->name }}</h4>
                                    </div>
        
                                   <!-- Three-dot menu -->
                                    <div class="dropdown">
                                        <button class="dropbtn">⋮</button>
                                        <div class="dropdown-content">
                                            <!-- Edit Button -->
                                            <a href="{{ route('posts.edit', $post->id) }}" class="edit-btn">Edit</a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-btn">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <p>{{ $post->content }}</p>
        
                                @if ($post->media)
                                    @php
                                        $extension = pathinfo($post->media, PATHINFO_EXTENSION);
                                    @endphp
                                    @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <img src="{{ asset('storage/uploads/' . basename($post->media)) }}" alt="Post Media" class="post-media" onclick="openImageModal(this.src)">
                                    @elseif (in_array(strtolower($extension), ['mp4', 'webm', 'ogg', 'mkv']))
                                        <video controls class="post-media" aria-label="Post video media">
                                            <source src="{{ asset('storage/' . $post->media) }}" type="video/{{ $extension }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                @endif
        
                                <!-- Image Modal -->
                                <div id="imageModal" class="image-modal">
                                    <span class="close-modal" onclick="closeImageModal()">✖</span>
                                    <img id="modalImage" src="" alt="Full Image">
                                </div>
        
                                <!-- Like Button and Count -->
                                <div class="post-footer">
                                    <div class="like-container">
                                        <button class="like-btn" id="like-button-{{ $post->id }}" data-post-id="{{ $post->id }}">
                                            <i class="fa-regular fa-heart"></i>
                                            <span class="like-count" id="like-count-{{ $post->id }}">{{ $post->usersWhoLiked()->count() }}</span>
                                        </button>
                                    </div>
                                    <small>{{ $post->created_at->format('F j, Y, g:i a') }}</small>
                                </div>
        
                                <!-- Comment Section -->
                                <div class="comments">
                                    <button class="toggle-comments-btn">Show Comments</button> ({{ $post->comments->count() }})    
        
                                    <!-- Comment Form -->
                                    <form method="POST" action="{{ route('comments.store', ['postId' => $post->id]) }}">
                                        @csrf
                                        <textarea name="content" placeholder="Write a comment..." required></textarea>
                                        <button type="submit">Post Comment</button>
                                    </form>
        
                                    <!-- Comment List (Initially hidden) -->
                                    <div class="comment-list" style="display: none;">
                                        @foreach($post->comments as $comment)
                                            <div class="comment">
                                                <img src="{{ asset('storage/' . ($comment->user->profile_image ?? 'assets/default-profile.jpg')) }}"
                                                     alt="Profile Image" class="comment-profile-img">
                                                <div class="comment-details">
                                                    <strong>{{ $comment->user->name }}</strong>
                                                    <p>{{ $comment->content }}</p>
                                                    <small>{{ $comment->created_at->diffForHumans() }}</small>
        
                                                    <!-- Reply Form -->
                                                    <form method="POST" action="{{ route('comments.reply', ['commentId' => $comment->id]) }}">
                                                        @csrf
                                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                        <textarea name="content" placeholder="Write a reply..." required></textarea>
                                                        <button type="submit">Reply</button>
                                                    </form>
        
                                                    <!-- Display Replies -->
                                                    <div class="reply-list">
                                                        @foreach($comment->replies as $reply)
                                                            <div class="comment reply">
                                                                <img src="{{ asset('storage/' . ($reply->user->profile_image ?? 'assets/default-profile.jpg')) }}"
                                                                     alt="Profile Image" class="comment-profile-img">
                                                                <div class="comment-details">
                                                                    <strong>{{ $reply->user->name }}</strong>
                                                                    <p>{{ $reply->content }}</p>
                                                                    <small>{{ $reply->created_at->diffForHumans() }}</small>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
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