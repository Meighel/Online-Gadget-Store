<?php
session_start();
require '../db.php';  // Your mysqli connection ($conn)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];

    // Update the quantity in the cart
    $updateQuery = "UPDATE Cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('iii', $quantity, $user_id, $product_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
    }
    exit;
}
?>
