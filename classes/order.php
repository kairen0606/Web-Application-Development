<?php
require_once __DIR__ . '/database.php';

class Order {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct() {
        $this->conn = Database::connect();
    }

    // Get order history for a user with pagination
    public function getOrderHistory($userID, $page = 1, $itemsPerPage = 10) {
        $offset = ($page - 1) * $itemsPerPage;
        
        $query = "SELECT orderID, date, totalAmount, paymentMethod, orderStatus 
                  FROM Orders 
                  WHERE userID = ? 
                  ORDER BY date DESC 
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $userID, $itemsPerPage, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }

    // Get total count of orders for a user
    public function getOrderCount($userID) {
        $query = "SELECT COUNT(*) as total 
                  FROM Orders 
                  WHERE userID = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }

    // Get order details by order ID
    public function getOrderHistoryById($orderID) {
        // Get order information
        $orderQuery = "SELECT * FROM Orders WHERE orderID = ?";
        $orderStmt = $this->conn->prepare($orderQuery);
        $orderStmt->bind_param("i", $orderID);
        $orderStmt->execute();
        $orderResult = $orderStmt->get_result();
        $order = $orderResult->fetch_assoc();
        
        // Get order items
        $itemsQuery = "SELECT oi.*, p.name, p.colour, pv.size, pi.image_url 
                       FROM OrderItems oi 
                       LEFT JOIN Products p ON oi.productID = p.productID 
                       LEFT JOIN ProductVariants pv ON oi.variantID = pv.variantID 
                       LEFT JOIN ProductImages pi ON p.productID = pi.productID 
                       WHERE oi.orderID = ? 
                       GROUP BY oi.orderItemID";
        
        $itemsStmt = $this->conn->prepare($itemsQuery);
        $itemsStmt->bind_param("i", $orderID);
        $itemsStmt->execute();
        $itemsResult = $itemsStmt->get_result();
        
        $orderItems = [];
        while ($row = $itemsResult->fetch_assoc()) {
            $orderItems[] = $row;
        }
        
        return [
            'order' => $order,
            'orderItems' => $orderItems
        ];
    }

    // Insert a new order
    public function createOrder($userID, $total, $paymentMethod, $unit, $state, $postcode, $city) {
        $stmt = $this->conn->prepare("INSERT INTO Orders (userID, totalAmount, paymentMethod, unit, state, postcode, city) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idsssis", $userID, $total, $paymentMethod, $unit, $state, $postcode, $city);
        $stmt->execute();
        return $stmt->insert_id; // Return the newly created order ID
    }

    // Insert order items
    public function addOrderItems($orderID, $cart) {
        foreach ($cart as $item) {
            $stmt = $this->conn->prepare("INSERT INTO OrderItems (orderID, productID, variantID, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiii", $orderID, $item['product_id'], $item['variant_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }
    }

    // Reduce stock for each item in the cart
    public function reduceStock($cart) {
        foreach ($cart as $item) {
            $stmt = $this->conn->prepare("UPDATE ProductVariants SET stock = stock - ? WHERE variantID = ?");
            $stmt->bind_param("ii", $item['quantity'], $item['variant_id']);
            $stmt->execute();
        }
    }
}
?>