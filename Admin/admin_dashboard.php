<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SB Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .logo i {
            margin-right: 0.5rem;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem;
            border-radius: 50%;
        }

        .search-container {
            flex: 1;
            max-width: 400px;
            margin: 0 2rem;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: none;
            border-radius: 25px;
            background: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            background: white;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.3);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-badge {
            position: relative;
            padding: 0.5rem;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-badge:hover {
            background: rgba(255,255,255,0.2);
            transform: scale(1.1);
        }

        .badge-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: rgba(255,255,255,0.1);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 80px;
            bottom: 0;
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            overflow-y: auto;
            z-index: 999;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-section {
            margin-bottom: 2rem;
        }

        .sidebar-title {
            padding: 0 1.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.7);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            cursor: pointer;
        }

        .sidebar-item:hover, .sidebar-item.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #feca57;
            transform: translateX(5px);
        }

        .sidebar-item i {
            margin-right: 1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-expandable {
            position: relative;
        }

        .sidebar-expandable::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1.5rem;
            transition: transform 0.3s ease;
        }

        .sidebar-expandable.expanded::after {
            transform: rotate(180deg);
        }

        .sidebar-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0,0,0,0.1);
        }

        .sidebar-submenu.expanded {
            max-height: 200px;
        }

        .sidebar-submenu .sidebar-item {
            padding-left: 3.5rem;
            font-size: 0.9rem;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            background: rgba(0,0,0,0.2);
            text-align: center;
        }

        .upgrade-btn {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .upgrade-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 80px;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .dashboard-title {
            font-size: 2rem;
            color: #2c3e50;
            font-weight: 300;
        }

        .generate-report-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .generate-report-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .stat-card.earnings-monthly {
            border-left-color: #667eea;
        }

        .stat-card.earnings-annual {
            border-left-color: #2ed573;
        }

        .stat-card.tasks {
            border-left-color: #1e90ff;
        }

        .stat-card.pending {
            border-left-color: #ffa502;
        }

        .stat-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .stat-icon {
            float: right;
            font-size: 2rem;
            color: #ecf0f1;
            margin-top: -1rem;
        }

        .progress-bar {
            background: #ecf0f1;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 1rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #1e90ff, #00bfff);
            width: 50%;
            border-radius: 4px;
            animation: progressAnimation 2s ease-in-out;
        }

        @keyframes progressAnimation {
            from { width: 0; }
            to { width: 50%; }
        }

        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-title {
            font-size: 1.2rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .chart-menu {
            color: #7f8c8d;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .chart-menu:hover {
            background: #ecf0f1;
            color: #2c3e50;
        }

        /* Line Chart */
        .line-chart {
            height: 300px;
            position: relative;
            overflow: hidden;
        }

        .chart-canvas {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .chart-line {
            stroke: #667eea;
            stroke-width: 3;
            fill: none;
            filter: drop-shadow(0 2px 4px rgba(102, 126, 234, 0.3));
        }

        .chart-area {
            fill: url(#gradient);
            opacity: 0.1;
        }

        .chart-point {
            fill: #667eea;
            stroke: white;
            stroke-width: 3;
            r: 5;
            transition: all 0.3s ease;
        }

        .chart-point:hover {
            r: 8;
            filter: drop-shadow(0 2px 8px rgba(102, 126, 234, 0.5));
        }

        /* Donut Chart */
        .donut-chart {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .donut-svg {
            width: 200px;
            height: 200px;
        }

        .donut-segment {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .donut-segment:hover {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        /* Footer */
        .footer {
            background: white;
            padding: 1.5rem 2rem;
            margin-left: 250px;
            margin-top: 2rem;
            border-top: 1px solid #ecf0f1;
            color: #7f8c8d;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .footer {
                margin-left: 0;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-tachometer-alt"></i>
                SB ADMIN
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
                    <span>Douglas McGee</span>
                    <div class="user-avatar">DM</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-section">
            <div class="sidebar-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Interface</div>
            
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)">
                <i class="fas fa-puzzle-piece"></i>
                <span>Components</span>
            </div>
            <div class="sidebar-submenu">
                <a href="#" class="sidebar-item">Buttons</a>
                <a href="#" class="sidebar-item">Cards</a>
                <a href="#" class="sidebar-item">Forms</a>
            </div>
            
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)">
                <i class="fas fa-wrench"></i>
                <span>Utilities</span>
            </div>
            <div class="sidebar-submenu">
                <a href="#" class="sidebar-item">Colors</a>
                <a href="#" class="sidebar-item">Borders</a>
                <a href="#" class="sidebar-item">Animations</a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Addons</div>
            
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)">
                <i class="fas fa-folder"></i>
                <span>Pages</span>
            </div>
            <div class="sidebar-submenu">
                <a href="#" class="sidebar-item">Login</a>
                <a href="#" class="sidebar-item">Register</a>
                <a href="#" class="sidebar-item">Forgot Password</a>
            </div>
            
            <div class="sidebar-item">
                <i class="fas fa-chart-area"></i>
                <span>Charts</span>
            </div>
            
            <div class="sidebar-item">
                <i class="fas fa-table"></i>
                <span>Tables</span>
            </div>
        </div>

        <div class="sidebar-footer">
            <i class="fas fa-rocket" style="font-size: 2rem; margin-bottom: 1rem; color: #feca57;"></i>
            <p style="margin-bottom: 1rem; font-size: 0.9rem; opacity: 0.8;">
                SB Admin Pro is packed with premium features, components, and more!
            </p>
            <a href="#" class="upgrade-btn">Upgrade to Pro!</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard</h1>
            <button class="generate-report-btn">
                <i class="fas fa-download"></i>
                Generate Report
            </button>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card earnings-monthly">
                <div class="stat-label">Earnings (Monthly)</div>
                <div class="stat-value">$40,000</div>
                <i class="fas fa-calendar stat-icon"></i>
            </div>
            
            <div class="stat-card earnings-annual">
                <div class="stat-label">Earnings (Annual)</div>
                <div class="stat-value">$215,000</div>
                <i class="fas fa-dollar-sign stat-icon"></i>
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
        <p>&copy; 2025 SB Admin Dashboard. Built with modern web technologies.</p>
    </footer>

    <script>
        // Sidebar functionality
        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const isExpanded = element.classList.contains('expanded');
            
            // Close all other submenus
            document.querySelectorAll('.sidebar-expandable.expanded').forEach(item => {
                if (item !== element) {
                    item.classList.remove('expanded');
                    item.nextElementSibling.classList.remove('expanded');
                }
            });
            
            // Toggle current submenu
            if (isExpanded) {
                element.classList.remove('expanded');
                submenu.classList.remove('expanded');
            } else {
                element.classList.add('expanded');
                submenu.classList.add('expanded');
            }
        }

        // Chart interactions
        document.querySelectorAll('.chart-point').forEach(point => {
            point.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.5)';
            });
            
            point.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Search functionality
        document.querySelector('.search-input').addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        document.querySelector('.search-input').addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });

        // Generate report button animation
        document.querySelector('.generate-report-btn').addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<div class="loading"></div> Generating...';
            this.disabled = true;
            
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-check"></i> Report Generated!';
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1500);
            }, 2000);
        });

        // Mobile sidebar toggle (for responsive design)
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-open');
        }

        // Add mobile menu button if needed
        if (window.innerWidth <= 768) {
            const mobileMenuBtn = document.createElement('button');
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
            `;
            mobileMenuBtn.onclick = toggleMobileSidebar;
            document.body.appendChild(mobileMenuBtn);
        }

        // Animate stats on load
        window.addEventListener('load', function() {
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach((stat, index) => {
                stat.style.opacity = '0';
                stat.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    stat.style.transition = 'all 0.6s ease';
                    stat.style.opacity = '1';
                    stat.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });

        // Notification interactions
        document.querySelectorAll('.notification-badge').forEach(badge => {
            badge.addEventListener('click', function() {
                const count = this.querySelector('.badge-count');
                if (count) {
                    count.style.animation = 'pulse 0.3s ease';
                    setTimeout(() => {
                        count.style.animation = '';
                    }, 300);
                }
            });
        });
    </script>
</body>
</html>