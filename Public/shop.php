<?php
session_start();
require '../db.php';

$isLoggedIn = isset($_SESSION['user_id']); // <-- Ensure this is defined

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    header('Content-Type: application/json');

    $action = $_GET['action'] ?? 'all';

    if ($action === 'all') {
        $sql = "SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name
                FROM Products p
                LEFT JOIN Categories c ON p.category_id = c.id";
        $stmt = $conn->prepare($sql);
    }
    elseif ($action === 'category' && !empty($_GET['category'])) {
        $category = strtolower($_GET['category']);
        $sql = "SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name
                FROM Products p
                LEFT JOIN Categories c ON p.category_id = c.id
                WHERE LOWER(REPLACE(c.name, ' ', '')) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $category);
    }
    elseif ($action === 'search' && !empty($_GET['term'])) {
        $term = '%' . $_GET['term'] . '%';
        $sql = "SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name
                FROM Products p
                LEFT JOIN Categories c ON p.category_id = c.id
                WHERE p.name LIKE ? OR p.description LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $term, $term);
    }
    else {
        echo json_encode([]);
        exit;
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'image_url' => $row['image'],
                'category_name' => $row['category_name'] ?? 'Uncategorized',
                'rating' => rand(3, 5),
                'badge' => ''
            ];
        }
        echo json_encode($products);
    } else {
        echo json_encode(['error' => 'Failed to fetch products']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNest - Gadgets Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/shop_styles.css">
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
          <a href="../User/client_dashboard.php" class="nav-link text-white">Dashboard</a>
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


<!-- Hero Section -->
<section class="hero-section" id="home">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="display-4 mb-4">Discover Amazing Gadgets</h1>
            <p class="lead mb-4">Find the latest tech gadgets at unbeatable prices</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="search-container position-relative">
                        <input type="text" class="search-input" placeholder="Search for gadgets..." id="searchInput">
                        <button class="search-btn" onclick="searchProducts()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container" id="products">
    <div id="loadingSpinner" class="loading" style="display: none;">
        <div class="spinner"></div>
        <p class="mt-3">Loading amazing gadgets...</p>
    </div>
    <div class="product-grid" id="productGrid">
        <!-- Products loaded by JS -->
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    const userId = <?php echo $isLoggedIn ? $_SESSION['user_id'] : 'null'; ?>;
</script>
<script src="../javascript/shop.js"></script>
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
