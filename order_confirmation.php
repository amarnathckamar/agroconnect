<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$conn = new mysqli('localhost', 'root', '', 'marketplace');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT orders.id AS order_id, orders.order_date
        FROM orders
        WHERE orders.user_id = ?
        ORDER BY orders.order_date DESC LIMIT 1";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing the SQL statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows == 0) {
    echo "No order found!";
    exit();
}

$order = $order_result->fetch_assoc();
$order_id = $order['order_id'];
$order_date = $order['order_date'];

$product_sql = "SELECT order_details.product_id, order_details.quantity, products.name AS product_name, products.price 
                FROM order_details
                JOIN products ON order_details.product_id = products.id
                WHERE order_details.order_id = ?";

$product_stmt = $conn->prepare($product_sql);
$product_stmt->bind_param("i", $order_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

$order_items = [];
$total_price = 0;
while ($row = $product_result->fetch_assoc()) {
    $order_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

$stmt->close();
$product_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f6f8fa;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 25px 0;
            text-align: center;
        }

        main {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1, h2, h3 {
            color: #2d3436;
        }

        .order-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #f1f2f6;
            border-left: 6px solid #4CAF50;
            border-radius: 6px;
        }

        ul.order-items {
            list-style-type: none;
            padding: 0;
        }

        ul.order-items li {
            margin: 10px 0;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }

        .total {
            margin-top: 20px;
            font-size: 1.4em;
            color: #27ae60;
            font-weight: bold;
        }

        .links {
            margin-top: 30px;
        }

        .links a {
            margin-right: 20px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #388e3c;
        }

        footer {
            text-align: center;
            padding: 15px 0;
            background-color: #2f2f2f;
            color: white;
            margin-top: 60px;
        }
    </style>
</head>
<body>

<header>
    <h1>Order Confirmation</h1>
</header>

<main>
    <h2>Thank you for your order!</h2>
    <p>Your order has been placed successfully. Below are your order details:</p>

    <div class="order-info">
        <h3>Order ID: #<?= $order_id ?></h3>
        <p>Order Date: <?= date("F j, Y, g:i a", strtotime($order_date)) ?></p>
    </div>

    <h3>Items in your order:</h3>
    <ul class="order-items">
        <?php foreach ($order_items as $item): ?>
            <li>
                <strong><?= htmlspecialchars($item['product_name']) ?></strong><br>
                Quantity: <?= $item['quantity'] ?> <br>
                Price: ₹<?= number_format($item['price'], 2) ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="total">
        Total Price: ₹<?= number_format($total_price, 2) ?>
    </div>

    <div class="links">
        <a href="index.php">Return to Homepage</a>
        <a href="order_history.php">View Order History</a>
    </div>
</main>

<footer>
    &copy; <?= date("Y") ?> Marketplace. All rights reserved.
</footer>

</body>
</html>
