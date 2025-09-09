<?php
// Start session to get user information
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection settings
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

// Get user ID from session (assuming user is logged in)
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Initialize variables
$wishlistItems = [];

// If user is logged in, get wishlist items
if ($userID > 0) {
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['remove_item'])) {
            $productID = intval($_POST['product_id']);
            
            $deleteSql = "DELETE FROM Wishlist WHERE userID = $userID AND productID = $productID";
            if ($conn->query($deleteSql)) {
                $successMsg = "Item removed from wishlist.";
            } else {
                $errorMsg = "Error removing item: " . $conn->error;
            }
        }
        
        if (isset($_POST['add_to_cart'])) {
            $productID = intval($_POST['product_id']);
            
            // Check if product already in cart
            $checkSql = "SELECT * FROM Cart WHERE userID = $userID AND productID = $productID";
            $checkResult = $conn->query($checkSql);
            
            if ($checkResult && $checkResult->num_rows > 0) {
                // Update quantity if already in cart
                $updateSql = "UPDATE Cart SET quantity = quantity + 1 WHERE userID = $userID AND productID = $productID";
                if ($conn->query($updateSql)) {
                    $successMsg = "Item quantity increased in cart.";
                } else {
                    $errorMsg = "Error adding to cart: " . $conn->error;
                }
            } else {
                // Add new item to cart
                $insertSql = "INSERT INTO Cart (userID, productID, quantity) VALUES ($userID, $productID, 1)";
                if ($conn->query($insertSql)) {
                    $successMsg = "Item added to cart.";
                } else {
                    $errorMsg = "Error adding to cart: " . $conn->error;
                }
            }
        }
    }
    
    // Get wishlist items from database
    $sql = "SELECT w.productID, p.name, p.price, p.description, 
                   (SELECT image_url FROM ProductImages WHERE productID = p.productID LIMIT 1) as image_url
            FROM Wishlist w 
            JOIN Products p ON w.productID = p.productID 
            WHERE w.userID = $userID";
    
    $result = $conn->query($sql);
    
    if ($result) {
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $wishlistItems[] = $row;
            }
        }
    } else {
        $errorMsg = "Error retrieving wishlist items: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist - PR ind</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f7f8fb;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .wishlist-hero {
            padding: 150px 60px 20px;
            text-align: center;
            background: #f7f8fb;
        }
        
        .wishlist-hero h1 {
            margin: 0;
            font-size: 36px;
            color: #1a1a1a;
        }
        
        .wishlist-hero p {
            font-size: 18px;
            color: #666;
            margin-top: 10px;
        }
        
        .wishlist-section {
            padding: 30px 20px 80px;
        }
        
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
        
        .wishlist-item {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .wishlist-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .wishlist-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .wishlist-item-content {
            padding: 20px;
        }
        
        .wishlist-item h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #1a1a1a;
        }
        
        .wishlist-item p {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .wishlist-item-price {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 15px;
        }
        
        .wishlist-item-actions {
            display: flex;
            gap: 10px;
        }
        
        .wishlist-btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .add-to-cart-btn {
            background: #1a1a1a;
            color: white;
        }
        
        .add-to-cart-btn:hover {
            background: #333;
        }
        
        .remove-btn {
            background: #e11d48;
            color: white;
        }
        
        .remove-btn:hover {
            background: #be123c;
        }
        
        .empty-wishlist {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .empty-wishlist i {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ccc;
        }
        
        .empty-wishlist h2 {
            margin-bottom: 15px;
            color: #666;
        }
        
        .empty-wishlist p {
            margin-bottom: 25px;
            color: #888;
        }
        
        .shop-btn {
            display: inline-block;
            padding: 12px 24px;
            background: #1a1a1a;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.2s;
        }
        
        .shop-btn:hover {
            background: #333;
        }
        
        .login-required {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .login-required i {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ffc107;
        }
        
        .login-required h2 {
            margin-bottom: 15px;
            color: #666;
        }
        
        .login-required p {
            margin-bottom: 25px;
            color: #888;
        }
        
        .login-btn {
            display: inline-block;
            padding: 12px 24px;
            background: #1a1a1a;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.2s;
        }
        
        .login-btn:hover {
            background: #333;
        }
        
        @media (max-width: 768px) {
            .wishlist-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
        
        .error-msg {
            background: #ffebee;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #f44336;
            color: #c62828;
        }
        
        .success-msg {
            background: #d4edda;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            color: #155724;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <section class="wishlist-hero">
        <div class="container">
            <h1>Your Wishlist</h1>
            <p>Save your favorite items for later</p>
        </div>
    </section>

    <section class="wishlist-section">
        <div class="container">
            <?php if (isset($errorMsg)): ?>
                <div class="error-msg"><?php echo $errorMsg; ?></div>
            <?php endif; ?>
            
            <?php if (isset($successMsg)): ?>
                <div class="success-msg"><?php echo $successMsg; ?></div>
            <?php endif; ?>
            
            <?php if ($userID == 0): ?>
                <div class="login-required">
                    <i class="fa-solid fa-user-lock"></i>
                    <h2>Login Required</h2>
                    <p>You need to be logged in to view your wishlist.</p>
                    <a href="../user/login.php" class="login-btn">Login Now</a>
                </div>
            <?php elseif (count($wishlistItems) == 0): ?>
                <div class="empty-wishlist">
                    <i class="fa-solid fa-heart"></i>
                    <h2>Your wishlist is empty</h2>
                    <p>Start adding items you love to your wishlist</p>
                    <a href="../product.php" class="shop-btn">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="wishlist-grid">
                    <?php foreach ($wishlistItems as $item): ?>
                        <div class="wishlist-item">
                            <img src="<?php echo $item['image_url'] ? $item['image_url'] : 'https://via.placeholder.com/300x200?text=Product'; ?>" alt="<?php echo $item['name']; ?>">
                            <div class="wishlist-item-content">
                                <h3><?php echo $item['name']; ?></h3>
                                <p><?php echo substr($item['description'], 0, 100); ?>...</p>
                                <div class="wishlist-item-price">RM <?php echo number_format($item['price'], 2); ?></div>
                                <div class="wishlist-item-actions">
                                    <form method="POST" style="flex: 1;">
                                        <input type="hidden" name="product_id" value="<?php echo $item['productID']; ?>">
                                        <button type="submit" name="add_to_cart" class="wishlist-btn add-to-cart-btn">Add to Cart</button>
                                    </form>
                                    <form method="POST" style="flex: 1;">
                                        <input type="hidden" name="product_id" value="<?php echo $item['productID']; ?>">
                                        <button type="submit" name="remove_item" class="wishlist-btn remove-btn">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
        // JavaScript for enhanced functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add confirmation for remove actions
            const removeForms = document.querySelectorAll('form[action$="remove_item"]');
            removeForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to remove this item from your wishlist?')) {
                        e.preventDefault();
                    }
                });
            });
            
            // Add confirmation for add to cart actions
            const addToCartForms = document.querySelectorAll('form[action$="add_to_cart"]');
            addToCartForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // You could add a notification here instead of confirmation
                    // e.preventDefault();
                    // alert('Item added to cart!');
                });
            });
        });
    </script>
</body>
</html>

<?php
// Close database connection
if (isset($conn)) {
    $conn->close();
}
?>