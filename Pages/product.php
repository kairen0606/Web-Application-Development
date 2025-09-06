<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - PR ind Badminton Store</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Lato', sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #e63946;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
        }
        
        .nav-links li {
            margin-left: 25px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #e63946;
        }
        
        .icons {
            display: flex;
            gap: 15px;
        }
        
        .icons i {
            font-size: 20px;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .icons i:hover {
            color: #e63946;
        }
        
        /* Filter Section */
        .filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .filter-group select, .filter-group input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px 15px;
            background: #fff;
        }
        
        .search-box input {
            border: none;
            padding: 10px;
            width: 200px;
            outline: none;
        }
        
        /* Product Grid */
        .products-header {
            text-align: center;
            margin: 40px 0 30px;
        }
        
        .products-header h2 {
            font-size: 32px;
            color: #1d3557;
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }
        
        .products-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #e63946;
        }
        
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .product {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }
        
        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }
        
        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .product:hover img {
            transform: scale(1.05);
        }
        
        .product h3 {
            font-size: 18px;
            font-weight: 700;
            margin: 15px 0 5px;
            padding: 0 15px;
            color: #1d3557;
        }
        
        .product-category {
            color: #457b9d;
            font-size: 14px;
            padding: 0 15px;
            margin-bottom: 10px;
        }
        
        .product-price {
            font-weight: 700;
            color: #e63946;
            font-size: 20px;
            padding: 0 15px 15px;
        }
        
        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(29, 53, 87, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .product:hover .product-overlay {
            opacity: 1;
        }
        
        .product-btn {
            background-color: #e63946;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .product-btn:hover {
            background-color: #c1121f;
        }
        
        /* Category Navigation */
        .category-nav {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .category-btn {
            padding: 10px 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .category-btn.active, .category-btn:hover {
            background: #1d3557;
            color: white;
            border-color: #1d3557;
        }
        
        /* Footer */
        .footer {
            background-color: #1d3557;
            color: white;
            padding: 40px 0 20px;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 40px;
            margin-bottom: 30px;
        }
        
        .footer-section {
            flex: 1;
            min-width: 200px;
        }
        
        .footer-section h3 {
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background-color: #e63946;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #f1faee;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #e63946;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-icons a {
            color: white;
            font-size: 20px;
            transition: color 0.3s;
        }
        
        .social-icons a:hover {
            color: #e63946;
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #457b9d;
            font-size: 14px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .product-list {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            
            .nav-links {
                display: none;
            }
        }
        
        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Header -->


    <div class="container">
        <!-- Category Navigation -->
        <div class="category-nav">
            <button class="category-btn active" data-category="all">All Products</button>
            <button class="category-btn" data-category="1">Rackets</button>
            <button class="category-btn" data-category="2">Clothes</button>
            <button class="category-btn" data-category="3">Grips</button>
            <button class="category-btn" data-category="4">Bags</button>
        </div>

        <!-- Filter Section -->
        <div class="filters">
            <div class="filter-group">
                <span>Sort by:</span>
                <select id="sortSelect">
                    <option value="default">Default</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="name">Name</option>
                </select>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search products...">
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-header">
            <h2>Our Products</h2>
        </div>

        <div class="product-list">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root"; // Change if needed
            $password = ""; // Change if needed
            $dbname = "pr_ind_db";
            
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            // Get category filter from URL if set
            $categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'all';
            
            // Build SQL query - fixed to only use valid category IDs (1-4)
            $sql = "SELECT p.productID, p.name, p.description, p.price, p.colour, 
                           c.name as category_name, c.categoryID,
                           pi.image_url 
                    FROM Products p 
                    JOIN Categories c ON p.categoryID = c.categoryID 
                    LEFT JOIN ProductImages pi ON p.productID = pi.productID 
                    WHERE pi.image_type = 'Front' OR pi.image_type IS NULL";
            
            if ($categoryFilter != 'all') {
                $sql .= " AND p.categoryID = " . intval($categoryFilter);
            }
            
            $sql .= " GROUP BY p.productID ORDER BY p.productID";
            
            $result = $conn->query($sql);

            
            
            if ($result && $result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<div class="product animate-scale" data-category="' . $row['category_name'] . '" data-price="' . $row['price'] . '" data-name="' . htmlspecialchars($row['name']) . '">';
$image_url = "https://source.unsplash.com/random/300x200/?badminton," . urlencode($row['category_name']);
echo '<img src="' . $image_url . '" alt="' . htmlspecialchars($row['name']) . '">';                    echo '<div class="product-category">' . $row['category_name'] . '</div>';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<div class="product-price">RM ' . number_format($row['price'], 2) . '</div>';
                    echo '<div class="product-overlay">';
                    echo '<button class="product-btn" onclick="viewProduct(' . $row['productID'] . ')">View Details</button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-products">No products found.</p>';
                
                // Debug information
                if (!$result) {
                    echo '<p class="no-products">SQL Error: ' . $conn->error . '</p>';
                }
            }
            
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p>PR ind is a premium badminton equipment store providing high-quality products for players of all levels.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#">Products</a></li>
                        <li><a href="#">Collections</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Sport Street, Kuala Lumpur</p>
                    <p><i class="fas fa-phone"></i> +60 12 345 6789</p>
                    <p><i class="fas fa-envelope"></i> info@prind.com</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>© 2025 PR ind. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Category filter event listeners
        const categoryButtons = document.querySelectorAll('.category-btn');
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category');
                // Redirect to the same page with category filter
                window.location.href = 'products.php?category=' + categoryId;
            });
        });
        
        // Set active category button based on URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const categoryParam = urlParams.get('category') || 'all';
        
        categoryButtons.forEach(button => {
            if (button.getAttribute('data-category') === categoryParam) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
        
        // Sort functionality
        const sortSelect = document.getElementById('sortSelect');
        sortSelect.addEventListener('change', function() {
            const products = Array.from(document.querySelectorAll('.product'));
            const sortedProducts = sortProducts(products, this.value);
            
            const productList = document.querySelector('.product-list');
            productList.innerHTML = '';
            sortedProducts.forEach(product => productList.appendChild(product));
        });
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const products = document.querySelectorAll('.product');
            
            products.forEach(product => {
                const productName = product.getAttribute('data-name').toLowerCase();
                const productCategory = product.getAttribute('data-category').toLowerCase();
                
                if (productName.includes(query) || productCategory.includes(query)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        });
        
        // Function to sort products
        function sortProducts(productsToSort, sortBy) {
            switch(sortBy) {
                case 'price-low':
                    return [...productsToSort].sort((a, b) => 
                        parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price')));
                case 'price-high':
                    return [...productsToSort].sort((a, b) => 
                        parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price')));
                case 'name':
                    return [...productsToSort].sort((a, b) => 
                        a.getAttribute('data-name').localeCompare(b.getAttribute('data-name')));
                default:
                    return productsToSort;
            }
        }
        
        // Function to view product details
        function viewProduct(productId) {
            window.location.href = 'product_detail.php?id=' + productId;
        }
        
        // Initialize animation for product cards
        document.addEventListener('DOMContentLoaded', function() {
            const productCards = document.querySelectorAll('.product');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            productCards.forEach(card => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>