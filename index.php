<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PR ing - Badminton Store</title>

        <link rel="stylesheet" href="Styles/style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" >
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <body>
        <!-- Header -->
        <?php include 'includes/header.php'; ?>

        <!-- Hero -->
        <section class="hero" style="background: url('pr.jpg') no-repeat center center/cover; height:90vh;">  
            <div class="hero-content">
            <h2><i class="fa-solid fa-magnifying-glass"></i>Smash Your Limits</h2>
            <p>Premium Badminton Rackets & Accessories</p>
            <button class="btn" onclick="shopNow()">Shop Now</button>
            </div>
        </section>

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
            function shopNow() {
            alert("Redirecting to Shop Page...");
            window.location.href = "#"; // replace with shop page link
            }
        </script>
    </body>
</html>
