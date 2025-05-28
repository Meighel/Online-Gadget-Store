<?php
session_start();
require 'db.php';

$sql = "SELECT * FROM products ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);

if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 'admin':
            header("Location: Admin/admin_dashboard.php");
            exit();
        case 'staff':
            header("Location: Staff/staff_dashboard.php");
            exit();
        case 'client':
        default:
            header("Location: User/client_dashboard.php");
            exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome to TechNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">TechNest</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="Public/shop.php">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="Public/about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="Public/contact.php">Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="User/client_dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="User/cart.php">Cart</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="Public/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero">
  <div class="container">
    <h1>Welcome to TechNest</h1>
    <p class="lead">Shop your favorite items now!</p>
    <a href="Public/shop.php" class="btn btn-light btn-lg mt-3">Browse Products</a>
  </div>
</section>

<!-- Product Preview -->
<section class="py-5">
  <div class="container">
    <h2 class="mb-4">Featured Products</h2>
    <div class="row">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while($product = $result->fetch_assoc()): ?>
          <div class="col-md-4 mb-4">
            <div class="card product-card">
              <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="card-text">₱<?= number_format($product['price'], 2) ?></p>
                <a href="Public/product.php?id=<?= $product['id'] ?>" class="btn btn-primary">View Product</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No products found.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
  &copy; <?= date("Y") ?> TechNest. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
