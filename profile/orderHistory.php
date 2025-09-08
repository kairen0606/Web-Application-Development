<?php
session_start();
require_once '../classes/order.php';
require_once '../classes/user.php';
require_once '../classes/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit;
}

$userID = $_SESSION['user_id'];
$orderID = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$itemsPerPage = 10;

$order = new Order();
$user = new User();
$orderHistory = $order->getOrderHistory($userID, $page, $itemsPerPage); // Assume modified method with pagination
$totalOrders = $order->getOrderCount($userID); // Assume new method
$totalPages = ceil($totalOrders / $itemsPerPage);
$orderDetails = $orderID ? $order->getOrderHistoryById($orderID) : [];

$rating = 0;
$comment = "";
$commentError = $ratingError = "";
$successMessage = "";
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $successMessage = "Invalid CSRF token.";
    } else {
        $productID = intval($_POST['product_id']);
        $orderID = intval($_POST['order_id']);
        $rating = intval($_POST['rating']) ?? 0;
        $comment = trim($_POST['comment']) ?? "";
        $size = trim($_POST['size'] ?? "");
        $valid = true;

        if ($rating < 1 || $rating > 5) {
            $ratingError = '<i class="ri-error-warning-fill"></i> Rating must be between 1 and 5.';
            $valid = false;
        }
        if (empty($comment)) {
            $commentError = '<i class="ri-error-warning-fill"></i> Comment is required.';
            $valid = false;
        }

        if ($valid) {
            $success = $user->saveUserReview($userID, $productID, $size, $orderID, $rating, $comment);
            $successMessage = $success ? "Thank you for your comment!" : "Failed to save your comment.";
            header("Location: ?order_id=$orderID&status=" . ($success ? "success" : "error"));
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <style>
        .order-details-table, .order-history-container { width: 100%; border-collapse: collapse; }
        .order-details-table td, .order-history-container td { padding: 10px; border-bottom: 1px solid #eee; }
        .back-btn, .cancel-btn, .submit-btn { padding: 10px 20px; margin: 5px; cursor: pointer; }
        .message { margin-bottom: 10px; padding: 10px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error-message { background-color: #f8d7da; color: #721c24; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a { margin: 0 5px; padding: 5px 10px; text-decoration: none; }
        .pagination a.active { font-weight: bold; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <a href="personalInfo.php">Personal Info</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="orderHistory.php" style="font-weight: bold;">| Order History</a>
            <a href="viewStatistic.php">View Statistic</a>
            <a href="logout.php">Log out</a>
        </div>
        <div class="content">
            <div class="card">
                <h2><?php echo isset($_GET['order_id']) && isset($_GET['product_id']) ? "Review Product" : "Order History"; ?></h2>
                <br>
                <?php if ($successMessage): ?>
                    <div class="message <?php echo strpos($successMessage, 'Thank') !== false ? 'success' : 'error-message'; ?>">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>
                <hr>
                <br>
                <?php if (empty($orderHistory)): ?>
                    <p style="text-align: center;">You have not made any orders yet.</p>
                <?php elseif (isset($_GET['order_id'])): ?>
                    <h2>Order #<?php echo htmlspecialchars($_GET['order_id']); ?></h2>
                    <table class="order-details-table">
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ($orderDetails['orderItems'] as $item):
                                $total += $item['price'] * $item['quantity'];
                                $reviewed = $user->hasReviewed($userID, $item['productID'], $item['size'], $orderID); // Assume new method
                            ?>
                                <tr>
                                    <td>
                                        <div style="display: flex;">
                                            <img src="<?php echo htmlspecialchars($item['image_url'] ?? '../img/default.png'); ?>" alt="Product Image" style="width: 100px; height: auto;">
                                            <div style="margin-left: 20px;">
                                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                                <p>Price: RM <?php echo number_format($item['price'], 2); ?></p>
                                                <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                                <p>Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></p>
                                                <p>Colour: <?php echo htmlspecialchars($item['colour'] ?? 'N/A'); ?></p>
                                                <?php if (!$reviewed): ?>
                                                    <a href="?order_id=<?php echo $orderID; ?>&product_id=<?php echo $item['productID']; ?>&size=<?php echo urlencode($item['size']); ?>">Write a Review</a>
                                                <?php endif; ?>
                                                <?php if (isset($_GET['product_id']) && $_GET['product_id'] == $item['productID'] && $_GET['size'] == $item['size']): ?>
                                                    <form method="post" id="rating-form">
                                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                        <input type="hidden" name="order_id" value="<?php echo $orderID; ?>">
                                                        <input type="hidden" name="product_id" value="<?php echo $item['productID']; ?>">
                                                        <input type="hidden" name="size" value="<?php echo $item['size']; ?>">
                                                        <label>Your Rating <span style="color:red">*</span></label>
                                                        <div class="star-rating">
                                                            <i class="ri-star-line" data-value="1"></i>
                                                            <i class="ri-star-line" data-value="2"></i>
                                                            <i class="ri-star-line" data-value="3"></i>
                                                            <i class="ri-star-line" data-value="4"></i>
                                                            <i class="ri-star-line" data-value="5"></i>
                                                            <input type="hidden" name="rating" id="rating-value" class="rating-value" value="<?php echo $rating; ?>">
                                                        </div>
                                                        <div id="ratingError" class="error"><?php echo $ratingError; ?></div>
                                                        <div class="form-field">
                                                            <label for="comment">Review <span style="color:red">*</span></label>
                                                            <textarea id="comment" rows="5" cols="98" name="comment" placeholder="Enter your comment"></textarea>
                                                            <div id="commentError" class="error"><?php echo $commentError; ?></div>
                                                        </div>
                                                        <br>
                                                        <button class="submit-btn" type="submit">Submit</button>
                                                        <button type="button" class="cancel-btn" onclick="window.location.href='?order_id=<?php echo $orderID; ?>'">Cancel</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (!isset($_GET['product_id'])): ?>
                        <p style="text-align:right;"><strong>Total: RM<?php echo number_format($total, 2); ?></strong></p>
                        <button class="back-btn"><a href="orderHistory.php">Back</a></button>
                    <?php endif; ?>
                <?php else: ?>
                    <table class="order-history-container">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderHistory as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['orderID']); ?></td>
                                    <td><?php echo htmlspecialchars($order['date']); ?></td>
                                    <td>RM <?php echo number_format($order['totalAmount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($order['paymentMethod']); ?></td>
                                    <td><a href="?order_id=<?php echo urlencode($order['orderID']); ?>">Order Details</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script>
        document.querySelectorAll('.star-rating').forEach(starBlock => {
            const stars = starBlock.querySelectorAll('i');
            const ratingInput = starBlock.querySelector('.rating-value');
            const setStars = (rating) => {
                stars.forEach(star => {
                    const val = parseInt(star.dataset.value);
                    star.classList.toggle('ri-star-fill', val <= rating);
                    star.classList.toggle('ri-star-line', val > rating);
                });
            };
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const rating = parseInt(star.dataset.value);
                    ratingInput.value = rating;
                    setStars(rating);
                });
                star.addEventListener('mouseover', () => setStars(parseInt(star.dataset.value)));
                star.addEventListener('mouseout', () => setStars(parseInt(ratingInput.value)));
            });
            setStars(parseInt(ratingInput.value));
        });
    </script>
</body>
</html>