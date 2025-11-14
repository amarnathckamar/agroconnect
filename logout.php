<?php

session_start();
session_unset();
session_destroy();
echo '<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>';
header("Location: login.php");



if (isset($_SESSION['email'])) {

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "marketplace";


    $conn = new mysqli($servername, $username, $password, $dbname);


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $email = $_SESSION['email'];


    $sql = "UPDATE users SET logged_in = 0 WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {

        session_unset();
        session_destroy();


        header("Location: login.php");
        exit();
    } else {

        echo "Error updating the database: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {

    header("Location: login.php");
    exit();
}
