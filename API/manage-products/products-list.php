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
        throw new Exception('db.php file not found at ../db.php');
    }
    
    require_once '../../db.php';
    
    // Check if $conn variable exists
    if (!isset($conn)) {
        throw new Exception('Database connection variable $conn not found');
    }
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }
    
    // Test basic query first
    $test_query = "SELECT 1";
    $test_result = $conn->query($test_query);
    if (!$test_result) {
        throw new Exception('Basic database query failed: ' . $conn->error);
    }
    
    // Check if products table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'products'");
    if ($table_check->num_rows == 0) {
        throw new Exception('Products table does not exist');
    }
    
    // Check table structure
    $structure_check = $conn->query("DESCRIBE products");
    $columns = [];
    while ($row = $structure_check->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    // Check if required columns exist
    $required_columns = ['id', 'name', 'description', 'price', 'image'];
    $missing_columns = [];
    foreach ($required_columns as $col) {
        if (!in_array($col, $columns)) {
            $missing_columns[] = $col;
        }
    }
    
    if (!empty($missing_columns)) {
        throw new Exception('Missing columns in products table: ' . implode(', ', $missing_columns));
    }
    
    // Build query based on available columns
    $select_columns = ['id', 'name', 'description', 'price', 'image AS image_url'];
    
    // Add optional columns if they exist
    if (in_array('category', $columns)) {
        $select_columns[] = 'category';
    }
    if (in_array('stocks', $columns)) {
        $select_columns[] = 'stocks';
    }
    
    $sql = "SELECT " . implode(', ', $select_columns) . " FROM products";
    
    // Add ORDER BY if created_at column exists
    if (in_array('created_at', $columns)) {
        $sql .= " ORDER BY created_at DESC";
    } else {
        $sql .= " ORDER BY id DESC";
    }
    
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }
    
    $products = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    
    echo json_encode([
        'status' => 'success', 
        'products' => $products,
        'debug_info' => [
            'total_products' => count($products),
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