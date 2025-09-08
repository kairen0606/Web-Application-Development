<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

require_once '../classes/user.php';
require_once '../classes/order.php';

$userID = $_SESSION['user_id'];
$orderID = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

$order = new Order();
$user = new User();
$orderHistory = $order->getOrderHistory($userID);
$yearlyStats = [];
$monthlyAmountData = [];
$monthlyOrderCounts = [];
$availableYears = [];

foreach ($orderHistory as $orderItem) {
    $orderDate = new DateTime($orderItem['date']);
    $year = $orderDate->format('Y');
    $month = $orderDate->format('F');
    if (!in_array($year, $availableYears)) {
        $availableYears[] = $year;
    }
    if (!isset($yearlyStats[$year])) {
        $yearlyStats[$year] = [
            'totalAmount' => 0,
            'orderCount' => 0,
            'gender' => ['Men' => 0, 'Women' => 0],
            'colors' => [],
            'topProduct' => ['name' => 'N/A', 'count' => 0]
        ];
    }
    if (!isset($monthlyAmountData[$year][$month])) {
        $monthlyAmountData[$year][$month] = 0;
        $monthlyOrderCounts[$year][$month] = 0;
    }
    $yearlyStats[$year]['totalAmount'] += $orderItem['totalAmount'];
    $yearlyStats[$year]['orderCount'] += 1;
    $monthlyAmountData[$year][$month] += $orderItem['totalAmount'];
    $monthlyOrderCounts[$year][$month] += 1;

    $orderDetails = $order->getOrderHistoryById($orderItem['orderID']);
    foreach ($orderDetails['orderItems'] as $item) {
        $gender = $item['category'] ?? 'Women';
        $color = $item['colour'] ?? 'Unknown';
        $productName = $item['name'] ?? 'Unknown';
        if (!isset($yearlyStats[$year]['gender'][$gender])) {
            $yearlyStats[$year]['gender'][$gender] = 0;
        }
        $yearlyStats[$year]['gender'][$gender] += $item['quantity'];
        if (!isset($yearlyStats[$year]['colors'][$color])) {
            $yearlyStats[$year]['colors'][$color] = 0;
        }
        $yearlyStats[$year]['colors'][$color] += $item['quantity'];
        if (!isset($yearlyStats[$year]['topProduct']['name']) || $item['quantity'] > $yearlyStats[$year]['topProduct']['count']) {
            $yearlyStats[$year]['topProduct'] = ['name' => $productName, 'count' => $item['quantity']];
        }
    }
}

