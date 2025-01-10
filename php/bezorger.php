<?php
include 'config.php';

// Default view: Active Orders
$view = 'active';
if (isset($_GET['view']) && $_GET['view'] === 'previous') {
    $view = 'previous';
}

// Fetch active or previous orders
if ($view === 'active') {
    $sql = "SELECT * FROM orders WHERE status = 'out_for_delivery' ORDER BY created_at";
} else {
    $sql = "SELECT * FROM orders 
        WHERE status = 'delivered' 
        AND DATE(completed_at) = CURDATE() 
        ORDER BY completed_at DESC";

}
$result = $conn->query($sql);

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $update_sql = "UPDATE orders SET status = 'delivered', completed_at = NOW() WHERE id = $order_id";
    $conn->query($update_sql);
    header("Location: bezorger.php?view=active");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bezorger Dashboard</title>
    <meta http-equiv="refresh" content="2">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<h1>Bezorger Dashboard</h1>

<!-- Toggle between Active and Previous Orders -->
<div class="order-toggle">
    <a href="bezorger.php?view=active" class="<?= $view === 'active' ? 'active' : '' ?>">Active Orders</a>
    <a href="bezorger.php?view=previous" class="<?= $view === 'previous' ? 'active' : '' ?>">Previous Orders</a>
</div>

<?php if ($result && $result->num_rows > 0): ?>
    <div class="order-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="order-box">
                <h2>Order #<?= htmlspecialchars($row['id']) ?></h2>
                <p><strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']) ?></p>
                <?php if (!empty($row['customer_address'])): ?>
                    <p><strong>Address:</strong> <?= htmlspecialchars($row['customer_address']) ?></p>
                <?php endif; ?>
                <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($row['status'])) ?></p>
                <p><strong>Placed At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>

                <?php if ($view === 'active'): ?>
                    <!-- Update Status Button -->
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <button type="submit">Mark as Delivered</button>
                    </form>
                <?php endif; ?>

                <?php if ($view === 'previous'): ?>
                    <p><strong>Completed At:</strong> <?= htmlspecialchars($row['completed_at']) ?></p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="message">
        <?= $view === 'active' ? 'No active orders.' : 'No previous orders.' ?>
    </div>
<?php endif; ?>
</body>
</html>
