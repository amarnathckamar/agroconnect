<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketplace";
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT cart.id, cart.quantity, products.id AS product_id, products.name, products.price, products.image 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


$cart_items = [];
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

$stmt->close();
$conn->close();


$_SESSION['cart'] = $cart_items;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - Cart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            background-color: #f9f9f9;
        }

        main {
            flex: 1;
            padding: 20px;
            max-width: 900px;
            margin: auto;
            width: 100%;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .cart-item img {
            width: 100px;
            height: auto;
            border-radius: 4px;
        }

        .cart-item .details {
            flex: 1;
            padding-left: 10px;
        }

        .cart-item button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .cart-item button:hover {
            background-color: #d32f2f;
        }

        .total-price {
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
        }

        .checkout-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        .checkout-btn:hover {
            background-color: #555;
        }

        header {
            background: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header img {
            width: 60px;
            height: 50px;
            margin-right: 10px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <header>
        <img src="logo.png" alt="logo">
        <h1>agroconnect</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <main>
        <h2>Your Cart</h2>

        <?php if (empty($cart_items)): ?>
            <p>No items in your cart.</p>
        <?php else: ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                    <div class="details">
                        <h3><?= $item['name'] ?></h3>
                        <p>Price: &#8377;<?= $item['price'] ?></p>
                        <p>Quantity: <?= $item['quantity'] ?></p>
                    </div>
                    <form action="remove_from_cart.php" method="POST">
                        <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                        <button type="submit">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>

            <div class="total-price">
                <p>Total: ₹<?= number_format($total_price, 2) ?></p>
            </div>

            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2023 Marketplace</p>
    </footer>
</body>

</html>
