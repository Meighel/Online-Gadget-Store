<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update_profile') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../User/account_settings.php?error=Unauthorized access.");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email)) {
        header("Location: ../User/account_settings.php?error=Name and email are required.");
        exit;
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE Users SET name = ?, email = ?, password_hash = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE Users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: ../User/account_settings.php?success=Profile updated successfully.");
    } else {
        header("Location: ../User/account_settings.php?error=Failed to update profile.");
    }

    exit;
}
