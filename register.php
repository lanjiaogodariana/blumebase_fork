<?php
// start session to manage state
session_start();

// include db connection
require_once 'includes/db.php';

// initialize error message variable
$error = '';

// check if form is submitted via post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // trim incoming full name, email, and password inputs
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        
        // Check if email already exists in db
        $stmt = $pdo->prepare("SELECT id FROM base_users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "An account with this email already exists.";
        } else {
            
            // Securely hash the password before saving using PASSWORD_DEFAULT
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // insert new user record into db
            $insert = $pdo->prepare("INSERT INTO base_users (full_name, email, password) VALUES (?, ?, ?)");
            
            if ($insert->execute([$full_name, $email, $hashed_password])) {
                // redirect to login with success query parameter
                header("Location: login.php?registered=success");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
// tell navbar to use solid white background
$use_solid_nav = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account | Blume</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-with-nav">
    <!-- include navbar -->
    <?php include 'includes/navbar.php'; ?>

    <main class="blume-container" style="max-width: 500px;">
        <h1 class="serif-font" style="text-align: center; font-size: 36px; margin-bottom: 24px;">Create Account</h1>
        
        <!-- display error message if validation or registration fails -->
        <?php if ($error): ?>
            <div style="color: red; margin-bottom: 16px; text-align: center; font-size: 14px;"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- registration form with client-side js validation on submit -->
        <form action="register.php" method="POST" onsubmit="return validateRegister()">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="reg_name" name="full_name" required>
                <span class="error-message" id="regNameError">Please enter your full name.</span>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="reg_email" name="email" required>
                <span class="error-message" id="regEmailError">Please enter a valid email.</span>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="reg_password" name="password" required>
                <span class="error-message" id="regPasswordError">Password must be at least 6 characters.</span>
            </div>
            
            <button type="submit" class="btn-primary" style="margin-top: 24px;">REGISTER</button>
        </form>

        <p style="text-align: center; margin-top: 24px; font-size: 12px; color: #666;">
            Already have an account? <a href="login.php" style="color: #000; text-decoration: underline;">Sign In</a>
        </p>
    </main>

    <!-- include footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
    // client-side registration form validation function
    function validateRegister() {
        let isValid = true;
        // hide all error messages initially
        document.querySelectorAll('.error-message').forEach(e => e.style.display = 'none');
        
        // check if name is empty
        if (document.getElementById('reg_name').value.trim() === "") {
            document.getElementById('regNameError').style.display = 'block';
            isValid = false;
        }
        // check if email is empty
        if (document.getElementById('reg_email').value.trim() === "") {
            document.getElementById('regEmailError').style.display = 'block';
            isValid = false;
        }
        // check if password is at least 6 characters long
        if (document.getElementById('reg_password').value.length < 6) {
            document.getElementById('regPasswordError').style.display = 'block';
            isValid = false;
        }
        return isValid;
    }
    </script>
</body>
</html>