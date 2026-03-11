# PRIND Concept Store - Badminton E-Commerce Website


## 📋 Overview

PRIND Concept Store is a full-stack e-commerce web application specialized in badminton equipment and accessories. Developed as a coursework project for Web Application Development (UECS2194), this platform demonstrates the integration of front-end and back-end technologies to create a modern, responsive, and user-friendly online shopping experience.

**Live Demo:** [Coming Soon]  
**GitHub Repository:** [https://github.com/kairen0606/Web-Application-Development](https://github.com/kairen0606/Web-Application-Development)

## 👥 Team Members

| Name | ID | Role |
|------|-----|------|
| Lim Kai Yang | 2205805 | Code Development & Report Preparation |
| Chin Jia Wei | 2207102 | Code Development & Report Preparation |
| Ong Wei Jun | 2206727 | Code Development & Report Preparation |
| Cheok Kai Ren | 2105088 | Code Development & Report Preparation |

## ✨ Features

### 🛍️ Product Browsing
- **Category-based filtering**: Browse products by categories (rackets, clothes, grips, bags)
- **Product sorting**: Sort by price (low to high, high to low) and name
- **Search functionality**: Search products by name
- **Product details**: View detailed information including images, variants (size, weight, grip options)
- **Related products**: Display related items from the same category

### 👤 User Authentication
- **User registration**: Create new accounts with validation
- **Secure login**: Session-based authentication
- **Password hashing**: Secure password storage using hashing algorithms
- **Access control**: Protected routes for authenticated users

### 🛒 Shopping Cart
- **Add to cart**: Add products with quantity selection
- **Cart management**: Update quantities, remove items
- **Real-time updates**: Dynamic subtotal and total calculations
- **Persistent storage**: Cart data stored in database per user
- **Stock validation**: Prevent adding more than available stock

### ❤️ Wishlist
- **Save for later**: Add products to wishlist
- **Quick add to cart**: Move items from wishlist to cart
- **Remove items**: Delete items from wishlist

### 👤 User Profile
- **Personal information**: View and edit profile details
- **Order history**: Track past orders with details
- **Purchase statistics**: View graphical representation of purchase history
  - Time series charts
  - Pie charts
  - Bar charts
  - Year filter functionality

### 📞 Contact & Support
- **Contact form**: Submit inquiries with validation
- **Store location**: Embedded Google Maps
- **Social media links**: Connect to PRIND social platforms

### 📱 Responsive Design
- **Mobile-friendly**: Hamburger menu for mobile viewports
- **Adaptive layouts**: Optimized for various screen sizes
- **Consistent experience**: Cross-device compatibility

## 🛠️ Technology Stack

### Front-End
- **HTML5**: Structure and content
- **CSS3**: Styling and responsive design
- **JavaScript**: Client-side interactivity and validation
- **Chart.js**: Statistical visualizations

### Back-End
- **PHP**: Server-side logic and processing
- **MySQL**: Database management
- **Object-Oriented PHP**: Modular and maintainable code structure

### Database
- **9 Tables**: users, categories, products, product_images, product_variants, orders, order_items, cart, wishlist
- **Relationships**: Proper foreign key constraints
- **Data integrity**: Validation at database level

## 📁 Project Structure

```
pr-ind-concept-store/
├── index.php                 # Homepage
├── product.php                # Product listing page
├── product-details.php        # Product details page
├── cart.php                   # Shopping cart
├── wishlist.php               # Wishlist page
├── contact.php                # Contact page
├── login.php                  # User login
├── register.php               # User registration
├── profile.php                # User profile
├── order-history.php          # Order history
├── statistics.php             # Purchase statistics
├── about.php                  # About/brand story
├── assets/
│   ├── css/                   # Stylesheets
│   ├── js/                    # JavaScript files
│   │   └── validation.js      # Client-side validation
│   └── images/                # Product and UI images
├── includes/
│   ├── database.php           # Database connection class
│   ├── user.php               # User class
│   ├── header.php             # Common header
│   └── footer.php             # Common footer
├── api/
│   ├── add_to_cart.php        # Add to cart handler
│   ├── update_cart.php        # Update cart handler
│   └── remove_from_wishlist.php # Wishlist handler
└── database/
    └── database.sql           # Database schema
```

## 🔧 Installation & Setup

### Prerequisites
- XAMPP/WAMP/MAMP (PHP 7.4+)
- MySQL 5.7+
- Web browser (Chrome, Firefox, etc.)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/kairen0606/Web-Application-Development.git
   ```

2. **Move to web server directory**
   - For XAMPP: Copy to `C:\xampp\htdocs\prind-store`
   - For WAMP: Copy to `C:\wamp64\www\prind-store`
   - For MAMP: Copy to `/Applications/MAMP/htdocs/prind-store`

3. **Set up the database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `pr_ind_db`
   - Import `database/database.sql` file

4. **Configure database connection**
   - Update database credentials in `includes/database.php` if needed:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "pr_ind_db";
   ```

5. **Run the application**
   - Navigate to: `http://localhost/prind-store`

## 🔒 Security Features

- **Password hashing**: Secure storage using PHP's password_hash()
- **Input validation**: Both client-side and server-side validation
- **SQL injection prevention**: Prepared statements and parameter binding
- **Session security**: Session-based authentication
- **Access control**: Protected routes for authenticated users only
- **XSS prevention**: Output sanitization

## 📊 Database Schema

### Tables Overview

1. **users** - User accounts and profiles
2. **categories** - Product categories
3. **products** - Product information
4. **product_images** - Multiple images per product
5. **product_variants** - Size, color, grip options
6. **orders** - Order header information
7. **order_items** - Order line items
8. **cart** - Shopping cart items
9. **wishlist** - User wishlist items

## 🎯 Key Code Highlights

### Object-Oriented Database Connection
```php
class Database {
    public static function connect() {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
```

### Client-Side Validation
```javascript
function validatePassword(password) {
    const minLength = 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumbers = /\d/.test(password);
    const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    
    return password.length >= minLength && 
           hasUpperCase && hasLowerCase && 
           hasNumbers && hasSpecial;
}
```

### Dynamic Product Loading
```php
$sql = "SELECT p.productID, p.name, p.price, p.description, 
               c.name as category_name, pi.image_url 
        FROM Products p 
        JOIN Categories c ON p.categoryID = c.categoryID 
        LEFT JOIN ProductImages pi ON p.productID = pi.productID 
        WHERE 1=1";
if ($categoryFilter != 'all') {
    $sql .= " AND p.categoryID = " . intval($categoryFilter);
}
$sql .= " GROUP BY p.productID ORDER BY p.productID";
```

## 📱 Responsive Design Examples

- **Desktop**: Full navigation bar with all options visible
- **Tablet**: Collapsed sections with touch-friendly buttons
- **Mobile**: Hamburger menu, stacked product cards, optimized forms

## 🏆 Course Learning Outcomes

This project successfully demonstrates:

1. **CO1 (15 marks)**: Construction of static web pages with excellent HTML/CSS understanding
2. **CO2 (15 marks)**: Building dynamic web pages using event-driven client-side scripts
3. **CO3 (15 marks)**: Developing server-side and database-driven web applications
4. **Documentation (15 marks)**: Well-documented code and comprehensive report

## 🤝 Contribution Guidelines

Team members contributed equally to:
- Code development and debugging
- Report writing and documentation
- Database design and implementation
- UI/UX design and testing
- Presentation preparation

## 📝 References

- [PRIND Sports Malaysia Facebook](https://www.facebook.com/p/Prind-Sports-Malaysia-61552048646873/)
- [SUB e-Store](https://www.sub.com.my/)

## 📄 License

This project is developed for educational purposes as part of the Web Application Development course (UECS2194) at Tunku Abdul Rahman University of Management and Technology.

---

**Grade Achieved:** /60 (20% of total)  
**Submission Date:** September 2024

---

*For any inquiries regarding this project, please contact any of the team members.*
