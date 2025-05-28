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

    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-tachometer-alt"></i>
                TechNest Staff
            </div>
            
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search for...">
            </div>
            
            <div class="header-actions">
                <div class="notification-badge">
                    <i class="fas fa-bell"></i>
                    <span class="badge-count">4</span>
                </div>
                
                <div class="notification-badge">
                    <i class="fas fa-envelope"></i>
                    <span class="badge-count">7</span>
                </div>
                
                <div class="user-profile">
                    <span>namitoki (staff)</span>
                    <div class="user-avatar">DM</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-section">
                <div class="sidebar-item <?php echo ($current_page == 'admin_dashboard' || $current_page == 'index') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Management</div>
                
                <!-- Product Management -->
                <a href="manage_products.php" class="sidebar-item <?php echo ($current_page == 'products') ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i>
                    <span>Product Management</span>
                </a>
                
                <!-- View Users -->
                <a href="view_users.php" class="sidebar-item <?php echo ($current_page == 'Users') ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Users List</span>
                </a>
                
                <!-- Inventory Management -->
                <a href="view_inventory.php" class="sidebar-item <?php echo ($current_page == 'inventory') ? 'active' : ''; ?>">
                    <i class="fas fa-warehouse"></i>
                    <span>Inventory Management</span>
                </a>
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-item"  id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </div>
        </div>
    </aside>

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
