<?php
require_once __DIR__ . '/database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    // Register a new user
    public function register($name, $phone, $email, $password) {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $hashedPassword);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // Login method to authenticate user
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT userID, name, email, phone, birthday, gender, password FROM users WHERE BINARY email = ?"); // Fetch additional fields
        $stmt->bind_param("s", $email);
        
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $name, $email, $phone, $birthday, $gender, $storedPassword);
        
        // Check if a result was returned (email exists)
        if ($stmt->num_rows > 0) {
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $storedPassword)) {
                // Return user details including birthday and gender
                return [
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'birthday' => $birthday,
                    'gender' => $gender
                ];
            }
        }
        $stmt->close();
        return false; // No user found or incorrect password
    }

    // Check if email already exists
    public function isEmailExists($email){
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE BINARY email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0; // Return true if email exists, false otherwise
    }

    // Get user details by ID
    public function getUserById($userID) {
        $stmt = $this->conn->prepare("SELECT userID, name, email, phone, birthday, gender FROM users WHERE userID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return $user; // Returns an associative array of user details
    }

    // Update user information
    public function updateUserInfo($userID, $name, $phone, $email, $birthday, $gender) {
        $stmt = $this->conn->prepare("UPDATE users SET name = ?, phone = ?, email = ?, birthday = ?, gender = ? WHERE userID = ?");
        $stmt->bind_param("sssssi", $name, $phone, $email, $birthday, $gender, $userID);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Save user review
    public function saveUserReview($userID, $productID, $size, $orderID, $rating, $review) {    
        $stmt = $this->conn->prepare(
            "INSERT INTO Ratings (productID, userID, orderID, rating, review, size) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("iiiiss", $productID, $userID, $orderID, $rating, $review, $size);
        $success = $stmt->execute();
        $stmt->close();
    
        return $success;
    }

    // Check if user has already reviewed a product in an order with a specific size
    public function hasReviewedProductInOrder($userID, $productID, $orderID, $size) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Ratings WHERE userID = ? AND productID = ? AND orderID = ? AND size = ?");
        $stmt->bind_param("iiis", $userID, $productID, $orderID, $size);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    
        return $count > 0;
    }
    
    public function __destruct() {
        Database::close();
    }
}
?>