<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

$conn = new mysqli('localhost', 'root', '', 'marketplace');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT n.id, n.message, n.status, n.created_at, n.address, n.order_status, p.name AS product_name 
        FROM notifications n 
        JOIN products p ON n.product_id = p.id 
        WHERE n.seller_id = $seller_id 
        ORDER BY n.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Notifications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .notification {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .notification.unread {
            border-left: 5px solid #007bff;
            background-color: #e7f3ff;
        }

        .notification.read {
            border-left: 5px solid #6c757d;
            background-color: #f9f9f9;
        }

        .notification strong {
            display: block;
            margin-bottom: 5px;
        }

        .actions {
            margin-top: 10px;
        }

        header img {
            width: 60px;
            height: 50px;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Seller Notifications</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="notification <?= $row['status'] === 'unread' ? 'unread' : 'read' ?>">
                    <p><strong>Product:</strong> <?= htmlspecialchars($row['product_name']) ?></p>
                    <p><strong>Message:</strong> <?= htmlspecialchars($row['message']) ?></p>
                    <p><strong>Received at:</strong> <?= htmlspecialchars($row['created_at']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>

                    <form action="update_order_status.php" method="POST" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="notification_id" value="<?= $row['id'] ?>">
                        <label for="order_status"><strong>Status:</strong></label>
                        <select name="order_status" class="form-select w-auto" onchange="this.form.submit()">
                            <?php
                            $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                            foreach ($statuses as $status) {
                                $selected = $row['order_status'] === $status ? 'selected' : '';
                                echo "<option value=\"$status\" $selected>" . ucfirst($status) . "</option>";
                            }
                            ?>
                        </select>
                    </form>

                    <div class="actions mt-2">
                        <?php if ($row['status'] === 'unread'): ?>
                            <a href="mark_as_read.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-sm btn-primary">Mark as Read</a>
                        <?php else: ?>
                            <span class="text-muted">Already Read</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">You have no notifications.</p>
        <?php endif; ?>
        <div class="text-center mt-4">
            <a href="seller.php" class="btn btn-secondary">Back Home</a>
        </div>

    </div>
</body>

</html>

<?php
$conn->close();
?>