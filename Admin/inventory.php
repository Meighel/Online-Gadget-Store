<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/products.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- JavaScript Files -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="../assets/javascript/inventory-list.js"></script>
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
    <script>
            function logout() {
            if (confirm('Are you sure you want to logout?')) {
                fetch('/../API/logout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = '/../../index.php';
                    } else {
                        alert('Logout failed. Please try again.');
                    }
                })
                .catch(err => {
                    console.error('Error during logout:', err);
                    alert('An error occurred. Please try again.');
                });
            }
        }
    </script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-tachometer-alt"></i>
                TechNest Admin
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
                    <span id="user-name">Admin User</span>
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
            <div class="sidebar-item" onclick="logout()">
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

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 TechNest Admin Dashboard.</p>
    </footer>
</body>
</html>
