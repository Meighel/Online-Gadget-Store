<?php
session_start();
require '../db.php';

// Check if user is logged in and role is client
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header("Location: ../Public/login.php");
    exit();
}

$userName = $_SESSION['user_name'];
$userId = $_SESSION['user_id'];

// Example: Fetch last 5 orders of the client (assuming you have an orders table)
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
    <button class="btn btn-outline-light ms-auto" id="logoutBtn">Logout</button>
  </div>
</nav>

<div class="container mt-5">
  <h1>Welcome, <?= htmlspecialchars($userName) ?>!</h1>
  <p class="lead">This is your client dashboard.</p>

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

<script>
document.getElementById('logoutBtn').addEventListener('click', async () => {
  await fetch('../API/logout.php', { method: 'POST' });
  window.location.href = '../Public/login.php';
});
</script>

</body>
</html>
