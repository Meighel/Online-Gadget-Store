<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';

    if ($id && !empty($name)) {
        $stmt = $conn->prepare("UPDATE Categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
    }

    header("Location: ../Admin/categories.php");
    exit;
}
