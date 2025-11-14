<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty or you're not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];

$conn = new mysqli('localhost', 'root', '', 'marketplace');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's address
$address = '';
$user_query = $conn->query("SELECT address FROM users WHERE id = $user_id");
if ($user_query && $user_query->num_rows > 0) {
    $user_data = $user_query->fetch_assoc();
    $address = $conn->real_escape_string($user_data['address']);
}

// Create new order
$order_id = null;
$sql = "INSERT INTO orders (user_id, order_date) VALUES ($user_id, NOW())";
if ($conn->query($sql)) {
    $order_id = $conn->insert_id;
}

// Process each cart item
foreach ($cart as $item) {
    $product_id = (int)$item['product_id'];
    $quantity = (int)$item['quantity'];

    // Insert into order details
    $sql = "INSERT INTO order_details (order_id, product_id, quantity) VALUES ($order_id, $product_id, $quantity)";
    $conn->query($sql);

    // Get seller id
    $sql = "SELECT seller_id FROM products WHERE id = $product_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $seller_id = (int)$row['seller_id'];

        // Create notification message
        $product_name = $conn->real_escape_string($item['name']);
        $message = "You have a new order! Product: $product_name, Quantity: $quantity.";

        // 🔁 UPDATED INSERT with order_id and default order_status
        $sql = "INSERT INTO notifications (seller_id, product_id, order_id, message, address, order_status) 
                VALUES ($seller_id, $product_id, $order_id, '$message', '$address', 'pending')";
        $conn->query($sql);
    }
}

// Clear cart
unset($_SESSION['cart']);

// Redirect to order confirmation page
header('Location: order_confirmation.php');
exit();
?>
