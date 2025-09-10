CREATE DATABASE IF NOT EXISTS pr_ind_db CHARACTER SET utf8 COLLATE utf8_general_ci;
USE pr_ind_db;

-- Users Table
CREATE TABLE IF NOT EXISTS Users (
    userID INT AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(13) NOT NULL,
    birthday DATE,  
    gender ENUM('Male', 'Female') NULL DEFAULT NULL,  
    CONSTRAINT Users_userID_pk PRIMARY KEY(userID)
);


-- Categories Table
CREATE TABLE IF NOT EXISTS Categories (
    categoryID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    UNIQUE KEY unique_category_name (name)
);

-- Products Table
CREATE TABLE IF NOT EXISTS Products (
    productID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(13.2) NOT NULL,
    categoryID INT,
    colour VARCHAR(20),
    FOREIGN KEY (categoryID) REFERENCES Categories(categoryID),
    UNIQUE KEY unique_product_name (name)
);

-- Product Images
CREATE TABLE IF NOT EXISTS ProductImages (
    imageID INT AUTO_INCREMENT PRIMARY KEY,
    productID INT,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (productID) REFERENCES Products(productID),
    UNIQUE KEY unique_image_url (image_url)
);

-- Product Variants
CREATE TABLE IF NOT EXISTS ProductVariants (
    variantID INT AUTO_INCREMENT PRIMARY KEY,
    productID INT,
    size VARCHAR(10),
    weight VARCHAR(10),
    grip_size VARCHAR(10),
    stock INT NOT NULL DEFAULT 0,
    FOREIGN KEY (productID) REFERENCES Products(productID)
);

