CREATE DATABASE IF NOT EXISTS pr_ind_db CHARACTER SET utf8 COLLATE utf8_general_ci;
USE pr_ind_db;

-- Users Table
CREATE TABLE IF NOT EXISTS Users (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(13) NOT NULL,
    birthday DATE,
    gender ENUM('Male', 'Female') DEFAULT NULL
);

-- Categories Table
CREATE TABLE IF NOT EXISTS Categories (
    categoryID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Products Table
CREATE TABLE IF NOT EXISTS Products (
    productID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(13,2) NOT NULL,
    categoryID INT,
    colour VARCHAR(20),
    FOREIGN KEY (categoryID) REFERENCES Categories(categoryID)
);

-- Product Images
CREATE TABLE IF NOT EXISTS ProductImages (
    imageID INT AUTO_INCREMENT PRIMARY KEY,
    productID INT,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (productID) REFERENCES Products(productID)
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
    price DECIMAL(13,2) NOT NULL,
    FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    FOREIGN KEY (productID) REFERENCES Products(productID),
    FOREIGN KEY (variantID) REFERENCES ProductVariants(variantID)
);

-- Ratings Table
CREATE TABLE IF NOT EXISTS Ratings (
    ratingID INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT,
    productID INT,
    userID INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    review TEXT,
    FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    FOREIGN KEY (productID) REFERENCES Products(productID),
    FOREIGN KEY (userID) REFERENCES Users(userID)
);

-- Insert Categories
INSERT INTO Categories (name, description) VALUES
('Racket', 'Badminton rackets of various types and specifications'),
('Clothes', 'Sports clothes for badminton players'),
('Grip', 'Badminton equipment and accessories'),
('Bag', 'Sports bags for carrying equipment');

-- Insert Products (categoryID corrected)
INSERT INTO Products (name, description, price, categoryID, colour) VALUES
('Racket Kranted A', 'High-performance badminton racket.', 299.90, 1, NULL),
('Racket Kranted S', 'Colorful pickleball paddle.', 199.90, 1, NULL),
('Racket 8023', 'Comfortable and flexible skirt.', 89.90, 1, NULL),
('Racket Meteor', 'Professional grade badminton net.', 129.90, 1, NULL),
('Racket Aerospace White', 'Spacious bag for rackets.', 159.90, 1, 'White'),
('Racket Aerospace Black', 'Spacious bag for rackets.', 159.90, 1, 'Black'),
('Racket Ultra Seraph 5th Years Blue', 'Spacious bag.', 159.90, 1, 'Blue'),
('Racket Ultra Seraph 5th Years White', 'Spacious bag.', 159.90, 1, 'White'),
('Racket Ultra Seraph 5th Years Red White', 'Spacious bag.', 159.90, 1, 'Red/White'),

('P.R IND Large Capacity Sports Bag White', 'Spacious bag.', 159.90, 4, 'White'),
('P.R IND Large Capacity Sports Bag Blue', 'Spacious bag.', 159.90, 4, 'Blue'),
('C4 Multi Functional Backpack FengXin Violet', 'Spacious bag.', 159.90, 4, 'Violet'),
('C4 Multi Functional Backpack Orange-Blue', 'Spacious bag.', 159.90, 4, 'Orange/Blue'),
('Leisure Sport Backpack White', 'Spacious bag.', 159.90, 4, 'White'),
('Dragon Year Sport Satchel Black', 'Spacious bag.', 159.90, 4, 'Black'),
('Dragon Year Sport Satchel White', 'Spacious bag.', 159.90, 4, 'White'),
('P.R IND Badminton Backpack Black White', 'Spacious bag.', 159.90, 4, 'Black/White'),
('P.R IND Badminton Backpack Dark Grey', 'Spacious bag.', 159.90, 4, 'Dark Grey'),

('PK777 Tee Black-White', 'Comfortable sports tee.', 159.90, 2, 'Black/White'),
('PK777 Tee Black-Blue', 'Comfortable sports tee.', 159.90, 2, 'Black/Blue'),
('P.R Gold Label Logo Tee White', 'Comfortable sports tee.', 159.90, 2, 'White'),
('P.R Gold Label Logo Tee Black', 'Comfortable sports tee.', 159.90, 2, 'Black'),
('Kaleidoscope Plus Tee Pink', 'Comfortable sports tee.', 159.90, 2, 'Pink'),
('Kaleidoscope Plus Tee Yellow', 'Comfortable sports tee.', 159.90, 2, 'Yellow'),

('7C Grips Fluorescent Green', 'Grip for badminton.', 159.90, 3, 'Fluorescent Green'),
('7C Grips Green', 'Grip for badminton.', 159.90, 3, 'Green'),
('7C Grips Yellow', 'Grip for badminton.', 159.90, 3, 'Yellow'),
('G Keel Overgrip Orange', 'Grip for badminton.', 159.90, 3, 'Orange');

-- Product Variants
INSERT INTO ProductVariants (productID, size, weight, grip_size, stock) VALUES
-- Rackets
(1, '3U', '85-89g', 'G4', 20),
(1, '4U', '80-84g', 'G5', 15),
(2, NULL, '250g', 'Standard', 30),
(4, 'Standard', NULL, NULL, 10),
-- Bags
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
-- Clothes
(19, 'S', NULL, NULL, 20),
(19, 'M', NULL, NULL, 20),
(19, 'L', NULL, NULL, 15),
(20, 'S', NULL, NULL, 20),
(20, 'M', NULL, NULL, 20),
(20, 'L', NULL, NULL, 15),
-- Grips
(25, NULL, NULL, NULL, 50),
(26, NULL, NULL, NULL, 50),
(27, NULL, NULL, NULL, 50),
(28, NULL, NULL, NULL, 50);

-- Product Images
INSERT INTO ProductImages (productID, image_url) VALUES
(1,'../img/racket1.png'),
(2,'../img/racket2.png'),
(3,'../img/racket3.png'),
(4,'../img/racket4.png'),
(5,'../img/bag1.png'),
(6,'../img/bag2.png'),
(7,'../img/bag3.png'),
(8,'../img/bag4.png'),
(9,'../img/bag5.png'),
(10,'../img/bag6.png'),
(11,'../img/bag7.png'),
(12,'../img/bag8.png'),
(13,'../img/bag9.png'),
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


