<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Everyday - Profile</title>
    <link rel="stylesheet" href="../static/asset/profile.css">
    <link rel="shortcut icon" href="../static/asset/logo.png" type="image/x-icon">
    <link href="../static/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="../static/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <button class="btn btn-light me-3" id="toggleSidebar"><i class="fa-solid fa-bars"></i></button>
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="../static/asset/logo.png" alt="Logo" height="40">
            <span class="ms-2">My Everyday</span>
        </a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown">
                    <img src="<?php echo htmlspecialchars($profile_image ?: '../static/asset/default-profile.jpg'); ?>" alt="Profile" class="profile-img">
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <a href="dashboard.php" class="nav-link"><i class="fa-solid fa-house"></i> Home</a>
    <a href="profile.php" class="nav-link"><i class="fa-solid fa-user"></i> My Profile</a>
    <a href="caption.php" class="nav-link"><i class="fa-solid fa-file-alt"></i> Captions</a>
    <a href="gallery.php" class="nav-link"><i class="fa-solid fa-image"></i> Gallery</a>
</div>

<div class="content" id="content">
    <h2>My Profile</h2>
    <div class="profile-container">
        <img src="<?php echo htmlspecialchars($profile_image ?: '../static/asset/default-profile.jpg'); ?>" alt="Profile Picture" class="profile-pic">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($name ?? 'N/A'); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email ?? 'N/A'); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address ?? 'N/A'); ?></p>
        <form action="edit-profile.php" method="get">
            <button type="submit" class="edit-button">Edit Profile</button>
        </form>
    </div>
    
    <h3 class="mt-4">My Captions</h3>
    <ul class="list-group">
        <?php foreach ($captions as $caption): ?>
            <li class="list-group-item">
                <span><?php echo htmlspecialchars($caption['content']); ?> <br>
                <small class="text-muted">Posted on <?php echo $caption['created_at']; ?></small></span>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <h3 class="mt-4">My Gallery</h3>
    <div class="gallery-container">
        <?php foreach ($gallery as $post): ?>
            <div class="gallery-item">
                <?php if (!empty($post['media'])): ?>
                    <?php $file_extension = pathinfo($post['media'], PATHINFO_EXTENSION); ?>
                    <?php if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                        <img src="<?php echo htmlspecialchars($post['media']); ?>" alt="Gallery Image" class="gallery-image">
                    <?php elseif (in_array($file_extension, ['mp4', 'webm', 'ogg', 'mkv'])): ?>
                        <video controls class="gallery-video">
                            <source src="<?php echo htmlspecialchars($post['media']); ?>" type="video/<?php echo $file_extension; ?>">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                <?php endif; ?>

                <small class="text-muted">Posted on <?php echo $post['created_at']; ?></small>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.getElementById("toggleSidebar").addEventListener("click", function () {
    document.getElementById("sidebar").classList.toggle("show");
    document.getElementById("content").classList.toggle("shift");
});
</script>

</body>
</html>
