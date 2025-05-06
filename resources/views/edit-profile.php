<?php
session_start();
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$stmt = $conn->prepare("SELECT name, address, email, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($name, $address, $email, $profile_image);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_address = trim($_POST["address"]);
    $new_email = trim($_POST["email"]);
    $new_profile_image = $profile_image;

    // Handle file upload
    if (!empty($_FILES["profile_image"]["name"])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExt = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($fileExt), $allowedExtensions) && $_FILES["profile_image"]["size"] <= 2 * 1024 * 1024) {
            $newFileName = uniqid('profile_', true) . '.' . $fileExt;
            $new_profile_image = $uploadDir . $newFileName;

            if (!move_uploaded_file($_FILES["profile_image"]["tmp_name"], $new_profile_image)) {
                $new_profile_image = $profile_image; // Keep old image if upload fails
            }
        }
    }

    // Update user details
    $stmt = $conn->prepare("UPDATE users SET address = ?, email = ?, profile_image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $new_address, $new_email, $new_profile_image, $_SESSION['user_id']);
    if ($stmt->execute()) {
        header("Location: profile.php?message=" . urlencode("Profile updated successfully."));
        exit();
    } else {
        echo "Error updating profile.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../static/asset/logo.png" type="image/x-icon">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../static/bootstrap/bootstrap.min.css">
    <script src="../static/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container">
    <div class="edit-card p-4">
        <h3 class="text-center">Edit Profile</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="text-center mb-3">
                <img src="<?= htmlspecialchars($profile_image ?: 'default-profile.jpg'); ?>" alt="Profile Picture" class="profile-img">
            </div>
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($name); ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($address); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Profile Picture</label>
                <input type="file" name="profile_image" class="form-control">
            </div>
            <button type="submit" class="btn btn-success w-100">Save Changes</button>
        </form>
    </div>
</div>

</body>
</html>
