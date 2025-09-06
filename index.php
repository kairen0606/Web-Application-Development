<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PR ind - Badminton Store</title>

    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Effect -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
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
            <h2>Browse By Sport</h2>
        </div>
        <div class="product-list">
            <div class="product animate-scale">
                <img src="./img/racket1.png" alt="Badminton">
                <h3>Racket</h3>
                <div class="product-overlay">
                    <button class="product-btn" onclick="">Shop Now</button>
                </div>
            </div>
            <div class="product animate-scale">
                <img src="./img/skirt1.png" alt="Skirt">
                <h3>Skirt</h3>
                <div class="product-overlay">
                    <button class="product-btn">Shop Now</button>
                </div>
            </div>
            <div class="product animate-scale">
                <img src="./img/grid1.png" alt="Grid">
                <h3>Grid</h3>
                <div class="product-overlay">
                    <button class="product-btn">Shop Now</button>
                </div>
            </div>
            <div class="product animate-scale">
                <img src="./img/bag1.jpg" alt="Badminton">
                <h3>Bag</h3>
                <div class="product-overlay">
                    <button class="product-btn">Shop Now</button>
                </div>
            </div>
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
    </script>
</body>

</html>