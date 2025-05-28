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
    <link rel="stylesheet" href="../assets/css/contact.css">

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

    <!-- Contact Section -->
    <section class="contact">
        <div class="container">

        <div class="section-subtitle">Get in Touch</div>
        <h2 class="section-title">We'd Love to Hear From You</h2>
        <p class="section-description">Have questions, feedback, or just want to say hello? Fill out the form below and we’ll get back to you shortly.</p>

        <!-- New Contact Info -->
        <div class="contact-info" aria-label="Contact information">
            <div class="info-item">
            <i class="fas fa-map-marker-alt info-icon" aria-hidden="true"></i>
            <div class="info-text">123 TechTrove Ave, Silicon Valley, CA</div>
            </div>
            <div class="info-item">
            <i class="fas fa-phone info-icon" aria-hidden="true"></i>
            <div class="info-text">+1 (555) 123-4567</div>
            </div>
            <div class="info-item">
            <i class="fas fa-envelope info-icon" aria-hidden="true"></i>
            <div class="info-text">contact@techtrove.com</div>
            </div>
        </div>

        <form class="contact-form" action="mailto:your-email@example.com" method="POST" enctype="text/plain" aria-label="Contact form">
            <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Your Name" required autocomplete="name" />
            </div>

            <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="your@email.com" required autocomplete="email" />
            </div>

            <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Message Subject" required />
            </div>

            <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" placeholder="Type your message here..." required></textarea>
            </div>

            <button type="submit" class="cta-button-white">Send Message</button>
        </form>

        </div>
    </section>

    <footer>
        <div class="footer-bottom">
        <p>&copy; 2025 TechTrove. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

