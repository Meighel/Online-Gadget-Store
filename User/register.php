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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | TechNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            radial-gradient(circle at 25% 25%, rgba(116, 185, 255, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(9, 132, 227, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 50% 50%, rgba(108, 92, 231, 0.2) 0%, transparent 50%);
        z-index: -1;
        animation: backgroundFloat 10s ease-in-out infinite alternate;
    }

    @keyframes backgroundFloat {
        0% { transform: scale(1) rotate(0deg); }
        100% { transform: scale(1.1) rotate(2deg); }
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

    /* Register Container */
    .register-container {
        min-height: calc(100vh - 76px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    /* Enhanced Register Card */
    .register-card {
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
        max-width: 500px;
        width: 100%;
    }

    .register-card:hover {
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

    /* Register Header */
    .register-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .register-title {
        font-size: 2rem;
        font-weight: 700;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .register-subtitle {
        color: #666;
        font-size: 1rem;
        font-weight: 400;
    }

    .register-icon {
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
    .form-group {
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
        top: 3.2rem;
        color: #666;
        z-index: 5;
        transition: var(--transition);
    }

    .form-control:focus ~ .input-icon {
        color: var(--primary-color);
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Password Strength Indicator */
    .password-strength {
        margin-top: 0.5rem;
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        transition: var(--transition);
    }

    .password-strength-bar {
        height: 100%;
        width: 0%;
        transition: var(--transition);
    }

    .strength-weak { background: #ff6b9d; width: 25%; }
    .strength-fair { background: #fdcb6e; width: 50%; }
    .strength-good { background: #6c5ce7; width: 75%; }
    .strength-strong { background: #00b894; width: 100%; }

    /* Enhanced Register Button */
    .btn-register {
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

    .btn-register::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(108, 92, 231, 0.4);
        background: var(--gradient-accent);
    }

    .btn-register:hover::before {
        left: 100%;
    }

    .btn-register:active {
        transform: translateY(0);
    }

    /* Loading State */
    .btn-register.loading {
        pointer-events: none;
        opacity: 0.8;
    }

    .btn-register.loading::after {
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

    /* Enhanced Alerts */
    .alert {
        border: none;
        border-radius: 12px;
        font-weight: 500;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        animation: slideInDown 0.5s ease-out;
    }

    .alert-success {
        background: linear-gradient(135deg, #00b894, #00cec9);
        color: white;
        box-shadow: 0 8px 25px rgba(0, 184, 148, 0.3);
    }

    .alert-danger {
        background: linear-gradient(135deg, #ff6b9d, #ff8a9a);
        color: white;
        box-shadow: 0 8px 25px rgba(255, 107, 157, 0.3);
        animation: shake 0.5s ease-in-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Login Link */
    .login-link {
        text-align: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .login-link a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }

    .login-link a:hover {
        color: var(--accent-color);
        text-decoration: underline;
    }

    /* Password Toggle */
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 3.2rem;
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

    /* Form Validation */
    .form-control.is-valid {
        border-color: #00b894;
    }

    .form-control.is-invalid {
        border-color: #ff6b9d;
    }

    .valid-feedback {
        color: #00b894;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .invalid-feedback {
        color: #ff6b9d;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .register-card {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }
        
        .register-title {
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
          <a class="nav-link" href="../Public/shop.php">Shop</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../Public/about.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../Public/contact.php">Contact</a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="User/client_dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="User/cart.php">Cart</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="../Public/login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Enhanced Register Container -->
<div class="register-container">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="register-card">
          <!-- Register Header -->
          <div class="register-header">
            <div class="register-icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <h3 class="register-title">Create Account</h3>
            <p class="register-subtitle">Join TechNest and start shopping today</p>
          </div>

          <!-- Message Area -->
          <div class="mt-3 text-center" id="message"></div>

          <!-- Enhanced Register Form -->
          <form id="registerForm">
            <div class="form-group">
              <label for="name" class="form-label">
                <i class="fas fa-user me-2"></i>Full Name
              </label>
              <div class="position-relative">
                <input type="text" name="name" id="name" class="form-control" required placeholder="Enter your full name" />
              </div>
              <div class="valid-feedback">Looks good!</div>
              <div class="invalid-feedback">Please enter your full name.</div>
            </div>

            <div class="form-group">
              <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Email Address
              </label>
              <div class="position-relative">
                <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email address" />
              </div>
              <div class="valid-feedback">Email looks valid!</div>
              <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>

            <div class="form-group">
              <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Password
              </label>
              <div class="position-relative">
                <input type="password" name="password" id="password" class="form-control" required placeholder="Create a strong password" />
                <button type="button" class="password-toggle" id="togglePassword">
                  <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
              </div>
              <div class="password-strength">
                <div class="password-strength-bar" id="strengthBar"></div>
              </div>
              <div class="valid-feedback">Strong password!</div>
              <div class="invalid-feedback">Password must be at least 6 characters long.</div>
            </div>

            <button type="submit" class="btn btn-register" id="registerBtn">
              <i class="fas fa-user-plus me-2"></i>
              <span id="registerText">Create Account</span>
            </button>
          </form>

          <!-- Login Link -->
          <div class="login-link">
            <small>
              Already have an account? 
              <a href="../Public/login.php">
                <i class="fas fa-sign-in-alt me-1"></i>Sign In
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

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
  const password = this.value;
  const strengthBar = document.getElementById('strengthBar');
  let strength = 0;
  
  // Check password strength
  if (password.length >= 6) strength++;
  if (password.match(/[a-z]/)) strength++;
  if (password.match(/[A-Z]/)) strength++;
  if (password.match(/[0-9]/)) strength++;
  if (password.match(/[^a-zA-Z0-9]/)) strength++;
  
  // Update strength bar
  strengthBar.className = 'password-strength-bar';
  if (strength === 1) strengthBar.classList.add('strength-weak');
  else if (strength === 2) strengthBar.classList.add('strength-fair');
  else if (strength === 3) strengthBar.classList.add('strength-good');
  else if (strength >= 4) strengthBar.classList.add('strength-strong');
});

// Form validation
function validateForm() {
  const name = document.getElementById('name');
  const email = document.getElementById('email');
  const password = document.getElementById('password');
  let isValid = true;
  
  // Validate name
  if (name.value.trim().length < 2) {
    name.classList.add('is-invalid');
    name.classList.remove('is-valid');
    isValid = false;
  } else {
    name.classList.add('is-valid');
    name.classList.remove('is-invalid');
  }
  
  // Validate email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.value)) {
    email.classList.add('is-invalid');
    email.classList.remove('is-valid');
    isValid = false;
  } else {
    email.classList.add('is-valid');
    email.classList.remove('is-invalid');
  }
  
  // Validate password
  if (password.value.length < 6) {
    password.classList.add('is-invalid');
    password.classList.remove('is-valid');
    isValid = false;
  } else {
    password.classList.add('is-valid');
    password.classList.remove('is-invalid');
  }
  
  return isValid;
}

// Add real-time validation
document.querySelectorAll('#registerForm input').forEach(input => {
  input.addEventListener('blur', validateForm);
  input.addEventListener('input', function() {
    if (this.classList.contains('is-invalid')) {
      validateForm();
    }
  });
});

// Enhanced form submission
document.getElementById('registerForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  if (!validateForm()) {
    return;
  }

  const name = document.getElementById('name').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const registerBtn = document.getElementById('registerBtn');
  const registerText = document.getElementById('registerText');
  const messageDiv = document.getElementById('message');

  // Add loading state
  registerBtn.classList.add('loading');
  registerText.textContent = 'Creating Account...';
  registerBtn.disabled = true;

  // Clear previous messages
  messageDiv.innerHTML = '';

  try {
    const response = await fetch('../API/register.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, email, password })
    });

    const result = await response.json();

    if (response.ok && result.status === 'success') {
      // Success animation
      registerText.textContent = 'Account Created!';
      registerBtn.style.background = 'linear-gradient(135deg, #00b894, #00cec9)';
      
      messageDiv.innerHTML = `<div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i>
        ${result.message} <a href="../Public/login.php" class="text-white text-decoration-underline">Login now</a>
      </div>`;
      
      // Reset form after success
      setTimeout(() => {
        this.reset();
        document.querySelectorAll('.form-control').forEach(input => {
          input.classList.remove('is-valid', 'is-invalid');
        });
        document.getElementById('strengthBar').className = 'password-strength-bar';
        
        // Reset button
        registerBtn.classList.remove('loading');
        registerText.textContent = 'Create Account';
        registerBtn.disabled = false;
        registerBtn.style.background = '';
      }, 2000);
      
    } else {
      // Show error
      messageDiv.innerHTML = `<div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        ${result.message || 'Registration failed.'}
      </div>`;
      
      // Reset button state
      registerBtn.classList.remove('loading');
      registerText.textContent = 'Create Account';
      registerBtn.disabled = false;
    }
  } catch (error) {
    console.error('Registration error:', error);
    messageDiv.innerHTML = `<div class="alert alert-danger">
      <i class="fas fa-exclamation-triangle me-2"></i>
      Network error. Please try again.
    </div>`;
    
    // Reset button state
    registerBtn.classList.remove('loading');
    registerText.textContent = 'Create Account';
    registerBtn.disabled = false;
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
</script>

</body>
</html>