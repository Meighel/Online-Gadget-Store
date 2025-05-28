<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            if (data.role === 'staff') {
                console.log("Staff is logged in");
                // Hide admin-only UI elements, etc.
            }
        })
        .catch(err => {
            console.error('Error fetching user info:', err);
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
                <a href="categories.php" class="sidebar-item <?php echo ($current_page == 'categories') ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Category Management</span>
                </a>
                
                <!-- User Management -->
                <a href="users.php" class="sidebar-item <?php echo ($current_page == 'users-list') ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
                
                <!-- Inventory Management -->
                <a href="inventory.php" class="sidebar-item <?php echo ($current_page == 'inventory') ? 'active' : ''; ?>">
                    <i class="fas fa-warehouse"></i>
                    <span>Inventory Management</span>
                </a>
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-item" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </div>
        </div>
    </aside>




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

    // Mobile sidebar toggle (for responsive design)
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.toggle('mobile-open');
            
            // Add/remove body scroll lock on mobile
            if (sidebar.classList.contains('mobile-open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        } else {
            console.warn('Sidebar element not found');
        }
    }

    // Close mobile sidebar when clicking outside
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

    // Add mobile menu button
    function addMobileMenuButton() {
        // Check if button already exists
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
            
            // Add hover effect
            mobileMenuBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
                this.style.background = 'rgba(102, 126, 234, 1)';
            });
            
            mobileMenuBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.background = 'rgba(102, 126, 234, 0.9)';
            });
            
            document.body.appendChild(mobileMenuBtn);
            
            // Add click outside listener for mobile
            document.addEventListener('click', closeMobileSidebarOnOutsideClick);
        } else {
            // Remove mobile menu button on larger screens
            const existingBtn = document.querySelector('.mobile-menu-btn');
            if (existingBtn) {
                existingBtn.remove();
            }
            
            // Remove click outside listener
            document.removeEventListener('click', closeMobileSidebarOnOutsideClick);
        }
    }

    // Chart interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Chart point interactions
        document.querySelectorAll('.chart-point').forEach(point => {
            point.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.5)';
                this.style.transition = 'transform 0.2s ease';
            });
            
            point.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

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

        // Generate report button animation
        const generateBtn = document.querySelector('.generate-report-btn');
        if (generateBtn) {
            generateBtn.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<div class="loading-spinner"></div> Generating...';
                this.disabled = true;
                this.style.opacity = '0.7';
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check"></i> Report Generated!';
                    this.style.background = '#2ed573';
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                        this.style.opacity = '1';
                        this.style.background = '';
                    }, 1500);
                }, 2000);
            });
        }

        // Animate stats on load
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

        // Initialize mobile functionality
        addMobileMenuButton();
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        addMobileMenuButton();
        
        // Close mobile sidebar if window becomes large
        if (window.innerWidth > 768) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && sidebar.classList.contains('mobile-open')) {
                sidebar.classList.remove('mobile-open');
                document.body.style.overflow = '';
            }
        }
    });

    // Add loading spinner styles
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
    `;
    document.head.appendChild(loadingSpinnerStyle);

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