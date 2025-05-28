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
  <link rel="stylesheet" href="css/shop_styles.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">TechNest</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center gap-2">
                <li class="nav-item">
                <a href="../Public/shop.php" class="nav-link text-white">Shop</a>
                </li>

                <li class="nav-item position-relative">
                    <a class="nav-link text-white" href="cart.php">
                    <i class="fas fa-shopping-cart me-1"></i>
                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link text-white">Orders</a>
                </li>
                <li class="nav-item">
                    <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
                </li>
            </ul>
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
              <td><?= htmlspecialchars($order['status']) ?></td>
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

  fetch('/API/get_cart_count.php')
              .then(res => res.json())
              .then(data => {
                  document.getElementById('cartCount').innerText = data.count || 0;
              })
              .catch(err => console.error('Failed to fetch cart count:', err));
  </script>

</body>
</html>
