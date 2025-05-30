<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-products.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- JavaScript Files -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="../assets/javascript/products-list.js"></script>
    <script>
    fetch('../API/fetch_user_name.php')
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            document.getElementById('user-name').textContent = `${data.name} (${data.role})`;
            
            // Optional: customize UI based on role
            if (data.role === 'admin') {
                console.log("Admin is logged in");
                // Hide admin-only UI elements, etc.
            }
        })
        .catch(err => {
            console.error('Error fetching user info:', err);
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
                <!-- FIXED: Added id, name, and autocomplete attributes -->
                <input type="text" id="admin-search" name="admin-search" class="search-input" placeholder="Search for..." autocomplete="off">
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
                
                <a href="products.php" class="sidebar-item active">
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
                
                <a href="inventory.php" class="sidebar-item">
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
            <h1 class="page-title">Product Management</h1>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Product Management</h2>
                <button class="add-btn" onclick="openAddModal()">
                    <i class="fas fa-plus"></i>
                    Add Product
                </button>
            </div>
            
            <div id="table-content">
                <div class="loading-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <h3>Loading Products...</h3>
                    <p>Please wait while we fetch your products from the database.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Add Product</h3>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId" name="id">
                    
                    <div class="form-group">
                        <label class="form-label" for="productName">Product Name</label>
                        <!-- FIXED: Added autocomplete attribute -->
                        <input type="text" id="productName" name="name" class="form-input" autocomplete="off" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="productDescription">Description</label>
                        <!-- FIXED: Added autocomplete attribute with explicit form association -->
                        <textarea id="productDescription" name="description" class="form-input form-textarea" autocomplete="off" form="productForm" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="productPrice">Price ($)</label>
                        <!-- FIXED: Added autocomplete attribute -->
                        <input type="number" id="productPrice" name="price" class="form-input" step="0.01" min="0" autocomplete="off" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="productImage">Image URL</label>
                        <!-- FIXED: Added autocomplete attribute -->
                        <input type="url" id="productImage" name="image_url" class="form-input" autocomplete="url" required>
                    </div>
                    
                    <!-- Add this new field for category -->
                    <div class="form-group">
                        <label class="form-label" for="productCategory">Category</label>
                        <!-- FIXED: Added autocomplete attribute -->
                        <select id="productCategory" name="category_id" class="form-input" autocomplete="off">
                            <option value="">-- Select Category --</option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="productStocks">Stocks</label>
                        <!-- FIXED: Added autocomplete attribute -->
                        <input type="number" id="productStocks" name="stocks" class="form-input" min="0" autocomplete="off" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn-primary" onclick="saveProduct()">Save Product</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 TechNest Admin Dashboard.</p>
    </footer>
</body>
</html>