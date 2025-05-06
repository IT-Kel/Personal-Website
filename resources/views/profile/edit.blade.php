@extends('layouts.app') <!-- Assuming you have a layout file like app.blade.php -->

@section('content')
<div class="container-fluid main-container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="{{ route('dashboard') }}">Home</a>
        <a href="#">Explore</a>
        <a href="{{ route('feed') }}">Feed</a>
        <a href="#">Terms</a>
        <a href="{{ route('profile.edit') }}" class="profile-btn">View Profile</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="profile-section">
            <h2>Edit Your Profile</h2>
            
            <!-- Profile Image and Name -->
            @php
                $authUser = auth()->user();
                $profileImage = $authUser->profile_image
                    ? asset('storage/' . $authUser->profile_image)
                    : asset('assets/default-profile.jpg');
            @endphp

            <div class="profile-details">
                <img src="{{ $profileImage }}" alt="{{ e($authUser->name) }}'s profile image" class="profile-img">
                <h3>{{ auth()->user()->name }}</h3>
            </div>
        </div>

        <!-- Profile Update Form -->
        <div class="profile-update-section">
            <h3>Update Profile Information</h3>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $authUser->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $authUser->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea name="bio" id="bio" class="form-control" rows="4">{{ old('bio', $authUser->bio) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="profile_image">Profile Image</label>
                    <input type="file" name="profile_image" id="profile_image" class="form-control-file">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
