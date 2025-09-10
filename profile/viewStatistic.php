<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

require_once '../classes/user.php';
require_once '../classes/order.php';
require_once '../classes/product.php';

$userID = $_SESSION['user_id'];
$order = new Order();
$user = new User();
$product = new Product();

// 获取用户订单历史
$orderHistory = $order->getOrderHistory($userID);

// 初始化统计数组
$yearlyStats = [];
$monthlyAmountData = [];
$monthlyOrderCounts = [];
$availableYears = [];
$categoryStats = [];

// 处理订单数据
if (!empty($orderHistory)) {
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
                'categories' => [],
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
        
        if (isset($orderDetails['orderItems'])) {
            foreach ($orderDetails['orderItems'] as $item) {
                $category = $item['category'] ?? 'Uncategorized';
                $productName = $item['name'] ?? 'Unknown';
                
                // 更新类别统计
                if (!isset($yearlyStats[$year]['categories'][$category])) {
                    $yearlyStats[$year]['categories'][$category] = 0;
                }
                $yearlyStats[$year]['categories'][$category] += $item['quantity'];
                
                // 更新全局类别统计
                if (!isset($categoryStats[$category])) {
                    $categoryStats[$category] = 0;
                }
                $categoryStats[$category] += $item['quantity'];
                
                // 更新最受欢迎产品
                if (!isset($yearlyStats[$year]['topProduct']['name']) || 
                    $item['quantity'] > $yearlyStats[$year]['topProduct']['count']) {
                    $yearlyStats[$year]['topProduct'] = ['name' => $productName, 'count' => $item['quantity']];
                }
            }
        }
    }
}

// 设置默认年份
rsort($availableYears);
$defaultYear = $availableYears[0] ?? date('Y');
$defaultStats = $yearlyStats[$defaultYear] ?? [
    'totalAmount' => 0, 
    'orderCount' => 0, 
    'categories' => [], 
    'topProduct' => ['name' => 'N/A', 'count' => 0]
];

// 计算平均消费
$averageSpending = $defaultStats['orderCount'] ? $defaultStats['totalAmount'] / $defaultStats['orderCount'] : 0;

