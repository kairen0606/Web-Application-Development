<?php
    session_start();
    
    require_once '../classes/user.php';

    // Initialize error messages and form values
    $nameError = $phoneError = $emailError = $passwordError = $errorMessage = "";
    $name = $phone = $email = $password = "";


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = new User();

        $name = isset($_POST['name']) ? trim($_POST['name']) : "";
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";
        $email = isset($_POST['email']) ? trim($_POST['email']) : "";
        $password = isset($_POST['password']) ? trim($_POST['password']) : "";

        $valid = true;

        // Backend Validation

        // validatio for name
        // example
        if (empty($name)) {
            $nameError = '<i class="ri-error-warning-fill"></i> Name is required.';
            $valid = false;
        }

        // validation for phone 
        if (empty($phone)) {
            $phoneError = '<i class="ri-error-warning-fill"></i> Phone is required.';
            $valid = false;
        }


        if (empty($email)) {
            $emailError = '<i class="ri-error-warning-fill"></i> Email is required.';
            $valid = false;
        } elseif ($user->isEmailExists($email)) {
            $emailError = '<i class="ri-error-warning-fill"></i> Email is already registered.';
            $valid = false;
        }

        if (empty($password)) {
            $passwordError = '<i class="ri-error-warning-fill"></i> Password is required.';
            $valid = false;
        }

         // If all validations pass, register the user
        if ($valid) {
            if ($user->register($name, $phone, $email, $password)) {
                $_SESSION['user_email'] = $email;
                echo "<script>
                        alert('Registered successfully! Redirecting to dashboard...');
                        window.location.href = 'Login.php';
                      </script>";
                exit();
            } else {
                $errorMessage = '<i class="ri-error-warning-fill"></i> Registration failed. Please try again.';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- REMIXICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../Styles/style.css">
    <link rel="stylesheet" href="../Styles/login-signUp-style.css">
</head>
<body>
    <?php include '../includes/header.php';?>

    <section class="signUp-section">
        <h1 class="authentication-heading">Create Account</h1>

           <!-- Sign Up Form -->
        <form id="signUp-form" class="form-container" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <!-- Name -->
            <div class="form-field">
                <p>Name</p>
                <input type="text" id="name" name="name" placeholder="Enter your full name" oninput="validateName()"
                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                <div id="nameError" class="error"><?php echo $nameError; ?></div>
            </div>

            <!-- Phone Number -->
            <div class="form-field">
                <p>Phone Number</p>
                <input type="tel" id="phone" name="phone" placeholder="XXX-XXXXXXX" pattern="\d{3}-\d{7}" oninput="validatePhone()"
                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                <div id="phoneError" class="error"><?php echo $phoneError; ?></div>
            </div>

            <!-- Email Address -->
            <div class="form-field">
                <p>Email Address</p>
                <input type="text" id="email" name="email" placeholder="Enter your email" oninput="validateEmail()" 
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <div id="emailError" class="error"><?php echo $emailError; ?></div>
            </div>

            <!-- Password -->
            <div class="form-field">
                <p>Password</p>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" placeholder="Enter your password"  oninput="validatePassword()"
                        value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
                    <button type="button" id="togglePassword">
                        <i class="ri-eye-off-line"></i>
                    </button>
                </div>
                <div id="passwordError" class="error"><?php echo $passwordError; ?></div>
            </div>

            <button type="submit">Sign Up</button>
        </form>
    </section>

    <?php include '../includes/footer.php';?>
    
    <!-- JavaScript -->
    <script src="../user/validation.js"></script>
</body>
</html>