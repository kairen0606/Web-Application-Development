<?php
session_start();
require_once '../classes/user.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit;
}

// CSRF validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: personalInfo.php?status=error&message=Invalid%20CSRF%20token");
    exit;
}

$user_id = $_SESSION['user_id'];
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$birthday = trim($_POST['birthday'] ?? '');
$gender = trim($_POST['gender'] ?? '');

// Server-side validation
$errors = [];
if (empty($name) || strlen($name) < 2) {
    $errors[] = "Name must be at least 2 characters.";
}
if (!preg_match('/^\+?\d{10,13}$/', $phone)) {
    $errors[] = "Invalid phone number format.";
}
if ($gender && !in_array($gender, ['male', 'female'])) {
    $errors[] = "Invalid gender selection.";
}
if ($birthday && !DateTime::createFromFormat('Y-m-d', $birthday)) {
    $errors[] = "Invalid birthday format.";
}

if (!empty($errors)) {
    header("Location: personalInfo.php?status=error&message=" . urlencode(implode(", ", $errors)));
    exit;
}

$user = new User();
$success = $user->updateUserInfo($user_id, $name, $phone, $email, $birthday, $gender);

if ($success) {
    $_SESSION['user_name'] = $name;
    $_SESSION['user_phone'] = $phone;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_birthday'] = $birthday;
    $_SESSION['user_gender'] = $gender;
    header("Location: personalInfo.php?status=success");
    exit;
} else {
    header("Location: personalInfo.php?status=error&message=Database%20update%20failed");
    exit;
}
?>