<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<link rel="stylesheet" href="../../assets/css/admin_dashboard.css">

<!-- Add the sidebar wrapper with proper ID and class -->
<aside id="sidebar" class="sidebar">
    <div class="sidebar-content">
        <div class="sidebar-section">
            <div class="sidebar-item <?php echo ($current_page == 'admin_dashboard' || $current_page == 'index') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Interface</div>
            
            <!-- Components Menu -->
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)" 
                 data-target="components-menu">
                <i class="fas fa-puzzle-piece"></i>
                <span>Components</span>
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </div>
            <div class="sidebar-submenu" id="components-menu">
                <a href="components/buttons.php" class="sidebar-item <?php echo ($current_page == 'buttons') ? 'active' : ''; ?>">
                    <i class="fas fa-hand-pointer"></i>
                    <span>Buttons</span>
                </a>
                <a href="components/cards.php" class="sidebar-item <?php echo ($current_page == 'cards') ? 'active' : ''; ?>">
                    <i class="fas fa-id-card"></i>
                    <span>Cards</span>
                </a>
                <a href="components/forms.php" class="sidebar-item <?php echo ($current_page == 'forms') ? 'active' : ''; ?>">
                    <i class="fas fa-wpforms"></i>
                    <span>Forms</span>
                </a>
                <a href="components/modals.php" class="sidebar-item <?php echo ($current_page == 'modals') ? 'active' : ''; ?>">
                    <i class="fas fa-window-restore"></i>
                    <span>Modals</span>
                </a>
            </div>
            
            <!-- Utilities Menu -->
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)" 
                 data-target="utilities-menu">
                <i class="fas fa-wrench"></i>
                <span>Utilities</span>
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </div>
            <div class="sidebar-submenu" id="utilities-menu">
                <a href="utilities/colors.php" class="sidebar-item <?php echo ($current_page == 'colors') ? 'active' : ''; ?>">
                    <i class="fas fa-palette"></i>
                    <span>Colors</span>
                </a>
                <a href="utilities/borders.php" class="sidebar-item <?php echo ($current_page == 'borders') ? 'active' : ''; ?>">
                    <i class="fas fa-border-style"></i>
                    <span>Borders</span>
                </a>
                <a href="utilities/animations.php" class="sidebar-item <?php echo ($current_page == 'animations') ? 'active' : ''; ?>">
                    <i class="fas fa-play"></i>
                    <span>Animations</span>
                </a>
                <a href="utilities/other.php" class="sidebar-item <?php echo ($current_page == 'other') ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    <span>Other</span>
                </a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Admin Management</div>
            
            <!-- User Management -->
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)" 
                 data-target="users-menu">
                <i class="fas fa-users"></i>
                <span>User Management</span>
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </div>
            <div class="sidebar-submenu" id="users-menu">
                <a href="admin/users/list.php" class="sidebar-item <?php echo ($current_page == 'users-list') ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span>All Users</span>
                </a>
                <a href="admin/users/add.php" class="sidebar-item <?php echo ($current_page == 'users-add') ? 'active' : ''; ?>">
                    <i class="fas fa-user-plus"></i>
                    <span>Add User</span>
                </a>
                <a href="admin/users/roles.php" class="sidebar-item <?php echo ($current_page == 'users-roles') ? 'active' : ''; ?>">
                    <i class="fas fa-user-shield"></i>
                    <span>User Roles</span>
                </a>
                <a href="admin/users/permissions.php" class="sidebar-item <?php echo ($current_page == 'users-permissions') ? 'active' : ''; ?>">
                    <i class="fas fa-key"></i>
                    <span>Permissions</span>
                </a>
            </div>
            
            <!-- Content Management -->
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)" 
                 data-target="content-menu">
                <i class="fas fa-file-alt"></i>
                <span>Content Management</span>
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </div>
            <div class="sidebar-submenu" id="content-menu">
                <a href="admin/content/pages.php" class="sidebar-item <?php echo ($current_page == 'content-pages') ? 'active' : ''; ?>">
                    <i class="fas fa-file"></i>
                    <span>Pages</span>
                </a>
                <a href="admin/content/posts.php" class="sidebar-item <?php echo ($current_page == 'content-posts') ? 'active' : ''; ?>">
                    <i class="fas fa-newspaper"></i>
                    <span>Posts</span>
                </a>
                <a href="admin/content/media.php" class="sidebar-item <?php echo ($current_page == 'content-media') ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i>
                    <span>Media Library</span>
                </a>
                <a href="admin/content/categories.php" class="sidebar-item <?php echo ($current_page == 'content-categories') ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
            </div>
            
            <!-- System Settings -->
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)" 
                 data-target="settings-menu">
                <i class="fas fa-cogs"></i>
                <span>System Settings</span>
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </div>
            <div class="sidebar-submenu" id="settings-menu">
                <a href="admin/settings/general.php" class="sidebar-item <?php echo ($current_page == 'settings-general') ? 'active' : ''; ?>">
                    <i class="fas fa-sliders-h"></i>
                    <span>General</span>
                </a>
                <a href="admin/settings/security.php" class="sidebar-item <?php echo ($current_page == 'settings-security') ? 'active' : ''; ?>">
                    <i class="fas fa-shield-alt"></i>
                    <span>Security</span>
                </a>
                <a href="admin/settings/email.php" class="sidebar-item <?php echo ($current_page == 'settings-email') ? 'active' : ''; ?>">
                    <i class="fas fa-envelope-open"></i>
                    <span>Email Settings</span>
                </a>
                <a href="admin/settings/backup.php" class="sidebar-item <?php echo ($current_page == 'settings-backup') ? 'active' : ''; ?>">
                    <i class="fas fa-database"></i>
                    <span>Backup & Restore</span>
                </a>
            </div>
            
            <!-- Reports & Analytics -->
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)" 
                 data-target="reports-menu">
                <i class="fas fa-chart-line"></i>
                <span>Reports & Analytics</span>
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </div>
            <div class="sidebar-submenu" id="reports-menu">
                <a href="admin/reports/dashboard.php" class="sidebar-item <?php echo ($current_page == 'reports-dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Analytics Dashboard</span>
                </a>
                <a href="admin/reports/users.php" class="sidebar-item <?php echo ($current_page == 'reports-users') ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>User Reports</span>
                </a>
                <a href="admin/reports/activity.php" class="sidebar-item <?php echo ($current_page == 'reports-activity') ? 'active' : ''; ?>">
                    <i class="fas fa-history"></i>
                    <span>Activity Logs</span>
                </a>
                <a href="admin/reports/performance.php" class="sidebar-item <?php echo ($current_page == 'reports-performance') ? 'active' : ''; ?>">
                    <i class="fas fa-rocket"></i>
                    <span>Performance</span>
                </a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Tools & Addons</div>
            
            <!-- Pages -->
            <div class="sidebar-item sidebar-expandable" onclick="toggleSubmenu(this)" 
                 data-target="pages-menu">
                <i class="fas fa-folder"></i>
                <span>Pages</span>
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </div>
            <div class="sidebar-submenu" id="pages-menu">
                <a href="pages/login.php" class="sidebar-item <?php echo ($current_page == 'login') ? 'active' : ''; ?>">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
                <a href="pages/register.php" class="sidebar-item <?php echo ($current_page == 'register') ? 'active' : ''; ?>">
                    <i class="fas fa-user-plus"></i>
                    <span>Register</span>
                </a>
                <a href="pages/forgot-password.php" class="sidebar-item <?php echo ($current_page == 'forgot-password') ? 'active' : ''; ?>">
                    <i class="fas fa-key"></i>
                    <span>Forgot Password</span>
                </a>
                <a href="pages/404.php" class="sidebar-item <?php echo ($current_page == '404') ? 'active' : ''; ?>">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>404 Page</span>
                </a>
                <a href="pages/blank.php" class="sidebar-item <?php echo ($current_page == 'blank') ? 'active' : ''; ?>">
                    <i class="fas fa-file"></i>
                    <span>Blank Page</span>
                </a>
            </div>
            
            <!-- Charts -->
            <a href="charts.php" class="sidebar-item <?php echo ($current_page == 'charts') ? 'active' : ''; ?>">
                <i class="fas fa-chart-area"></i>
                <span>Charts</span>
            </a>
            
            <!-- Tables -->
            <a href="tables.php" class="sidebar-item <?php echo ($current_page == 'tables') ? 'active' : ''; ?>">
                <i class="fas fa-table"></i>
                <span>Tables</span>
            </a>
            
            <!-- File Manager -->
            <a href="admin/file-manager.php" class="sidebar-item <?php echo ($current_page == 'file-manager') ? 'active' : ''; ?>">
                <i class="fas fa-folder-open"></i>
                <span>File Manager</span>
            </a>
            
            <!-- System Monitor -->
            <a href="admin/system-monitor.php" class="sidebar-item <?php echo ($current_page == 'system-monitor') ? 'active' : ''; ?>">
                <i class="fas fa-server"></i>
                <span>System Monitor</span>
            </a>
        </div>

        <!-- Admin Quick Actions -->
        <div class="sidebar-section">
            <div class="sidebar-title">Quick Actions</div>
            
            <div class="sidebar-item quick-action" onclick="showQuickUserAdd()">
                <i class="fas fa-user-plus"></i>
                <span>Quick Add User</span>
            </div>
            
            <div class="sidebar-item quick-action" onclick="showSystemStatus()">
                <i class="fas fa-heartbeat"></i>
                <span>System Status</span>
            </div>
            
            <div class="sidebar-item quick-action" onclick="clearCache()">
                <i class="fas fa-broom"></i>
                <span>Clear Cache</span>
            </div>
            
            <div class="sidebar-item quick-action" onclick="exportData()">
                <i class="fas fa-download"></i>
                <span>Export Data</span>
            </div>
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

