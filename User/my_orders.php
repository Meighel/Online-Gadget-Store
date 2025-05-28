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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/shop_styles.css">
  <link rel="stylesheet" href="../css/orders.css">
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="../index.php">TechNest</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center gap-2">
          <li class="nav-item">
            <a href="client_dashboard.php" class="nav-link text-white">Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="../Public/shop.php" class="nav-link text-white">Shop</a>
          </li>

            <li class="nav-item position-relative">
              <a class="nav-link text-white" href="../User/cart.php">
                <i class="fas fa-shopping-cart me-1"></i>
                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="../User/my_orders.php" class="nav-link text-white">Orders</a>
            </li>
            <li class="nav-item">
              <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
            </li>
        </ul>
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

  <script>
    document.getElementById('logoutBtn').addEventListener('click', async () => {
      await fetch('../API/logout.php', { method: 'POST' });
      window.location.href = '../Public/login.php';
    });

    fetch('/API/get_cart_count.php')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('cartCount').innerText = data.count || 0;
                })
                .catch(err => console.error('Failed to fetch cart count:', err));
  </script>
</body>
</html>
