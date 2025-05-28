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

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

try {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method is allowed');
    }
    
    // Check if db.php file exists
    if (!file_exists('../../db.php')) {
        throw new Exception('db.php file not found');
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
    
    // Get POST data
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    // Validate required fields
    if ($id <= 0) {
        throw new Exception('Valid product ID is required');
    }
    
    // Check if product exists and get its details for confirmation
    $check_stmt = $conn->prepare("SELECT id, name FROM products WHERE id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Product not found');
    }
    
    $product = $result->fetch_assoc();
    $check_stmt->close();
    
    // Prepare SQL statement for deletion
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("i", $id);
    
    // Execute the statement
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Product deleted successfully',
                'deleted_product' => [
                    'id' => $id,
                    'name' => $product['name']
                ]
            ]);
        } else {
            throw new Exception('No product was deleted. Product may not exist.');
        }
    } else {
        throw new Exception('Failed to delete product: ' . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}

$conn->close();
?>