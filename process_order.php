<?php
// start session to access cart and user state
session_start();

// include db connection
require_once 'includes/db.php';

// redirect to checkout if request method is not post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: checkout.php");
    exit;
}

// Server-Side Validation: sanitize and trim incoming form inputs
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = htmlspecialchars(trim($_POST['address'] ?? ''));

// Check for empty fields
if (empty($full_name) || empty($email) || empty($phone) || empty($address)) {
    die("Server Validation Failed: All fields are required.");
}

// Check for valid email format using filter_var
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Server Validation Failed: Invalid email format.");
}

// Check for exactly 8 digits in the phone number using regex
if (!preg_match('/^\d{8}$/', $phone)) {
    die("Server Validation Failed: Phone number must be exactly 8 digits.");
}

// calculate total order amount from cart items
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

try {
    // start database transaction to ensure data integrity
    $pdo->beginTransaction();

    // insert parent order record into orders table
    $stmt = $pdo->prepare("INSERT INTO orders (user_name, user_email, phone, shipping_address, total_amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$full_name, $email, $phone, $address, $total_amount]);
    
    // get last inserted order id
    $order_id = $pdo->lastInsertId();

    // insert individual cart items into order_items table
    $stmt_items = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $item) {
        $stmt_items->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
    }

    // commit transaction if all queries succeed
    $pdo->commit();

    // Local Web Server Email Simulation (saves to an email_logs.txt file in your project folder)
    $email_subject = "Blume Order Acknowledgement - #" . $order_id;
    $email_body = "Thank you $full_name. Your order #$order_id has been received. Total: $$total_amount.";
    file_put_contents("email_logs.txt", "To: $email\nSubject: $email_subject\nBody: $email_body\n\n", FILE_APPEND);

    // clear cart session after successful order
    unset($_SESSION['cart']);

    // Redirect to Mock-up Verification as required by Theme 2
    header("Location: mockup_verification.php?order_id=" . $order_id);
    exit;

} catch (Exception $e) {
    // rollback transaction on error
    $pdo->rollBack();
    die("Order Processing Failed: " . $e->getMessage());
}
?>[cite: 12]