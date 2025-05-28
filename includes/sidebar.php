<?php
session_start();

$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

switch ($role) {
    case 'admin':
        include 'sidebars/admin_sidebar.php';
        break;
    case 'staff':
        include 'sidebars/staff_sidebar.php';
        break;
    default:
        include 'sidebars/user_sidebar.php';
}
?>
