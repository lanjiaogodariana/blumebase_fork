<?php 
// include db connection
require_once 'includes/db.php'; 

// get order id from query string and cast to int, default to 0 if not set
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// redirect to shop if order id is invalid (0)
if ($order_id === 0) {
    header("Location: shop.php");
    exit;
}

// tell navbar to use solid white background
$use_solid_nav = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Mock-Up | Blume</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-with-nav">
    <!-- include navbar -->
    <?php include 'includes/navbar.php'; ?>

    <main class="blume-container" style="max-width: 600px;">
        <h1 class="serif-font blume-title" style="text-align: center; margin-bottom: 16px;">Verify Mock-Up Design</h1>
        <p style="text-align: center; color: #666; margin-bottom: 48px; font-size: 14px;">
            Please review your customized fashion items before final production.
        </p>
        
        <div style="border-top: 1px solid #eaeaea; padding-top: 32px;">
            <!-- display order id reference -->
            <h2 class="serif-font" style="font-size: 24px; margin-bottom: 16px;">Order #<?php echo $order_id; ?></h2>
            <p style="font-size: 14px; line-height: 1.6; margin-bottom: 32px;">
                Your base designs have been logged. Please specify any final tailoring changes, hemline adjustments, or color shifts needed below.
            </p>
            
            <!-- form to submit mockup design changes via post -->
            <form action="process_mockup.php" method="POST">
                <!-- hidden input holding order id -->
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                
                <div class="form-group">
                    <label style="font-weight: bold; text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px;">Final Design Changes (Leave blank if approved)</label>
                    <textarea name="design_changes" rows="6" placeholder="e.g., Shorten the sleeves by 2 inches..." style="margin-top: 8px;"></textarea>
                </div>
                
                <button type="submit" class="btn-primary" style="margin-top: 24px;">PAYMENT</button>
            </form>
        </div>
    </main>

    <!-- include footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>