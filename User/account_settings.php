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
    <title>Account Settings - TechNest</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/shop_styles.css">
    
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .settings-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .settings-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .settings-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--dark-color);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .settings-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }

        .settings-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: var(--transition);
        }

        .settings-card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-5px);
        }

        .card-header {
            background: var(--secondary-color);
            color: white;
            padding: 1.5rem;
            border: none;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
            background: white;
        }

        .btn-save {
            background: var(--gradient-primary);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 92, 231, 0.4);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #e17055, #fd79a8);
            color: white;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--secondary-color);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .security-section {
            margin-top: 2rem;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .strength-fill {
            height: 100%;
            transition: var(--transition);
            border-radius: 2px;
        }

        .strength-weak { background: #e17055; width: 25%; }
        .strength-fair { background: #fdcb6e; width: 50%; }
        .strength-good { background: #00b894; width: 75%; }
        .strength-strong { background: #00cec9; width: 100%; }

        @media (max-width: 768px) {
            .settings-title {
                font-size: 2rem;
            }
            
            .profile-stats {
                grid-template-columns: 1fr;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }

        .nav-link {
            transition: var(--transition);
        }

        .nav-link:hover {
            color: var(--light-color) !important;
        }

        #cartCount {
            background: var(--gradient-accent) !important;
        }

        .btn-outline-light:hover {
            background: var(--gradient-primary);
            border-color: transparent;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand">
        <div class="container">
            <a class="navbar-brand" href="../index.php">TechNest</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center gap-2">
                <li class="nav-item">
                <a href="../Public/shop.php" class="nav-link text-white">Shop</a>
                </li>

                <li class="nav-item position-relative">
                    <a class="nav-link text-white" href="cart.php">
                    <i class="fas fa-shopping-cart me-1"></i>
                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="my_orders.php" class="nav-link text-white">Orders</a>
                </li>
                <li class="nav-item">
                    <a href="account_settings.php" class="nav-link text-white">Settings</a>
                </li>
                <li class="nav-item">
                    <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
                </li>
            </ul>
            </div>
        </div>
    </nav>


<div class="settings-container">
    <div class="settings-header">
        <h1 class="settings-title">Account Settings</h1>
        <p class="settings-subtitle">Manage your profile and account preferences</p>
    </div>

    <div class="profile-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="stat-label">Account Status</div>
            <div class="stat-value">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-label">Member Since</div>
            <div class="stat-value">2024</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-label">Security Level</div>
            <div class="stat-value">High</div>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($success) ?>
        </div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="settings-card">
        <div class="card-header">
            <h4>
                <i class="fas fa-user-edit"></i>
                Profile Information
            </h4>
        </div>
        <div class="card-body">
            <form action="../API/config.php" method="POST" id="settingsForm">
                <input type="hidden" name="action" value="update_profile">

                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-user"></i>
                        Full Name
                    </label>
                    <input type="text" name="name" id="name" class="form-control" 
                           value="<?= htmlspecialchars($user['name']) ?>" required
                           placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" name="email" id="email" class="form-control" 
                           value="<?= htmlspecialchars($user['email']) ?>" required
                           placeholder="Enter your email address">
                </div>

                <div class="security-section">
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            New Password
                        </label>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Leave blank to keep current password">
                        <small class="form-text text-muted">
                            Password must be at least 8 characters long
                        </small>
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <small class="strength-text" id="strengthText"></small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="fas fa-save me-2"></i>
                        Save Changes
                    </button>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Changes will take effect immediately
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Logout functionality
    document.getElementById('logoutBtn').addEventListener('click', async () => {
        await fetch('../API/logout.php', { method: 'POST' });
        window.location.href = '../Public/login.php';
    });

    // Cart count
    fetch('../API/get_cart_count.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('cartCount').innerText = data.count || 0;
        })
        .catch(err => console.error('Failed to fetch cart count:', err));

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length === 0) {
            strengthIndicator.style.display = 'none';
            return;
        }

        strengthIndicator.style.display = 'block';
        
        let strength = 0;
        let strengthLabel = '';
        
        // Check password criteria
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        // Remove existing classes
        strengthFill.className = 'strength-fill';
        
        if (strength <= 2) {
            strengthFill.classList.add('strength-weak');
            strengthLabel = 'Weak';
        } else if (strength === 3) {
            strengthFill.classList.add('strength-fair');
            strengthLabel = 'Fair';
        } else if (strength === 4) {
            strengthFill.classList.add('strength-good');
            strengthLabel = 'Good';
        } else {
            strengthFill.classList.add('strength-strong');
            strengthLabel = 'Strong';
        }
        
        strengthText.textContent = `Password strength: ${strengthLabel}`;
    });

    // Form validation and animation
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('.btn-save');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        btn.disabled = true;
        
        // Re-enable button after a delay (in case of form errors)
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 3000);
    });

    // Add subtle animations to form inputs
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
</script>

</body>
</html>