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
$cartItems = [];
$itemsTotal = 0;
$shipping = 0;
$grandTotal = 0;

// If user is logged in, get cart items
if ($userID > 0) {
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['update_quantity'])) {
            $cartID = intval($_POST['cart_id']);
            $quantity = intval($_POST['quantity']);
            
            if ($quantity < 1) $quantity = 1;
            
            $updateSql = "UPDATE Cart SET quantity = $quantity WHERE cartID = $cartID AND userID = $userID";
            if ($conn->query($updateSql)) {
                $successMsg = "Quantity updated successfully.";
            } else {
                $errorMsg = "Error updating quantity: " . $conn->error;
            }
        }
        
        if (isset($_POST['remove_item'])) {
            $cartID = intval($_POST['cart_id']);
            
            $deleteSql = "DELETE FROM Cart WHERE cartID = $cartID AND userID = $userID";
            if ($conn->query($deleteSql)) {
                $successMsg = "Item removed from cart.";
            } else {
                $errorMsg = "Error removing item: " . $conn->error;
            }
        }
    }
    
    // Get cart items from database
    $sql = "SELECT c.cartID, c.quantity, p.productID, p.name, p.price, 
                   (SELECT image_url FROM ProductImages WHERE productID = p.productID LIMIT 1) as image_url
            FROM Cart c 
            JOIN Products p ON c.productID = p.productID 
            WHERE c.userID = $userID";
    
    $result = $conn->query($sql);
    
    if ($result) {
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $cartItems[] = $row;
                $itemsTotal += $row['price'] * $row['quantity'];
            }
        }
    } else {
        $errorMsg = "Error retrieving cart items: " . $conn->error;
    }
    
    $grandTotal = $itemsTotal + $shipping;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - PR ind</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="../Styles/style.css">

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
        
        .cart-hero {
            margin-top: 90px;
            padding: 60px 20px;
            text-align: center;
            background: #f7f8fb;
        }
        
        .cart-hero h1 {
            margin: 0;
            font-size: 36px;
            color: #1a1a1a;
        }
        
        .cart-hero p {
            font-size: 18px;
            color: #666;
            margin-top: 10px;
        }
        
        .cart-section {
            padding: 30px 20px 80px;
        }
        
        .cart-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
        }
        
        .cart-items {
            overflow-x: auto;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .cart-table th, .cart-table td {
            padding: 14px;
            border-bottom: 1px solid #f0f0f0;
            text-align: left;
        }
        
        .cart-table th {
            background: #fafafa;
            font-weight: 700;
            color: #333;
        }
        
        .cart-item {
            vertical-align: middle;
        }
        
        .cart-item-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .cart-item-title img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        
        .qty-input {
            width: 60px;
            padding: 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        
        .remove-btn {
            background: none;
            border: none;
            color: #e11d48;
            cursor: pointer;
            font-size: 16px;
        }
        
        .update-btn {
            padding: 5px 10px;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 5px;
        }
        
        .cart-summary {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
            height: fit-content;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .cart-summary h2 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #1a1a1a;
        }
        
        .summary-row, .summary-total {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
        }
        
        .summary-total {
            font-size: 18px;
            font-weight: 700;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        
        .payment-methods {
            margin-top: 20px;
        }
        
        .payment-methods h3 {
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .pay-option {
            display: block;
            margin: 8px 0;
            padding: 8px;
            border-radius: 6px;
            transition: background 0.2s;
        }
        
        .pay-option:hover {
            background: #f5f5f5;
        }
        
        .checkout-btn {
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            background: #1a1a1a;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.2s;
        }
        
        .checkout-btn:hover {
            background: #333;
        }
        
        .checkout-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .checkout-msg {
            margin-top: 10px;
            color: #16a34a;
            text-align: center;
        }
        
        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .empty-cart i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #ccc;
        }
        
        .empty-cart p {
            margin-top: 10px;
            font-size: 16px;
        }
        
        .shop-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #1a1a1a;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.2s;
        }
        
        .shop-btn:hover {
            background: #333;
        }
        
        @media (max-width: 900px) {
            .cart-grid {
                grid-template-columns: 1fr;
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
        
        .debug-info {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>

	<?php include '../includes/header.php'; ?>

    <section class="cart-hero">
        <div class="container">
            <h1>Your Cart</h1>
            <p>Review your items and complete your purchase.</p>
        </div>
    </section>

    <section class="cart-section">
        <div class="container">
            <?php if (isset($errorMsg)): ?>
                <div class="error-msg"><?php echo $errorMsg; ?></div>
            <?php endif; ?>
            
            <?php if (isset($successMsg)): ?>
                <div class="success-msg"><?php echo $successMsg; ?></div>
            <?php endif; ?>
                
            <div class="cart-grid">
                <div class="cart-items">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            <?php if ($userID == 0): ?>
                                <tr id="cart-empty-row">
                                    <td colspan="5" class="empty-cart">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                        <p>Your cart is empty.</p>
                                        <a href="products.php" class="shop-btn">Continue Shopping</a>
                                    </td>
                                </tr>
                            <?php elseif (count($cartItems) == 0): ?>
                                <tr id="cart-empty-row">
                                    <td colspan="5" class="empty-cart">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                        <p>Your cart is empty.</p>
                                        <a href="products.php" class="shop-btn">Continue Shopping</a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr class="cart-item">
                                        <td>
                                            <div class="cart-item-title">
                                                <img src="<?php echo $item['image_url'] ? $item['image_url'] : 'https://via.placeholder.com/60x60?text=Product'; ?>" alt="<?php echo $item['name']; ?>">
                                                <div>
                                                    <div><?php echo $item['name']; ?></div>
                                                    <small>ID: <?php echo $item['productID']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>RM <?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <form method="POST" style="display: flex; align-items: center;">
                                                <input type="hidden" name="cart_id" value="<?php echo $item['cartID']; ?>">
                                                <input class="qty-input" type="number" min="1" name="quantity" value="<?php echo $item['quantity']; ?>">
                                                <button type="submit" name="update_quantity" class="update-btn">Update</button>
                                            </form>
                                        </td>
                                        <td class="line-subtotal">RM <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        <td>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="cart_id" value="<?php echo $item['cartID']; ?>">
                                                <button type="submit" name="remove_item" class="remove-btn"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <aside class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-row"><span>Items total</span><span id="items-total">RM <?php echo number_format($itemsTotal, 2); ?></span></div>
                    <div class="summary-row"><span>Shipping</span><span id="shipping">RM <?php echo number_format($shipping, 2); ?></span></div>
                    <hr>
                    <div class="summary-total"><span>Total</span><span id="grand-total">RM <?php echo number_format($grandTotal, 2); ?></span></div>

                    <div class="payment-methods">
                        <h3>Payment Method</h3>
                        <label class="pay-option"><input type="radio" name="pay" value="online_banking" checked> <span>Online Banking (FPX)</span></label>
                        <label class="pay-option"><input type="radio" name="pay" value="tng"> <span>TNG eWallet</span></label>
                        <label class="pay-option"><input type="radio" name="pay" value="grabpay"> <span>GrabPay</span></label>
                    </div>

                    <?php if (count($cartItems) > 0): ?>
                        <button id="checkout-btn" class="checkout-btn">Proceed to Pay</button>
                    <?php else: ?>
                        <button id="checkout-btn" class="checkout-btn" disabled>Proceed to Pay</button>
                    <?php endif; ?>
                    <p id="checkout-msg" class="checkout-msg" style="display:none;"></p>
                </aside>
            </div>
        </div>
    </section>

	 <?php include '../includes/footer.php'; ?>
    <div class="copyright">
        <p>© 2025 MyWebsite. All rights reserved.</p>
    </div>

    <script>
        // JavaScript for enhanced functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listener to checkout button
            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn && !checkoutBtn.disabled) {
                checkoutBtn.addEventListener('click', function() {
                    const method = document.querySelector('input[name="pay"]:checked').value;
                    const msg = document.getElementById('checkout-msg');
                    msg.textContent = 'Redirecting to ' + 
                        (method === 'tng' ? 'TNG eWallet' : 
                         method === 'grabpay' ? 'GrabPay' : 'Online Banking (FPX)') + ' checkout...';
                    msg.style.display = 'block';
                    
                    // In a real application, you would redirect to a payment processor
                    setTimeout(() => { 
                        alert('Payment successful. Thank you for your order!'); 
                        
                        // In a real application, you would create an order here and clear the cart
                        window.location.href = 'thankyou.php';
                    }, 1500);
                });
            }
            
            // Add confirmation for remove actions
            const removeForms = document.querySelectorAll('form[action$="remove_item"]');
            removeForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to remove this item from your cart?')) {
                        e.preventDefault();
                    }
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