<script>
// Admin-specific JavaScript functions
function showQuickUserAdd() {
    console.log('Opening quick user add modal...');
    // You can integrate with your modal system here
}

function showSystemStatus() {
    console.log('Checking system status...');
    // Ajax call to get system status
}

function clearCache() {
    if (confirm('Are you sure you want to clear the system cache?')) {
        console.log('Clearing cache...');
        // Show loading state and success message
    }
}

function exportData() {
    console.log('Exporting system data...');
    // Show export options modal
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'logout.php';
    }
}
</script>

<?php
/**
 * Helper function to check if user has admin privileges
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Helper function to get notification count for admin
 */
function getAdminNotificationCount() {
    return [
        'pending_users' => 3,
        'system_alerts' => 2,
        'backup_status' => 1,
        'security_issues' => 0
    ];
}

/**
 * Helper function to check if menu item should be visible
 */
function canAccessMenuItem($menu_item) {
    $user_permissions = $_SESSION['user_permissions'] ?? [];
    return in_array($menu_item, $user_permissions) || isAdmin();
}
?>

<style>
/* Enhanced sidebar styles */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 280px;
    height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    z-index: 1000;
    overflow-y: auto;
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
}

.sidebar-content {
    flex: 1;
    padding: 1rem 0;
}

.sidebar-footer {
    padding: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-section .sidebar-title {
    position: relative;
    padding: 1rem 1.5rem 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.7);
}

