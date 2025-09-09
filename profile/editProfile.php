<?php
session_start();
require_once '../classes/user.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = new User();

// Fetch user details
$userData = $user->getUserById($user_id);
$name = htmlspecialchars($userData['name']);
$phone = htmlspecialchars($userData['phone']);
$email = htmlspecialchars($userData['email']);
$birthday = htmlspecialchars($userData['birthday'] ?? '');
$gender = isset($userData['gender']) && $userData['gender'] !== null ? strtolower($userData['gender']) : '';

// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Styles/profile.css">
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <style>
        .error { color: red; font-size: 0.9em; margin-top: 2px; }
        .valid { color: green; }
        .message { margin-bottom: 10px; padding: 10px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error-message { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="sidebar">
            <a href="personalInfo.php">Personal Info</a>
            <a href="editProfile.php" style="font-weight: bold;">| Edit Profile</a>
            <a href="orderHistory.php">Order History</a>
            <a href="viewStatistic.php">View Statistic</a>
            <a href="logout.php">Log out</a>
        </div>

        <div class="content">
            <div class="card">
                <h2>Edit Profile</h2>
                <br>
                <?php
                if (isset($_GET['status'])) {
                    if ($_GET['status'] === 'success') {
                        echo '<div class="message success">Profile updated successfully!</div>';
                    } elseif ($_GET['status'] === 'error') {
                        echo '<div class="message error-message">Failed to update profile. Please try again.</div>';
                    }
                }
                ?>
                <form id="editProfile-form" action="updateProfile.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" oninput="validateName()">
                        <div id="nameError" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone No</label>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" oninput="validatePhone()">
                        <div id="phoneError" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="birthday">Birthday</label>
                        <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($birthday ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <br>
                        <span>
                            <input type="radio" id="male" name="gender" value="male" <?= $gender === 'male' ? 'checked' : '' ?>> 
                            <label for="male">Male</label>
                            <input type="radio" id="female" name="gender" value="female" <?= $gender === 'female' ? 'checked' : '' ?>> 
                            <label for="female">Female</label>
                        </span>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-cancel" id="cancelBtn">Cancel</button>
                        <button type="submit" class="btn btn-save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../js/validation.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("editProfileForm");
            const cancelBtn = document.getElementById("cancelBtn");
            let initialValues = {
                name: document.getElementById("name").value,
                phone: document.getElementById("phone").value,
                birthday: document.getElementById("birthday").value,
                gender: document.querySelector('input[name="gender"]:checked')?.value || ''
            };

            cancelBtn.addEventListener("click", function () {
                document.getElementById("name").value = initialValues.name;
                document.getElementById("phone").value = initialValues.phone;
                document.getElementById("birthday").value = initialValues.birthday;
                document.querySelectorAll('input[name="gender"]').forEach(input => {
                    input.checked = input.value === initialValues.gender;
                });
                document.getElementById("nameError").innerHTML = "";
                document.getElementById("phoneError").innerHTML = "";
            });
        });
    </script>
</body>
</html>