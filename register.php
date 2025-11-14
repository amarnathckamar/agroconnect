<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketplace";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = $_POST["role"];
    $address = isset($_POST["address"]) ? $_POST["address"] : null;

    if ($role === "user") {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password, $role, $address);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
    }

    if ($stmt->execute()) {
        echo "Registration successful as $role!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            min-height: 100vh;
        }

        header {
            background: #333;
            color: #fff;
            width: 100%;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
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

        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin-top: 2rem;
        }

        h2 {
            text-align: center;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        input,
        select {
            margin-bottom: 1rem;
            padding: 0.5rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 0.5rem;
            font-size: 1rem;
            color: #fff;
            background: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #555;
        }

        .switch {
            text-align: center;
            margin-top: 1rem;
        }

        .switch a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .switch a:hover {
            text-decoration: underline;
        }

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
            <a href="login.php">Login</a>
        </nav>
    </header>

    <div class="container" id="register-container">
        <h2>Register</h2>
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="seller">Seller</option>
            </select>

            <label for="address" id="address-label" style="display: none;">Address:</label>
            <input type="text" id="address" name="address" style="display: none;">

            <button type="submit">Register</button>
        </form>
        <div class="switch">
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const addressField = document.getElementById('address');
        const addressLabel = document.getElementById('address-label');

        roleSelect.addEventListener('change', function () {
            if (this.value === 'user') {
                addressField.style.display = 'block';
                addressLabel.style.display = 'block';
                addressField.setAttribute("required", "required");
            } else {
                addressField.style.display = 'none';
                addressLabel.style.display = 'none';
                addressField.removeAttribute("required");
            }
        });

        // Run on page load
        roleSelect.dispatchEvent(new Event('change'));
    </script>
</body>
</html>
