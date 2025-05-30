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
    $table_check = $conn->query("SHOW TABLES LIKE 'Products'");
    if ($table_check->num_rows == 0) {
        throw new Exception('Products table does not exist');
    }
    
    // Check if categories table exists
    $categories_check = $conn->query("SHOW TABLES LIKE 'Categories'");
    if ($categories_check->num_rows == 0) {
        throw new Exception('Categories table does not exist');
    }
    
    // Check table structure
    $structure_check = $conn->query("DESCRIBE Products");
    $columns = [];
    while ($row = $structure_check->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    // Check if required columns exist
    $required_columns = ['id', 'name', 'description', 'price', 'image', 'category_id', 'stocks'];
    $missing_columns = [];
    foreach ($required_columns as $col) {
        if (!in_array($col, $columns)) {
            $missing_columns[] = $col;
        }
    }
    
    if (!empty($missing_columns)) {
        throw new Exception('Missing columns in products table: ' . implode(', ', $missing_columns));
    }
    
    // Build the query with JOIN to get category name
    $sql = "SELECT 
                p.id, 
                p.name, 
                p.description, 
                p.price, 
                p.image AS image_url,
                p.stocks,
                p.category_id,
                c.name AS category_name
            FROM Products p
            LEFT JOIN Categories c ON p.category_id = c.id
            ORDER BY p.id DESC";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }
    
    $products = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Ensure all required fields are present in the response
            $products[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'image_url' => $row['image_url'],
                'stocks' => $row['stocks'],
                'category_id' => $row['category_id'],
                'category_name' => $row['category_name']
            ];
        }
    }
    
    // Remove debug info from production response
    $response = [
        'status' => 'success', 
        'products' => $products
    ];
    
    // Add debug info only if in development
    if (isset($_GET['debug'])) {
        $response['debug_info'] = [
            'total_products' => count($products),
            'available_columns' => $columns,
            'query_used' => $sql
        ];
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>