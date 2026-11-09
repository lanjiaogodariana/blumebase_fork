<?php
// initialize or resume session
session_start();

// connect to db to securely get prices
require_once 'includes/db.php'; 

// check if request is post and action is add
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    
    // cast product_id to int to prevent sql injection
    $product_id = (int)$_POST['product_id'];
    
    // cast quantity to safe int
    $quantity = (int)$_POST['quantity'];
    
    // default size to standard if not set
    $size = $_POST['size'] ?? 'Standard'; 
    
    // default color to standard if not set
    $color = $_POST['color'] ?? 'Standard'; 

    // fetch product details from db using prepared statement
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    
    // execute query with product id
    $stmt->execute([$product_id]);
    
    // fetch row as associative array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // verify if valid product record was found and returned from db
    if ($product) {
        
        // initialize cart session array if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // flag to track if exact variant already exists in cart
        $item_exists = false;
        
        // loop through cart items by reference to update quantity if matched
        foreach ($_SESSION['cart'] as &$item) {
            
            // check if product id, size, and color match
            if ($item['id'] == $product_id && $item['size'] == $size && $item['color'] == $color) {
                
                // add to existing quantity
                $item['quantity'] += $quantity; 
                
                // set flag to true
                $item_exists = true;
                
                // exit loop since match is found
                break;
            }
        }

        // if it's a new combination, add it to the cart array
        if (!$item_exists) {
            
            // push new item array into session cart
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image_url'],
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color
            ];
        }
    }

    // redirect back to previous page or shop catalog
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'shop.php'));
    
    // stop script execution after redirect
    exit;
}

// handle item quantity updates and removal
if (isset($_POST['action']) && isset($_POST['cart_key'])) {
    
    // get requested action type
    $action = $_POST['action'];
    
    // get cart array index key
    $cart_key = $_POST['cart_key']; 
    
    // check if cart item index exists in session
    if (isset($_SESSION['cart'][$cart_key])) {
        
        // check if action is increase
        if ($action == 'increase') {
            
            // increment item quantity by 1
            $_SESSION['cart'][$cart_key]['quantity'] += 1;
            
        // check if action is decrease
        } elseif ($action == 'decrease') {
            
            // decrement item quantity by 1
            $_SESSION['cart'][$cart_key]['quantity'] -= 1;
            
            // remove item if quantity drops to zero or below
            if ($_SESSION['cart'][$cart_key]['quantity'] <= 0) {
                unset($_SESSION['cart'][$cart_key]);
            }
            
        // check if action is direct remove
        } elseif ($action == 'remove') {
            
            // remove item from cart session
            unset($_SESSION['cart'][$cart_key]);
        }
    }
    
    // re-index cart array to fix any gaps
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    
    // set flag to keep cart drawer open on reload
    $_SESSION['keep_cart_open'] = true; 
    
    // redirect back to previous page or shop catalog
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'shop.php'));
    
    // stop script execution
    exit;
}

// fallback redirect to shop if accessed directly without post params
header("Location: shop.php");

// stop script execution
exit;
?>