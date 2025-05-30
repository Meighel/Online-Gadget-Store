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
