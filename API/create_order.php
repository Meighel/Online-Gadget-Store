<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['user_id'];
$items = $data['items'];
$total = $data['total_amount'];

$conn->begin_transaction();

try {
    // Insert into Orders
    $stmt = $conn->prepare("INSERT INTO Orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert each item into Order_items
    $stmt = $conn->prepare("INSERT INTO Order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $pid = intval($item['id']);
        $qty = intval($item['quantity']);
        $price = floatval($item['price']);
        $stmt->bind_param("iiid", $order_id, $pid, $qty, $price);
        $stmt->execute();
    }

    // Clear the selected items from the cart
    $stmt = $conn->prepare("DELETE FROM Cart WHERE user_id = ? AND product_id = ?");
    foreach ($items as $item) {
        $pid = intval($item['id']);
        $stmt->bind_param("ii", $user_id, $pid);
        $stmt->execute();
    }

    // Prepare once
    $stmtInventory = $conn->prepare("INSERT INTO Inventory (product_id, user_id, quantity, price_at_purchase, order_id) VALUES (?, ?, ?, ?, ?)");
    $stmtUpdateStock = $conn->prepare("UPDATE Products SET stocks = stocks - ? WHERE id = ?");

    // Loop to insert inventory log and update stock
    foreach ($items as $item) {
        $product_id = intval($item['id']);
        $quantity = intval($item['quantity']);
        $price = floatval($item['price']);

        // Insert audit log
        $stmtInventory->bind_param("iiidi", $product_id, $user_id, $quantity, $price, $order_id);
        $stmtInventory->execute();

        // Decrease stock
        $stmtUpdateStock->bind_param("ii", $quantity, $product_id);
        $stmtUpdateStock->execute();
    }


    $conn->commit();
    $_SESSION['latest_order_id'] = $order_id;

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>