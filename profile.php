<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketplace";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT name, email, role, address FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($name, $email, $role, $address);
$stmt->fetch();
$stmt->close();

// Handle form submission
$updateMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = htmlspecialchars($_POST['name']);
    $newAddress = ($role === 'user' && isset($_POST['address'])) ? htmlspecialchars($_POST['address']) : null;

    if ($role === 'user') {
        $updateStmt = $conn->prepare("UPDATE users SET name = ?, address = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $newName, $newAddress, $email);
    } else {
        $updateStmt = $conn->prepare("UPDATE users SET name = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $newName, $email);
    }

    if ($updateStmt->execute()) {
        $updateMessage = "Profile updated successfully!";
        $name = $newName;
        $address = $newAddress;
        header("Location: index.php");
        exit();
    } else {
        $updateMessage = "Failed to update profile. Please try again.";
    }

    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"] {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        button {
            padding: 10px 15px;
            background-color: #333;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        .message {
            text-align: center;
            margin-top: 10px;
            font-size: 1rem;
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>

        <?php if ($updateMessage): ?>
            <p class="message"><?= htmlspecialchars($updateMessage) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly>

            <label for="role">Role</label>
            <input type="text" id="role" name="role" value="<?= htmlspecialchars($role) ?>" readonly>

            <?php if ($role === 'user'): ?>
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($address) ?>">
            <?php endif; ?>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
