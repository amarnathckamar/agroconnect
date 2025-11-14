<?php

session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'marketplace');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$notification_id = $_GET['id'];


$sql = "UPDATE notifications SET status='read' WHERE id = $notification_id AND seller_id = " . $_SESSION['user_id'];
$conn->query($sql);


header("Location: notification.php");
exit();
?>
