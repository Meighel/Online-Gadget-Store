<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="../assets/css/thank-you.css">
</head>
<body>
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>

    <div class="success-container">
        <div class="success-icon">
            <div class="checkmark">
                <svg viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        
        <h2 class="success-title">Thank You for Your Purchase!</h2>
        <p class="success-message">Your order has been successfully processed.<br>We'll send you a confirmation email shortly.</p>
        
        <a href="../Public/shop.php" class="continue-btn">
            Continue Shopping
            <svg viewBox="0 0 24 24">
                <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

        <div class="particles">
            <div class="particle" style="--random-x: -200px; --random-y: -150px; animation-delay: 0.1s; width: 4px; height: 4px;"></div>
            <div class="particle" style="--random-x: 180px; --random-y: -120px; animation-delay: 0.3s; width: 3px; height: 3px;"></div>
            <div class="particle" style="--random-x: -150px; --random-y: 200px; animation-delay: 0.5s; width: 5px; height: 5px;"></div>
            <div class="particle" style="--random-x: 220px; --random-y: 180px; animation-delay: 0.7s; width: 4px; height: 4px;"></div>
            <div class="particle" style="--random-x: -100px; --random-y: -200px; animation-delay: 0.9s; width: 3px; height: 3px;"></div>
            <div class="particle" style="--random-x: 160px; --random-y: -180px; animation-delay: 1.1s; width: 4px; height: 4px;"></div>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 TechNest. All rights reserved.</p>
    </footer>

    <script>
        // Add celebration effect on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger confetti-like animation
            setTimeout(() => {
                const particles = document.querySelectorAll('.particle');
                particles.forEach((particle, index) => {
                    setTimeout(() => {
                        particle.style.animationPlayState = 'running';
                    }, index * 100);
                });
            }, 1000);

            // Add smooth scroll behavior
            document.querySelector('.continue-btn').addEventListener('click', function(e) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
</body>
</html>