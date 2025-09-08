<?php
session_start();
// Debug: Remove in production
var_dump($_SESSION);
?>

<header class="header">
    <!--logo-->
    <div class="logo"></div>
    <nav class="header-center">
        <a href="#about">
            <span>About</span><svg viewBox="0 0 13 20">
                <polyline points="0.5 19.5 3 19.5 12.5 10 3 0.5" />
            </svg>
        </a>
        <a href="#contact">
            <span>Contact</span><svg viewBox="0 0 13 20">
                <polyline points="0.5 19.5 3 19.5 12.5 10 3 0.5" />
            </svg>
        </a>
    </nav>
    <div class="header-right">
        <a href="/Web-Application-Development"><i class="fa-solid fa-house"></i></a>
        <?php if (isset($_SESSION['user_email'])): ?>
                <!-- Redirect to Cart if logged in -->
                <a href="/Web-Application-Development/cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
            <?php else: ?>
                <!-- Redirect to Login if not logged in -->
                <a href="/Web-Application-Development/user/login.php" onclick="alert('Please log in to access your cart.');"><i class="fa-solid fa-cart-shopping"></i></a>
            <?php endif; ?>

        <?php if (isset($_SESSION['user_email'])): ?>
                <!-- Show Personal Info if logged in -->
                <a href="/Web-Application-Development/profile/personalInfo.php"><i class="fa-solid fa-user"></i></a>
            <?php else: ?>
            
                <!-- Show Login if not logged in -->
                <a href="/Web-Application-Development/user/login.php" onclick="alert('Please log in to access your profile.');"><i class="fa-solid fa-user"></i></i></a>
            <?php endif; ?>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.header');
        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', function() {

            if (window.scrollY > lastScrollY && window.scrollY > 100) {

                header.classList.add('hidden');
            } else {

                header.classList.remove('hidden');
            }

            lastScrollY = window.scrollY;
        });
    });
</script>