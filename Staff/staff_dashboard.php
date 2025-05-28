<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: ../Public/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
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




    <!-- Main Content -->
    <main class="main-content">

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card earnings-monthly">
                <div class="stat-label">Products Stocks</div>
                <div class="stat-value">10,000 <h5>remaining</h5></div>
                <i class="fas fa-calendar stat-icon"></i>
            </div>
            
            <div class="stat-card tasks">
                <div class="stat-label">Tasks</div>
                <div class="stat-value">50%</div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <i class="fas fa-clipboard-list stat-icon"></i>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-label">Pending Requests</div>
                <div class="stat-value">18</div>
                <i class="fas fa-comments stat-icon"></i>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Earnings Overview</h3>
                    <i class="fas fa-ellipsis-v chart-menu"></i>
                </div>
                <div class="line-chart">
                    <svg class="chart-canvas" viewBox="0 0 800 300">
                        <defs>
                            <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#667eea;stop-opacity:0.8" />
                                <stop offset="100%" style="stop-color:#667eea;stop-opacity:0" />
                            </linearGradient>
                        </defs>
                        
                        <path class="chart-area" d="M 50 250 L 100 220 L 200 200 L 300 180 L 400 160 L 500 140 L 600 120 L 700 80 L 750 60 L 750 300 L 50 300 Z"/>
                        <path class="chart-line" d="M 50 250 L 100 220 L 200 200 L 300 180 L 400 160 L 500 140 L 600 120 L 700 80 L 750 60"/>
                        
                        <circle class="chart-point" cx="50" cy="250" />
                        <circle class="chart-point" cx="100" cy="220" />
                        <circle class="chart-point" cx="200" cy="200" />
                        <circle class="chart-point" cx="300" cy="180" />
                        <circle class="chart-point" cx="400" cy="160" />
                        <circle class="chart-point" cx="500" cy="140" />
                        <circle class="chart-point" cx="600" cy="120" />
                        <circle class="chart-point" cx="700" cy="80" />
                        <circle class="chart-point" cx="750" cy="60" />
                    </svg>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Revenue Sources</h3>
                    <i class="fas fa-ellipsis-v chart-menu"></i>
                </div>
                <div class="donut-chart">
                    <svg class="donut-svg" viewBox="0 0 200 200">
                        <circle class="donut-segment" cx="100" cy="100" r="80" 
                                fill="none" stroke="#667eea" stroke-width="20" 
                                stroke-dasharray="150 300" stroke-dashoffset="0"/>
                        <circle class="donut-segment" cx="100" cy="100" r="80" 
                                fill="none" stroke="#2ed573" stroke-width="20" 
                                stroke-dasharray="100 300" stroke-dashoffset="-150"/>
                        <circle class="donut-segment" cx="100" cy="100" r="80" 
                                fill="none" stroke="#1dd1a1" stroke-width="20" 
                                stroke-dasharray="50 300" stroke-dashoffset="-250"/>
                    </svg>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #667eea;"></div>
                            <span>Direct</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #2ed573;"></div>
                            <span>Social</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #1dd1a1;"></div>
                            <span>Referral</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 TechNest Admin Dashboard.</p>
    </footer>

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