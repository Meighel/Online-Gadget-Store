<?php
session_start();
require '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch user details from the database
$stmt = $conn->prepare("SELECT name, role FROM Users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    echo json_encode([
        'name' => $user['name'],
        'role' => $user['role']
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}
?>
