<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketplace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (isset($_POST['user_login'])) {
        $query = "SELECT id, password FROM users WHERE email = ?";
    } elseif (isset($_POST['seller_login'])) {
        $query = "SELECT id, password FROM users WHERE email = ?";
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $id;

            if (isset($_POST['user_login'])) {
                $_SESSION['role'] = "user";
                header("Location: index.php");
            } elseif (isset($_POST['seller_login'])) {
                $_SESSION['role'] = "seller";
                header("Location: seller.php");
            }
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Account not found!";
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
    <title>Login for Users and Sellers</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            background-image: url("background.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow-y: scroll;
            overflow-x: scroll;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        .container {
            background: #fff;
            padding: 2rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .tabs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            border-bottom: 1px solid #ccc;
        }

        .tab {
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-weight: bold;
            color: #555;
            text-align: center;
            flex: 1;
        }

        .tab.active {
            color: #000;
            border-bottom: 2px solid #000;
        }

        form {
            display: none;
        }

        form.active {
            display: block;
        }

        label {
            margin-bottom: 0.5rem;
            font-weight: bold;
            display: block;
        }

        input {
            width: 100%;
            margin-bottom: 1rem;
            padding: 0.5rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
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

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* background: #333; */
            color: #fff;
            width: 100%;
            padding: 1rem 2rem;
            position: fixed;
            top: 0;
            z-index: 1000;
            /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); */
        }

        header h1 {
            margin: 0;
            padding-right: 1000px;
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

        body {
            margin-top: 80px;
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
    <main>
        <div class="container">
            <div class="tabs">
                <div class="tab active" data-tab="user">User Login</div>
                <div class="tab" data-tab="seller">Seller Login</div>
            </div>
            <form id="user" class="active" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" name="user_login">Login as User</button>
            </form>

            <form id="seller" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" name="seller_login">Login as Seller</button>
            </form>

            <div class="switch">
                <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            </div>
        </div>
    </main>

    <script>
        const tabs = document.querySelectorAll('.tab');
        const forms = document.querySelectorAll('form');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                forms.forEach(form => form.classList.remove('active'));
                document.getElementById(tab.dataset.tab).classList.add('active');
            });
        });
    </script>

</body>

</html>