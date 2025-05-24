<?php
session_start();
require '../db.php';  // Your mysqli connection ($conn)

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

$query = "SELECT SUM(quantity) AS count FROM Cart WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['count' => (int)$row['count']]);
?>
