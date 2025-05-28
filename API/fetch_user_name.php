<?php
session_start();
require '../db.php';

$user_id = $_SESSION['user_id']; 

$query = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['name' => $row['name'] ?? 'Guest']);
?>
