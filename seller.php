<?php
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Redirect to login if the user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketplace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the user's name based on their email
$email = $_SESSION['email'];
$sql = "SELECT name FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($userName);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = $conn->real_escape_string($_POST['price']);

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $image_file_type = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $valid_file_types = ["jpg", "png", "jpeg"];
            if (!in_array($image_file_type, $valid_file_types)) {
                echo "Only JPG, JPEG, PNG files are allowed.";
                exit();
            }

            $image_name = time() . '.' . $image_file_type;
            $image_path = $target_dir . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

            $sql = "INSERT INTO products (name, description, price, image, seller_id) VALUES ('$name', '$description', '$price', '$image_path', '$seller_id')";
            if ($conn->query($sql)) {
                echo "Product added successfully!";
            } else {
                echo "Error adding product: " . $conn->error;
            }
        } else {
            echo "Please upload a valid image.";
        }
    } elseif (isset($_POST['edit_product'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = $conn->real_escape_string($_POST['price']);

        if (!empty($_FILES['image']['name'])) {
            $image_file_type = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $valid_file_types = ["jpg", "png", "jpeg"];
            if (!in_array($image_file_type, $valid_file_types)) {
                echo "Only JPG, JPEG, PNG files are allowed.";
                exit();
            }

            $target_dir = "uploads/";
            $image_name = time() . '.' . $image_file_type;
            $new_image = $target_dir . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $new_image);

            $sql = "UPDATE products SET name='$name', description='$description', price='$price', image='$new_image' WHERE id=$id AND seller_id=$seller_id";
        } else {
            $sql = "UPDATE products SET name='$name', description='$description', price='$price' WHERE id=$id AND seller_id=$seller_id";
        }

        if ($conn->query($sql)) {
            header("Location: seller.php");
            exit();
        } else {
            echo "Error updating product: " . $conn->error;
        }
    } elseif (isset($_POST['delete_product'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $sql = "SELECT image FROM products WHERE id=$id AND seller_id=$seller_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $image_path = $row['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $sql = "DELETE FROM products WHERE id=$id AND seller_id=$seller_id";
        if ($conn->query($sql)) {
            echo "Product deleted successfully!";
        } else {
            echo "Error deleting product: " . $conn->error;
        }
    }
}

$sql = "SELECT * FROM products WHERE seller_id = $seller_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="seller.css">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        header img {
            width: 60px;
            height: 50px;
            margin: 0;
        }
    </style>
</head>

<body>
    <header>
        <img src="logo.png" alt="logo">
        <h1>agroconnect</h1>
        <nav>
            <a href="login.php" class="text-white me-3">Login</a>
            <a href="notification.php" class="text-white">Messages</a>
            <?php if (isset($_SESSION['email'])): ?>
                <div class="dropdown">
                    <button class="dropbtn">Welcome, <?= $userName ?> ▼</button>
                    <div class="dropdown-content">
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    <h2>Seller Dashboard</h2>

    <div class="container dash">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-container">
                    <h2 class="mb-3">Add New Product</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter product name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter product description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="Enter product price" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" name="image" id="image" accept="image/*" required>
                        </div>
                        <button type="submit" name="add_product" class="btn btn-primary w-100">Add Product</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <h2 class="mb-3">Product List</h2>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['description'] ?></td>
                                <td><?= $row['price'] ?></td>
                                <td><img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="img-thumbnail"></td>
                                <td>
                                    <a href="seller.php?edit=<?= $row['id'] ?>" class="btn btn-info btn-sm">Edit</a>
                                    <form method="POST" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" name="delete_product" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>