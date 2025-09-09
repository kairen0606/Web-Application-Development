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
            while ($row = $result->fetch_assoc()) {
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

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="../Styles/cart.css">
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
                                    <div class="shop-btn-container">
                                        <a href="product.php" class="shop-btn">Continue Shopping</a>
                                        <div>
                                </td>
                            </tr>
                        <?php elseif (count($cartItems) == 0): ?>
                            <tr id="cart-empty-row">
                                <td colspan="5" class="empty-cart">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <p>Your cart is empty.</p>
                                    <div class="shop-btn-container">
                                        <a href="product.php" class="shop-btn">Continue Shopping</a>
                                        <div>
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
        </div>
    </section>
    <section class="summary-section">
        <div class="cart-summary">
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