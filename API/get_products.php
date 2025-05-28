<?php
require '../db.php';
header('Content-Type: application/json');

$sql = "SELECT id, name, description, price, image FROM products";
$result = $conn->query($sql);

if ($result) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Rename 'image' to 'image_url' for frontend consistency
        $row['image_url'] = $row['image'];
        unset($row['image']); // optional: remove original 'image' key
        $products[] = $row;
    }
    echo json_encode(['status' => 'success', 'products' => $products]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database query failed']);
}
?>
