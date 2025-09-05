<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmashGear - Badminton Store</title>
  <style>
    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: #f9f9f9;
      color: #333;
    }

    /* Navbar */
    header {
      background: #111;
      color: #fff;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      font-size: 1.5rem;
      color: #ffcc00;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 1.5rem;
    }

    nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    nav ul li a:hover {
      color: #ffcc00;
    }

    /* Hero Section */
    .hero {
      height: 90vh;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: #fff;
      position: relative;
    }

    .hero::after {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
    }

    .hero-content {
      background-image: url(“pr.jpg”);
      position: relative;
      z-index: 1;
    }

    .hero-content h2 {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .hero-content p {
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
    }

    .btn {
      padding: 0.8rem 1.5rem;
      background: #ffcc00;
      border: none;
      color: #111;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn:hover {
      background: #ffaa00;
    }

    /* Featured Products */
    .products {
      padding: 3rem 2rem;
      text-align: center;
    }

    .products h2 {
      margin-bottom: 2rem;
      font-size: 2rem;
      color: #111;
    }

    .product-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
    }

    .product {
      background: #fff;
      border-radius: 10px;
      padding: 1rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .product:hover {
      transform: translateY(-10px);
    }

    .product img {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 1rem;
    }

    .product h3 {
      margin-bottom: 0.5rem;
      font-size: 1.2rem;
    }

    .product p {
      margin-bottom: 1rem;
      color: #666;
    }

    

    /* Footer */
    footer {
      background: #111;
      color: #fff;
      padding: 2rem;
      text-align: center;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <header>
    <h1>🏸 SmashGear</h1>
    <nav>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Shop</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero -->
  <section class="hero" style="background: url('pr.jpg') no-repeat center center/cover; height:90vh;">  
    <div class="hero-content">
      <h2>Smash Your Limits</h2>
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
  <footer>
    <p>&copy; 2025 SmashGear. All Rights Reserved.</p>
  </footer>

  <!-- JavaScript -->
  <script>
    function shopNow() {
      alert("Redirecting to Shop Page...");
      window.location.href = "#"; // replace with shop page link
    }
  </script>

</body>
</html>
