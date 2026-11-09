<?php 
// start session to access user name if logged in
session_start(); 

// tell navbar to use solid white background
$use_solid_nav = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success | Blume</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-with-nav" style="display: flex; flex-direction: column; min-height: 100vh;">

    <!-- include navbar -->
    <?php include 'includes/navbar.php'; ?>

    <main class="blume-container" style="flex-grow: 1; display: flex; align-items: center; justify-content: center; width: 100%;">
        <div style="text-align: center; max-width: 500px; width: 100%;">
            <h1 class="serif-font blume-title" style="margin-bottom: 16px;">
                <!-- greet user by name from session if available, else fallback to Customer -->
                Thank You, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Customer'; ?>!
            </h1>
            
            <p style="color: #666; font-size: 14px; line-height: 1.8; margin-bottom: 40px;">
                Your order has been securely placed. We are preparing your items for shipment and will send an update to your email shortly.
            </p>
            
            <!-- button to continue shopping -->
            <a href="shop.php" class="btn-primary" style="display: inline-block; width: auto; padding: 15px 40px;">
                CONTINUE SHOPPING
            </a>
        </div>
    </main>

    <!-- include footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>