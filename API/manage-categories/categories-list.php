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

    // Test basic query
    $test_query = "SELECT 1";
    if (!$conn->query($test_query)) {
        throw new Exception('Basic query failed: ' . $conn->error);
    }

    // Check if categories table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'categories'");
    if ($table_check->num_rows == 0) {
        throw new Exception('Categories table does not exist');
    }

    // Get table columns
    $structure_check = $conn->query("DESCRIBE categories");
    $columns = [];
    while ($row = $structure_check->fetch_assoc()) {
        $columns[] = $row['Field'];
    }

    // Define required columns
    $required_columns = ['id', 'name'];
    $missing_columns = [];
    foreach ($required_columns as $col) {
        if (!in_array($col, $columns)) {
            $missing_columns[] = $col;
        }
    }

    if (!empty($missing_columns)) {
        throw new Exception('Missing columns in categories table: ' . implode(', ', $missing_columns));
    }

    // Build SELECT query
    $select_columns = ['id', 'name'];
    if (in_array('description', $columns)) {
        $select_columns[] = 'description';
    }
    if (in_array('image', $columns)) {
        $select_columns[] = 'image AS image_url';
    }

    $sql = "SELECT " . implode(', ', $select_columns) . " FROM categories";

    // Add ORDER BY clause
    if (in_array('created_at', $columns)) {
        $sql .= " ORDER BY created_at DESC";
    } else {
        $sql .= " ORDER BY id DESC";
    }

    $result = $conn->query($sql);
    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'categories' => $categories,
        'debug_info' => [
            'total_categories' => count($categories),
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
