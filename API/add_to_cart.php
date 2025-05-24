<?php
session_start();
require '../db.php';  // Your mysqli connection ($conn)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];

    // Check if the product is already in the cart
    $checkQuery = "SELECT id FROM Cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if product already exists in the cart
        $updateQuery = "UPDATE Cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('iii', $quantity, $user_id, $product_id);
    } else {
        // Insert new product into the cart
        $insertQuery = "INSERT INTO Cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('iii', $user_id, $product_id, $quantity);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add to cart']);
    }
    exit;
}
?>
