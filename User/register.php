<?php
session_start();
require '../db.php';

if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 'admin':
            header("Location: ../Admin/admin_dashboard.php");
            exit();
        case 'staff':
            header("Location: ../Staff/staff_dashboard.php");
            exit();
        case 'client':
        default:
            header("Location: ../User/client_dashboard.php");
            exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register | TechNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="../index.php">TechNest</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="../Public/shop.php">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="../Public/about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="../Public/contact.php">Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="../User/client_dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../User/cart.php">Cart</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="../Public/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="mb-4 text-center">Register an Account</h3>

          <!--
            This form POSTS directly to the API endpoint,
            which returns JSON — so we need JS to handle it.
          -->
          <form id="registerForm">
            <div class="mb-3">
              <label>Name</label>
              <input type="text" name="name" id="name" class="form-control" required />
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" id="email" class="form-control" required />
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" id="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
          </form>

          <div class="mt-3 text-center" id="message"></div>

          <div class="mt-3 text-center">
            <small>Already have an account? <a href="../Public/login.php">Login here</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const name = document.getElementById('name').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;

  const response = await fetch('../API/register.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, email, password })
  });

  const result = await response.json();
  const messageDiv = document.getElementById('message');

  if (response.ok && result.status === 'success') {
    messageDiv.innerHTML = `<div class="alert alert-success">${result.message} <a href="../Public/login.php">Login now</a></div>`;
    this.reset();
  } else {
    messageDiv.innerHTML = `<div class="alert alert-danger">${result.message || 'Registration failed.'}</div>`;
  }
});
</script>

</body>
</html>
