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

    // Use $_POST to get id
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        throw new Exception('Valid category ID is required');
    }

    // Check if category exists
    $check_stmt = $conn->prepare("SELECT id, name FROM categories WHERE id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Category not found');
    }

    $category = $result->fetch_assoc();
    $check_stmt->close();

    // Delete category
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Category deleted successfully',
                'deleted_category' => [
                    'id' => $id,
                    'name' => $category['name']
                ]
            ]);
        } else {
            throw new Exception('No category was deleted. It may not exist.');
        }
    } else {
        throw new Exception('Failed to delete category: ' . $stmt->error);
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
