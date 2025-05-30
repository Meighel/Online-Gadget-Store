<?php
session_start();
require '../db.php'; // DB connection

$product_id = $_GET['id'] ?? null;

if (!$product_id || !is_numeric($product_id)) {
    echo "Invalid product ID.";
    exit;
}

// Fetch product details
$sql = "SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name
        FROM Products p
        LEFT JOIN categories c ON p.id = c.id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/shop_styles.css">
    <link rel="icon" href="../assets/images/favicon.png">
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
            <?php if ($isLoggedIn): ?>
            <li class="nav-item">
            <a href="../User/client_dashboard.php" class="nav-link text-white">Dashboard</a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
            <a href="shop.php" class="nav-link text-white">Shop</a>
            </li>
            <?php if ($isLoggedIn): ?>
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
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
        </ul>
        </div>
    </div>
  </nav>


    <div class="container mt-5">
        <a href="shop.php" class="btn btn-secondary mb-4">Back to Shop</a>
        <div class="row">
            <div class="col-md-6">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h2><?= htmlspecialchars($product['name']) ?></h2>
                <p class="text-muted"><?= htmlspecialchars($product['category_name']) ?></p>
                <h4>$<?= number_format($product['price'], 2) ?></h4>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                <button class="btn btn-primary" onclick="addToCart(<?= $product['id'] ?>)">Add to Cart</button>
            </div>
        </div>
    </div>

    <script>
        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
        async function addToCart(productId) {
            try {
                const response = await fetch(`/API/add_to_cart.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ product_id: productId, quantity: 1 })
                });

                const result = await response.json();
                if (result.success) {
                    alert("Product added to cart!");
                } else {
                    alert(result.message);
                }
            } catch (err) {
                alert("Error: " + err.message);
            }
        }
    </script>
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
