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
$orderHistory = $order->getOrderHistory($userID, $page, $itemsPerPage);
$totalOrders = $order->getOrderCount($userID);
$totalPages = ceil($totalOrders / $itemsPerPage);
$orderDetails = $orderID ? $order->getOrderHistoryById($orderID) : [];

$successMessage = "";
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $successMessage = "Order processed successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - PRESENT PR IND</title>
     <link rel="stylesheet" href="../Styles/profile.css">
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <a href="personalInfo.php">Personal Info</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="orderHistory.php" style="font-weight: bold;">Order History</a>
            <a href="viewStatistic.php">View Statistic</a>
            <a href="logout.php">Log out</a>
        </div>
        <div class="content">
            <div class="card">
                <h2>Order History</h2>
                <br>
                <?php if ($successMessage): ?>
                    <div class="message success">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>
                <br>
                <?php if (empty($orderHistory) && !isset($_GET['order_id'])): ?>
                    <p style="text-align: center;">You have not made any orders yet.</p>
                <?php elseif (isset($_GET['order_id'])): ?>
                    <h2>Order #<?php echo htmlspecialchars($_GET['order_id']); ?></h2>
                    <?php if (!empty($orderDetails['orderItems'])): ?>
                        <table class="order-details-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Details</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                foreach ($orderDetails['orderItems'] as $item):
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($item['image_url'] ?? '../img/default.png'); ?>" 
                                                 alt="Product Image" class="product-image">
                                        </td>
                                        <td>
                                            <div class="product-info">
                                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                                <?php if (!empty($item['size'])): ?>
                                                    <p>Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($item['colour'])): ?>
                                                    <p>Color: <?php echo htmlspecialchars($item['colour']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>RM <?php echo number_format($item['price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td>RM <?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div style="text-align: right; margin-top: 20px;">
                            <p><strong>Total: RM <?php echo number_format($total, 2); ?></strong></p>
                            <p>
                                <strong>Status: </strong>
                                <span class="order-status status-<?php echo strtolower($orderDetails['order']['orderStatus'] ?? 'pending'); ?>">
                                    <?php echo htmlspecialchars($orderDetails['order']['orderStatus'] ?? 'Pending'); ?>
                                </span>
                            </p>
                            <p><strong>Order Date: </strong><?php echo date('F j, Y', strtotime($orderDetails['order']['date'])); ?></p>
                            <p><strong>Payment Method: </strong><?php echo htmlspecialchars($orderDetails['order']['paymentMethod']); ?></p>
                        </div>
                        <button class="back-btn"><a href="orderHistory.php">Back to Orders</a></button>
                    <?php else: ?>
                        <p style="text-align: center;">Order not found.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <table class="order-history-container">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderHistory as $orderItem): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($orderItem['orderID']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($orderItem['date'])); ?></td>
                                    <td>RM <?php echo number_format($orderItem['totalAmount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($orderItem['paymentMethod']); ?></td>
                                    <td>
                                        <span class="order-status status-<?php echo strtolower($orderItem['orderStatus']); ?>">
                                            <?php echo htmlspecialchars($orderItem['orderStatus']); ?>
                                        </span>
                                    </td>
                                    <td><a href="?order_id=<?php echo urlencode($orderItem['orderID']); ?>">View Details</a></td>
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
</body>
</html>