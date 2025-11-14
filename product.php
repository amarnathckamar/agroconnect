<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketplace";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// If product not found
if (!$product) {
    echo "Product not found!";
    exit;
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Check if product already in cart
    $check_cart_sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $check_stmt = $conn->prepare($check_cart_sql);
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity
        $update_sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $update_stmt->execute();
    } else {
        // Insert new cart entry
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_stmt->execute();
    }

    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header img {
            width: 60px;
            height: 50px;
        }

        header nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
        }

        header nav a:hover {
            text-decoration: underline;
        }

        .product {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 30px auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 320px;
            background-color: #fff;
        }

        .product img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .product h2 {
            font-size: 22px;
            margin: 10px 0;
        }

        .product p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .price {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        .quantity-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-input {
            width: 60px;
            padding: 6px;
            font-size: 16px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .add-to-cart {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .add-to-cart:hover {
            background-color: #555;
        }

        footer {
            text-align: center;
            padding: 15px;
            margin-top: 50px;
            font-size: 14px;
            color: #777;
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

    <div class="product">
        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
        <div class="info">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p><?= htmlspecialchars($product['description']) ?></p>
            <p class="price">&#8377;<?= htmlspecialchars($product['price']) ?></p>
            <form action="" method="POST">
                <div class="quantity-container">
                    <label for="quantity">Qty:</label>
                    <input type="number" name="quantity" id="quantity" value="1" class="quantity-input" min="1" required>
                </div>
                <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Agroconnect Marketplace</p>
    </footer>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
