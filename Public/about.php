<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TechTrove Gadget Store</title>
    <link rel="stylesheet" href="../assets/css/settings_about.css">
    <link rel="icon" href="images/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/about.css">
    <link rel="stylesheet" href="../assets/css/shop_styles.css">
    <link rel="icon" href="../assets/images/favicon.png">
</head>
<body>

<div class="main-content">
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
                <li class="nav-item"><a class="nav-link" href="User/client_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="User/cart.php">Cart</a></li>
                <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="section-subtitle">About Us</div>
            <h1 class="section-title">Empowering Your Digital Lifestyle</h1>
            <p class="section-description">At TechTrove, we deliver the latest gadgets and smart devices to enhance how you live, work, and play.</p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission">
        <div class="container mission-container">
            <div class="mission-image">
                <img src="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=1000&q=80" alt="Gadget showcase">
            </div>
            <div class="mission-content">
                <div class="section-subtitle">Our Mission</div>
                <h2>Making Technology Accessible to Everyone</h2>
                <p>TechTrove is committed to bringing top-quality gadgets to your doorstep. Whether it's the newest smartphone, gaming gear, or smart home devices, we ensure you get the best technology at competitive prices.</p>
                <p>Founded in 2023 by passionate tech enthusiasts, we aim to make innovative products more accessible and provide a seamless shopping experience online and in-store.</p>
                <p>We believe that great tech should be for everyone. That’s why we prioritize customer satisfaction, fast delivery, and expert support.</p>
            </div>
        </div>
    </section>


    <!-- Team Section -->
    <section class="team">
        <div class="container">
            <div class="team-header">
                <div class="section-subtitle">Meet Our Team</div>
                <h2 class="section-title">The People Behind TechTrove</h2>
                <p class="section-description">Our dedicated team combines tech expertise with a passion for innovation and customer service.</p>
            </div>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-info">
                        <h3 class="member-name">Meighel Nicolle Padon</h3>
                        <div class="member-role">CEO & Founder</div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-info">
                        <h3 class="member-name">Ken Cinco</h3>
                        <div class="member-role">Chief Technology Officer</div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-info">
                        <h3 class="member-name">Frances Bea Magdayao</h3>
                        <div class="member-role">Head of Product</div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-info">
                        <h3 class="member-name">Carla Jane Lagan</h3>
                        <div class="member-role">Customer Experience Manager</div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-header">
                <div class="section-subtitle">Our Reach</div>
                <h2 class="section-title">Delivering Technology Nationwide</h2>
                <p class="section-description">Trusted by thousands of tech lovers across the country.</p>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">100K+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Gadgets in Stock</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4.9</div>
                    <div class="stat-label">Average Rating</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Customer Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-header">
                <div class="section-subtitle">What We Offer</div>
                <h2 class="section-title">TechTrove Features</h2>
                <p class="section-description">The smart way to shop for gadgets.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3 class="feature-title">Fast Delivery</h3>
                    <p class="feature-description">Get your gadgets delivered quickly and safely across the country.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3 class="feature-title">Best Prices</h3>
                    <p class="feature-description">Competitive prices on top-rated gadgets and accessories.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Warranty Protection</h3>
                    <p class="feature-description">Peace of mind with warranty options on all purchases.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="feature-title">Flexible Payment</h3>
                    <p class="feature-description">Enjoy multiple payment options including COD, credit, and installments.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3 class="feature-title">Exclusive Deals</h3>
                    <p class="feature-description">Subscribe to get access to exclusive tech promos and discounts.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-title">Live Support</h3>
                    <p class="feature-description">Our tech experts are ready to help, 24/7.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-container">
                <h2 class="cta-title">Ready to Upgrade Your Gear?</h2>
                <p class="cta-description">Join thousands of happy customers who trust TechTrove for their gadgets.</p>
                <a href="shop.php" class="cta-button-white">Shop Now</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <h3>TechTrove</h3>
                    <p>Your one-stop shop for the latest and greatest in personal technology and gadgets.</p>
                </div>
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="shop.html">Shop</a></li>
                        <li><a href="faq.html">FAQ</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Categories</h3>
                    <ul class="footer-links">
                        <li><a href="#">Smartphones</a></li>
                        <li><a href="#">Laptops</a></li>
                        <li><a href="#">Gaming</a></li>
                        <li><a href="#">Accessories</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Contact Us</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-envelope"></i> support@techtrove.com</a></li>
                        <li><a href="#"><i class="fas fa-phone"></i> +1 (800) 987-6543</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> 456 Tech Avenue, Silicon Valley</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 TechTrove. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>

</body>
</html>
