<?php
session_start();
require '../db.php';  // Your mysqli connection ($conn)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products'])) {
    $selectedProducts = $_POST['selected_products'];
    $products = [];

    foreach ($selectedProducts as $productId) {
        $query = "SELECT p.id, p.name, p.price FROM Products p WHERE p.id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    header('Location: checkout.php'); // Redirect to thank you page after checkout
} else {
    header('Location: cart.php'); // Redirect if no products selected
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/shop_styles.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">TechNest</a>
            <div class="d-flex gap-2 align-items-center">
                <a href="../Public/shop.php" class="nav-link text-white me-2">Shop</a>
                <a class="nav-link position-relative text-white" href="../User /cart.php">
                    <i class="fas fa-shopping-cart me-1 bg-light"></i>
                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
                </a>
                <a href="../User/my_orders.php" class="nav-link text-white me-2">Orders</a>
                <a href="settings.php" class="nav-link text-white me-2">Settings</a>
                <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Checkout</h2>
        <form action="process_checkout.php" method="POST">
            <div class="row">
                <div class="col-12">
                    <h4>Your Selected Products</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Complete Purchase</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5">
        <p>&copy; 2023 TechNest. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
