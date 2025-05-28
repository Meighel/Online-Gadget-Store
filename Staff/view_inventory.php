<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: ../Public/login.php");
    exit;
}

// Fetch inventory data with joins
$query = "
    SELECT 
        Inventory.id,
        Users.name AS user_name,
        Products.name AS product_name,
        Products.stocks AS current_stocks,
        Inventory.quantity,
        Inventory.price_at_purchase,
        Inventory.order_id,
        Inventory.purchased_at
    FROM Inventory
    JOIN Users ON Inventory.user_id = Users.id
    JOIN Products ON Inventory.product_id = Products.id
    ORDER BY Inventory.purchased_at DESC
";

$result = $conn->query($query);
$inventory = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Log</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <li class="nav-item"><a href="manage_products.php" class="nav-link text-white">Products</a></li>
                <li class="nav-item"><a href="view_users.php" class="nav-link text-white">Users</a></li>
                <li class="nav-item"><a href="view_inventory.php" class="nav-link text-white">Inventory</a></li>
                <li class="nav-item"><button class="btn btn-outline-light" id="logoutBtn">Logout</button></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Inventory Log</h2>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Buyer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price at Purchase (₱)</th>
                <th>Order ID</th>
                <th>Current Stock</th>
                <th>Purchased At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($inventory) === 0): ?>
                <tr><td colspan="7" class="text-center">No inventory logs found.</td></tr>
            <?php else: ?>
                <?php foreach ($inventory as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>₱<?= number_format($row['price_at_purchase'], 2) ?></td>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= $row['current_stocks'] ?></td>
                        <td><?= $row['purchased_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

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
