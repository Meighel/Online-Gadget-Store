<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view orders.");
}

$user_id = $_SESSION['user_id'];

// Fetch unpaid (ongoing) orders
$ongoing_stmt = $conn->prepare("
    SELECT id, total_amount, created_at
    FROM Orders
    WHERE user_id = ? AND is_paid = FALSE
    ORDER BY created_at DESC
");
if (!$ongoing_stmt) die("Prepare failed: " . $conn->error);
$ongoing_stmt->bind_param("i", $user_id);
$ongoing_stmt->execute();
$ongoing_orders = $ongoing_stmt->get_result();

// Fetch paid (processed) orders
$processed_stmt = $conn->prepare("
    SELECT id, total_amount, created_at
    FROM Orders
    WHERE user_id = ? AND is_paid = TRUE
    ORDER BY created_at DESC
");
if (!$processed_stmt) die("Prepare failed: " . $conn->error);
$processed_stmt->bind_param("i", $user_id);
$processed_stmt->execute();
$processed_orders = $processed_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="client_dashboard.php">TechNest</a>
      <div class="d-flex gap-2 align-items-center">
        <a href="../Public/shop.php" class="nav-link text-white me-2">Shop</a>
        <a href="../User/cart.php" class="nav-link text-white me-2">Cart</a>
        <a href="../User/my_orders.php" class="nav-link text-white me-2">Orders</a>
        <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <h2 class="mb-4">Ongoing Orders</h2>
    <?php if ($ongoing_orders->num_rows > 0): ?>
      <div class="row">
        <?php while($order = $ongoing_orders->fetch_assoc()): ?>
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Order #<?= $order['id'] ?></h5>
                <p class="card-text mb-1"><strong>Total:</strong> ₱<?= number_format($order['total_amount'], 2) ?></p>
                <p class="card-text mb-1"><strong>Date:</strong> <?= $order['created_at'] ?></p>
                <p class="text-warning font-weight-bold">Status: Unpaid</p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>No ongoing orders found.</p>
    <?php endif; ?>

    <h2 class="mt-5 mb-4">Processed Orders</h2>
    <?php if ($processed_orders->num_rows > 0): ?>
      <div class="row">
        <?php while($order = $processed_orders->fetch_assoc()): ?>
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-success">
              <div class="card-body">
                <h5 class="card-title">Order #<?= $order['id'] ?></h5>
                <p class="card-text mb-1"><strong>Total:</strong> ₱<?= number_format($order['total_amount'], 2) ?></p>
                <p class="card-text mb-1"><strong>Date:</strong> <?= $order['created_at'] ?></p>
                <p class="text-success font-weight-bold">Status: Paid</p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>No processed orders found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
