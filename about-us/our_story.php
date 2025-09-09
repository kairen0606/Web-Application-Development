<?php
// Start session to get user information
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Story - Present PR IND</title>

    <!-- Google Font: Figtree -->
     <link rel="stylesheet" href="../Styles/style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- REMIXICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <style>

        
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--dark-color);
            background-color: var(--light-color);
        }
        
        .video-container {
            padding-top:100px;
            position: relative;
            width: 100%;
            height: 75vh; /* Full viewport height */
            overflow: hidden;
            background-color: #000; /* Fallback */
            margin-bottom: 40px;
        }

        /* Video Element */
        #our-story-video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: 0;
            object-fit: cover;
            opacity: 0.7; /* Slightly transparent for better text contrast */
        }

        /* Text Overlay */
        .brand-statement {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 100%;
            z-index: 1;
            color: white;
            text-transform: uppercase;
            padding: 0 20px;
        }

        .main-title {
            font-family: Figtree;
            font-size: 4.5rem;
            margin: 0;
            letter-spacing: 8px;
            text-shadow: 3px 3px 10px rgba(0,0,0,0.7);
            animation: fadeIn 1.5s ease-out;
            line-height: 1;
        }

        .subtitle {
            font-family: Figtree;
            font-size: 2.5rem;
            margin-top: 20px;
            letter-spacing: 5px;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
            opacity: 0;
            text-align: center;
            animation: fadeIn 1s ease-out 0.5s forwards;
        }

        /* Animation */
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(20px);
            }
            to { 
                opacity: 1; 
                transform: translateY(0);
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .main-title {
                font-size: 3rem;
                letter-spacing: 5px;
            }
            .subtitle {
                font-size: 1.5rem;
                letter-spacing: 3px;
            }
            .video-container {
                height: 80vh;
            }
        }

        .why-choose-section {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            padding: 60px 5%;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .why-choose-image-container {
            flex: 1;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .why-choose-image-container img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        .why-choose-image-container:hover img {
            transform: scale(1.03);
        }

        .story-description {
            flex: 1;
        }
        
        .story-description h2, p {
            text-align: left;
            
        }
        
        .story-description h2{
            font-size: 32px;
            margin-bottom: 8px;
            color: var(--primary-color);
            font-family: 'Figtree', sans-serif;
        }
        
        .story-description p{
            font-size: 16px;
            line-height: 1.5;
            color: var(--dark-color);
        }
        
    </style>
</head>
<body>
    <!-- Note: The PHP includes would need to be adjusted based on your file structure -->
    <?php include '../includes/header.php';?>

    <div class="video-container">
        <video autoplay muted loop id="our-story-video">
            <!-- Replace with a badminton-related video -->
            <source src="../img/pr.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="brand-statement">
            <h1 class="main-title">PRESENT PR IND</h1>
            <p class="subtitle">Excellence in Every Game</p>
        </div>
    </div>

    <section class="why-choose-section" id="why-choose-section" data-aos="fade-up" data-aos-duration="2500">
        <div class="why-choose-image-container">
            <img name="why-choose-image" src="../img/pr.jpg" alt="Our Badminton Story">
        </div>
        <div class="story-description">
            <h2>Our Story</h2>
            <p>
                Founded in 2005 by professional badminton players, Present PR IND was born from a passion for the sport and a commitment to excellence. 
                We started as a small workshop crafting custom rackets for elite players and have grown into a trusted name in badminton equipment.
            </p>
            <br>
            <p>
                Our journey began when our founders noticed a gap in the market for high-quality, performance-driven badminton gear that was accessible to players at all levels. 
                Today, we combine cutting-edge technology with expert craftsmanship to create equipment that enhances performance and withstands the test of time.
            </p>
        </div>
    </section>

 

    <script>
        AOS.init();
        
        // Fallback in case video doesn't load
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('our-story-video');
            video.addEventListener('error', function() {
                // You could set a fallback background image here
                this.parentElement.style.background = 'url("../img/pr.jpg") center/cover no-repeat';
                this.style.display = 'none';
            });
        });
    </script>
    
    <?php include '../includes/footer.php';?>
</body>
</html>