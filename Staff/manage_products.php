<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: ../Public/login.php");
    exit;
}

// Fetch products
$result = $conn->query("SELECT * FROM Products");
$products = $result->fetch_all(MYSQLI_ASSOC);
$current_page = 'products';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - TechNest Staff</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="../assets/css/staff_products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="../assets/images/favicon.png">
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
                <i class="fas fa-tachometer-alt"></i> TechNest Staff
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
                
                <a href="manage_products.php" class="sidebar-item">
                    <i class="fas fa-box"></i>
                    <span>Product Management</span>
                </a>
            
                
                <a href="view_users.php" class="sidebar-item">
                    <i class="fas fa-users"></i>
                    <span>Users List</span>
                </a>
                
                <a href="view_inventory.php" class="sidebar-item">
                    <i class="fas fa-warehouse"></i>
                    <span>Inventory List</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-item" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </div>
        </div>
    </aside>

       <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Product Management</h1>
            <p class="page-subtitle">Manage your product inventory with ease</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h4><?= count($products) ?></h4>
                    <p>Total Products</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stock">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="stat-info">
                    <h4><?= array_sum(array_column($products, 'stocks')) ?></h4>
                    <p>Total Stock</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon value">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h4>$<?= number_format(array_sum(array_map(function($p) { return $p['price'] * $p['stocks']; }, $products)), 2) ?></h4>
                    <p>Total Value</p>
                </div>
            </div>
        </div>

        <!-- Add Product Section -->
        <div class="add-product-section">
            <h3 class="section-title">
                <i class="fas fa-plus-circle"></i>
                Add New Product
            </h3>
            <form action="../API/staff_crud_products.php" method="POST" id="addProductForm">
                <input type="hidden" name="action" value="create">
                <div class="form-row">
                    <div class="col">
                        <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568;">Product Name</label>
                        <input name="name" id="name" class="form-control" placeholder="Enter product name" required>
                    </div>
                    <div class="col">
                        <label for="price" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568;">Price</label>
                        <input name="price" id="price" class="form-control" type="number" step="0.01" placeholder="0.00" required>
                    </div>
                    <div class="col">
                        <label for="stocks" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568;">Stock</label>
                        <input name="stocks" id="stocks" class="form-control" type="number" placeholder="0" required>
                    </div>
                    <div class="col">
                        <label for="image" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568;">Image URL</label>
                        <input name="image" id="image" class="form-control" type="text" placeholder="https://...">
                    </div>
                    <div class="col">
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-plus"></i>
                            Add Product
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Product Table Section -->
        <div class="table-section">
            <div class="table-header">
                <h3>
                    <i class="fas fa-list"></i>
                    Product Inventory
                </h3>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Image URL</th>
                        <th>Preview</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <form action="../API/staff_crud_products.php" method="POST" class="product-form">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="action" value="update">
                                <td><strong><?= $product['id'] ?></strong></td>
                                <td><input name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required></td>
                                <td><input name="price" type="number" step="0.01" value="<?= $product['price'] ?>" class="form-control" required></td>
                                <td><input name="stocks" type="number" value="<?= $product['stocks'] ?>" class="form-control" required></td>
                                <td><input name="image" type="text" value="<?= htmlspecialchars($product['image']) ?>" class="form-control"></td>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" width="60" height="60" class="product-image">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #a0aec0;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" type="submit">
                                        <i class="fas fa-save"></i>
                                        Update
                                    </button>
                                    <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Script -->
    <!--<script src="../assets/javascript/products-list.js"></script>-->
    <script>
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            await fetch('../API/logout.php', { method: 'POST' });
            window.location.href = '../Public/login.php';
        });

        // Enhanced form handling
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        });

        // Enhanced product form handling
        document.querySelectorAll('.product-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (e.submitter.value === 'update') {
                    const submitBtn = e.submitter;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                }
            });
        });
    </script>
</body>
</html>