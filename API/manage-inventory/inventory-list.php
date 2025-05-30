<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

try {
    // Check if db.php file exists
    if (!file_exists('../../db.php')) {
        throw new Exception('db.php file not found at ../../db.php');
    }

    require_once '../../db.php';

    if (!isset($conn)) {
        throw new Exception('Database connection variable $conn not found');
    }

    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

    // Use the same query structure as your staff page but without unknown columns
    $sql = "
        SELECT 
            Inventory.id,
            Users.name AS user_name,
            Products.name AS product_name,
            Products.stocks AS current_stocks,
            Inventory.quantity,
            Inventory.price_at_purchase,
            Inventory.order_id,
            Inventory.purchased_at
        FROM Inventory
        JOIN Users ON Inventory.user_id = Users.id
        JOIN Products ON Inventory.product_id = Products.id
        ORDER BY Inventory.purchased_at DESC
    ";

    $result = $conn->query($sql);
    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }

    $inventory = [];
    while ($row = $result->fetch_assoc()) {
        // Format data to match your inventory structure exactly
        $inventory[] = [
            'id' => (int)$row['id'],
            'product_name' => $row['product_name'],
            'user_name' => $row['user_name'],
            'quantity' => (int)$row['quantity'],
            'price_at_purchase' => (float)$row['price_at_purchase'],
            'order_id' => (int)$row['order_id'],
            'current_stocks' => (int)$row['current_stocks'],
            'purchased_at' => $row['purchased_at']
        ];
    }

    // Return 'items' to match JavaScript expectation
    echo json_encode([
        'status' => 'success',
        'items' => $inventory,
        'total' => count($inventory)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>