rsort($availableYears);
$defaultYear = $availableYears[0] ?? date('Y');
$defaultStats = $yearlyStats[$defaultYear] ?? ['totalAmount' => 0, 'orderCount' => 0, 'gender' => [], 'colors' => [], 'topProduct' => ['name' => 'N/A', 'count' => 0]];
$averageSpending = $defaultStats['orderCount'] ? $defaultStats['totalAmount'] / $defaultStats['orderCount'] : 0;
$topColor = 'N/A';
$topColorCount = 0;
foreach ($defaultStats['colors'] as $color => $count) {
    if ($count > $topColorCount) {
        $topColor = $color;
        $topColorCount = $count;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overall Statistic</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .mycard { padding: 20px; border: 1px solid #eee; border-radius: 5px; }
        canvas { max-width: 100%; height: auto !important; }
        .no-data { text-align: center; padding: 20px; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <a href="personalInfo.php">Personal Info</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="orderHistory.php">Order History</a>
            <a href="#" style="font-weight: bold;">| View Statistic</a>
            <a href="logout.php">Log out</a>
        </div>
        <div style="margin-bottom: 20px; text-align: center;">
            <p style="font-weight: bold; margin: 40px 0 5px 5px;">Filter:</p>
            <select id="yearSelector" style="margin-left: 5px;">
                <option disabled>Select Year</option>
                <?php foreach ($availableYears as $year): ?>
                    <option value="<?php echo $year; ?>" <?php echo $year == $defaultYear ? 'selected' : ''; ?>><?php echo $year; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if (empty($orderHistory)): ?>
            <div class="no-data">No order data available to display statistics.</div>
        <?php else: ?>
            <div class="dashboard-grid">
                <div class="mycard stat-card">
                    <h2>Total Amount Spent across Month</h2>
                    <canvas id="timeSeriesChart"></canvas>
                </div>
                <div class="mycard stat-card">
                    <div class="stat-item">
                        <h2>Total Amount Spent</h2>
                        <div class="stat-value" id="totalAmount">RM <?php echo number_format($defaultStats['totalAmount'], 2); ?></div>
                        <div class="stat-label">Across All Orders</div>
                    </div>
                    <div class="stat-item">
                        <h2>Average Spending Per Order</h2>
                        <div class="stat-value" id="averageSpending">RM <?php echo number_format($averageSpending, 2); ?></div>
                        <div class="stat-label">Across All Orders</div>
                    </div>
                    <div class="stat-item">
                        <h2>Top Favorite Color</h2>
                        <div class="stat-value" id="topColor"><?php echo htmlspecialchars($topColor); ?></div>
                        <div class="stat-label" id="topColorCount">Count: <?php echo $topColorCount; ?></div>
                    </div>
                    <div class="stat-item">
                        <h2>Top Product</h2>
                        <div class="stat-value" id="topProduct"><?php echo htmlspecialchars($defaultStats['topProduct']['name']); ?></div>
                        <div class="stat-label">Count: <?php echo $defaultStats['topProduct']['count']; ?></div>
                    </div>
                </div>
                <div class="mycard stat-card">
                    <h2>Top Categories Purchased [Gender]</h2>
                    <canvas id="genderPieChart"></canvas>
                </div>
                <div class="mycard stat-card">
                    <h2>Total Orders per Month</h2>
                    <canvas id="ordersPerMonthChart" width="400" height="200"></canvas>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script>
        const availableYears = <?php echo json_encode($availableYears); ?>;
        const allMonthlyData = <?php echo json_encode($monthlyAmountData); ?>;
        const allOrdersData = <?php echo json_encode($monthlyOrderCounts); ?>;
        const yearlyStats = <?php echo json_encode($yearlyStats); ?>;

        const ctx1 = document.getElementById('timeSeriesChart').getContext('2d');
        const ctx2 = document.getElementById('genderPieChart').getContext('2d');
        const ctx3 = document.getElementById('ordersPerMonthChart').getContext('2d');

        const timeSeriesChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Spending (RM)',
                    data: Object.values(allMonthlyData['<?php echo $defaultYear; ?>'] || {}),
                    borderColor: '#007bff',
                    fill: false
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        const genderPieChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: Object.keys(yearlyStats['<?php echo $defaultYear; ?>'].gender),
                datasets: [{
                    data: Object.values(yearlyStats['<?php echo $defaultYear; ?>'].gender),
                    backgroundColor: ['#007bff', '#ff6f61']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        const ordersPerMonthChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Orders',
                    data: Object.values(allOrdersData['<?php echo $defaultYear; ?>'] || {}),
                    backgroundColor: '#007bff'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        document.getElementById('yearSelector').addEventListener('change', function() {
            const year = this.value;
            timeSeriesChart.data.datasets[0].data = Object.values(allMonthlyData[year] || {});
            genderPieChart.data.labels = Object.keys(yearlyStats[year].gender);
            genderPieChart.data.datasets[0].data = Object.values(yearlyStats[year].gender);
            ordersPerMonthChart.data.datasets[0].data = Object.values(allOrdersData[year] || {});
            document.getElementById('totalAmount').textContent = 'RM ' + (yearlyStats[year].totalAmount || 0).toFixed(2);
            document.getElementById('averageSpending').textContent = 'RM ' + (yearlyStats[year].orderCount ? (yearlyStats[year].totalAmount / yearlyStats[year].orderCount).toFixed(2) : 0);
            document.getElementById('topColor').textContent = Object.keys(yearlyStats[year].colors).reduce((a, b) => yearlyStats[year].colors[a] > yearlyStats[year].colors[b] ? a : b, 'N/A');
            document.getElementById('topColorCount').textContent = 'Count: ' + (Object.values(yearlyStats[year].colors).reduce((a, b) => Math.max(a, b), 0) || 0);
            document.getElementById('topProduct').textContent = yearlyStats[year].topProduct.name;
            document.getElementById('topProductCount').textContent = 'Count: ' + yearlyStats[year].topProduct.count;
            timeSeriesChart.update();
            genderPieChart.update();
            ordersPerMonthChart.update();
        });
    </script>
</body>
</html>