-- Orders Table
CREATE TABLE IF NOT EXISTS Orders (
    orderID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    orderStatus VARCHAR(50) DEFAULT 'Pending',
    totalAmount DECIMAL(13,2) NOT NULL,
    paymentMethod VARCHAR(50) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unit INT NOT NULL,
    state VARCHAR(50) NOT NULL,
    postcode VARCHAR(5) NOT NULL,
    city VARCHAR(255) NOT NULL,
    FOREIGN KEY (userID) REFERENCES Users(userID)
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS OrderItems (
    orderItemID INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT,
    productID INT,
    variantID INT,
    quantity INT,
    price DECIMAL(13.2) NOT NULL,
    FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    FOREIGN KEY (productID) REFERENCES Products(productID),
    FOREIGN KEY (variantID) REFERENCES ProductVariants(variantID)
);

-- Cart
CREATE TABLE IF NOT EXISTS Cart (
    cartID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    productID INT NOT NULL,
    variantID INT,
    quantity INT NOT NULL DEFAULT 1,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(userID),
    FOREIGN KEY (productID) REFERENCES Products(productID),
    FOREIGN KEY (variantID) REFERENCES ProductVariants(variantID)
);

-- Wish List
CREATE TABLE IF NOT EXISTS Wishlist (
    wishlistID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    productID INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(userID),
    FOREIGN KEY (productID) REFERENCES Products(productID),
    UNIQUE KEY unique_wishlist (userID, productID)
);

-- Insert Categories
INSERT IGNORE INTO Categories (name, description) VALUES
('Racket', 'Badminton rackets of various types and specifications'),
('Clothes', 'Sports clothes for badminton players'),
('Grip', 'Badminton equipment and accessories'),
('Bag', 'Sports bags for carrying equipment');

-- Insert Products
INSERT IGNORE INTO Products (name, description, price, categoryID, colour) VALUES
('Racket Kranted A', 'High-performance badminton racket designed for speed, power, and precision.', 299.90, 1, NULL),
('Racket Kranted S', 'A lightweight, colorful pickleball paddle built for fun and competitive play.', 199.90, 1, NULL),
('Racket 8023', 'Durable badminton racket offering flexibility and comfort for all playing levels.', 89.90, 1, NULL),
('Racket Meteor', 'Professional-grade badminton racket engineered for control and consistency.', 129.90, 1, NULL),
('Racket Aerospace White', 'Spacious racket bag with secure compartments, perfect for travel and tournaments.', 159.90, 1, 'White'),
('Racket Aerospace Black', 'Large-capacity racket bag offering durability and stylish design.', 159.90, 1, 'Black'),
('Racket Ultra Seraph 5th Years Blue', 'Special edition sports bag with roomy storage and sleek design.', 159.90, 1, 'Blue'),
('Racket Ultra Seraph 5th Years White', 'Limited edition racket bag with ample space and a clean look.', 159.90, 1, 'White'),
('Racket Ultra Seraph 5th Years Red White', 'Two-tone racket bag combining functionality with modern style.', 159.90, 1, 'Red/White'),


('P.R IND Large Capacity Sports Bag White', 'Extra-large sports bag with multiple compartments for gear and accessories.', 159.90, 4, 'White'),
('P.R IND Large Capacity Sports Bag Blue', 'Spacious and durable sports bag designed to carry all essentials.', 159.90, 4, 'Blue'),
('C4 Multi Functional Backpack FengXin Violet', 'Trendy multifunctional backpack with smart compartments for everyday and sports use.', 159.90, 4, 'Violet'),
('C4 Multi Functional Backpack Orange-Blue', 'Stylish dual-tone backpack offering comfort, durability, and storage space.', 159.90, 4, 'Orange/Blue'),
('Leisure Sport Backpack White', 'Lightweight sports backpack with roomy storage and a clean design.', 159.90, 4, 'White'),
('Dragon Year Sport Satchel Black', 'Compact satchel bag with sporty design, perfect for daily training.', 159.90, 4, 'Black'),
('Dragon Year Sport Satchel White', 'Modern sport satchel with a spacious interior and sleek finish.', 159.90, 4, 'White'),
('P.R IND Badminton Backpack Black White', 'Stylish badminton backpack with padded straps and smart compartments.', 159.90, 4, 'Black/White'),
('P.R IND Badminton Backpack Dark Grey', 'Durable sports backpack in dark grey, offering functionality and comfort.', 159.90, 4, 'Dark Grey'),


('PK777 Tee Black-White', 'Breathable and comfortable sports tee with a classic black-white design.', 159.90, 2, 'Black/White'),
('PK777 Tee Black-Blue', 'Lightweight sports tee in black-blue, perfect for training and casual wear.', 159.90, 2, 'Black/Blue'),
('P.R Gold Label Logo Tee White', 'Premium sports tee with gold label branding, soft and stylish.', 159.90, 2, 'White'),
('P.R Gold Label Logo Tee Black', 'Classic black tee with gold label design, offering comfort and style.', 159.90, 2, 'Black'),
('Kaleidoscope Plus Tee Pink', 'Vibrant pink sports tee with a modern fit for comfort and performance.', 159.90, 2, 'Pink'),
('Kaleidoscope Plus Tee Yellow', 'Bright yellow tee with breathable fabric, ideal for both sports and casual wear.', 159.90, 2, 'Yellow'),

('7C Grips Fluorescent Green', 'High-quality badminton grip in fluorescent green for extra comfort and control.', 159.90, 3, 'Fluorescent Green'),
('7C Grips Green', 'Durable badminton grip in green, designed to enhance your hold and stability.', 159.90, 3, 'Green'),
('7C Grips Yellow', 'Soft and sweat-absorbent grip in yellow, improving play comfort.', 159.90, 3, 'Yellow'),
('G Keel Overgrip Orange', 'Premium overgrip in orange, offering a secure feel and reliable performance.', 159.90, 3, 'Orange');

-- Product Variants
INSERT IGNORE INTO ProductVariants (productID, size, weight, grip_size, stock) VALUES
(1, '3U', '85-89g', 'G4', 20),
(1, '4U', '80-84g', 'G5', 15),
(2, NULL, '250g', 'Standard', 30),
(4, 'Standard', NULL, NULL, 10),
(5, NULL, NULL, NULL, 20),
(6, NULL, NULL, NULL, 20),
(7, NULL, NULL, NULL, 20),
(8, NULL, NULL, NULL, 20),
(9, NULL, NULL, NULL, 20),
(10, NULL, NULL, NULL, 20),
(11, NULL, NULL, NULL, 20),
(12, NULL, NULL, NULL, 20),
(13, NULL, NULL, NULL, 20),
(14, NULL, NULL, NULL, 20),
(15, NULL, NULL, NULL, 20),
(16, NULL, NULL, NULL, 20),
(17, NULL, NULL, NULL, 20),
(18, NULL, NULL, NULL, 20),
(19, 'S', NULL, NULL, 20),
(19, 'M', NULL, NULL, 20),
(19, 'L', NULL, NULL, 15),
(20, 'S', NULL, NULL, 20),
(20, 'M', NULL, NULL, 20),
(20, 'L', NULL, NULL, 15),
(25, NULL, NULL, NULL, 50),
(26, NULL, NULL, NULL, 50),
(27, NULL, NULL, NULL, 50),
(28, NULL, NULL, NULL, 50);

-- Product Images
INSERT IGNORE INTO ProductImages (productID, image_url) VALUES
(1,'../img/racket1.png'),
(2,'../img/racket2.png'),
(3,'../img/racket3.png'),
(4,'../img/racket4.png'),
(5,'../img/racket5.png'),
(6,'../img/racket6.png'),
(7,'../img/racket7.png'),
(8,'../img/racket8.png'),
(9,'../img/racket9.png'),
(10,'../img/bag1.png'),
(11,'../img/bag2.png'),
(12,'../img/bag3.png'),
(13,'../img/bag4.png'),
(14,'../img/bag5.png'),
(15,'../img/bag6.png'),
(16,'../img/bag7.png'),
(17,'../img/bag8.png'),
(18,'../img/bag9.png'),
(19,'../img/clothes1.png'),
(20,'../img/clothes2.png'),
(21,'../img/clothes3.png'),
(22,'../img/clothes4.png'),
(23,'../img/clothes5.png'),
(24,'../img/clothes6.png'),
(25,'../img/grid1.png'),
(26,'../img/grid2.png'),
(27,'../img/grid3.png'),
(28,'../img/grid4.png');
