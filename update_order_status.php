<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: seller_notifications.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'marketplace');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$notification_id = intval($_POST['notification_id']);
$order_status = $_POST['order_status'];

$sql = "UPDATE notifications SET order_status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $order_status, $notification_id);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: notification.php");
exit();
