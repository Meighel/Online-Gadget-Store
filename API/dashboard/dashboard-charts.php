<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Include your database configuration
require_once '../config/database.php'; // Adjust path as needed

try {
    // Revenue Trend - Last 12 months
    $revenueTrendQuery = "
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            DATE_FORMAT(created_at, '%M %Y') as month_name,
            COALESCE(SUM(total_amount), 0) as revenue
        FROM Orders 
        WHERE is_paid = 1 
        AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month ASC
    ";
    
    $stmt = $pdo->prepare($revenueTrendQuery);
    $stmt->execute();
    $revenueTrend = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fill in missing months with 0 revenue
    $completeRevenueTrend = [];
    for ($i = 11; $i >= 0; $i--) {
        $monthKey = date('Y-m', strtotime("-$i months"));
        $monthName = date('M Y', strtotime("-$i months"));
        
        $found = false;
        foreach ($revenueTrend as $data) {
            if ($data['month'] === $monthKey) {
                $completeRevenueTrend[] = [
                    'month' => $monthName,
                    'revenue' => $data['revenue']
                ];
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $completeRevenueTrend[] = [
                'month' => $monthName,
                'revenue' => '0.00'
            ];
        }
    }
    
    // Category Distribution
    $categoryDistributionQuery = "
        SELECT 
            c.name,
            COUNT(p.id) as count
        FROM Categories c
        LEFT JOIN Products p ON c.id = p.category_id
        GROUP BY c.id, c.name
        HAVING count > 0
        ORDER BY count DESC
    ";
    
    $stmt = $pdo->prepare($categoryDistributionQuery);
    $stmt->execute();
    $categoryDistribution = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Monthly Orders Trend
    $ordersQuery = "
        SELECT 
            DATE_FORMAT(created_at, '%M %Y') as month,
            COUNT(*) as total_orders,
            SUM(CASE WHEN is_paid = 1 THEN 1 ELSE 0 END) as paid_orders
        FROM Orders 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY created_at ASC
    ";
    
    $stmt = $pdo->prepare($ordersQuery);
    $stmt->execute();
    $ordersTrend = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Top Selling Products (by quantity sold)
    $topProductsQuery = "
        SELECT 
            p.name,
            p.price,
            COALESCE(SUM(oi.quantity), 0) as total_sold,
            COALESCE(SUM(oi.quantity * oi.price), 0) as revenue
        FROM Products p
        LEFT JOIN Order_items oi ON p.id = oi.product_id
        LEFT JOIN Orders o ON oi.order_id = o.id AND o.is_paid = 1
        GROUP BY p.id, p.name, p.price
        ORDER BY total_sold DESC
        LIMIT 10
    ";
    
    $stmt = $pdo->prepare($topProductsQuery);
    $stmt->execute();
    $topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Prepare response
    $response = [
        'revenue_trend' => $completeRevenueTrend,
        'category_distribution' => $categoryDistribution,
        'orders_trend' => $ordersTrend,
        'top_products' => $topProducts
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