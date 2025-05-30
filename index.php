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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to TechNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<!-- Enhanced Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">TechNest</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="Public/shop.php">Shop</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Public/about.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Public/contact.php">Contact</a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="User/client_dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="User/cart.php">Cart</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="Public/login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Enhanced Hero Section -->
<section class="hero">
  <div class="container">
    <h1><i class="fas fa-rocket me-3"></i>Welcome to TechNest</h1>
    <p class="lead">Discover cutting-edge technology and shop your favorite items now!</p>
    <a href="Public/shop.php" class="btn btn-light btn-lg">
      <i class="fas fa-arrow-right me-2"></i>Browse Products
    </a>
  </div>
</section>

<!-- Enhanced Product Preview -->
<section class="featured-section">
  <div class="container">
    <h2 class="section-title fade-in">
      <i class="fas fa-star me-3"></i>Featured Products
    </h2>
    <div class="row">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php $delay = 0; ?>
        <?php while($product = $result->fetch_assoc()): ?>
          <div class="col-lg-4 col-md-6 mb-4 fade-in" style="animation-delay: <?= $delay ?>s;">
            <div class="card product-card">
              <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="card-text">
                  <i class="fas fa-peso-sign me-1"></i><?= number_format($product['price'], 2) ?>
                </p>
                <a href="Public/product.php?id=<?= $product['id'] ?>" class="btn btn-primary">
                  <i class="fas fa-eye me-2"></i>View Product
                </a>
              </div>
            </div>
          </div>
          <?php $delay += 0.1; ?>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12">
          <div class="no-products">
            <i class="fas fa-box-open fa-3x mb-3" style="color: #ddd;"></i>
            <p>No products found. Check back soon for exciting new arrivals!</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Enhanced Footer -->
<footer class="bg-dark text-white text-center py-4">
  <div class="container">
    <p class="mb-0">
      <i class="fas fa-copyright me-1"></i>
      <?= date("Y") ?> TechNest. All rights reserved.
    </p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Add smooth scrolling and navbar background change on scroll
  window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
      navbar.style.background = 'rgba(45, 52, 54, 0.98)';
    } else {
      navbar.style.background = 'rgba(45, 52, 54, 0.95)';
    }
  });

  // Add fade-in animation to cards when they come into view
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, observerOptions);

  document.querySelectorAll('.product-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
  });
</script>

</body>
</html>