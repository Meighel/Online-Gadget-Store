<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['latest_order_id'])) {
    header("Location: ../User/cart.php");
    exit;
}

$order_id = $_SESSION['latest_order_id'];
$user_id = $_SESSION['user_id'];

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM Orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit;
}

// Fetch order items
$stmt = $conn->prepare("
    SELECT oi.*, p.name 
    FROM Order_items oi 
    JOIN Products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
      function confirmCancel() {
        return confirm("Are you sure you want to cancel this order?");
      }
    </script>
</head>
<body class="container mt-5">
    <h2>Order Confirmation</h2>
    <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
    <p><strong>Total:</strong> ₱<?= number_format($order['total_amount'], 2) ?></p>

    <h4>Items:</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>₱<?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <form method="POST" action="../API/confirm_payment.php" class="mr-2">
        <input type="hidden" name="order_id" value="<?= $order_id ?>">
        
        <h4>Select Mode of Payment</h4>
        <select class="form-control mb-3" name="payment_mode" required>
            <option value="GCash">GCash</option>
            <option value="Maya">Maya</option>
            <option value="COD">Cash on Delivery (COD)</option>
        </select>

        <button type="submit" class="btn btn-success">Confirm Payment</button>
    </form>

    <form method="POST" action="../API/cancel_order.php" onsubmit="return confirmCancel();" class="mt-3">
        <input type="hidden" name="order_id" value="<?= $order_id ?>">
        <button type="submit" class="btn btn-danger">Cancel Order</button>
    </form>

</body>
</html>
