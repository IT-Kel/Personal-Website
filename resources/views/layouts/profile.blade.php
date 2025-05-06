<!-- resources/views/layouts/profile.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
    <title>My Everyday - Profile</title>

    <!-- Include the profile-specific CSS -->
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <!-- Include Bootstrap CSS -->
    <link href="{{ asset('bootstrap/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    
     <!-- jQuery -->
     <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
     <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Include any additional JS libraries (if required) -->
    <script src="{{ asset('js/jQuery.js') }}"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="profile-page">
    <!-- Navbar specific to profile page -->
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">My Everyday</a>
            <div class="d-flex">
                <a href="{{ route('logout') }}" class="nav-link text-light"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        @yield('content') <!-- Profile page content will be inserted here -->
    </div>

    <script src="{{ asset('static/js/script.js') }}"></script>
</body>
</html>
