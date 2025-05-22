<?php
header('Content-Type: application/json');
require_once '../db.php';

// Simple GET request to return all products
$sql = "SELECT id, name, description, price, image_url FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);

$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode(['status' => 'success', 'products' => $products]);
    } else {
    echo json_encode(['status' => 'success', 'products' => []]);
}

echo json_encode([
    'status' => 'success',
    'products' => $products
]);
?>
