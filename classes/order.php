<?php
require_once __DIR__ . '/database.php';

class Order {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct() {
        $this->conn = Database::connect();
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
            $stmt = $this->conn->prepare("INSERT INTO OrderItems (orderID, productID, variantID, quantity) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $orderID, $item['product_id'], $item['variant_id'], $item['quantity']);
            $stmt->execute();
        }
    }

    // Get order history by User ID
    public function getOrderHistory($userID) {
        $stmt = $this->conn->prepare("SELECT orderID, date, totalAmount, paymentMethod FROM Orders WHERE userID = ? ORDER BY date DESC");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }

    // Reduce stock for each item in the cart
    public function reduceStock($cart) {
        foreach ($cart as $item) {
            $stmt = $this->conn->prepare("UPDATE ProductVariants SET stock = stock - ? WHERE productID = ? AND size = ?");
            $stmt->bind_param("iis", $item['quantity'], $item['product_id'], $item['size']);
            $stmt->execute();
        }
    }

    // Get order details by Order ID
    public function getOrderHistoryById($orderID){ 
        $sql = "SELECT 
                    oi.orderID,
                    oi.orderItemID,
                    oi.productID,
                    oi.variantID,
                    oi.quantity,
    
                    p.name,
                    p.description,
                    p.price AS product_price,
                    p.colour,
                    p.category,
    
                    pv.variantID,
                    pv.size,
                    pv.stock,
    
                    pi.imageID,
                    pi.image_url,
                    pi.image_type
    
                FROM OrderItems oi
                JOIN Products p ON oi.productID = p.productID
                LEFT JOIN ProductVariants pv ON oi.variantID = pv.variantID
                LEFT JOIN ProductImages pi ON pi.imageID = (
                    SELECT MIN(imageID) 
                    FROM ProductImages 
                    WHERE productID = p.productID
                )
                WHERE oi.orderID = ?";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $orderDetails = [
            'orderID'    => $orderID,
            'orderItems' => []
        ];
    
        // Fetch order items and their details
        while ($row = $result->fetch_assoc()) {
            $orderDetails['orderItems'][] = [
                'orderItemID' => $row['orderItemID'],
                'productID'   => $row['productID'],
                'variantID'   => $row['variantID'],
                'quantity'    => $row['quantity'],
                'name'        => $row['name'],
                'description' => $row['description'],
                'price'       => $row['product_price'],
                'colour'      => $row['colour'],
                'category'    => $row['category'],
                'size'        => $row['size'],
                'stock'       => $row['stock'],
                'image'       => $row['image_url'] ? [
                    'imageID' => $row['imageID'],
                    'url'     => $row['image_url'],
                    'type'    => $row['image_type']
                ] : null
            ];
        }
    
        return $orderDetails;
    }
}
?>