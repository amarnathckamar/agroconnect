<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'marketplace');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT 
        orders.id AS order_id, 
        orders.order_date, 
        order_details.product_id, 
        order_details.quantity, 
        products.name, 
        products.price
    FROM orders
    JOIN order_details ON orders.id = order_details.order_id
    JOIN products ON order_details.product_id = products.id
    WHERE orders.user_id = ?
    ORDER BY orders.order_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    $product_id = $row['product_id'];

    // Separate query to get order_status from notifications table
    $status_stmt = $conn->prepare("SELECT order_status FROM notifications WHERE order_id = ? AND product_id = ? LIMIT 1");
    $status_stmt->bind_param("ii", $order_id, $product_id);
    $status_stmt->execute();
    $status_result = $status_stmt->get_result();

    $status_row = $status_result->fetch_assoc();
    $row['order_status'] = $status_row ? $status_row['order_status'] : 'Pending';

    $orders[$order_id][] = $row;

    $status_stmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary-color: #007bff;
            --background: #f5f5f5;
            --text-color: #333;
            --card-bg: #fff;
            --card-shadow: rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: var(--background);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
        }

        header {
            background: #333;
            color: #fff;
            width: 100%;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        nav {
            display: flex;
            gap: 1rem;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }

        nav a:hover {
            background: #555;
        }

        main {
            flex: 1;
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
        }

        h2 {
            color: #222;
            margin-bottom: 20px;
        }

        .order {
            background: var(--card-bg);
            border-radius: 8px;
            margin-bottom: 30px;
            padding: 20px;
            box-shadow: 0 2px 8px var(--card-shadow);
        }

        .order h3 {
            margin-top: 0;
            color: var(--primary-color);
        }

        .order p {
            margin: 5px 0;
            color: #666;
        }

        .order ul {
            list-style-type: none;
            padding: 0;
            margin: 15px 0;
        }

        .order ul li {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .order ul li:last-child {
            border-bottom: none;
        }

        .order ul li strong {
            display: block;
            font-size: 16px;
            color: #333;
        }

        .order ul li span {
            font-size: 14px;
            color: #555;
        }

        .order-footer {
            margin-top: 15px;
            text-align: right;
            font-weight: bold;
            color: #28a745;
            font-size: 16px;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
            width: 100%;
        }

        @media (max-width: 600px) {
            .order h3 {
                font-size: 18px;
            }

            nav {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>Order History</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Your Orders</h2>

        <?php if (empty($orders)): ?>
            <p>You have no previous orders.</p>
        <?php else: ?>
            <?php foreach ($orders as $order_id => $order_items): ?>
                <div class="order">
                    <h3>Order ID: #<?= $order_id ?></h3>
                    <p>Order Date: <?= date('F j, Y', strtotime($order_items[0]['order_date'])) ?></p>
                    <ul>
                        <?php foreach ($order_items as $item): ?>
                            <li>
                                <strong><?= htmlspecialchars($item['name']) ?></strong>
                                <span>
                                    Quantity: <?= $item['quantity'] ?> |
                                    Price: ₹<?= number_format($item['price'], 2) ?> each |
                                    Status: <em><?= htmlspecialchars(ucfirst($item['order_status'])) ?></em>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="order-footer">
                        Total: ₹<?= number_format(array_sum(array_map(function ($item) {
                            return $item['price'] * $item['quantity'];
                        }, $order_items)), 2) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Marketplace. All rights reserved.
    </footer>
</body>
</html>
