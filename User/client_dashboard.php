<?php
session_start();
require '../db.php';

// Check if user is logged in and role is client
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header("Location: ../Public/login.php");
    exit();
}

$userName = $_SESSION['user_name'];
$userRole = $_SESSION['user_role'];
$userEmail = $_SESSION['user_email'];
$userId = $_SESSION['user_id'];
$userCreatedAt = $_SESSION['user_created_at'];


// Fetch last 5 orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$ordersResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Client Dashboard | TechNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">TechNest</a>
    <div class="d-flex gap-2">
      <a href="../Public/shop.php" class="btn text-white me-2">Shop</a>
      <a href="cart.php" class="btn text-white me-2">Cart</a>
      <a href="settings.php" class="btn text-white me-2">Settings</a>
      <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h1>Welcome, <?= htmlspecialchars($userName) ?>!</h1>
  <p><strong>Email:</strong> <?= htmlspecialchars($userEmail) ?></p>
  <p><strong>Role:</strong> <?= htmlspecialchars($userRole) ?></p>
  <p><strong>Joined:</strong> <?= date('F d, Y', strtotime($userCreatedAt)) ?></p>
  <hr>

  <h3>Your Recent Orders</h3>
  <?php if ($ordersResult && $ordersResult->num_rows > 0): ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Date</th>
          <th>Status</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($order = $ordersResult->fetch_assoc()): ?>
          <tr>
            <td><?= $order['id'] ?></td>
            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
            <td><?= htmlspecialchars($order['status']) ?></td>
            <td>₱<?= number_format($order['total_amount'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>You have no recent orders.</p>
  <?php endif; ?>
</div>


<div class="container mt-5">
  <hr>
  <h3>Available Products</h3>
  <div id="productContainer" class="row g-4 mt-3">
    <!-- Products will be dynamically injected here -->
  </div>
  <div class="text-end mt-3">
    <a href="../Public/shop.php" class="btn btn-primary">View More Products</a>
  </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', () => {
  fetch('../API/get_products.php')
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        const products = data.products;
        const container = document.getElementById('productContainer');

        if (products.length === 0) {
          container.innerHTML = '<p>No products available.</p>';
          return;
        }

        // Display only first 5 products
        products.slice(0, 5).forEach(product => {
          const card = document.createElement('div');
          card.className = 'col-md-4';

          card.innerHTML = `
            <div class="card h-100">
              <img src="../${product.image_url}" class="card-img-top" alt="${product.name}" style="max-height: 200px; object-fit: contain;">
              <div class="card-body">
                <h5 class="card-title">${product.name}</h5>
                <p class="card-text">${product.description}</p>
                <p class="text-primary fw-bold">₱${Number(product.price).toLocaleString()}</p>
                <button class="btn btn-sm btn-outline-primary">Add to Cart</button>
              </div>
            </div>
          `;

          container.appendChild(card);
        });
      } else {
        console.error('Failed to load products');
      }
    })
    .catch(error => {
      console.error('Error fetching products:', error);
    });
});



document.getElementById('logoutBtn').addEventListener('click', async () => {
  await fetch('../API/logout.php', { method: 'POST' });
  window.location.href = '../Public/login.php';
});
</script>

</body>
</html>