// 找出最受欢迎的类别
$topCategory = 'N/A';
$topCategoryCount = 0;
if (!empty($defaultStats['categories'])) {
    foreach ($defaultStats['categories'] as $category => $count) {
        if ($count > $topCategoryCount) {
            $topCategory = $category;
            $topCategoryCount = $count;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .container {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            gap: 20px;
        }
        
        .sidebar {
            width: 250px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .sidebar a {
            display: block;
            padding: 12px 15px;
            margin-bottom: 8px;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .sidebar a:hover {
            background-color: #f0f0f0;
        }
        
        .content {
            flex: 1;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-card h2 {
            margin-top: 0;
            font-size: 18px;
            color: #555;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .stat-item {
            margin-bottom: 15px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .stat-label {
            font-size: 14px;
            color: #777;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-container {
            background: white;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }
        
        canvas {
            max-width: 100%;
            height: auto !important;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: auto;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <a href="personalInfo.php">Personal Info</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="orderHistory.php">Order History</a>
            <a href="#" style="font-weight: bold; background-color: #f0f0f0;">View Statistics</a>
            <a href="logout.php">Log out</a>
        </div>
        
        <div class="content">
            <div class="filter-container">
                <p style="font-weight: bold; margin: 0;">Filter by Year:</p>
                <select id="yearSelector">
                    <?php foreach ($availableYears as $year): ?>
                        <option value="<?php echo $year; ?>" <?php echo $year == $defaultYear ? 'selected' : ''; ?>><?php echo $year; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php if (empty($orderHistory)): ?>
                <div class="no-data">
                    <h2>No Order Data Available</h2>
                    <p>You haven't made any purchases yet. Start shopping to see your statistics here!</p>
                </div>
            <?php else: ?>
                <div class="dashboard-grid">
                    <!-- Monthly Spending Chart -->
                    <div class="stat-card">
                        <h2>Monthly Spending (<?php echo $defaultYear; ?>)</h2>
                        <canvas id="monthlySpendingChart"></canvas>
                    </div>
                    
                    <!-- Key Statistics -->
                    <div class="stat-card">
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
                            <h2>Most Popular Category</h2>
                            <div class="stat-value" id="topCategory"><?php echo htmlspecialchars($topCategory); ?></div>
                            <div class="stat-label" id="topCategoryCount">Items: <?php echo $topCategoryCount; ?></div>
                        </div>
                        <div class="stat-item">
                            <h2>Top Product</h2>
                            <div class="stat-value" id="topProduct"><?php echo htmlspecialchars($defaultStats['topProduct']['name']); ?></div>
                            <div class="stat-label">Quantity: <?php echo $defaultStats['topProduct']['count']; ?></div>
                        </div>
                    </div>
                    
                    <!-- Category Distribution -->
                    <div class="stat-card">
                        <h2>Category Distribution (<?php echo $defaultYear; ?>)</h2>
                        <canvas id="categoryChart"></canvas>
                    </div>
                    
                    <!-- Monthly Orders -->
                    <div class="stat-card">
                        <h2>Monthly Orders (<?php echo $defaultYear; ?>)</h2>
                        <canvas id="monthlyOrdersChart"></canvas>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Prepare data for charts
        const availableYears = <?php echo json_encode($availableYears); ?>;
        const allMonthlyData = <?php echo json_encode($monthlyAmountData); ?>;
        const allOrdersData = <?php echo json_encode($monthlyOrderCounts); ?>;
        const yearlyStats = <?php echo json_encode($yearlyStats); ?>;
        
        // Get current year data
        const currentYearData = allMonthlyData['<?php echo $defaultYear; ?>'] || {};
        const monthlySpendingData = Object.values(currentYearData);
        
        const currentOrdersData = allOrdersData['<?php echo $defaultYear; ?>'] || {};
        const monthlyOrdersData = Object.values(currentOrdersData);
        
        const categoryData = yearlyStats['<?php echo $defaultYear; ?>']?.categories || {};
        const categoryLabels = Object.keys(categoryData);
        const categoryValues = Object.values(categoryData);
        
        // Monthly Spending Chart
        const monthlySpendingCtx = document.getElementById('monthlySpendingChart').getContext('2d');
        const monthlySpendingChart = new Chart(monthlySpendingCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Spending (RM)',
                    data: monthlySpendingData,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Category Distribution Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryValues,
                    backgroundColor: [
                        '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', 
                        '#fd7e14', '#20c997', '#e83e8c', '#6c757d', '#0dcaf0'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true
            }
        });
        
        // Monthly Orders Chart
        const monthlyOrdersCtx = document.getElementById('monthlyOrdersChart').getContext('2d');
        const monthlyOrdersChart = new Chart(monthlyOrdersCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Orders',
                    data: monthlyOrdersData,
                    backgroundColor: '#28a745'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        
        // Year selector functionality
        document.getElementById('yearSelector').addEventListener('change', function() {
            const year = this.value;
            
            // Update charts with new year's data
            const yearData = allMonthlyData[year] || {};
            const yearOrdersData = allOrdersData[year] || {};
            const yearCategoryData = yearlyStats[year]?.categories || {};
            
            // Update monthly spending chart
            monthlySpendingChart.data.datasets[0].data = Object.values(yearData);
            monthlySpendingChart.update();
            
            // Update category chart
            categoryChart.data.labels = Object.keys(yearCategoryData);
            categoryChart.data.datasets[0].data = Object.values(yearCategoryData);
            categoryChart.update();
            
            // Update monthly orders chart
            monthlyOrdersChart.data.datasets[0].data = Object.values(yearOrdersData);
            monthlyOrdersChart.update();
            
            // Update statistics
            const yearStats = yearlyStats[year] || {
                totalAmount: 0, 
                orderCount: 0, 
                categories: {}, 
                topProduct: {name: 'N/A', count: 0}
            };
            
            document.getElementById('totalAmount').textContent = 'RM ' + (yearStats.totalAmount || 0).toFixed(2);
            
            const avgSpending = yearStats.orderCount ? (yearStats.totalAmount / yearStats.orderCount).toFixed(2) : 0;
            document.getElementById('averageSpending').textContent = 'RM ' + avgSpending;
            
            // Find top category
            let topCat = 'N/A';
            let topCatCount = 0;
            if (Object.keys(yearStats.categories).length > 0) {
                for (const [category, count] of Object.entries(yearStats.categories)) {
                    if (count > topCatCount) {
                        topCat = category;
                        topCatCount = count;
                    }
                }
            }
            
            document.getElementById('topCategory').textContent = topCat;
            document.getElementById('topCategoryCount').textContent = 'Items: ' + topCatCount;
            document.getElementById('topProduct').textContent = yearStats.topProduct.name;
            document.getElementById('topProductCount').textContent = 'Quantity: ' + yearStats.topProduct.count;
        });
    </script>
</body>
</html>