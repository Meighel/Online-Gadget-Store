<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js"></script>
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
                    <span class="badge-count" id="notification-count">0</span>
                </div>
                
                <div class="notification-badge">
                    <i class="fas fa-envelope"></i>
                    <span class="badge-count" id="message-count">0</span>
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
                <div class="sidebar-item <?php echo ($current_page == 'admin_dashboard' || $current_page == 'index') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Management</div>
                
                <!-- Product Management -->
                <a href="products.php" class="sidebar-item <?php echo ($current_page == 'products') ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i>
                    <span>Product Management</span>
                </a>
                
                <!-- Category Management -->
                <a href="admin/categories.php" class="sidebar-item <?php echo ($current_page == 'categories') ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Category Management</span>
                </a>
                
                <!-- User Management -->
                <a href="admin/users/list.php" class="sidebar-item <?php echo ($current_page == 'users-list') ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
                
                <!-- Inventory Management -->
                <a href="admin/inventory.php" class="sidebar-item <?php echo ($current_page == 'inventory') ? 'active' : ''; ?>">
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
                <div class="stat-value" id="monthly-revenue">Loading...</div>
                <div class="stat-change" id="monthly-change"></div>
                <i class="fas fa-calendar stat-icon"></i>
            </div>
            
            <div class="stat-card earnings-annual">
                <div class="stat-label">Revenue (Annual)</div>
                <div class="stat-value" id="annual-revenue">Loading...</div>
                <div class="stat-change" id="annual-change"></div>
                <i class="fas fa-dollar-sign stat-icon"></i>
            </div>
            
            <div class="stat-card tasks">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value" id="total-orders">Loading...</div>
                <div class="stat-subtext"><span id="paid-orders">0</span> paid, <span id="unpaid-orders">0</span> pending</div>
                <i class="fas fa-shopping-cart stat-icon"></i>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-label">Low Stock Items</div>
                <div class="stat-value" id="low-stock-count">Loading...</div>
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
                <div class="recent-orders" id="recent-orders">
                    Loading...
                </div>
            </div>
            
            <div class="analytics-card">
                <div class="analytics-header">
                    <h3>Top Products</h3>
                    <a href="products.php" class="view-all-link">View All</a>
                </div>
                <div class="top-products" id="top-products">
                    Loading...
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 TechNest Admin Dashboard.</p>
    </footer>

    <script>
        // Dashboard Data Management
        let dashboardData = {};

        // Load all dashboard data
        async function loadDashboardData() {
            try {
                // Load stats
                const statsResponse = await fetch('/API/dashboard_stats.php');
                const stats = await statsResponse.json();
                
                // Load chart data
                const chartsResponse = await fetch('/API/dashboard_charts.php');
                const charts = await chartsResponse.json();
                
                // Load recent data
                const recentResponse = await fetch('/API/dashboard_recent.php');
                const recent = await recentResponse.json();
                
                dashboardData = { stats, charts, recent };
                
                updateDashboard();
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                showErrorMessage('Failed to load dashboard data');
            }
        }

        // Update dashboard with loaded data
        function updateDashboard() {
            updateStats();
            updateCharts();
            updateRecentData();
        }

        // Update statistics cards
        function updateStats() {
            const { stats } = dashboardData;
            
            // Monthly revenue
            document.getElementById('monthly-revenue').textContent = 
                '$' + Number(stats.monthly_revenue || 0).toLocaleString();
            
            // Annual revenue
            document.getElementById('annual-revenue').textContent = 
                '$' + Number(stats.annual_revenue || 0).toLocaleString();
            
            // Total orders
            document.getElementById('total-orders').textContent = stats.total_orders || 0;
            document.getElementById('paid-orders').textContent = stats.paid_orders || 0;
            document.getElementById('unpaid-orders').textContent = stats.unpaid_orders || 0;
            
            // Low stock count
            document.getElementById('low-stock-count').textContent = stats.low_stock_count || 0;
            
            // Update notification counts
            document.getElementById('notification-count').textContent = stats.low_stock_count || 0;
            document.getElementById('message-count').textContent = stats.unpaid_orders || 0;
        }

        // Update charts
        function updateCharts() {
            const { charts } = dashboardData;
            
            // Revenue trend chart
            if (charts.revenue_trend) {
                const revenueChart = new CanvasJS.Chart("revenueChart", {
                    animationEnabled: true,
                    theme: "light2",
                    backgroundColor: "transparent",
                    title: {
                        text: "",
                        fontSize: 16
                    },
                    axisY: {
                        title: "Revenue ($)",
                        prefix: "$",
                        gridColor: "#e8e8e8"
                    },
                    axisX: {
                        title: "Month",
                        gridColor: "#e8e8e8"
                    },
                    data: [{
                        type: "splineArea",
                        color: "rgba(102, 126, 234, 0.8)",
                        markerSize: 5,
                        dataPoints: charts.revenue_trend.map(item => ({
                            label: item.month,
                            y: parseFloat(item.revenue)
                        }))
                    }]
                });
                revenueChart.render();
            }
            
            // Category distribution chart
            if (charts.category_distribution) {
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
                        dataPoints: charts.category_distribution.map((item, index) => ({
                            y: parseInt(item.count),
                            label: item.name,
                            color: getChartColor(index)
                        }))
                    }]
                });
                categoryChart.render();
            }
        }

        // Update recent data sections
        function updateRecentData() {
            const { recent } = dashboardData;
            
            // Recent orders
            if (recent.orders) {
                const ordersHtml = recent.orders.map(order => `
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">Order #${order.id}</span>
                            <span class="item-detail">${order.user_name}</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">$${Number(order.total_amount).toFixed(2)}</span>
                            <span class="status ${order.is_paid ? 'paid' : 'pending'}">
                                ${order.is_paid ? 'Paid' : 'Pending'}
                            </span>
                        </div>
                    </div>
                `).join('');
                document.getElementById('recent-orders').innerHTML = ordersHtml || '<p>No recent orders</p>';
            }
            
            // Top products
            if (recent.products) {
                const productsHtml = recent.products.map(product => `
                    <div class="recent-item">
                        <div class="item-info">
                            <span class="item-name">${product.name}</span>
                            <span class="item-detail">Stock: ${product.stocks}</span>
                        </div>
                        <div class="item-value">
                            <span class="amount">$${Number(product.price).toFixed(2)}</span>
                            <span class="sales">${product.total_sold || 0} sold</span>
                        </div>
                    </div>
                `).join('');
                document.getElementById('top-products').innerHTML = productsHtml || '<p>No products available</p>';
            }
        }

        // Generate chart colors
        function getChartColor(index) {
            const colors = [
                "#667eea", "#2ed573", "#1dd1a1", "#ffa502", 
                "#ff4757", "#5352ed", "#70a1ff", "#ff9ff3"
            ];
            return colors[index % colors.length];
        }

        // Generate report functionality
        function generateReport() {
            const btn = document.querySelector('.generate-report-btn');
            const originalContent = btn.innerHTML;
            
            btn.innerHTML = '<div class="loading-spinner"></div> Generating...';
            btn.disabled = true;
            btn.style.opacity = '0.7';
            
            fetch('/API/generate_report.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    type: 'dashboard',
                    data: dashboardData
                })
            })
            .then(response => response.blob())
            .then(blob => {
                // Create download link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `dashboard_report_${new Date().toISOString().split('T')[0]}.pdf`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                
                btn.innerHTML = '<i class="fas fa-check"></i> Report Generated!';
                btn.style.background = '#2ed573';
                
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.background = '';
                }, 2000);
            })
            .catch(error => {
                console.error('Error generating report:', error);
                btn.innerHTML = '<i class="fas fa-times"></i> Error!';
                btn.style.background = '#ff4757';
                
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.background = '';
                }, 2000);
            });
        }

        // Error message display
        function showErrorMessage(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                ${message}
                <button onclick="this.parentElement.remove()">×</button>
            `;
            errorDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #ff4757;
                color: white;
                padding: 1rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                z-index: 1000;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            `;
            document.body.appendChild(errorDiv);
            
            setTimeout(() => {
                if (document.body.contains(errorDiv)) {
                    errorDiv.remove();
                }
            }, 5000);
        }

        // Auto-refresh data every 5 minutes
        setInterval(loadDashboardData, 5 * 60 * 1000);

        // Sidebar functionality (keeping original code)
        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const isExpanded = element.classList.contains('expanded');
            
            document.querySelectorAll('.sidebar-expandable.expanded').forEach(item => {
                if (item !== element) {
                    item.classList.remove('expanded');
                    item.nextElementSibling.classList.remove('expanded');
                }
            });
            
            if (isExpanded) {
                element.classList.remove('expanded');
                submenu.classList.remove('expanded');
            } else {
                element.classList.add('expanded');
                submenu.classList.add('expanded');
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('mobile-open');
                
                if (sidebar.classList.contains('mobile-open')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
        }

        function closeMobileSidebarOnOutsideClick(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            
            if (sidebar && sidebar.classList.contains('mobile-open')) {
                if (!sidebar.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    sidebar.classList.remove('mobile-open');
                    document.body.style.overflow = '';
                }
            }
        }

        function addMobileMenuButton() {
            if (document.querySelector('.mobile-menu-btn')) {
                return;
            }
            
            if (window.innerWidth <= 768) {
                const mobileMenuBtn = document.createElement('button');
                mobileMenuBtn.className = 'mobile-menu-btn';
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                mobileMenuBtn.style.cssText = `
                    position: fixed;
                    top: 20px;
                    left: 20px;
                    z-index: 1001;
                    background: rgba(102, 126, 234, 0.9);
                    color: white;
                    border: none;
                    padding: 0.75rem;
                    border-radius: 50%;
                    cursor: pointer;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                    transition: all 0.3s ease;
                    backdrop-filter: blur(10px);
                `;
                
                mobileMenuBtn.onclick = toggleMobileSidebar;
                document.body.appendChild(mobileMenuBtn);
                document.addEventListener('click', closeMobileSidebarOnOutsideClick);
            } else {
                const existingBtn = document.querySelector('.mobile-menu-btn');
                if (existingBtn) {
                    existingBtn.remove();
                }
                document.removeEventListener('click', closeMobileSidebarOnOutsideClick);
            }
        }

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

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            addMobileMenuButton();
            
            // Search functionality
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                    this.parentElement.style.transition = 'transform 0.2s ease';
                });

                searchInput.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            }
        });

        window.addEventListener('resize', function() {
            addMobileMenuButton();
            
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar && sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    document.body.style.overflow = '';
                }
            }
        });

        // Loading spinner styles
        const loadingSpinnerStyle = document.createElement('style');
        loadingSpinnerStyle.textContent = `
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

            .analytics-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.5rem;
                margin-top: 2rem;
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

            .stat-change, .stat-subtext {
                font-size: 0.8rem;
                color: #666;
                margin-top: 0.25rem;
            }
        `;
        document.head.appendChild(loadingSpinnerStyle);
    </script>
</body>
</html>