<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 70px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .search-container {
            position: relative;
            max-width: 400px;
            flex: 1;
            margin: 0 2rem;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 25px;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-badge {
            position: relative;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .notification-badge:hover {
            background: #f0f0f0;
        }

        .user-profile {
            font-weight: 500;
            color: #333;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 70px;
            bottom: 0;
            width: 260px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 999;
            display: flex;
            flex-direction: column;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .sidebar-section {
            margin-bottom: 1.5rem;
        }

        .sidebar-title {
            padding: 0 1.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #666;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sidebar-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .sidebar-item.active {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-right: 3px solid #667eea;
        }

        .sidebar-item i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        .sidebar-footer {
            border-top: 1px solid #e0e0e0;
            padding: 1rem 0;
        }

        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .dashboard-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .generate-report-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .generate-report-btn:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .stat-change {
            font-size: 0.8rem;
            color: #28a745;
        }

        .stat-subtext {
            font-size: 0.8rem;
            color: #666;
        }

        .stat-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 2rem;
            color: rgba(102, 126, 234, 0.2);
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .chart-menu {
            color: #999;
            cursor: pointer;
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .analytics-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .analytics-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .analytics-header h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #333;
        }

        .view-all-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .view-all-link:hover {
            text-decoration: underline;
        }

        .recent-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .recent-item:last-child {
            border-bottom: none;
        }

        .item-info {
            display: flex;
            flex-direction: column;
        }

        .item-name {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .item-detail {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .item-value {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .amount {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .status {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            margin-top: 0.25rem;
        }

        .status.paid {
            background: #d4edda;
            color: #155724;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .sales {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .footer {
            background: white;
            padding: 1rem 2rem;
            margin-left: 260px;
            text-align: center;
            color: #666;
            border-top: 1px solid #e0e0e0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .footer {
                margin-left: 0;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                    <span id="user-name">Admin User</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-section">
                <div class="sidebar-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Management</div>
                
                <!-- Product Management -->
                <a href="products.php" class="sidebar-item">
                    <i class="fas fa-box"></i>
                    <span>Product Management</span>
                </a>
                
                <!-- Category Management -->
                <a href="categories.php" class="sidebar-item">
                    <i class="fas fa-tags"></i>
                    <span>Category Management</span>
                </a>
                
                <!-- User Management -->
                <a href="users.php" class="sidebar-item">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
                
                <!-- Inventory Management -->
                <a href="inventory.php" class="sidebar-item">
                    <i class="fas fa-warehouse"></i>
                    <span>Inventory Management</span>
                </a>
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-item" onclick="logout()">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard</h1>
            <button class="generate-report-btn" onclick="generateReport()">
                <i class="fas fa-download"></i>
                Generate Report
            </button>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card earnings-monthly">
                <div class="stat-label">Revenue (Monthly)</div>
                <div class="stat-value">₱487,350</div>
                <div class="stat-change">+12.5% from last month</div>
                <i class="fas fa-calendar stat-icon"></i>
            </div>
            
            <div class="stat-card earnings-annual">
                <div class="stat-label">Revenue (Annual)</div>
                <div class="stat-value">₱5,248,200</div>
                <div class="stat-change">+18.2% from last year</div>
                <i class="fas fa-dollar-sign stat-icon"></i>
            </div>
            
            <div class="stat-card tasks">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value">2,847</div>
                <div class="stat-subtext">2,156 paid, 691 pending</div>
                <i class="fas fa-shopping-cart stat-icon"></i>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-label">Low Stock Items</div>
                <div class="stat-value">23</div>
                <div class="stat-subtext">Items with stock ≤ 10</div>
                <i class="fas fa-exclamation-triangle stat-icon"></i>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Monthly Revenue Trend</h3>
                    <i class="fas fa-ellipsis-v chart-menu"></i>
                </div>
                <div id="revenueChart" style="height: 300px; width: 100%;"></div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Product Categories</h3>
                    <i class="fas fa-ellipsis-v chart-menu"></i>
                </div>
                <div id="categoryChart" style="height: 300px; width: 100%;"></div>
            </div>
        </div>

        <!-- Additional Analytics -->
        <div class="analytics-grid">
            <div class="analytics-card">
                <div class="analytics-header">
                    <h3>Recent Orders</h3>
                    <a href="orders.php" class="view-all-link">View All</a>
                </div>
                <div class="recent-orders">
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">Order #1847</span>
                            <span class="item-detail">Juan Dela Cruz</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱15,750.00</span>
                            <span class="status paid">Paid</span>
                        </div>
                    </div>
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">Order #1846</span>
                            <span class="item-detail">Maria Santos</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱8,990.00</span>
                            <span class="status pending">Pending</span>
                        </div>
                    </div>
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">Order #1845</span>
                            <span class="item-detail">Carlos Rodriguez</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱22,450.00</span>
                            <span class="status paid">Paid</span>
                        </div>
                    </div>
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">Order #1844</span>
                            <span class="item-detail">Ana Garcia</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱12,300.00</span>
                            <span class="status paid">Paid</span>
                        </div>
                    </div>
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">Order #1843</span>
                            <span class="item-detail">Miguel Torres</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱6,850.00</span>
                            <span class="status pending">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="analytics-card">
                <div class="analytics-header">
                    <h3>Top Products</h3>
                    <a href="products.php" class="view-all-link">View All</a>
                </div>
                <div class="top-products">
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">iPhone 15 Pro Max</span>
                            <span class="item-detail">Stock: 45</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱89,990.00</span>
                            <span class="sales">127 sold</span>
                        </div>
                    </div>
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">Samsung Galaxy S24 Ultra</span>
                            <span class="item-detail">Stock: 32</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱79,990.00</span>
                            <span class="sales">89 sold</span>
                        </div>
                    </div>
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">MacBook Air M3</span>
                            <span class="item-detail">Stock: 18</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱79,990.00</span>
                            <span class="sales">76 sold</span>
                        </div>
                    </div>
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">AirPods Pro 2</span>
                            <span class="item-detail">Stock: 156</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱14,990.00</span>
                            <span class="sales">234 sold</span>
                        </div>
                    </div>
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">iPad Pro 12.9"</span>
                            <span class="item-detail">Stock: 28</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">₱69,990.00</span>
                            <span class="sales">54 sold</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 TechNest Online Gadget Store.</p>
    </footer>

    <script>
        // Static chart data
        const staticData = {
            revenueData: [
                { month: "Jan", revenue: 385000 },
                { month: "Feb", revenue: 420000 },
                { month: "Mar", revenue: 395000 },
                { month: "Apr", revenue: 465000 },
                { month: "May", revenue: 487350 },
                { month: "Jun", revenue: 445000 }
            ],
            categoryData: [
                { name: "Smartphones", count: 45, color: "#667eea" },
                { name: "Laptops", count: 28, color: "#2ed573" },
                { name: "Accessories", count: 156, color: "#ffa502" },
                { name: "Tablets", count: 32, color: "#ff4757" },
                { name: "Audio", count: 78, color: "#5352ed" }
            ]
        };

        // Initialize charts
        function initializeCharts() {
            // Revenue trend chart
            const revenueChart = new CanvasJS.Chart("revenueChart", {
                animationEnabled: true,
                theme: "light2",
                backgroundColor: "transparent",
                title: {
                    text: "",
                    fontSize: 16
                },
                axisY: {
                    title: "Revenue (₱)",
                    prefix: "₱",
                    gridColor: "#e8e8e8",
                    labelFormatter: function(e) {
                        return "₱" + (e.value / 1000) + "K";
                    }
                },
                axisX: {
                    title: "Month",
                    gridColor: "#e8e8e8"
                },
                data: [{
                    type: "splineArea",
                    color: "rgba(102, 126, 234, 0.8)",
                    markerSize: 5,
                    dataPoints: staticData.revenueData.map(item => ({
                        label: item.month,
                        y: item.revenue
                    }))
                }]
            });
            revenueChart.render();

            // Category distribution chart
            const categoryChart = new CanvasJS.Chart("categoryChart", {
                animationEnabled: true,
                theme: "light2",
                backgroundColor: "transparent",
                title: {
                    text: "",
                    fontSize: 16
                },
                data: [{
                    type: "doughnut",
                    startAngle: 60,
                    innerRadius: 60,
                    indexLabelFontSize: 12,
                    indexLabel: "{label} - #percent%",
                    toolTipContent: "<b>{label}:</b> {y} products (#percent%)",
                    dataPoints: staticData.categoryData.map(item => ({
                        y: item.count,
                        label: item.name,
                        color: item.color
                    }))
                }]
            });
            categoryChart.render();
        }

        // Generate report functionality
        function generateReport() {
            const btn = document.querySelector('.generate-report-btn');
            const originalContent = btn.innerHTML;
            
            btn.innerHTML = '<div class="loading-spinner"></div> Generating...';
            btn.disabled = true;
            btn.style.opacity = '0.7';
            
            // Simulate report generation
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> Report Generated!';
                btn.style.background = '#2ed573';
                
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.background = '';
                }, 2000);
            }, 2000);
        }

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                alert('Logged out successfully!');
                // In a real application, this would redirect to login page
                // window.location.href = '/login.php';
            }
        }

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            
            // Add loading spinner styles
            const style = document.createElement('style');
            style.textContent = `
                .loading-spinner {
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    border: 2px solid rgba(255,255,255,0.3);
                    border-radius: 50%;
                    border-top-color: #fff;
                    animation: spin 1s ease-in-out infinite;
                }
                
                @keyframes spin {
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>