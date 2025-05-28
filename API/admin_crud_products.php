<?php
require '../db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stocks = $_POST['stocks'];
        $image = $_POST['image'] ?? ''; // get image URL or empty string

        $stmt = $conn->prepare("INSERT INTO Products (name, price, stocks, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdis", $name, $price, $stocks, $image);
        $stmt->execute();
        header("Location: ../Admin/products.php");
        exit;

    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stocks = $_POST['stocks'];
        $image = $_POST['image'] ?? ''; // get updated image URL

        $stmt = $conn->prepare("UPDATE Products SET name = ?, price = ?, stocks = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sdisi", $name, $price, $stocks, $image, $id);
        $stmt->execute();
        header("Location: ../Admin/products.php");
        exit;

    } elseif ($action === 'delete') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM Products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: ../Admin/products.php");
        exit;
    }
}
