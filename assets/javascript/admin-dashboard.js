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