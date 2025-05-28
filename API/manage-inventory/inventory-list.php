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

    // Check if inventory table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'inventory'");
    if ($table_check->num_rows == 0) {
        throw new Exception('Inventory table does not exist');
    }

    // Check columns
    $structure_check = $conn->query("DESCRIBE inventory");
    $columns = [];
    while ($row = $structure_check->fetch_assoc()) {
        $columns[] = $row['Field'];
    }

    $required_columns = ['id', 'product_id', 'user_id', 'quantity', 'price_at_purchase', 'order_id', 'purchased_at'];
    $missing_columns = array_diff($required_columns, $columns);

    if (!empty($missing_columns)) {
        throw new Exception('Missing columns in inventory table: ' . implode(', ', $missing_columns));
    }

    // Build SELECT query
    $select_columns = ['id', 'product_id', 'user_id', 'quantity', 'price_at_purchase', 'order_id', 'purchased_at'];
    $sql = "SELECT " . implode(', ', $select_columns) . " FROM inventory ORDER BY purchased_at DESC";

    $result = $conn->query($sql);
    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }

    $inventory = [];
    while ($row = $result->fetch_assoc()) {
        $inventory[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'inventory' => $inventory,
        'debug_info' => [
            'total_records' => count($inventory),
            'available_columns' => $columns,
            'query_used' => $sql
        ]
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
