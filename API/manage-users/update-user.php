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

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Only POST method allowed'
    ]);
    exit();
}

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

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($input['id']) || empty($input['name']) || empty($input['email']) || empty($input['role'])) {
        throw new Exception('ID, name, email, and role are required');
    }

    $id = (int)$input['id'];
    $name = trim($input['name']);
    $email = trim($input['email']);
    $role = $input['role'];
    $password = isset($input['password']) ? $input['password'] : null;

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Validate role
    $valid_roles = ['admin', 'staff', 'client'];
    if (!in_array($role, $valid_roles)) {
        throw new Exception('Invalid role. Must be admin, staff, or client');
    }

    // Check if user exists
    $check_user = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $check_user->bind_param("i", $id);
    $check_user->execute();
    $result = $check_user->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('User not found');
    }

    // Check if email already exists for other users
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check_email->bind_param("si", $email, $id);
    $check_email->execute();
    $email_result = $check_email->get_result();
    
    if ($email_result->num_rows > 0) {
        throw new Exception('Email already exists for another user');
    }

    // Update user with or without password
    if (!empty($password)) {
        // Update with new password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password_hash = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $email, $password_hash, $role, $id);
    } else {
        // Update without changing password
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $id);
    }

    if ($stmt->execute()) {
        // Get the updated user
        $get_user = $conn->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
        $get_user->bind_param("i", $id);
        $get_user->execute();
        $user_result = $get_user->get_result();
        $user_data = $user_result->fetch_assoc();

        echo json_encode([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => $user_data
        ]);
    } else {
        throw new Exception('Failed to update user: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>