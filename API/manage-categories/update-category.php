<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // Only allow POST method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method is allowed');
    }

    if (!file_exists('../../db.php')) {
        throw new Exception('db.php file not found');
    }

    require_once '../../db.php';

    if (!isset($conn)) {
        throw new Exception('Database connection variable $conn not found');
    }

    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

    // Get JSON input
    $input = json_decode(file_get_contents("php://input"), true);

    $id = isset($input['id']) ? intval($input['id']) : 0;
    $name = isset($input['name']) ? trim($input['name']) : '';

    if ($id <= 0) {
        throw new Exception('Valid category ID is required');
    }

    if (empty($name)) {
        throw new Exception('Category name is required');
    }

    // Check if category exists
    $check_stmt = $conn->prepare("SELECT id FROM categories WHERE id = ?");
    if (!$check_stmt) {
        throw new Exception('Failed to prepare check statement: ' . $conn->error);
    }

    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Category not found');
    }
    $check_stmt->close();

    // Check if another category with the same name exists (excluding current category)
    $name_check_stmt = $conn->prepare("SELECT id FROM categories WHERE name = ? AND id != ?");
    if (!$name_check_stmt) {
        throw new Exception('Failed to prepare name check statement: ' . $conn->error);
    }

    $name_check_stmt->bind_param("si", $name, $id);
    $name_check_stmt->execute();
    $name_result = $name_check_stmt->get_result();

    if ($name_result->num_rows > 0) {
        throw new Exception('Another category with this name already exists');
    }
    $name_check_stmt->close();

    // Prepare update statement
    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Failed to prepare update statement: ' . $conn->error);
    }

    $stmt->bind_param("si", $name, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Category updated successfully',
                'data' => [
                    'id' => $id,
                    'name' => $name
                ]
            ]);
        } else {
            throw new Exception('No changes were made to the category');
        }
    } else {
        throw new Exception('Failed to update category: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>
