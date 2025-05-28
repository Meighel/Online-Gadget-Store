<?php
session_start();
require '../db.php';

header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$product_id = intval($data['product_id']);
$quantity = intval($data['quantity']);
$user_id = $_SESSION['user_id'];

if ($quantity < 1) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid quantity']);
    exit;
}

$stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("iii", $quantity, $user_id, $product_id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
