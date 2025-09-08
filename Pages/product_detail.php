<?php
session_start();

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

// Get product ID
$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Add to cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please log in to add items to cart";
        header("Location: login.php");
        exit();
    }
    
    $variantID = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $userID = $_SESSION['user_id'];
    
    // Check stock availability
    $stockCheck = $conn->prepare("SELECT stock FROM ProductVariants WHERE variantID = ?");
    $stockCheck->bind_param("i", $variantID);
    $stockCheck->execute();
    $stockResult = $stockCheck->get_result();
    
    if ($stockResult->num_rows > 0) {
        $variant = $stockResult->fetch_assoc();
        if ($variant['stock'] >= $quantity) {
            // Add to cart (cart table needs to be created)
            $addToCart = $conn->prepare("INSERT INTO Cart (userID, productID, variantID, quantity) VALUES (?, ?, ?, ?)");
            $addToCart->bind_param("iiii", $userID, $productID, $variantID, $quantity);
            
            if ($addToCart->execute()) {
                $_SESSION['cart_message'] = "Product successfully added to cart!";
            } else {
                $_SESSION['error'] = "Failed to add to cart, please try again";
            }
        } else {
            $_SESSION['error'] = "Insufficient stock, current stock: " . $variant['stock'];
        }
    }
}

// Add to wishlist functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please log in to add items to wishlist";
        header("Location: login.php");
        exit();
    }
    
    $userID = $_SESSION['user_id'];
    
    // Check if already in wishlist
    $checkWishlist = $conn->prepare("SELECT * FROM Wishlist WHERE userID = ? AND productID = ?");
    $checkWishlist->bind_param("ii", $userID, $productID);
    $checkWishlist->execute();
    
    if ($checkWishlist->get_result()->num_rows > 0) {
        $_SESSION['error'] = "This product is already in your wishlist";
    } else {
        // Add to wishlist (Wishlist table needs to be created)
        $addWishlist = $conn->prepare("INSERT INTO Wishlist (userID, productID, created_at) VALUES (?, ?, NOW())");
        $addWishlist->bind_param("ii", $userID, $productID);
        
        if ($addWishlist->execute()) {
            $_SESSION['success'] = "Product added to wishlist!";
        } else {
            $_SESSION['error'] = "Failed to add to wishlist, please try again";
        }
    }
}

// Get product details
$product = null;
$images = [];
$variants = [];
$relatedProducts = [];

