<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | TechNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" href="../assets/images/favicon.png">
  <style>
    :root {
        --primary-color: #6c5ce7;
        --secondary-color: #a29bfe;
        --accent-color: #fd79a8;
        --dark-color: #2d3436;
        --light-color: #ddd6fe;
        --gradient-primary: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        --gradient-accent: linear-gradient(135deg, var(--accent-color), #ff6b9d);
        --card-shadow: 0 10px 30px rgba(108, 92, 231, 0.1);
        --hover-shadow: 0 20px 40px rgba(108, 92, 231, 0.2);
        --border-radius: 15px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    /* Animated Background */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 107, 157, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(108, 92, 231, 0.2) 0%, transparent 50%);
        z-index: -1;
        animation: backgroundFloat 8s ease-in-out infinite alternate;
    }

    @keyframes backgroundFloat {
        0% { transform: scale(1) rotate(0deg); }
        100% { transform: scale(1.1) rotate(1deg); }
    }

    /* Enhanced Navbar */
    .navbar {
        background: var(--gradient-primary) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1rem 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
        font-weight: 800;
        font-size: 1.8rem;
        background: var(--light-color);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: none;
    }

    .nav-link {
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9) !important;
        transition: var(--transition);
        position: relative;
        padding: 0.5rem 1rem !important;
        margin: 0 0.25rem;
        border-radius: 8px;
    }

    .nav-link:hover {
        color: var(--accent-color) !important;
        background: rgba(253, 121, 168, 0.1);
        transform: translateY(-2px);
    }

    /* Login Container */
    .login-container {
        min-height: calc(100vh - 76px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    /* Enhanced Login Card */
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius);
        box-shadow: 
            0 25px 50px rgba(0, 0, 0, 0.1),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        padding: 3rem;
        transition: var(--transition);
        animation: slideInUp 0.8s ease-out;
        max-width: 450px;
        width: 100%;
    }

    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 
            0 35px 70px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.2);
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Login Header */
    .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .login-title {
        font-size: 2rem;
        font-weight: 700;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .login-subtitle {
        color: #666;
        font-size: 1rem;
        font-weight: 400;
    }

    .login-icon {
        width: 60px;
        height: 60px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 25px rgba(108, 92, 231, 0.3);
    }

    /* Enhanced Form Controls */
    .form-floating {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem 1rem 1rem 3rem;
        font-size: 1rem;
        transition: var(--transition);
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.1);
        background: rgba(255, 255, 255, 0.95);
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        z-index: 5;
        transition: var(--transition);
    }

    .form-control:focus + .input-icon {
        color: var(--primary-color);
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    /* Enhanced Login Button */
    .btn-login {
        background: var(--gradient-primary);
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        width: 100%;
        transition: var(--transition);
        box-shadow: 0 8px 25px rgba(108, 92, 231, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(108, 92, 231, 0.4);
        background: var(--gradient-accent);
    }

    .btn-login:hover::before {
        left: 100%;
    }

    .btn-login:active {
        transform: translateY(0);
    }

    /* Loading State */
    .btn-login.loading {
        pointer-events: none;
        opacity: 0.8;
    }

    .btn-login.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Enhanced Error Alert */
    .alert-danger {
        background: linear-gradient(135deg, #ff6b9d, #ff8a9a);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 25px rgba(255, 107, 157, 0.3);
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Register Link */
    .register-link {
        text-align: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .register-link a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }

    .register-link a:hover {
        color: var(--accent-color);
        text-decoration: underline;
    }

    /* Password Toggle */
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        z-index: 5;
        transition: var(--transition);
    }

    .password-toggle:hover {
        color: var(--primary-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .login-card {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }
        
        .login-title {
            font-size: 1.8rem;
        }
    }
  </style>
</head>
<body>

<!-- Enhanced Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
  <div class="container">
    <a class="navbar-brand" href="../index.php">TechNest</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="shop.php">Shop</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="../User/client_dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../User/cart.php">Cart</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Enhanced Login Container -->
<div class="login-container">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="login-card">
          <!-- Login Header -->
          <div class="login-header">
            <div class="login-icon">
              <i class="fas fa-user-lock"></i>
            </div>
            <h3 class="login-title">Welcome Back!</h3>
            <p class="login-subtitle">Sign in to your TechNest account</p>
          </div>

          <!-- Error Alert -->
          <div id="errorBox" class="alert alert-danger d-none">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span id="errorMessage">Login failed.</span>
          </div>

          <!-- Enhanced Login Form -->
          <form id="loginForm">
            <div class="form-group">
              <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Email Address
              </label>
              <div class="position-relative">
                <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email">
              </div>
            </div>

            <div class="form-group">
              <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Password
              </label>
              <div class="position-relative">
                <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password">
                <button type="button" class="password-toggle" id="togglePassword">
                </button>
              </div>
            </div>

            <button type="submit" class="btn btn-login" id="loginBtn">
              <i class="fas fa-sign-in-alt me-2"></i>
              <span id="loginText">Sign In</span>
            </button>
          </form>

          <!-- Register Link -->
          <div class="register-link">
            <small>
              Don't have an account? 
              <a href="../User/register.php">
                <i class="fas fa-user-plus me-1"></i>Create Account
              </a>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Password toggle functionality
document.getElementById('togglePassword').addEventListener('click', function() {
  const passwordInput = document.getElementById('password');
  const toggleIcon = document.getElementById('toggleIcon');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    toggleIcon.classList.remove('fa-eye');
    toggleIcon.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    toggleIcon.classList.remove('fa-eye-slash');
    toggleIcon.classList.add('fa-eye');
  }
});

// Enhanced form validation and submission
document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const loginBtn = document.getElementById('loginBtn');
  const loginText = document.getElementById('loginText');
  const errorBox = document.getElementById('errorBox');
  const errorMessage = document.getElementById('errorMessage');

  // Add loading state
  loginBtn.classList.add('loading');
  loginText.textContent = 'Signing In...';
  loginBtn.disabled = true;

  // Hide previous errors
  errorBox.classList.add('d-none');

  try {
    const res = await fetch('../API/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });

    const data = await res.json();

    if (data.status === 'success') {
      // Success animation
      loginText.textContent = 'Success!';
      loginBtn.style.background = 'linear-gradient(135deg, #00b894, #00cec9)';
      
      setTimeout(() => {
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
      }, 1000);
    } else {
      // Show error with animation
      errorMessage.textContent = data.message || "Login failed.";
      errorBox.classList.remove('d-none');
      
      // Reset button state
      loginBtn.classList.remove('loading');
      loginText.textContent = 'Sign In';
      loginBtn.disabled = false;
      
      // Shake animation for error
      errorBox.style.animation = 'none';
      setTimeout(() => {
        errorBox.style.animation = 'shake 0.5s ease-in-out';
      }, 10);
    }
  } catch (error) {
    console.error('Login error:', error);
    errorMessage.textContent = 'Network error. Please try again.';
    errorBox.classList.remove('d-none');
    
    // Reset button state
    loginBtn.classList.remove('loading');
    loginText.textContent = 'Sign In';
    loginBtn.disabled = false;
  }
});

// Add input focus animations
document.querySelectorAll('.form-control').forEach(input => {
  input.addEventListener('focus', function() {
    this.parentElement.style.transform = 'scale(1.02)';
  });
  
  input.addEventListener('blur', function() {
    this.parentElement.style.transform = 'scale(1)';
  });
});

// Add floating label effect
document.querySelectorAll('.form-control').forEach(input => {
  input.addEventListener('input', function() {
    if (this.value) {
      this.classList.add('has-value');
    } else {
      this.classList.remove('has-value');
    }
  });
});
</script>

</body>
</html>