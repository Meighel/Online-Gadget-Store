<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-products.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="icon" href="../assets/images/favicon.png">

    <!-- JavaScript Files -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="../assets/javascript/users-list.js"></script>
    
    <style>
        /* Role Badge Styles */
        .role-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-admin {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-staff {
            background-color: #fd7e14;
            color: white;
        }
        
        .badge-client {
            background-color: #28a745;
            color: white;
        }
        
        .badge-default {
            background-color: #6c757d;
            color: white;
        }

        /* Loading and Error States */
        .loading-state, .error-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .loading-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #007bff;
        }

        .error-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dc3545;
        }

        /* Form validation styles */
        .form-input:invalid {
            border-color: #dc3545;
        }

        .form-input:valid {
            border-color: #28a745;
        }

        /* Button loading state */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

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
                    <span id="user-name">Admin</span>
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
                
                <a href="users.php" class="sidebar-item active">
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
            <h1 class="page-title">Users Management</h1>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Users</h2>
                <button class="add-btn" onclick="addUserModal()">
                    <i class="fas fa-plus"></i>
                    Add User
                </button>
            </div>
            
            <div id="table-content">
                <div class="loading-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <h3>Loading Users...</h3>
                    <p>Please wait while we fetch your users from the database.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="addUserModal">Add User</h3>
                <button class="close" onclick="closeUserModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId" name="id">

                    <div class="form-group">
                        <label class="form-label" for="userName">Name <span style="color: red;">*</span></label>
                        <input type="text" id="userName" name="name" class="form-input" required placeholder="Enter full name">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="userEmail">Email <span style="color: red;">*</span></label>
                        <input type="email" id="userEmail" name="email" class="form-input" required placeholder="Enter email address">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="userPassword">Password <span style="color: red;">*</span></label>
                        <input type="password" id="userPassword" name="password" class="form-input" required placeholder="Enter password">
                        <small class="form-text">Leave blank when editing to keep current password</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="userRole">Role <span style="color: red;">*</span></label>
                        <select id="userRole" name="role" class="form-input" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="client">Client</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeUserModal()">Cancel</button>
                <button class="btn-primary" onclick="saveUser()">Save User</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 TechNest.</p>
    </footer>
</body>
</html>