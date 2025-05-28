<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO Categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }

    header("Location: ../Admin/categories.php");
    exit;
}
