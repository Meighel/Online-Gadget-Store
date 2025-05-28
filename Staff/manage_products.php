<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: ../Public/login.php");
    exit;
}

// Fetch products
$result = $conn->query("SELECT * FROM Products");
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">TechNest</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center gap-2">
                <li class="nav-item">
                <a href="manage_products.php" class="nav-link text-white">Products</a>
                </li>
                <li class="nav-item">
                    <a href="view_users.php" class="nav-link text-white">User</a>
                </li>
                <li class="nav-item">
                    <a href="view_inventory.php" class="nav-link text-white">Inventory</a>
                </li>
                <li class="nav-item">
                    <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
                </li>
            </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5">
    <h2>Manage Products</h2>

    <form action="../API/crud_products.php" method="POST" class="mb-4">
        <input type="hidden" name="action" value="create">
        <div class="form-row">
            <div class="col"><input name="name" class="form-control" placeholder="Product Name" required></div>
            <div class="col"><input name="price" class="form-control" type="number" step="0.01" placeholder="Price" required></div>
            <div class="col"><input name="stocks" class="form-control" type="number" placeholder="Stocks" required></div>
            <div class="col"><input name="image" class="form-control" type="text" placeholder="Image URL"></div>
            <div class="col"><button class="btn btn-success">Add Product</button></div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Stocks</th><th>Image</th></tr></thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <form action="../API/crud_products.php" method="POST">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="action" value="update">
                        <td><?= $product['id'] ?></td>
                        <td><input name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control"></td>
                        <td><input name="price" type="number" step="0.01" value="<?= $product['price'] ?>" class="form-control"></td>
                        <td><input name="stocks" type="number" value="<?= $product['stocks'] ?>" class="form-control"></td>
                        <td><input name="image" type="text" value="<?= htmlspecialchars($product['image']) ?>" class="form-control"></td>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" width="60">
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm">Update</button>
                            <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?');">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <script>
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            await fetch('../API/logout.php', { method: 'POST' });
            window.location.href = '../Public/login.php';
        });
    </script>
</body>
</html>
