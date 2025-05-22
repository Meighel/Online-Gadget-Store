<?php
session_start();
header('Content-Type: application/json');
require_once '../db.php';

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit();
}

// Get JSON body
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Name, email, and password are required.']);
    exit();
}

$name = trim($data['name']);
$email = trim($data['email']);
$password = $data['password'];

// Check for existing email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode(['status' => 'error', 'message' => 'Email already registered.']);
    exit();
}
$stmt->close();

// Hash password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $passwordHash);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Registration successful.',
        'user' => [
            'id' => $stmt->insert_id,
            'name' => $name,
            'email' => $email,
            'role' => 'client' // default from DB
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}

$stmt->close();
$conn->close();
