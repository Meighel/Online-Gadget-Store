<?php
session_start();
require '../db.php';

// Check if user is logged in and role is client
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header("Location: ../Public/login.php");
    exit();
}

$userName = $_SESSION['user_name'];
$userRole = $_SESSION['user_role'];
$userEmail = $_SESSION['user_email'];
$userId = $_SESSION['user_id'];
$userCreatedAt = $_SESSION['user_created_at'];

// Fetch last 5 orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$ordersResult = $stmt->get_result();

// Fetch up to 5 available products
$productSql = "SELECT * FROM products LIMIT 5";
$productResult = $conn->query($productSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Client Dashboard | TechNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style">
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
          <a class="navbar-brand" href="client_dashboard.php">TechNest</a>
          <div class="d-flex gap-2 align-items-center">
              <a href="../Public/shop.php" class="nav-link text-white me-2">Shop</a>
              <a class="nav-link position-relative text-white" href="../User/cart.php">
                  <i class="fas fa-shopping-cart me-1"></i>
                  <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
              </a>
              <a href="../User/my_orders.php" class="nav-link text-white me-2">Orders</a>
              <a href="account_settings.php" class="nav-link text-white me-2">Settings</a>
              <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
          </div>
      </div>
  </nav>

<div class="container mt-5">
  <h1>Welcome, <?= htmlspecialchars($userName) ?>!</h1>
  <p><strong>Email:</strong> <?= htmlspecialchars($userEmail) ?></p>
  <p><strong>Role:</strong> <?= htmlspecialchars($userRole) ?></p>
  <p><strong>Joined:</strong> <?= date('F d, Y', strtotime($userCreatedAt)) ?></p>
  <hr>

  <h3>Your Recent Orders</h3>
  <?php if ($ordersResult && $ordersResult->num_rows > 0): ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Date</th>
          <th>Status</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($order = $ordersResult->fetch_assoc()): ?>
          <tr>
            <td><?= $order['id'] ?></td>
            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
            <td><?= $order['is_paid'] == 1 ? 'Paid' : 'Unpaid' ?></td>
            <td>₱<?= number_format($order['total_amount'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>You have no recent orders.</p>
  <?php endif; ?>
</div>

<div class="container mt-5">
  <hr>
  <h3>Available Products</h3>
  <div class="row g-4 mt-3">
    <?php if ($productResult && $productResult->num_rows > 0): ?>
      <?php while ($product = $productResult->fetch_assoc()): ?>
        <div class="col-md-4">
          <div class="card h-100">
            <img src="../<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="max-height: 200px; object-fit: contain;">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
              <p class="text-primary fw-bold">₱<?= number_format($product['price'], 2) ?></p>
              <button class="btn btn-sm btn-outline-primary">Add to Cart</button>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No products available.</p>
    <?php endif; ?>
  </div>
  <div class="text-end mt-3">
    <a href="../Public/shop.php" class="btn btn-primary">View More Products</a>
  </div>
</div>

<script>
document.getElementById('logoutBtn').addEventListener('click', async () => {
  await fetch('../API/logout.php', { method: 'POST' });
  window.location.href = '../Public/login.php';
});
</script>

</body>
</html>
