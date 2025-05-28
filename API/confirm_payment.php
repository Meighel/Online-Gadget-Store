<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $payment_mode = $_POST['payment_mode'] ?? '';

    // Set is_paid = 1 if GCash or Maya, otherwise 0
    $is_paid = ($payment_mode === 'GCash' || $payment_mode === 'Maya') ? 1 : 0;

    // Corrected SQL and binding
    $stmt = $conn->prepare("UPDATE Orders SET is_paid = ? WHERE id = ?");
    $stmt->bind_param("ii", $is_paid, $order_id);

    if ($stmt->execute()) {
        // Redirect to a success page
        header("Location: ../User/thank_you.php");
        exit;
    } else {
        echo "Failed to update order.";
    }
} else {
    header("Location: ../User/cart.php");
    exit;
}
