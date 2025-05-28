<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require '../db.php';

$user_id = $_SESSION['user_id'];

// Optional success/error message feedback
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;

// Fetch user data
$stmt = $conn->prepare("SELECT name, email FROM Users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/shop_styles.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
          <a class="navbar-brand" href="client_dashboard.php">TechNest</a>
          <div class="d-flex gap-2 align-items-center">
              <a href="../Public/shop.php" class="nav-link text-white me-2">Shop</a>
              <a class="nav-link position-relative text-white" href="../User/cart.php">
                  <i class="fas fa-shopping-cart me-1"></i>
                  <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
              </a>
              <a href="../User/my_orders.php" class="nav-link text-white me-2">Orders</a>
              <a href="account_settings.php" class="nav-link text-white me-2">Settings</a>
              <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
          </div>
      </div>
  </nav>

  <div class="container mt-5">
    <h2>Account Settings</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="../API/config.php" method="POST">
        <input type="hidden" name="action" value="update_profile">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="form-group">
            <label for="password">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
    </div>

    <script>
          document.getElementById('logoutBtn').addEventListener('click', async () => {
        await fetch('../API/logout.php', { method: 'POST' });
        window.location.href = '../Public/login.php';
    });

    fetch('../API/get_cart_count.php')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('cartCount').innerText = data.count || 0;
                })
                .catch(err => console.error('Failed to fetch cart count:', err));
    </script>
</body>
</html>
