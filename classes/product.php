<?php
require_once __DIR__ . '/database.php';

class Product {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    // Fetch all products with images and variants
    public function getAllProducts() {
        $sql = "
            SELECT 
                p.productID,
                p.name,
                p.description,
                p.price,
                p.category,
                p.colour,
                pi.imageID,
                pi.image_url,
                pi.image_type,
                pv.variantID,
                pv.size,
                pv.stock
            FROM Products p
            LEFT JOIN ProductImages pi ON p.productID = pi.productID
            LEFT JOIN ProductVariants pv ON p.productID = pv.productID
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $productID = $row['productID'];
    
            // Check if the product already exists in the array, if not create it
            if (!isset($products[$productID])) {
                $products[$productID] = [
                    'productID' => $row['productID'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'category' => $row['category'],
                    'colour' => $row['colour'],
                    'images' => [],
                    'variants' => []
                ];
            }
    
            // Add image if it exists and is not added
            if ($row['imageID']) {
                $imageExists = false;
                foreach ($products[$productID]['images'] as $image) {
                    if ($image['imageID'] === $row['imageID']) {
                        $imageExists = true;
                        break;
                    }
                }
                if (!$imageExists) {
                    $products[$productID]['images'][] = [
                        'imageID' => $row['imageID'],
                        'image_url' => $row['image_url'],
                        'image_type' => $row['image_type']
                    ];
                }
            }
            
            // Add variant if it exists and is not added
            if ($row['variantID']) {
                $variantExists = false;
                foreach ($products[$productID]['variants'] as $variant) {
                    if ($variant['variantID'] === $row['variantID']) {
                        $variantExists = true;
                        break;
                    }
                }
                if (!$variantExists) {
                    $products[$productID]['variants'][] = [
                        'variantID' => $row['variantID'],
                        'size' => $row['size'],
                        'stock' => $row['stock']
                    ];
                }
            }
        }
    
        // Re-index the array to return a sequential array of products
        return array_values($products);
    }
    
    // Get a single product by ID with images and variants
    public function getAProduct($id) {
        $sql = "
            SELECT 
                p.productID,
                p.name,
                p.description,
                p.price,
                p.category,
                p.colour,
                pi.imageID,
                pi.image_url,
                pi.image_type,
                pv.variantID,
                pv.size,
                pv.stock
            FROM Products p
            LEFT JOIN ProductImages pi ON p.productID = pi.productID
            LEFT JOIN ProductVariants pv ON p.productID = pv.productID
            WHERE p.productID = ?
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $product = null;
        while ($row = $result->fetch_assoc()) {
            if (!$product) {
                $product = [
                    'productID' => $row['productID'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'category' => $row['category'],
                    'colour' => $row['colour'],
                    'images' => [],
                    'variants' => []
                ];
            }
    
            if ($row['imageID']) {
                $imageExists = false;
                foreach ($product['images'] as $image) {
                    if ($image['imageID'] === $row['imageID']) {
                        $imageExists = true;
                        break;
                    }
                }
                if (!$imageExists) {
                    $product['images'][] = [
                        'imageID' => $row['imageID'],
                        'image_url' => $row['image_url'],
                        'image_type' => $row['image_type']
                    ];
                }
            }
            
            if ($row['variantID']) {
                $variantExists = false;
                foreach ($product['variants'] as $variant) {
                    if ($variant['variantID'] === $row['variantID']) {
                        $variantExists = true;
                        break;
                    }
                }
                if (!$variantExists) {
                    $product['variants'][] = [
                        'variantID' => $row['variantID'],
                        'size' => $row['size'],
                        'stock' => $row['stock']
                    ];
                }
            }
        }
    
        return $product;
    }

    // Get all product variants for a specific product
    public function getProductVariantsID($productID, $size){
        $stmt = $this->conn->prepare("SELECT variantID FROM ProductVariants WHERE productID = ? AND size = ?");
        $stmt->bind_param("is", $productID, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['variantID'] : null; // return just the value or null if not found
    }
    
    // Get maximum stock available for a given product and size
    public function getMaxStock($productId, $size) {
        $stmt = $this->conn->prepare("SELECT stock FROM ProductVariants WHERE productID = ? AND size = ?");
        $stmt->bind_param("is", $productId, $size);  // Use $productId instead of $productID
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['stock'] : 0;  // Return stock value, or 0 if not found
    }

    // Get all reviews for a specific product
    public function getRecentReviews($productID) {    
        $query = "
            SELECT 
                Ratings.rating,
                Ratings.review,
                Ratings.productID,
                Ratings.size,
                Products.name AS productName,
                Users.name AS userName
            FROM Ratings
            JOIN Users ON Ratings.userID = Users.userID
            JOIN Products ON Ratings.productID = Products.productID
            WHERE Ratings.productID = ?
            ORDER BY Ratings.ratingID DESC

        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    
        $stmt->close();
        return $reviews;
    }
}
?>