if ($productID > 0) {
    // Get basic product information
    $productSql = "SELECT p.*, c.name as category_name 
                   FROM Products p 
                   JOIN Categories c ON p.categoryID = c.categoryID 
                   WHERE p.productID = ?";
    $stmt = $conn->prepare($productSql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    
    if ($product) {
        // Get product images
        $imageSql = "SELECT image_url FROM ProductImages WHERE productID = ?";
        $imageStmt = $conn->prepare($imageSql);
        $imageStmt->bind_param("i", $productID);
        $imageStmt->execute();
        $imageResult = $imageStmt->get_result();
        
        while ($row = $imageResult->fetch_assoc()) {
            $images[] = $row['image_url'];
        }
        
        if (empty($images)) {
            $images[] = "https://source.unsplash.com/random/600x400/?badminton," . urlencode($product['category_name']);
        }
        
        // Get product variants
        $variantSql = "SELECT * FROM ProductVariants WHERE productID = ? ORDER BY size, weight";
        $variantStmt = $conn->prepare($variantSql);
        $variantStmt->bind_param("i", $productID);
        $variantStmt->execute();
        $variantResult = $variantStmt->get_result();
        
        while ($row = $variantResult->fetch_assoc()) {
            $variants[] = $row;
        }
        
        // Get related products
        $relatedSql = "SELECT p.productID, p.name, p.price, pi.image_url 
                      FROM Products p 
                      LEFT JOIN ProductImages pi ON p.productID = pi.productID 
                      WHERE p.categoryID = ? AND p.productID != ? 
                      GROUP BY p.productID 
                      LIMIT 4";
        $relatedStmt = $conn->prepare($relatedSql);
        $relatedStmt->bind_param("ii", $product['categoryID'], $productID);
        $relatedStmt->execute();
        $relatedResult = $relatedStmt->get_result();
        
        while ($row = $relatedResult->fetch_assoc()) {
            $relatedProducts[] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product ? htmlspecialchars($product['name']) : 'Product Details'; ?> - PR ind Badminton Store</title>
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../Styles/product_detail.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['cart_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['cart_message']; unset($_SESSION['cart_message']); ?></div>
        <?php endif; ?>

        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb">
            <a href="../index.php">Home</a> &gt; 
            <a href="product.php">Products</a> &gt; 
            <?php if ($product): ?>
                <a href="product.php?category=<?php echo $product['categoryID']; ?>"><?php echo $product['category_name']; ?></a> &gt; 
            <?php endif; ?>
            <span>Product Details</span>
        </div>

        <?php if (!$product): ?>
            <div class="alert alert-error">Product not found</div>
        <?php else: ?>
            <form method="POST" action="product_detail.php?id=<?php echo $productID; ?>">
                <div class="product-detail">
                    <div class="product-images">
                        <img src="<?php echo $images[0]; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-image" id="mainImage">
                        
                        <?php if (count($images) > 1): ?>
                            <div class="thumbnail-container">
                                <?php foreach ($images as $index => $image): ?>
                                    <img src="<?php echo $image; ?>" alt="Thumbnail <?php echo $index+1; ?>" class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" data-image="<?php echo $image; ?>">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-info">
                        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                        <div class="product-category"><?php echo $product['category_name']; ?></div>
                        <div class="product-price">RM <?php echo number_format($product['price'], 2); ?></div>
                        
                        <div class="product-description">
                            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        </div>
                        
                        <?php if (!empty($variants)): ?>
                            <div class="product-variants">
                                <input type="hidden" name="variant_id" id="selectedVariant" value="<?php echo $variants[0]['variantID']; ?>" required>
                                
                                <?php 
                                // Group variant options
                                $sizeOptions = array_filter($variants, function($v) { return !empty($v['size']); });
                                $weightOptions = array_filter($variants, function($v) { return !empty($v['weight']); });
                                $gripOptions = array_filter($variants, function($v) { return !empty($v['grip_size']); });
                                ?>
                                
                                <?php if (!empty($sizeOptions)): ?>
                                    <div class="variant-group">
                                        <label>Size:</label>
                                        <div class="variant-options">
                                            <?php foreach ($sizeOptions as $variant): ?>
                                                <div class="variant-option <?php echo $variant['stock'] <= 0 ? 'disabled' : ''; ?>" 
                                                     data-variant-id="<?php echo $variant['variantID']; ?>"
                                                     data-type="size">
                                                    <?php echo $variant['size']; ?>
                                                    <?php if ($variant['stock'] <= 5 && $variant['stock'] > 0): ?>
                                                        <div class="stock-info">Only <?php echo $variant['stock']; ?> left</div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($weightOptions)): ?>
                                    <div class="variant-group">
                                        <label>Weight:</label>
                                        <div class="variant-options">
                                            <?php foreach ($weightOptions as $variant): ?>
                                                <div class="variant-option <?php echo $variant['stock'] <= 0 ? 'disabled' : ''; ?>" 
                                                     data-variant-id="<?php echo $variant['variantID']; ?>"
                                                     data-type="weight">
                                                    <?php echo $variant['weight']; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($gripOptions)): ?>
                                    <div class="variant-group">
                                        <label>Grip Size:</label>
                                        <div class="variant-options">
                                            <?php foreach ($gripOptions as $variant): ?>
                                                <div class="variant-option <?php echo $variant['stock'] <= 0 ? 'disabled' : ''; ?>" 
                                                     data-variant-id="<?php echo $variant['variantID']; ?>"
                                                     data-type="grip">
                                                    <?php echo $variant['grip_size']; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="quantity-selector">
                            <label>Quantity:</label>
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn" id="decreaseQty">-</button>
                                <input type="number" name="quantity" id="quantity" class="quantity-input" value="1" min="1" max="10" readonly>
                                <button type="button" class="quantity-btn" id="increaseQty">+</button>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" name="add_to_cart" class="add-to-cart">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            <button type="submit" name="add_to_wishlist" class="wishlist-btn">
                                <i class="fas fa-heart"></i> Add to Wishlist
                            </button>
                        </div>
                        
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Category:</span>
                                <span class="meta-value"><?php echo $product['category_name']; ?></span>
                            </div>
                            <?php if (!empty($product['colour'])): ?>
                                <div class="meta-item">
                                    <span class="meta-label">Color:</span>
                                    <span class="meta-value"><?php echo $product['colour']; ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="meta-item">
                                <span class="meta-label">Product ID:</span>
                                <span class="meta-value">PR-<?php echo $product['productID']; ?></span>
                            </div>
                            <?php if (!empty($variants) && $variants[0]['stock'] > 0): ?>
                                <div class="meta-item">
                                    <span class="meta-label">Stock Status:</span>
                                    <span class="meta-value" style="color: #28a745;">In Stock</span>
                                </div>
                            <?php elseif (empty($variants)): ?>
                                <div class="meta-item">
                                    <span class="meta-label">Stock Status:</span>
                                    <span class="meta-value" style="color: #28a745;">In Stock</span>
                                </div>
                            <?php else: ?>
                                <div class="meta-item">
                                    <span class="meta-label">Stock Status:</span>
                                    <span class="meta-value" style="color: #dc3545;">Out of Stock</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
            
            <?php if (!empty($relatedProducts)): ?>
                <div class="related-products">
                    <h2 class="section-title">Related Products</h2>
                    <div class="related-grid">
                        <?php foreach ($relatedProducts as $related): ?>
                            <div class="related-product">
                                <img src="<?php echo !empty($related['image_url']) ? $related['image_url'] : 'https://source.unsplash.com/random/300x200/?badminton'; ?>" alt="<?php echo htmlspecialchars($related['name']); ?>">
                                <div class="related-product-content">
                                    <h3><?php echo htmlspecialchars($related['name']); ?></h3>
                                    <div class="related-product-price">RM <?php echo number_format($related['price'], 2); ?></div>
                                </div>
                                <div class="related-product-overlay">
                                    <a href="product_detail.php?id=<?php echo $related['productID']; ?>" class="view-details-btn">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
    <div class="copyright">
        <p>© 2025 PR ind. All rights reserved.</p>
    </div>

    <script>
        // Execute after page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Thumbnail click event
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const mainImage = document.getElementById('mainImage');
                    mainImage.src = this.getAttribute('data-image');
                    
                    // Update active thumbnail
                    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Quantity controls
            document.getElementById('increaseQty').addEventListener('click', function() {
                const quantityInput = document.getElementById('quantity');
                if (parseInt(quantityInput.value) < 10) {
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                }
            });
            
            document.getElementById('decreaseQty').addEventListener('click', function() {
                const quantityInput = document.getElementById('quantity');
                if (parseInt(quantityInput.value) > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                }
            });
            
            // Variant selection
            document.querySelectorAll('.variant-option:not(.disabled)').forEach(option => {
                option.addEventListener('click', function() {
                    const variantID = this.getAttribute('data-variant-id');
                    const type = this.getAttribute('data-type');
                    
                    // Remove selected class from same type options
                    document.querySelectorAll('.variant-option[data-type="' + type + '"]').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    
                    // Update hidden variant ID field
                    document.getElementById('selectedVariant').value = variantID;
                });
            });
            
            // Select first available variant by default
            const firstAvailable = document.querySelector('.variant-option:not(.disabled)');
            if (firstAvailable) {
                firstAvailable.click();
            }
            
            // Form submission validation
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const selectedVariant = document.getElementById('selectedVariant');
                    if (selectedVariant && selectedVariant.value === '') {
                        e.preventDefault();
                        alert('Please select product specifications');
                        return false;
                    }
                    
                    const quantity = document.getElementById('quantity');
                    if (parseInt(quantity.value) < 1 || parseInt(quantity.value) > 10) {
                        e.preventDefault();
                        alert('Please enter a valid quantity (1-10)');
                        return false;
                    }
                    
                    // Show loading state
                });
            });
        });
    </script>
</body>
</html>