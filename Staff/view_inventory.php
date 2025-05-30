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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin-categories.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="icon" href="../assets/images/favicon.png">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
        // Load user name
        fetch('/../API/fetch_user_name.php')
            .then(res => res.json())
            .then(data => {
                document.getElementById('user-name').textContent = data.name;
            })
            .catch(err => {
                console.error('Error fetching name:', err);
            });
    </script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-tachometer-alt"></i>
                TechNest
            </div>
            
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search for...">
            </div>
            
            <div class="header-actions">
                <div class="notification-badge">
                    <i class="fas fa-bell"></i>
                </div>
                
                <div class="notification-badge">
                    <i class="fas fa-envelope"></i>
                </div>
                
                <div class="user-profile">
                    <span id="user-name">Loading...</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-section">
                <a href="staff_dashboard.php" class="sidebar-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-title">Management</div>
                <a href="manage_products.php" class="sidebar-item"><i class="fas fa-box"></i><span>Product Management</span></a>
                <a href="view_users.php" class="sidebar-item"><i class="fas fa-users"></i><span>Users List</span></a>
                <a href="view_inventory.php" class="sidebar-item active"><i class="fas fa-warehouse"></i><span>Inventory Lists</span></a>
            </div>
        </div>
        <div class="sidebar-footer">
            <div class="sidebar-item" id="logoutBtn"><i class="fas fa-sign-out-alt"></i><span>Logout</span></div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Inventory</h1>
            <p class="page-subtitle">View Inventory with ease</p>
        </div>
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Inventory Records</h2>
            </div>
            <div id="table-content">
                <table id="inventoryTable" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Buyer</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price (₱)</th>
                            <th>Order ID</th>
                            <th>Current Stock</th>
                            <th>Purchased At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($inventory) === 0): ?>
                            <tr><td colspan="8" class="text-center">No inventory logs found.</td></tr>
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
        </div>
    </main>

    <script>
        $(document).ready(function () {
            $('#inventoryTable').DataTable();
        });

        document.getElementById('logoutBtn').addEventListener('click', async () => {
            await fetch('../API/logout.php', { method: 'POST' });
            window.location.href = '../Public/login.php';
        });

        fetch('../API/fetch_user_name.php')
            .then(res => res.json())
            .then(data => {
                if (data.error) return console.error(data.error);
                document.getElementById('user-name').textContent = `${data.name} (${data.role})`;
            })
            .catch(err => console.error('Error fetching user info:', err));
    </script>
</body>
</html>
