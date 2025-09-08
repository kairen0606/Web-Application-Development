<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PR ind - Badminton Store</title>

    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero -->
    <div class="slider-container">
        <div class="slider">
            <div class="slide"><img src="./img/slider1.png"></div>
            <div class="slide"><img src="./img/slider2.png"></div>
            <div class="slide"><img src="./img/slider3.png"></div>
            <div class="slide"><img src="./img/slider4.png"></div>
            <div class="slide"><img src="./img/slider5.png"></div>
        </div>
        <div class="controls">
            <button class="prev-btn">&#10094;</button>
            <button class="next-btn">&#10095;</button>
        </div>

        <div class="dots"></div>

        <div class="progress-bar">
            <div class="progress"></div>
        </div>
    </div>

    <!-- Featured Products -->
    <section class="products">
        <div class="products-header animate-on-scroll">
            <h2>Browse By Category</h2>
        </div>
        <div class="product-list">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "pr_ind_db";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch only 4 categories from database to show in one row
            $sql = "SELECT categoryID, name, description FROM Categories LIMIT 4";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product animate-scale">';

                    // Set appropriate image based on category
                    $imagePath = "";
                    $categoryName = strtolower($row['name']);

                    if ($categoryName === 'racket') {
                        $imagePath = './img/racket1.png';
                    } elseif ($categoryName === 'clothes') {
                        $imagePath = './img/clothes1.png';
                    } elseif ($categoryName === 'grip') {
                        $imagePath = './img/grid1.png';
                    } elseif ($categoryName === 'bag') {
                        $imagePath = './img/bag1.png';
                    } else {
                        // Default image if category doesn't match
                        $imagePath = 'https://source.unsplash.com/random/300x200/?badminton,' . urlencode($row['name']);
                    }

                    echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<div class="product-overlay">';
                    echo '<a href="./Pages/product.php?category=' . $row['categoryID'] . '" class="product-btn">Shop Now</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                // Fallback to hardcoded categories if database is empty
                $fallbackCategories = [
                    ['id' => 1, 'name' => 'Racket', 'image' => './img/racket1.png'],
                    ['id' => 2, 'name' => 'Clothes', 'image' => './img/clothes1.png'],
                    ['id' => 3, 'name' => 'Grip', 'image' => './img/grid1.png'],
                    ['id' => 4, 'name' => 'Bag', 'image' => './img/bag1.png']
                ];

                foreach ($fallbackCategories as $category) {
                    echo '<div class="product animate-scale">';
                    echo '<img src="' . $category['image'] . '" alt="' . $category['name'] . '">';
                    echo '<h3>' . $category['name'] . '</h3>';
                    echo '<div class="product-overlay">';
                    echo '<a href="../Pages/product.php?category=' . $category['id'] . '" class="product-btn">Shop Now</a>';
                    echo '</div>';
                    echo '</div>';
                }
            }

            $conn->close();
            ?>
        </div>
    </section>

    <!--New Arrival-->
    <div class="item-showcase">
        <div class="item-image animate-left">
            <img src="./img/item.png" alt="item">
        </div>

        <div class="item-info animate-right">
            <span class="new-arrival-tag">New Arrival</span>
            <h3>Harimau Series</h3>
            <p>This is a high-performance badminton racket designed for players who seek both power and speed. Inspired by the strength and agility of the Malayan tiger, the racket features bold tiger-themed aesthetics that reflect dominance and fierceness.</p>
            <a href="#" class="item-btn">LEARN MORE</a>
        </div>
    </div>

    <div class="item-showcase">
        <div class="item-info animate-left">
            <span class="new-arrival-tag" style="text-align:right">Comming Soon</span>
            <h3>Pickle Ball Racket</h3>
            <p>This is a P.R IND (Purui) Lustre Fruit Series** pickleball paddle, featuring multiple colorful designs with fruit-themed patterns.</p>
            <a href="#" class="item-btn">LEARN MORE</a>
        </div>

        <div class="item-image animate-right">
            <img src="./img/new1.png" alt="item">
        </div>

    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <div class="copyright">
        <p>© 2025 MyWebsite. All rights reserved.</p>
    </div>

    <!-- JavaScript -->
    <script>
        // Slider
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.slider');
            const slides = document.querySelectorAll('.slide');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            const dotsContainer = document.querySelector('.dots');
            const progressBar = document.querySelector('.progress');

            let currentSlide = 0;
            let slideInterval;
            const slideDuration = 5000;

            slides.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            });

            const dots = document.querySelectorAll('.dot');

            startSlideInterval();

            function goToSlide(n) {
                currentSlide = n;
                updateSlider();
                resetSlideInterval();
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                updateSlider();
                resetSlideInterval();
            }

            function prevSlide() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                updateSlider();
                resetSlideInterval();
            }

            function updateSlider() {
                slider.style.transform = `translateX(-${currentSlide * 100}%)`;

                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentSlide);
                });

                progressBar.style.width = '0%';
            }

            function startSlideInterval() {
                slideInterval = setInterval(nextSlide, slideDuration);

                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.width = '100%';
                }, 10);

                progressBar.style.transition = `width ${slideDuration}ms linear`;
            }

            function resetSlideInterval() {
                clearInterval(slideInterval);
                startSlideInterval();
            }

            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);

            const sliderContainer = document.querySelector('.slider-container');
            sliderContainer.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
                progressBar.style.transition = 'none';
            });

            sliderContainer.addEventListener('mouseleave', () => {
                resetSlideInterval();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            });

            let touchStartX = 0;
            let touchEndX = 0;

            sliderContainer.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            }, false);

            sliderContainer.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, false);

            function handleSwipe() {
                const minSwipeDistance = 50;

                if (touchStartX - touchEndX > minSwipeDistance) {
                    nextSlide();
                } else if (touchEndX - touchStartX > minSwipeDistance) {
                    prevSlide();
                }
            }
        });

        // Function to check if element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight * 0.9) &&
                rect.bottom >= (window.innerHeight * 0.1)
            );
        }

        // Function to handle scroll animation
        function handleScrollAnimation() {
            const elements = document.querySelectorAll('.animate-on-scroll, .animate-left, .animate-right, .animate-scale');

            elements.forEach(element => {
                if (isInViewport(element)) {
                    element.classList.add('visible');
                } else {
                    element.classList.remove('visible');
                }
            });
        }

        // Initialize animation state on page load
        document.addEventListener('DOMContentLoaded', function() {
            handleScrollAnimation();

            // Add event listener with debouncing for performance
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(handleScrollAnimation, 50);
            });
        });

        // Function to view products by category
        function viewCategory(categoryId) {
            window.location.href = '../Pages/product.php?category=' + categoryId;
        }
    </script>
</body>

</html>