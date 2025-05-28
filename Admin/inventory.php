<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-products.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- JavaScript Files -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="../assets/javascript/inventory-list.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-tachometer-alt"></i>
                TechNest Admin
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
                <a href="admin_dashboard.php" class="sidebar-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-title">Management</div>
                <a href="products.php" class="sidebar-item">
                    <i class="fas fa-box"></i>
                    <span>Product Management</span>
                </a>
                <a href="categories.php" class="sidebar-item">
                    <i class="fas fa-tags"></i>
                    <span>Category Management</span>
                </a>
                <a href="users.php" class="sidebar-item">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
                <a href="inventory.php" class="sidebar-item active">
                    <i class="fas fa-warehouse"></i>
                    <span>Inventory Management</span>
                </a>
            </div>
        </div>
        <div class="sidebar-footer">
            <div class="sidebar-item" id="logoutBtn"">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Inventory Management</h1>
        </div>
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Inventory Records</h2>
                <button class="add-btn" onclick="openAddModal()">
                    <i class="fas fa-plus"></i>
                    Add Inventory Entry
                </button>
            </div>
            <div id="table-content">
                <div class="loading-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <h3>Loading Inventory...</h3>
                    <p>Please wait while we fetch your inventory from the database.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Add/Edit Inventory Modal -->
    <div id="inventoryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Add Inventory Entry</h3>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm">
                    <input type="hidden" id="inventoryId" name="id">
                    <div class="form-group">
                        <label for="productId">Product ID</label>
                        <input type="number" id="productId" name="product_id" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="userId">User ID</label>
                        <input type="number" id="userId" name="user_id" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="orderId">Order ID</label>
                        <input type="number" id="orderId" name="order_id" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-input" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price at Purchase</label>
                        <input type="number" id="price" name="price_at_purchase" class="form-input" step="0.01" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn-primary" onclick="saveInventory()">Save Entry</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 TechNest Admin Dashboard.</p>
    </footer>
        <script>
        document.getElementById('logoutBtn').addEventListener('click', async () => {
        await fetch('../API/logout.php', { method: 'POST' });
        window.location.href = '../Public/login.php';
        });

        fetch('../API/get_cart_count.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('cartCount').innerText = data.count || 0;
        })
        .catch(err => console.error('Failed to fetch cart count:', err));
    </script>
</body>
</html>
