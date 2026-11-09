<?php 
// check if session is none, then start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// initialize cart item count accumulator
$cart_count = 0;
// check if session cart exists and is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // loop through cart items to sum total quantities
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>

<!-- Cart Overlay -->
<div id="cartOverlay" class="blume-cart-overlay"></div>

<!-- Cart Drawer -->
<div id="cartDrawer" class="blume-cart-drawer">
    <div class="blume-cart-inner">
        <div class="blume-cart-header">
            <h2 class="serif-font" style="font-size: 20px; margin: 0;">Your Cart</h2>
            <!-- close cart drawer button -->
            <button id="closeCart" style="font-size: 20px; background: none; border: none; cursor: pointer;">✕</button>
        </div>
        
        <div class="blume-cart-items">
            <!-- check if cart session is empty -->
            <?php if (empty($_SESSION['cart'])): ?>
                <div style="text-align: center; color: #6b7280; margin-top: 40px;">
                    <p style="font-size: 18px; margin-bottom: 16px;">Your cart is empty</p>
                    <!-- prompt login if user name session is not set -->
                    <?php if(!isset($_SESSION['user_name'])): ?>
                        <p style="font-size: 14px;">Have an account? <a href="login.php" style="text-decoration: underline; color: black;">Log in</a> to check out faster.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php 
                // initialize cart total accumulator
                $cart_total = 0;
                // We use $index here to track the exact row in the array
                foreach ($_SESSION['cart'] as $index => $item): 
                    $item_total = $item['price'] * $item['quantity'];
                    $cart_total += $item_total;
                ?>
                    <div class="blume-cart-item">
                        <!-- display product image if available, else placeholder -->
                        <?php if(!empty($item['image'])): ?>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product">
                        <?php else: ?>
                            <div style="width: 80px; height: 96px; background: #e5e7eb;"></div>
                        <?php endif; ?>
                        
                        <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3 style="font-size: 14px; margin-bottom: 4px; margin-top: 0; font-weight: normal;"><?php echo htmlspecialchars($item['name']); ?></h3>
                                
                                <!-- SIZE AND COLOR DISPLAY -->
                                <div style="font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; margin-bottom: 8px;">
                                    Size: <?php echo htmlspecialchars($item['size'] ?? 'Standard'); ?> | 
                                    Color: <?php echo htmlspecialchars($item['color'] ?? 'Standard'); ?>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                                    <div style="display: flex; align-items: center; border: 1px solid #d1d5db;">
                                        <!-- decrease item quantity form -->
                                        <form action="cart_action.php" method="POST" style="margin:0;">
                                            <input type="hidden" name="action" value="decrease">
                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                            <input type="hidden" name="cart_key" value="<?php echo $index; ?>">
                                            <button type="submit" style="padding: 4px 8px; cursor: pointer; background: none; border: none;">-</button>
                                        </form>
                                        <span style="padding: 4px 12px; font-size: 12px; border-left: 1px solid #d1d5db; border-right: 1px solid #d1d5db;"><?php echo $item['quantity']; ?></span>
                                        <!-- increase item quantity form -->
                                        <form action="cart_action.php" method="POST" style="margin:0;">
                                            <input type="hidden" name="action" value="increase">
                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                            <input type="hidden" name="cart_key" value="<?php echo $index; ?>">
                                            <button type="submit" style="padding: 4px 8px; cursor: pointer; background: none; border: none;">+</button>
                                        </form>
                                    </div>
                                    <!-- remove item form -->
                                    <form action="cart_action.php" method="POST" style="margin:0;">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <input type="hidden" name="cart_key" value="<?php echo $index; ?>">
                                        <button type="submit" style="color: #f87171; background: none; border: none; font-size: 12px; cursor: pointer; text-decoration: underline;">Remove</button>
                                    </form>
                                </div>
                            </div>
                            <p style="font-size: 14px; font-weight: 500; text-align: right; margin: 0;">$<?php echo number_format($item_total, 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="blume-cart-footer">
            <!-- check if cart is not empty to display subtotal and checkout button -->
            <?php if (!empty($_SESSION['cart'])): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 14px; font-weight: bold; letter-spacing: 0.1em;">
                    <span>SUBTOTAL</span>
                    <span>$<?php echo number_format($cart_total, 2); ?></span>
                </div>
                <a href="checkout.php" class="btn-primary" style="display: block; text-decoration: none;">CHECKOUT</a>
            <?php else: ?>
                <!-- continue shopping button if cart is empty -->
                <button class="btn-primary" onclick="document.getElementById('closeCart').click()">CONTINUE SHOPPING</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Navigation Bar -->
<nav class="blume-nav <?php echo isset($use_solid_nav) && $use_solid_nav ? 'blume-nav-solid' : ''; ?>">
    <div class="blume-nav-left">
        <a href="index.php">HOME</a>
        <a href="shop.php">SHOP ALL</a>
    </div>
    
    <div class="blume-nav-center">
        <a href="index.php" class="serif-font">BLUME</a>
    </div>

    <div class="blume-nav-right">
        <!-- check if user is logged in to show greeting or account link -->
        <?php if(isset($_SESSION['user_name'])): ?>
            <span>HI, <?php echo strtoupper(htmlspecialchars($_SESSION['user_name'])); ?></span>
            <a href="logout.php">LOGOUT</a>
        <?php else: ?>
            <a href="login.php">ACCOUNT</a>
        <?php endif; ?>
        <!-- open cart button with item count badge -->
        <button id="openCart">CART (<?php echo $cart_count; ?>)</button>
    </div>
</nav>

<script>
    // wait for dom content loaded to bind cart drawer open/close events
    document.addEventListener("DOMContentLoaded", function() {
        const cartDrawer = document.getElementById('cartDrawer');
        const cartOverlay = document.getElementById('cartOverlay');
        
        // open cart drawer on click
        document.getElementById('openCart').addEventListener('click', () => {
            cartDrawer.classList.add('open');
            cartOverlay.classList.add('open');
        });

        // close cart drawer on click
        document.getElementById('closeCart').addEventListener('click', () => {
            cartDrawer.classList.remove('open');
            cartOverlay.classList.remove('open');
        });

        // close cart drawer when clicking overlay background
        cartOverlay.addEventListener('click', () => {
            cartDrawer.classList.remove('open');
            cartOverlay.classList.remove('open');
        });
    });
</script>

<!-- check if keep_cart_open session flag is set to keep cart drawer open after action -->
<?php if (isset($_SESSION['keep_cart_open']) && $_SESSION['keep_cart_open']): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('cartDrawer').classList.add('open');
            document.getElementById('cartOverlay').classList.add('open');
        });
    </script>
    <?php unset($_SESSION['keep_cart_open']); ?>
<?php endif; ?>