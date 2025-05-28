<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Include your database configuration
require_once '../config/database.php'; // Adjust path as needed

try {
    // Recent Orders - Last 10 orders
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
        LIMIT 10
    ";
    
    $stmt = $pdo->prepare($recentOrdersQuery);
    $stmt->execute();
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the orders data
    foreach ($recentOrders as &$order) {
        $order['user_name'] = $order['user_name'] ?? 'Guest User';
        $order['created_at'] = date('M d, Y H:i', strtotime($order['created_at']));
        $order['is_paid'] = (bool)$order['is_paid'];
    }
    
    // Top Products by Sales
    $topProductsQuery = "
        SELECT 
            p.id,
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
        LIMIT 10
    ";
    
    $stmt = $pdo->prepare($topProductsQuery);
    $stmt->execute();
    $topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Low Stock Products
    $lowStockQuery = "
        SELECT 
            id,
            name,
            price,
            stocks,
            category_id
        FROM Products 
        WHERE stocks <= 10
        ORDER BY stocks ASC
        LIMIT 10
    ";
    
    $stmt = $pdo->prepare($lowStockQuery);
    $stmt->execute();
    $lowStockProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent Users (Last 10 registered users)
    $recentUsersQuery = "
        SELECT 
            id,
            name,
            email,
            role,
            created_at
        FROM Users
        ORDER BY created_at DESC
        LIMIT 10
    ";
    
    $stmt = $pdo->prepare($recentUsersQuery);
    $stmt->execute();
    $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format users data
    foreach ($recentUsers as &$user) {
        $user['created_at'] = date('M d, Y', strtotime($user['created_at']));
        // Remove sensitive information
        unset($user['password_hash']);
    }
    
    // Daily Sales for Current Month
    $dailySalesQuery = "
        SELECT 
            DATE(created_at) as sale_date,
            COUNT(*) as order_count,
            COALESCE(SUM(total_amount), 0) as daily_revenue
        FROM Orders
        WHERE is_paid = 1 
        AND MONTH(created_at) = MONTH(NOW())
        AND YEAR(created_at) = YEAR(NOW())
        GROUP BY DATE(created_at)
        ORDER BY sale_date DESC
        LIMIT 15
    ";
    
    $stmt = $pdo->prepare($dailySalesQuery);
    $stmt->execute();
    $dailySales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format daily sales
    foreach ($dailySales as &$sale) {
        $sale['sale_date'] = date('M d', strtotime($sale['sale_date']));
    }
    
    // Category Performance
    $categoryPerformanceQuery = "
        SELECT 
            c.name as category_name,
            COUNT(DISTINCT p.id) as product_count,
            COALESCE(SUM(oi.quantity), 0) as items_sold,
            COALESCE(SUM(oi.quantity * oi.price), 0) as revenue
        FROM Categories c
        LEFT JOIN Products p ON c.id = p.category_id
        LEFT JOIN Order_items oi ON p.id = oi.product_id
        LEFT JOIN Orders o ON oi.order_id = o.id AND o.is_paid = 1
        GROUP BY c.id, c.name
        ORDER BY revenue DESC
    ";
    
    $stmt = $pdo->prepare($categoryPerformanceQuery);
    $stmt->execute();
    $categoryPerformance = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Prepare response
    $response = [
        'orders' => $recentOrders,
        'products' => $topProducts,
        'low_stock_products' => $lowStockProducts,
        'users' => $recentUsers,
        'daily_sales' => $dailySales,
        'category_performance' => $categoryPerformance
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