.sidebar-section .sidebar-title::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 1.5rem;
    right: 1.5rem;
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
}

.sidebar-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.sidebar-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(3px);
}

.sidebar-item.active {
    background: rgba(255, 255, 255, 0.15);
    border-right: 3px solid #fff;
}

.sidebar-item i {
    width: 20px;
    margin-right: 0.75rem;
    text-align: center;
}

.sidebar-expandable {
    position: relative;
}

.sidebar-arrow {
    position: absolute;
    right: 1.5rem;
    transition: transform 0.3s ease;
}

.sidebar-expandable.expanded .sidebar-arrow {
    transform: rotate(90deg);
}

.sidebar-submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: rgba(0, 0, 0, 0.2);
}

.sidebar-submenu.expanded {
    max-height: 500px;
}

.sidebar-submenu .sidebar-item {
    padding-left: 3.5rem;
    font-size: 0.9rem;
}

/* Quick actions styling */
.quick-action {
    background: rgba(255, 255, 255, 0.05) !important;
    margin: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.9rem;
}

.quick-action:hover {
    background: rgba(255, 255, 255, 0.15) !important;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.mobile-open {
        transform: translateX(0);
    }
}

/* Notification dots */
.sidebar-item .notification-dot {
    position: absolute;
    right: 1.5rem;
    width: 8px;
    height: 8px;
    background: #ff4757;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* Scrollbar styling */
.sidebar::-webkit-scrollbar {
    width: 4px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>