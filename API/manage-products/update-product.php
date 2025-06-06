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
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
    $category_id = isset($_POST['category_id']) && !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $stocks = isset($_POST['stocks']) ? intval($_POST['stocks']) : 0;
    
    // Validate required fields
    if ($id <= 0) {
        throw new Exception('Valid product ID is required');
    }
    
    if (empty($name)) {
        throw new Exception('Product name is required');
    }
    
    if (empty($description)) {
        throw new Exception('Product description is required');
    }
    
    if ($price <= 0) {
        throw new Exception('Product price must be greater than 0');
    }
    
    if (empty($image_url)) {
        throw new Exception('Product image URL is required');
    }
    
    if ($stocks < 0) {
        throw new Exception('Stock quantity cannot be negative');
    }
    
    // Validate URL format
    if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
        throw new Exception('Invalid image URL format');
    }
    
    // Check if product exists
    $check_stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Product not found');
    }
    
    $check_stmt->close();
    
    // Validate category_id if provided
    if ($category_id !== null) {
        $category_check = $conn->prepare("SELECT id FROM categories WHERE id = ?");
        $category_check->bind_param("i", $category_id);
        $category_check->execute();
        $category_result = $category_check->get_result();
        
        if ($category_result->num_rows === 0) {
            throw new Exception('Invalid category selected');
        }
        $category_check->close();
    }
    
    // Prepare SQL statement for update (including category and stocks)
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, category_id = ?, stocks = ? WHERE id = ?");
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    // Bind parameters (note: category_id can be null)
    $stmt->bind_param("ssdsiii", $name, $description, $price, $image_url, $category_id, $stocks, $id);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Get category name for response (if category was selected)
        $category_name = null;
        if ($category_id !== null) {
            $category_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
            $category_stmt->bind_param("i", $category_id);
            $category_stmt->execute();
            $category_result = $category_stmt->get_result();
            if ($category_row = $category_result->fetch_assoc()) {
                $category_name = $category_row['name'];
            }
            $category_stmt->close();
        }
        
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'data' => [
                    'id' => $id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'image_url' => $image_url,
                    'category_id' => $category_id,
                    'category_name' => $category_name,
                    'stocks' => $stocks
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'message' => 'No changes were made to the product',
                'data' => [
                    'id' => $id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'image_url' => $image_url,
                    'category_id' => $category_id,
                    'category_name' => $category_name,
                    'stocks' => $stocks
                ]
            ]);
        }
    } else {
        throw new Exception('Failed to update product: ' . $stmt->error);
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