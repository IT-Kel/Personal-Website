<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExploreController;

// Landing Page
Route::get('/', function () {
    return view('index'); 
})->name('index');

// Authentication Routes
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', function () {
    return view('index');
})->name('login.form');

Route::middleware(['auth'])->group(function () {
    // Feed route
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
});

Route::resource('posts', PostController::class);

Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');


Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');


// Comment routes
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');


// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Requires Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Post Routes
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');

    // Follow/Unfollow User Routes
    Route::post('/follow', [FollowController::class, 'followUser'])->name('follow');
    Route::post('/unfollow', [FollowController::class, 'unfollowUser'])->name('unfollow');

   // web.php (routes file)
    Route::get('/feed/{user}', [FeedController::class, 'show'])->name('user.feed');


    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('followUser');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollowUser');



   // Route for posting a comment (main comment)
Route::post('/comments/store/{postId}', [CommentController::class, 'store'])->name('comments.store');

// Route for posting a reply to a comment
Route::post('/comments/reply/{commentId}', [CommentController::class, 'reply'])->name('comments.reply');


    Route::post('/post/{post}/like', [PostController::class, 'like'])->name('post.like');

    // Route for searching users
    Route::get('/search/users', [UserController::class, 'search'])->name('search.users');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');


    // Route to view account settings
    Route::get('/settings', [UserController::class, 'showSettings'])->name('settings');

    // Route to handle password update
    Route::post('/settings/update-password', [UserController::class, 'updatePassword'])->name('settings.updatePassword');

    // Route to handle account deletion
    Route::delete('/settings/delete-account', [UserController::class, 'deleteAccount'])->name('settings.deleteAccount');


    // In routes/web.php
Route::get('/explore', [ExploreController::class, 'explore'])->name('explore');



});