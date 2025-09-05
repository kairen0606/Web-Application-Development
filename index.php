<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PR ing - Badminton Store</title>

    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero -->
<div class="slider-container">
        <div class="slider">
            <div class="slide">
                <img src="./img/pr.jpg">
            </div>
            <div class="slide">
                <img src="./img/YonexS.jpg">
            </div>
            <div class="slide">
                <img src="./img/LiningTBS.jpg">
            </div>
            <div class="slide">
                <img src="。/img/_狂徒 S 2.jpg">
            </div>
            <div class="slide">
                <img src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05" alt="Northern Lights">
            </div>
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
        <h2>Featured Products</h2>
        <div class="product-list">
            <div class="product">
                <img src=".jpg" alt="Racket"> // Add actual image path
                <h3>Racket1</h3>
                <p>Lightweight & powerful racket for advanced players.</p>
                <button class="btn">Buy Now</button>
            </div>
            <div class="product">
                <img src=".jpg" alt="Shuttlecocks">// Add actual image path
                <h3>Racket2</h3>
                <p>Durable feather shuttlecocks for training & tournaments.</p>
                <button class="btn">Buy Now</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <div class="copyright">
        <p>© 2025 MyWebsite. All rights reserved.</p>
    </div>

    <!-- JavaScript -->
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.slider');
            const slides = document.querySelectorAll('.slide');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            const dotsContainer = document.querySelector('.dots');
            const progressBar = document.querySelector('.progress');
            
            let currentSlide = 0;
            let slideInterval;
            const slideDuration = 5000; // 5 seconds per slide
            
            // Create dots for navigation
            slides.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            });
            
            const dots = document.querySelectorAll('.dot');
            
            // Start auto-sliding
            startSlideInterval();
            
            // Function to go to a specific slide
            function goToSlide(n) {
                currentSlide = n;
                updateSlider();
                resetSlideInterval();
            }
            
            // Next slide
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                updateSlider();
                resetSlideInterval();
            }
            
            // Previous slide
            function prevSlide() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                updateSlider();
                resetSlideInterval();
            }
            
            // Update slider position and active dot
            function updateSlider() {
                slider.style.transform = `translateX(-${currentSlide * 100}%)`;
                
                // Update active dot
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentSlide);
                });
                
                // Reset progress bar
                progressBar.style.width = '0%';
            }
            
            // Start the auto-slide interval
            function startSlideInterval() {
                slideInterval = setInterval(nextSlide, slideDuration);
                
                // Animate progress bar
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.width = '100%';
                }, 10);
                
                progressBar.style.transition = `width ${slideDuration}ms linear`;
            }
            
            // Reset the interval when user interacts
            function resetSlideInterval() {
                clearInterval(slideInterval);
                startSlideInterval();
            }
            
            // Event listeners for buttons
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            
            // Pause on hover
            const sliderContainer = document.querySelector('.slider-container');
            sliderContainer.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
                progressBar.style.transition = 'none';
            });
            
            sliderContainer.addEventListener('mouseleave', () => {
                resetSlideInterval();
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            });
            
            // Touch swipe support for mobile devices
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
                    // Swipe left - next slide
                    nextSlide();
                } else if (touchEndX - touchStartX > minSwipeDistance) {
                    // Swipe right - previous slide
                    prevSlide();
                }
            }
        });
    </script>
</body>

</html>