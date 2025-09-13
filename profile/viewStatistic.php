<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pr_ind_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION['user_id'];

// Get user order history
$orderHistory = [];
$sql = "SELECT o.orderID, o.totalAmount, o.date 
        FROM Orders o 
        WHERE o.userID = ? 
        ORDER BY o.date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderHistory[] = $row;
    }
}
$stmt->close();

// Initialize statistics arrays
$yearlyStats = [];
$monthlyAmountData = [];
$monthlyOrderCounts = [];
$availableYears = [];
$categoryStats = [];

// Process order data
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

        // Get order details
        $orderItemsSql = "SELECT oi.*, p.name as product_name, p.categoryID, c.name as category_name 
                         FROM OrderItems oi 
                         JOIN Products p ON oi.productID = p.productID 
                         JOIN Categories c ON p.categoryID = c.categoryID 
                         WHERE oi.orderID = ?";
        $stmt = $conn->prepare($orderItemsSql);
        $stmt->bind_param("i", $orderItem['orderID']);
        $stmt->execute();
        $itemsResult = $stmt->get_result();
        
        if ($itemsResult->num_rows > 0) {
            while ($item = $itemsResult->fetch_assoc()) {
                $category = $item['category_name'] ?? 'Uncategorized';
                $productName = $item['product_name'] ?? 'Unknown';
                $quantity = $item['quantity'] ?? 0;
                
                // Update category statistics
                if (!isset($yearlyStats[$year]['categories'][$category])) {
                    $yearlyStats[$year]['categories'][$category] = 0;
                }
                $yearlyStats[$year]['categories'][$category] += $quantity;
                
                // Update global category statistics
                if (!isset($categoryStats[$category])) {
                    $categoryStats[$category] = 0;
                }
                $categoryStats[$category] += $quantity;
                
                // Update top product
                if (!isset($yearlyStats[$year]['topProduct']['name']) || 
                    $quantity > $yearlyStats[$year]['topProduct']['count']) {
                    $yearlyStats[$year]['topProduct'] = ['name' => $productName, 'count' => $quantity];
                }
            }
        }
        $stmt->close();
    }
}

// Set default year
rsort($availableYears);
$defaultYear = $availableYears[0] ?? date('Y');
$defaultStats = $yearlyStats[$defaultYear] ?? [
    'totalAmount' => 0, 
    'orderCount' => 0, 
    'categories' => [], 
    'topProduct' => ['name' => 'N/A', 'count' => 0]
];

// Calculate average spending
$averageSpending = $defaultStats['orderCount'] ? $defaultStats['totalAmount'] / $defaultStats['orderCount'] : 0;

// Find most popular category
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

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Styles/profile.css">
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <a href="personalInfo.php">Personal Info</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="orderHistory.php">Order History</a>
            <a href="#" style="font-weight: bold;">| View Statistics</a>
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

    <?php include '../includes/footer.php'; ?>

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
        });
    </script>
</body>
</html>