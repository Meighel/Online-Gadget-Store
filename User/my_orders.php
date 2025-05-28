<?php
session_start();
require_once 'db_connection.php'; // Include your database connection

$user_id = $_SESSION['user_id'];

// Fetch ongoing orders
$ongoing_stmt = $conn->prepare("
  SELECT o.id, o.total_amount, o.created_at
  FROM Orders o
  WHERE o.user_id = ? AND o.status = 'ongoing'
  ORDER BY o.created_at DESC
");
$ongoing_stmt->bind_param("i", $user_id);
$ongoing_stmt->execute();
$ongoing_orders = $ongoing_stmt->get_result();

// Fetch processed orders
$processed_stmt = $conn->prepare("
  SELECT o.id, o.total_amount, o.created_at
  FROM Orders o
  WHERE o.user_id = ? AND o.status = 'processed'
  ORDER BY o.created_at DESC
");
$processed_stmt->bind_param("i", $user_id);
$processed_stmt->execute();
$processed_orders = $processed_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1>Ongoing Orders</h1>
  <?php while($order = $ongoing_orders->fetch_assoc()): ?>
    <div class="order-card">
      <p>Order ID: <?= $order['id'] ?></p>
      <p>Total Amount: ₱<?= number_format($order['total_amount'], 2) ?></p>
      <p>Order Date: <?= $order['created_at'] ?></p>
    </div>
  <?php endwhile; ?>

  <h1>Processed Orders</h1>
  <?php while($order = $processed_orders->fetch_assoc()): ?>
    <div class="order-card">
      <p>Order ID: <?= $order['id'] ?></p>
      <p>Total Amount: ₱<?= number_format($order['total_amount'], 2) ?></p>
      <p>Order Date: <?= $order['created_at'] ?></p>
    </div>
  <?php endwhile; ?>
</body>
</html>
