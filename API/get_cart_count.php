<?php
session_start();
require '../db.php';  // Make sure this path is correct

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT SUM(quantity) AS total_quantity FROM Cart WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalQuantity = $row['total_quantity'] ?? 0;

echo json_encode(['count' => $totalQuantity]);
