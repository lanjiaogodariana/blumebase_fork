<?php
// include db connection
require_once 'includes/db.php';

// check if request is post and order_id is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    
    // cast order id to int
    $order_id = (int)$_POST['order_id'];
    
    // sanitize and trim design changes, default to 'No changes' if empty
    $changes = htmlspecialchars(trim($_POST['design_changes'] ?? 'No changes'));

    // Update the order with the final customer changes in db
    $stmt = $pdo->prepare("UPDATE orders SET design_changes = ? WHERE id = ?");
    
    // execute update query with changes and order id
    $stmt->execute([$changes, $order_id]);

    // Send final local server email update simulation
    $email_body = "Order #$order_id finalized. Design changes noted: $changes";
    
    // append email log to local text file
    file_put_contents("email_logs.txt", "Subject: Order Finalized\nBody: $email_body\n\n", FILE_APPEND);

    // redirect user to order success page
    header("Location: order_success.php");
    
    // stop script execution
    exit;
}
?>