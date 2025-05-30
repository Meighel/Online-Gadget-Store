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
    <link rel="stylesheet" href="../assets/css/checkout.css">
    <link rel="icon" href="../assets/images/favicon.png">
    <script>
      function confirmCancel() {
        return confirm("Are you sure you want to cancel this order?");
      }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
            <p>Your order has been successfully placed!</p>
        </div>

        <div class="content">
            <div class="order-info">
                <div class="info-card">
                    <h3>Order ID</h3>
                    <div class="value"><?php echo htmlspecialchars($order['id']); ?></div>
                </div>
                <div class="info-card">
                    <h3>Total Amount</h3>
                    <div class="value">₱<?php echo number_format($order['total'], 2); ?></div>
                </div>
                <div class="info-card">
                    <h3>Order Date</h3>
                    <div class="value"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></div>
                </div>
                <div class="info-card">
                    <h3>Status</h3>
                    <div class="value"><?php echo ucfirst($order['status']); ?></div>
                </div>
            </div>

            <div class="items-section">
                <h2 class="section-title">Order Items</h2>
                <div class="items-table">
                    <div class="table-header">
                        <div>Product</div>
                        <div>Price</div>
                        <div>Quantity</div>
                        <div>Subtotal</div>
                    </div>
                    <?php foreach ($items as $item): ?>
                    <div class="table-row">
                        <div class="product-name" data-label="Product"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="price" data-label="Price">₱<?php echo number_format($item['price'], 2); ?></div>
                        <div class="quantity" data-label="Quantity"><?php echo $item['quantity']; ?></div>
                        <div class="subtotal" data-label="Subtotal">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="payment-section">
                <h2 class="section-title">Select Mode of Payment</h2>
                <form id="paymentForm" method="POST" action="../API/confirm_payment.php">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    
                    <div class="payment-options">
                        <div class="payment-option">
                            <input type="radio" id="gcash" name="payment_method" value="gcash" required>
                            <label for="gcash">
                                <span>GCash</span>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="maya" name="payment_method" value="maya" required>
                            <label for="maya">
                                <span>Maya</span>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="cod" name="payment_method" value="cod" required>
                            <label for="cod">
                                <span>Cash on Delivery (COD)</span>
                            </label>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="confirmBtn">
                            Confirm Payment
                        </button>
                        <a href="cancel_order.php?id=<?php echo $order['id']; ?>" class="btn btn-secondary" 
                           onclick="return confirm('Are you sure you want to cancel this order?')">
                            Cancel Order
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add loading state to confirm button
        document.getElementById('paymentForm').addEventListener('submit', function() {
            const confirmBtn = document.getElementById('confirmBtn');
            confirmBtn.textContent = 'Processing...';
            confirmBtn.classList.add('loading');
        });

        // Add success animation when payment option is selected
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                this.closest('.payment-option').classList.add('success-animation');
                setTimeout(() => {
                    this.closest('.payment-option').classList.remove('success-animation');
                }, 600);
            });
        });

        // Smooth scroll to payment section when page loads
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.querySelector('.payment-section').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 500);
        });
    </script>
</body>
</html>
