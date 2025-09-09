<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Story</title>

    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- REMIXICONS -->
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        .video-container {
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
            font-size: 1.5rem;
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

        .story-description{
            align-content: center;
            margin: 0 60px;
        }
        .story-description h2, p {
            text-align: left;
            
        }
        .story-description h2{
            font-size: 32px;
            margin-bottom: 8px;
        }
        .story-description p{
            font-size: 16px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php';?>

    <div class="video-container">
        <video autoplay muted loop id="our-story-video">
            <source src="../img/our_story.mp4" type="video/mp4">
        </video>

        <div class="brand-statement">
            <h1 class="main-title">TUB Clothing</h1>
            <p class="subtitle">No rules. Just Statement.</p>
        </div>
    </div>

    <section class="why-choose-section" id="why-choose-section" data-aos="fade-up" data-aos-duration="2500">
        <div class="why-choose-image-container">
            <img name="why-choose-image" src="../img/story.jpg" alt="Our Story Banner">
        </div>
        <div class="story-description">
            <h2>Our Story</h2>
            <p>
            Born in the streets of NYC in 1997, TUB was forged from rebellion and raw self-expression. We broke the rules then, and we’re 
            rewriting them now—merging downtown edge with Southeast Asian soul. This is clothing that doesn’t whisper, but shouts.
            </p>
            <br>
            <p>
            At TUB, apparel is our canvas. Each piece tells a story, sparking conversations and bridging cultures. This is more than clothing; 
            it’s a testament to our roots and a bold declaration of what our region can offer the world.
            </p>
        </div>
    </section>

    <script>
        AOS.init()
    </script>
    
    <?php include '../includes/footer.php';?>
</body>
</html>