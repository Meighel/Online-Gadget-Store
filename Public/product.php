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
    <link rel="stylesheet" href="/css/shop_styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../User/client_dashboard.php">TechNest</a>
            <div class="d-flex gap-2 align-items-center">
                <a href="../Public/shop.php" class="nav-link text-white me-2">Shop</a>
                <a class="nav-link position-relative text-white" href="../User/cart.php">
                    <i class="fas fa-shopping-cart me-1"></i>
                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
                </a>
                <a href="../User/my_orders.php" class="nav-link text-white me-2">Orders</a>
                <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
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
        fetch('/API/get_cart_count.php')
            .then(res => res.json())
            .then(data => {
                document.getElementById('cartCount').innerText = data.count || 0;
            })
            .catch(err => console.error('Failed to fetch cart count:', err));
    </script>
</body>
</html>
