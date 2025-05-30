<?php
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="dashboard_report_' . date('Y-m-d') . '.pdf"');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include your database configuration
require_once '../config/database.php'; // Adjust path as needed

// For PDF generation, you might want to use a library like TCPDF or FPDF
// For now, I'll create a simple HTML report that can be converted to PDF

try {
    // Get all dashboard data
    $currentMonth = date('Y-m');
    $currentYear = date('Y');
    
    // Get stats
    $monthlyRevenueQuery = "
        SELECT COALESCE(SUM(total_amount), 0) as revenue 
        FROM Orders 
        WHERE is_paid = 1 
        AND DATE_FORMAT(created_at, '%Y-%m') = ?
    ";
    $stmt = $pdo->prepare($monthlyRevenueQuery);
    $stmt->execute([$currentMonth]);
    $monthlyRevenue = $stmt->fetchColumn();
    
    $annualRevenueQuery = "
        SELECT COALESCE(SUM(total_amount), 0) as revenue 
        FROM Orders 
        WHERE is_paid = 1 
        AND YEAR(created_at) = ?
    ";
    $stmt = $pdo->prepare($annualRevenueQuery);
    $stmt->execute([$currentYear]);
    $annualRevenue = $stmt->fetchColumn();
    
    $totalOrdersQuery = "SELECT COUNT(*) FROM Orders";
    $totalOrders = $pdo->query($totalOrdersQuery)->fetchColumn();
    
    $paidOrdersQuery = "SELECT COUNT(*) FROM Orders WHERE is_paid = 1";
    $paidOrders = $pdo->query($paidOrdersQuery)->fetchColumn();
    
    $lowStockQuery = "SELECT COUNT(*) FROM Products WHERE stocks <= 10";
    $lowStockCount = $pdo->query($lowStockQuery)->fetchColumn();
    
    // Get recent orders
    $recentOrdersQuery = "
        SELECT 
            o.id,
            o.total_amount,
            o.is_paid,
            o.created_at,
            u.name as user_name
        FROM Orders o
        LEFT JOIN Users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
        LIMIT 20
    ";
    $stmt = $pdo->prepare($recentOrdersQuery);
    $stmt->execute();
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get product performance
    $topProductsQuery = "
        SELECT 
            p.name,
            p.price,
            p.stocks,
            COALESCE(SUM(oi.quantity), 0) as total_sold,
            COALESCE(SUM(oi.quantity * oi.price), 0) as revenue
        FROM Products p
        LEFT JOIN Order_items oi ON p.id = oi.product_id
        LEFT JOIN Orders o ON oi.order_id = o.id AND o.is_paid = 1
        GROUP BY p.id, p.name, p.price, p.stocks
        ORDER BY total_sold DESC
        LIMIT 15
    ";
    $stmt = $pdo->prepare($topProductsQuery);
    $stmt->execute();
    $topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Create HTML report
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Dashboard Report - ' . date('F Y') . '</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
            .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #667eea; padding-bottom: 20px; }
            .header h1 { color: #667eea; margin: 0; }
            .header p { margin: 5px 0; color: #666; }
            .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 30px 0; }
            .stat-card { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #667eea; }
            .stat-value { font-size: 28px; font-weight: bold; color: #667eea; margin: 10px 0; }
            .stat-label { font-size: 14px; color: #666; text-transform: uppercase; }
            .section { margin: 40px 0; }
            .section h2 { color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background: #f8f9fa; font-weight: bold; }
            .status-paid { color: #28a745; font-weight: bold; }
            .status-pending { color: #ffc107; font-weight: bold; }
            .footer { margin-top: 50px; text-align: center; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>TechNest Dashboard Report</h1>
            <p>Generated on: ' . date('F d, Y \a\t H:i:s') . '</p>
            <p>Report Period: ' . date('F Y') . '</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Monthly Revenue</div>
                <div class="stat-value">$' . number_format($monthlyRevenue, 2) . '</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Annual Revenue</div>
                <div class="stat-value">$' . number_format($annualRevenue, 2) . '</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value">' . number_format($totalOrders) . '</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Low Stock Items</div>
                <div class="stat-value">' . $lowStockCount . '</div>
            </div>
        </div>
        
        <div class="section">
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>';
    
    foreach ($recentOrders as $order) {
        $status = $order['is_paid'] ? 'Paid' : 'Pending';
        $statusClass = $order['is_paid'] ? 'status-paid' : 'status-pending';
        $customerName = $order['user_name'] ?? 'Guest User';
        
        $html .= '
                    <tr>
                        <td>#' . $order['id'] . '</td>
                        <td>' . htmlspecialchars($customerName) . '</td>
                        <td>$' . number_format($order['total_amount'], 2) . '</td>
                        <td><span class="' . $statusClass . '">' . $status . '</span></td>
                        <td>' . date('M d, Y H:i', strtotime($order['created_at'])) . '</td>
                    </tr>';
    }
    
    $html .= '
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <h2>Top Performing Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Units Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>';
    
    foreach ($topProducts as $product) {
        $html .= '
                    <tr>
                        <td>' . htmlspecialchars($product['name']) . '</td>
                        <td>$' . number_format($product['price'], 2) . '</td>
                        <td>' . $product['stocks'] . '</td>
                        <td>' . $product['total_sold'] . '</td>
                        <td>$' . number_format($product['revenue'], 2) . '</td>
                    </tr>';
    }
    
    $html .= '
                </tbody>
            </table>
        </div>
        
        <div class="footer">
            <p>&copy; 2025 TechNest Admin Dashboard - Confidential Report</p>
        </div>
    </body>
    </html>';
    
    // For now, output as HTML (you can integrate a PDF library later)
    // If you want PDF, consider using libraries like:
    // - TCPDF: composer require tecnickcom/tcpdf
    // - FPDF: composer require setasign/fpdf
    // - or use wkhtmltopdf with shell_exec
    
    // Simple approach: output HTML that can be printed as PDF
    header('Content-Type: text/html');
    echo $html;
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Report generation failed',
        'message' => $e->getMessage()
    ]);
}
?>