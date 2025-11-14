<?php
session_start();

if (!isset($_POST['razorpay_payment_id'])) {
    echo "Payment not verified.";
    exit();
}

$payment_id = $_POST['razorpay_payment_id'];
$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];

$conn = new mysqli('localhost', 'root', '', 'marketplace');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO orders (user_id, order_date, payment_id) VALUES ($user_id, NOW(), '$payment_id')";
$conn->query($sql);
$order_id = $conn->insert_id;

foreach ($cart as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];

    $conn->query("INSERT INTO order_details (order_id, product_id, quantity) VALUES ($order_id, $product_id, $quantity)");

    $res = $conn->query("SELECT seller_id FROM products WHERE id = $product_id");
    $row = $res->fetch_assoc();
    $seller_id = $row['seller_id'];

    $message = "New order: " . $item['name'] . ", Quantity: $quantity.";
    $conn->query("INSERT INTO notifications (seller_id, product_id, message) VALUES ($seller_id, $product_id, '$message')");
}

unset($_SESSION['cart']);
header("Location: order_confirmation.php");
exit();
?>
