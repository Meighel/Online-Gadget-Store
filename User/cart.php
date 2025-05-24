<?php
session_start();
require '../db.php';  // Your mysqli connection ($conn)

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
$query = "SELECT p.id, p.name, p.price, c.quantity FROM Cart c JOIN Products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/shop_styles.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">TechNest</a>
            <div class="d-flex gap-2 align-items-center">
                <a href="../Public/shop.php" class="nav-link text-white me-2">Shop</a>
                <a class="nav-link position-relative text-white" href="../User/cart.php">
                    <i class="fas fa-shopping-cart me-1 bg-light"></i>
                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
                </a>
                <a href="settings.php" class="nav-link text-white me-2">Settings</a>
                <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
            </div>
        </div>
    </nav>

    <!-- Cart Section -->
    <div class="container mt-5">
        <h2 class="text-center">Your Shopping Cart</h2>
        <div class="row">
            <div class="col-12">
                <div class="filter-section">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <?php 
                            $totalPrice = 0; // Initialize total price
                            foreach ($cartItems as $item): 
                                $itemTotal = $item['price'] * $item['quantity'];
                                $totalPrice += $itemTotal; // Accumulate total price
                            ?>
                            <tr id="item-<?php echo $item['id']; ?>">
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <input type="number" value="<?php echo $item['quantity']; ?>" min="1" class="form-control" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                                </td>
                                <td class="item-total" data-price="<?php echo $item['price']; ?>">$<?php echo number_format($itemTotal, 2); ?></td>
                                <td><button class="btn btn-danger" onclick="removeItem(<?php echo $item['id']; ?>)">Remove</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <h4>Total: <span id="total-price">$<?php echo number_format($totalPrice, 2); ?></span></h4>
                        <button class="btn btn-primary" id="checkout-btn">Proceed to Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5">
        <p>&copy; 2023 TechNest. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="cart.js"></script>
</body>
</html>
