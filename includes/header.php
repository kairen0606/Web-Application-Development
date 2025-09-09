<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <nav class="nav-bar">
        <div class="hamburger-menu">
            <i class="fa-solid fa-bars" aria-label="Menu" onclick="toggleHamburgerMenu()"></i>
        </div>

        <!--logo-->
        <div class="logo"></div>

        <!-- Transparent Overlay -->
        <div class="hamburger-overlay-bg" id="hamburgerOverlayBg" onclick="toggleHamburgerMenu()"></div>

        <!-- Sliding Hamburger Menu -->
        <div class="hamburger-overlay" id="hamburgerOverlay">
            <div class="close-icon"><i class="fa-solid fa-xmark" onclick="toggleHamburgerMenu()"></i></div>
            <ul class="hamburger-menu-list">
                <li><a href="/Web-Application-Development">Home</a></li>
                <li><a href="/Web-Application-Development/Pages/product.php">Product</a></li>
                <li><a href="/Web-Application-Development/about-us/our_story.php">Our Story</a></li>
                <li><a href="/Web-Application-Development/about-us/contact_us.php">Contact Us</a></li>
                <li><a href="/Web-Application-Development/Pages/cart.php">Cart</a></li>
                <li>
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <!-- Show Personal Info if logged in -->
                        <a href="/Web-Application-Development/profile/personalInfo.php">My Account</a>
                    <?php else: ?>

                        <!-- Show Login if not logged in -->
                        <a href="/Web-Application-Development/user/login.php">My Account</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>

        <ul class="nav-menu">
            <li class="dropdown">
                <a href="/Web-Application-Development/Pages/product.php">
                    <span>Product</span><svg viewBox="0 0 13 20">
                        <polyline points="0.5 19.5 3 19.5 12.5 10 3 0.5" />
                    </svg>
                </a>
            </li>

            <li class="dropdown">
                <a href="#"><span>About</span><svg viewBox="0 0 13 20">
                        <polyline points="0.5 19.5 3 19.5 12.5 10 3 0.5" />
                    </svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/Web_Application/about-us/our_story.php">Our Story</a></li>
                    <li><a href="/Web_Application/about-us/contact_us.php">Contact Us</a></li>
                </ul>
            </li>
        </ul>
        <div class="nav-icon">
            <a href="/Web-Application-Development"><i class="fa-solid fa-house"></i></a>

            <?php if (isset($_SESSION['user_email'])): ?>
                <!-- Redirect to Cart if logged in -->
                <a href="/Web-Application-Development/Pages/wishlist.php"><i class="fas fa-heart"></i></a>
            <?php else: ?>
                <!-- Redirect to Login if not logged in -->
                <a href="/Web-Application-Development/user/login.php" onclick="alert('Please log in to access your cart.');"><i class="fa-solid fa-cart-shopping"></i></a>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_email'])): ?>
                <!-- Redirect to Cart if logged in -->
                <a href="/Web-Application-Development/Pages/cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
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
    </nav>
    
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

    function toggleHamburgerMenu() {
        const overlay = document.getElementById('hamburgerOverlay');
        const overlayBg = document.getElementById('hamburgerOverlayBg');
        const html = document.documentElement;
        const body = document.body;

        overlay.classList.toggle('active');
        overlayBg.classList.toggle('active');

        // Prevent scrolling on the main content
        if (overlay.classList.contains('active')) {
            html.style.overflow = 'hidden';
            body.style.overflow = 'hidden';
        } else {
            html.style.overflow = 'auto';
            body.style.overflow = 'auto';
        }
    }
</script>