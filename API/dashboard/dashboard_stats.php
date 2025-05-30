<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Include your database configuration
require_once '../config/database.php'; // Adjust path as needed

try {
    // Get current date info
    $currentMonth = date('Y-m');
    $currentYear = date('Y');
    $lastMonth = date('Y-m', strtotime('-1 month'));
    $lastYear = date('Y', strtotime('-1 year'));
    
    // Monthly Revenue
    $monthlyRevenueQuery = "
        SELECT COALESCE(SUM(total_amount), 0) as revenue 
        FROM Orders 
        WHERE is_paid = 1 
        AND DATE_FORMAT(created_at, '%Y-%m') = ?
    ";
    $stmt = $pdo->prepare($monthlyRevenueQuery);
    $stmt->execute([$currentMonth]);
    $monthlyRevenue = $stmt->fetchColumn();
    
    // Previous month revenue for comparison
    $stmt->execute([$lastMonth]);
    $lastMonthRevenue = $stmt->fetchColumn();
    
    // Annual Revenue
    $annualRevenueQuery = "
        SELECT COALESCE(SUM(total_amount), 0) as revenue 
        FROM Orders 
        WHERE is_paid = 1 
        AND YEAR(created_at) = ?
    ";
    $stmt = $pdo->prepare($annualRevenueQuery);
    $stmt->execute([$currentYear]);
    $annualRevenue = $stmt->fetchColumn();
    
    // Previous year revenue for comparison
    $stmt->execute([$lastYear]);
    $lastYearRevenue = $stmt->fetchColumn();
    
    // Total Orders
    $totalOrdersQuery = "SELECT COUNT(*) FROM Orders";
    $totalOrders = $pdo->query($totalOrdersQuery)->fetchColumn();
    
    // Paid Orders
    $paidOrdersQuery = "SELECT COUNT(*) FROM Orders WHERE is_paid = 1";
    $paidOrders = $pdo->query($paidOrdersQuery)->fetchColumn();
    
    // Unpaid Orders
    $unpaidOrdersQuery = "SELECT COUNT(*) FROM Orders WHERE is_paid = 0";
    $unpaidOrders = $pdo->query($unpaidOrdersQuery)->fetchColumn();
    
    // Low Stock Count (items with stock <= 10)
    $lowStockQuery = "SELECT COUNT(*) FROM Products WHERE stocks <= 10";
    $lowStockCount = $pdo->query($lowStockQuery)->fetchColumn();
    
    // Calculate percentage changes
    $monthlyChange = 0;
    if ($lastMonthRevenue > 0) {
        $monthlyChange = (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
    }
    
    $annualChange = 0;
    if ($lastYearRevenue > 0) {
        $annualChange = (($annualRevenue - $lastYearRevenue) / $lastYearRevenue) * 100;
    }
    
    // Prepare response
    $response = [
        'monthly_revenue' => number_format($monthlyRevenue, 2, '.', ''),
        'annual_revenue' => number_format($annualRevenue, 2, '.', ''),
        'total_orders' => $totalOrders,
        'paid_orders' => $paidOrders,
        'unpaid_orders' => $unpaidOrders,
        'low_stock_count' => $lowStockCount,
        'monthly_change' => round($monthlyChange, 1),
        'annual_change' => round($annualChange, 1)
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error occurred',
        'message' => $e->getMessage()
    ]);
}
?>