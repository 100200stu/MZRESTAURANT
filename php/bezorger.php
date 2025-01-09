<?php
include 'config.php';

// Fetch active orders
$sql = "SELECT * FROM orders WHERE status = 'out_for_delivery' ORDER BY created_at";
$result = $conn->query($sql);

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $update_sql = "UPDATE orders SET status = 'delivered', completed_at = NOW() WHERE id = $order_id";
    $conn->query($update_sql);
    header("Location: bezorger.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bezorger Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<h1>Bezorger Dashboard</h1>

<?php if ($result && $result->num_rows > 0): ?>
    <div class="order-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="order-box">
                <h2>Order #<?= htmlspecialchars($row['id']) ?></h2>
                <p><strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($row['customer_address']) ?></p>
                <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($row['status'])) ?></p>
                <p><strong>Placed At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>

                <!-- Update Status Button -->
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                    <button type="submit">Mark as Delivered</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="message">No active orders.</div>
<?php endif; ?>
</body>
</html>
