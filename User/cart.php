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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/shop_styles.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">TechNest</a>
            <div class="d-flex gap-2 align-items-center">
                <a href="../Public/shop.php" class="nav-link text-white me-2">Shop</a>
                <a class="nav-link position-relative text-white" href="../User/cart.php">
                    <i class="fas fa-shopping-cart me-1"></i>
                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
                </a>
                <a href="settings.php" class="nav-link text-white me-2">Settings</a>
                <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Your Shopping Cart</h2>
        <a href="../Public/shop.php" class="btn btn-primary mb-4">Back to Shop</a>
        <form id="checkoutForm" action="checkout.php" method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
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
                        <td><input type="checkbox" name="selected_products[]" value="<?php echo $item['id']; ?>"></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <input type="number" value="<?php echo $item['quantity']; ?>" min="1" class="form-control" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                        </td>
                        <td class="item-total" data-price="<?php echo $item['price']; ?>">$<?php echo number_format($itemTotal, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-right">
                <h4>Total: <span id="total-price">$<?php echo number_format($totalPrice, 2); ?></span></h4>
                <button type="submit" class="btn btn-primary" id="checkout-btn">Proceed to Checkout</button>
            </div>
        </form>
    </div>

    <footer class="text-center mt-5">
        <p>&copy; 2023 TechNest. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        fetch('/API/get_cart_count.php')
            .then(res => res.json())
            .then(data => {
                document.getElementById('cartCount').innerText = data.count || 0;
            })
            .catch(err => console.error('Failed to fetch cart count:', err));
    </script>
    <script src="cart.js"></script>
</body>
</html>
