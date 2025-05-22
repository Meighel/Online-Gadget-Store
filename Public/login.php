<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | TechNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="../User/client_dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../User/cart.php">Cart</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="mb-4 text-center">Login to TechNest</h3>

          <div id="errorBox" class="alert alert-danger d-none"></div>

          <form id="loginForm">
            <div class="mb-3">
              <label for="email">Email</label>
              <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="password">Password</label>
              <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>

          <div class="mt-3 text-center">
            <small>Don't have an account? <a href="../User/register.php">Register here</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;

  const res = await fetch('../API/login.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  });

  const data = await res.json();

  if (data.status === 'success') {
    const role = data.user.role;
    switch (role) {
      case 'admin':
        window.location.href = '../Admin/admin_dashboard.php';
        break;
      case 'staff':
        window.location.href = '../Staff/staff_dashboard.php';
        break;
      case 'client':
      default:
        window.location.href = '../User/client_dashboard.php';
    }
  } else {
    const errorBox = document.getElementById('errorBox');
    errorBox.textContent = data.message || "Login failed.";
    errorBox.classList.remove('d-none');
  }
});

</script>

</body>
</html>
