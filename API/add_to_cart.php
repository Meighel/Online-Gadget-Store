<?php
session_start();
require '../db.php'; // adjust as needed

header('Content-Type: application/json');

// Check session and input
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['product_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID required']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($data['product_id']);

// Check if the product is already in the cart
$check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Already in cart: update quantity
    $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
    $update->bind_param("ii", $user_id, $product_id);
    if ($update->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Quantity updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update cart']);
    }
} else {
    // Not in cart: insert new row
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Added to cart']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Insert failed']);
    }
}
?>
