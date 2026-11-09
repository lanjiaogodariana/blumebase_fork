<?php
// start session to manage state and cart
session_start();

// include db connection
require_once 'includes/db.php';

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// redirect to shop if product id is invalid (0)
if ($product_id === 0) {
    header("Location: shop.php");
    exit;
}

// Fetch the product from the database using prepared statement
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// redirect to shop if product does not exist in db
if (!$product) {
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
    <title><?php echo htmlspecialchars($product['name']); ?> | Blume</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-with-nav">
    <!-- include navbar -->
    <?php include 'includes/navbar.php'; ?>

    <main class="blume-container">
        <div class="blume-product-layout">
            
            <!-- Left Side: Product Images -->
            <div class="blume-gallery">
                <!-- primary product image -->
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <!-- render secondary image if available -->
                <?php if (!empty($product['image_url_2'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image_url_2']); ?>" alt="Alternate View">
                <?php endif; ?>
                <!-- render tertiary image if available -->
                <?php if (!empty($product['image_url_3'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image_url_3']); ?>" alt="Detail View">
                <?php endif; ?>
            </div>
            
            <!-- Right Side: Product Details & Form -->
            <div class="blume-details">
                <h1 class="serif-font blume-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="blume-price">$<?php echo number_format($product['price'], 2); ?></div>
                
                <div class="blume-description">
                    <!-- convert newlines to line breaks for description text -->
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </div>

                <!-- add to cart form submits via post to cart_action.php -->
                <form action="cart_action.php" method="POST" style="margin-bottom: 32px; border-top: 1px solid #eaeaea; padding-top: 24px;">
                    <!-- hidden inputs for product id and action type -->
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="action" value="add">
                    
                    <!-- Dynamic Size Selection -->
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #666;">Size</label>
                        <select name="size" required style="width: 100%; padding: 12px; border: 1px solid #ccc; background: transparent; font-size: 14px;">
                            <?php 
                                // Check if the database has sizes listed, otherwise default to standard sizes
                                $db_sizes = !empty($product['sizes']) ? $product['sizes'] : 'XS, S, M, L, XL';
                                
                                // Split the comma-separated string into an array
                                $size_array = explode(',', $db_sizes);
                                
                                // Loop through each size and create an option tag
                                foreach ($size_array as $size): 
                                    $clean_size = trim($size); 
                            ?>
                                <option value="<?php echo htmlspecialchars($clean_size); ?>">
                                    <?php echo htmlspecialchars($clean_size); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Dynamic Color Selection -->
                    <div class="form-group" style="margin-bottom: 24px;">
                        <label style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #666;">Color</label>
                        <select name="color" required style="width: 100%; padding: 12px; border: 1px solid #ccc; background: transparent; font-size: 14px;">
                            <?php 
                                // Check if the database has colors listed, otherwise default to 'Standard'
                                $db_colors = !empty($product['colors']) ? $product['colors'] : 'Standard';
                                
                                // Split the comma-separated string into an array
                                $color_array = explode(',', $db_colors);
                                
                                // Loop through each color and create an option tag
                                foreach ($color_array as $color): 
                                    $clean_color = trim($color); 
                            ?>
                                <option value="<?php echo htmlspecialchars($clean_color); ?>">
                                    <?php echo htmlspecialchars($clean_color); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="display: flex; gap: 16px;">
                        <!-- quantity input field -->
                        <input type="number" name="quantity" value="1" min="1" style="width: 80px; padding: 12px; border: 1px solid #ccc; text-align: center; font-size: 14px;">
                        <!-- submit button to add item to cart -->
                        <button type="submit" class="btn-primary" style="margin-top: 0; flex-grow: 1;">ADD TO CART</button>
                    </div>
                </form>

                <div style="font-size: 12px; color: #666; line-height: 1.8;">
                    <p>Free standard shipping on all orders over $150.</p>
                    <p>Returns accepted within 14 days of delivery.</p>
                </div>
            </div>

        </div>
    </main>

    <!-- include footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>