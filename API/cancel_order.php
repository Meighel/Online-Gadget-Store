<?php
session_start();
require '../db.php';

if (!isset($_POST['order_id']) || !isset($_SESSION['user_id'])) {
    header("Location: ../User/my_orders.php");
    exit;
}

$order_id = $_POST['order_id'];
$user_id = $_SESSION['user_id'];

// Delete order and its items (only if it's unpaid)
$stmt = $conn->prepare("DELETE FROM Order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM Orders WHERE id = ? AND user_id = ? AND is_paid = FALSE");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();

// Clear session
if ($_SESSION['latest_order_id'] == $order_id) {
    unset($_SESSION['latest_order_id']);
}

header("Location: ../User/cart.php");
exit;
