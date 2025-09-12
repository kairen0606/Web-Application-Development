<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}
require_once '../classes/user.php';

$user = new User();
$userData = $user->getUserById($_SESSION['user_id']);
if (!$userData || isset($userData['success']) && !$userData['success']) {
    header("Location: ../user/login.php?error=User%20not%20found");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Info</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../Styles/profile.css">
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <a href="#" style="font-weight: bold;">| Personal Info</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="orderHistory.php">Order History</a>
            <a href="viewStatistic.php">View Statistic</a>
            <a href="logout.php">Log out</a>
        </div>
        <div class="content">
            <div class="card">
                <h2>Personal Info</h2>
                <br>
                <?php
                if (isset($_GET['status'])) {
                    if ($_GET['status'] === 'success') {
                        echo '<div class="message success">Profile updated successfully!</div>';
                    } elseif ($_GET['status'] === 'error') {
                        $message = isset($_GET['message']) ? htmlspecialchars(urldecode($_GET['message'])) : 'Failed to update profile.';
                        echo '<div class="message error-message">' . $message . '</div>';
                    }
                }
                ?>
                <table class="info-table">
                    <tr>
                        <td>Username</td>
                        <td><?php echo htmlspecialchars($userData['name']); ?></td>
                    </tr>
                    <tr>
                        <td>Phone No.</td>
                        <td><?php echo htmlspecialchars($userData['phone']); ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo htmlspecialchars($userData['email']); ?></td>
                    </tr>
                    <tr>
                        <td>Birthday</td>
                        <td><?php echo (!empty($userData['birthday']) && $userData['birthday'] !== '0000-00-00') ? htmlspecialchars($userData['birthday']) : 'Not set'; ?></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><?php echo !empty($userData['gender']) ? htmlspecialchars($userData['gender']) : 'Not set'; ?></td>
                    </tr>
                </table>
                <button class="edit-btn" onclick="window.location.href='editProfile.php'">Edit Profile</button>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>