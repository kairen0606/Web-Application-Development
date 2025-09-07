<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Test - PR ind Badminton Store</title>
    <link rel="stylesheet" href="../Styles/style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 20px;
        }
        .test-result {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            color: green;
            border-left: 4px solid green;
        }
        .error {
            color: red;
            border-left: 4px solid red;
        }
        .debug-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            font-family: monospace;
            white-space: pre-wrap;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Database Connection Test</h1>
        
        <div class="test-result">
            <h2>1. Database Connection</h2>
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "pr_ind_db";
            
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            
            // Check connection
            if ($conn->connect_error) {
                echo '<div class="error">Connection FAILED: ' . $conn->connect_error . '</div>';
            } else {
                echo '<div class="success">Connection SUCCESSFUL</div>';
            }
            ?>
        </div>

        <div class="test-result">
            <h2>2. Check Tables</h2>
            <?php
            if (!$conn->connect_error) {
                $tables = ['Products', 'Categories', 'ProductImages'];
                $allTablesExist = true;
                
                foreach ($tables as $table) {
                    $result = $conn->query("SHOW TABLES LIKE '$table'");
                    if ($result->num_rows > 0) {
                        echo '<div class="success">Table ' . $table . ' EXISTS</div>';
                    } else {
                        echo '<div class="error">Table ' . $table . ' DOES NOT EXIST</div>';
                        $allTablesExist = false;
                    }
                }
                
                if ($allTablesExist) {
                    echo '<div class="success">All required tables exist</div>';
                }
            }
            ?>
        </div>

        <div class="test-result">
            <h2>3. Check Data in Tables</h2>
            <?php
            if (!$conn->connect_error) {
                // Check Categories
                $result = $conn->query("SELECT COUNT(*) as count FROM Categories");
                $row = $result->fetch_assoc();
                echo '<div>Categories table has ' . $row['count'] . ' records</div>';
                
                // Check Products
                $result = $conn->query("SELECT COUNT(*) as count FROM Products");
                $row = $result->fetch_assoc();
                echo '<div>Products table has ' . $row['count'] . ' records</div>';
                
                // Check ProductImages
                $result = $conn->query("SELECT COUNT(*) as count FROM ProductImages");
                $row = $result->fetch_assoc();
                echo '<div>ProductImages table has ' . $row['count'] . ' records</div>';
            }
            ?>
        </div>

        <div class="test-result">
            <h2>4. Test Product Query</h2>
            <?php
            if (!$conn->connect_error) {
                $sql = "SELECT p.productID, p.name, p.description, p.price, p.colour, 
                               c.name as category_name, c.categoryID,
                               pi.image_url
                        FROM Products p 
                        JOIN Categories c ON p.categoryID = c.categoryID 
                        LEFT JOIN ProductImages pi ON p.productID = pi.productID 
                        GROUP BY p.productID 
                        ORDER BY p.productID 
                        LIMIT 5";
                
                $result = $conn->query($sql);
                
                if ($result && $result->num_rows > 0) {
                    echo '<div class="success">Query SUCCESSFUL - Found ' . $result->num_rows . ' products</div>';
                    echo '<table>';
                    echo '<tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Image URL</th></tr>';
                    
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['productID'] . '</td>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['category_name'] . '</td>';
                        echo '<td>RM ' . number_format($row['price'], 2) . '</td>';
                        echo '<td>' . ($row['image_url'] ? $row['image_url'] : 'No image') . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<div class="error">Query returned 0 results or failed</div>';
                    echo '<div class="debug-info">SQL: ' . htmlspecialchars($sql) . '</div>';
                    if (!$result) {
                        echo '<div class="error">Error: ' . $conn->error . '</div>';
                    }
                }
            }
            ?>
        </div>

        <div class="test-result">
            <h2>5. Test Image Paths</h2>
            <?php
            if (!$conn->connect_error) {
                $sql = "SELECT image_url FROM ProductImages LIMIT 5";
                $result = $conn->query($sql);
                
                if ($result && $result->num_rows > 0) {
                    echo '<div class="success">Found ' . $result->num_rows . ' images</div>';
                    echo '<table>';
                    echo '<tr><th>Image URL</th><th>File Exists</th></tr>';
                    
                    while($row = $result->fetch_assoc()) {
                        $imagePath = $row['image_url'];
                        $fileExists = file_exists($imagePath) ? 'Yes' : 'No';
                        $statusClass = file_exists($imagePath) ? 'success' : 'error';
                        
                        echo '<tr>';
                        echo '<td>' . $imagePath . '</td>';
                        echo '<td class="' . $statusClass . '">' . $fileExists . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
            }
            ?>
        </div>

    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>