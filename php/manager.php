<?php
include 'config.php'; // Include database connection

// Default view: Active Orders
$view = 'active';
if (isset($_GET['view']) && $_GET['view'] === 'previous') {
    $view = 'previous';
}

// Fetch orders
if ($view === 'active') {
    $sql = "SELECT o.id, o.customer_name, o.customer_address, o.order_type, o.status, o.created_at
            FROM orders o
            WHERE (o.order_type = 'afhalen' AND o.status = 'ready_for_pickup') 
               OR (o.order_type = 'bezorgen' AND o.status = 'ready_for_delivery')
            ORDER BY o.created_at";
} else {
    $sql = "SELECT o.id, o.customer_name, o.customer_address, o.order_type, o.status, o.created_at, o.completed_at
            FROM orders o
            WHERE (o.order_type = 'afhalen' AND o.status = 'picked_up') 
               OR (o.order_type = 'bezorgen' AND o.status = 'delivered')
            AND DATE(o.completed_at) = CURDATE()
            ORDER BY o.completed_at DESC";
}
$result = $conn->query($sql);

// Fetch order items for each order
function getOrderItems($conn, $order_id) {
    $items_sql = "SELECT mi.name AS item_name, oi.quantity 
                  FROM order_items oi 
                  JOIN menu_items mi ON oi.menu_item_id = mi.id 
                  WHERE oi.order_id = $order_id";
    return $conn->query($items_sql);
}

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action'])) {
    $order_id = $_POST['order_id'];
    if ($_POST['action'] === 'pickup') {
        $update_sql = "UPDATE orders SET status = 'picked_up', completed_at = NOW() WHERE id = $order_id";
    } elseif ($_POST['action'] === 'delivery') {
        $update_sql = "UPDATE orders SET status = 'out_for_delivery' WHERE id = $order_id";
    }
    $conn->query($update_sql);
    header("Location: manager.php?view=active");
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<h1>Manager Dashboard</h1>

<!-- Toggle between Active and Previous Orders -->
<div class="order-toggle">
    <a href="manager.php?view=active" class="<?= $view === 'active' ? 'active' : '' ?>">Active Orders</a>
    <a href="manager.php?view=previous" class="<?= $view === 'previous' ? 'active' : '' ?>">Previous Orders</a>
</div>

<?php if ($result && $result->num_rows > 0): ?>
    <div class="order-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="order-box">
                <h2>Order #<?= htmlspecialchars($row['id']) ?></h2>
                <p><strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']) ?></p>
                <?php if ($row['order_type'] === 'bezorgen'): ?>
                    <p><strong>Address:</strong> <?= htmlspecialchars($row['customer_address']) ?></p>
                <?php endif; ?>
                <p><strong>Type:</strong> <?= ucfirst(htmlspecialchars($row['order_type'])) ?></p>
                <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($row['status'])) ?></p>
                <p><strong>Placed At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>

                <!-- Order Items -->
                <h3>Order Details:</h3>
                <ul>
                    <?php
                    $items_result = getOrderItems($conn, $row['id']);
                    while ($item = $items_result->fetch_assoc()):
                        ?>
                        <li><?= $item['quantity'] ?> x <?= htmlspecialchars($item['item_name']) ?></li>
                    <?php endwhile; ?>
                </ul>

                <!-- Buttons Based on Order Type -->
                <?php if ($row['order_type'] === 'afhalen' && $row['status'] === 'ready_for_pickup'): ?>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="pick_up">
                        <button type="submit">Mark as Picked Up</button>
                    </form>
                <?php elseif ($row['order_type'] === 'bezorgen' && $row['status'] === 'ready_for_delivery'): ?>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="deliver">
                        <button type="submit">Ready for Delivery</button>
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
        <?= $view === 'active' ? 'No active orders.' : 'No previous orders for today.' ?>
    </div>
<?php endif; ?>
</body>
</html>
