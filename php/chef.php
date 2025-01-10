<?php
include 'config.php'; // Include database connection

// Default view: Active Orders
$view = 'active';
if (isset($_GET['view']) && $_GET['view'] === 'previous') {
    $view = 'previous';
}

// Fetch active or previous orders
if ($view === 'active') {
    $sql = "SELECT o.id, o.customer_name, o.order_type, o.status, o.created_at
            FROM orders o
            WHERE o.status IN ('pending', 'in_kitchen')
            ORDER BY o.created_at";
} else {
    $sql = "SELECT o.id, o.customer_name, o.order_type, o.status, o.created_at, o.completed_at
            FROM orders o
            WHERE o.status = 'delivered'
            AND DATE(o.completed_at) = CURDATE()
            ORDER BY o.completed_at DESC";
}
$result = $conn->query($sql);

// Fetch order items for each order
function getOrderItems($conn, $order_id) {
    $stmt = $conn->prepare("SELECT mi.name AS item_name, oi.quantity 
                            FROM order_items oi 
                            JOIN menu_items mi ON oi.menu_item_id = mi.id 
                            WHERE oi.order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']); // Ensure $order_id is an integer
    $stmt = $conn->prepare("UPDATE orders SET status = 'ready_for_delivery' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: chef.php?view=active"); // Refresh the page
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Dashboard</title>
    <meta http-equiv="refresh" content="2">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<h1>Chef Dashboard</h1>

<!-- Toggle between Active and Previous Orders -->
<div class="order-toggle">
    <a href="chef.php?view=active" class="<?= $view === 'active' ? 'active' : '' ?>">Active Orders</a>
    <a href="chef.php?view=previous" class="<?= $view === 'previous' ? 'active' : '' ?>">Previous Orders</a>
</div>

<?php if ($result && $result->num_rows > 0): ?>
    <div class="order-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="order-box">
                <h2>Order #<?= htmlspecialchars($row['id']) ?></h2>
                <p><strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']) ?></p>
                <p><strong>Type:</strong> <?= ucfirst(htmlspecialchars($row['order_type'])) ?></p>
                <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($row['status'])) ?></p>
                <p><strong>Placed At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>

                <!-- Order Items -->
                <h3>Order Details:</h3>
                <ul>
                    <?php
                    $items_result = getOrderItems($conn, $row['id']);
                    if ($items_result && $items_result->num_rows > 0):
                        while ($item = $items_result->fetch_assoc()):
                            ?>
                            <li><?= $item['quantity'] ?> x <?= htmlspecialchars($item['item_name']) ?></li>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <li>No items found for this order.</li>
                    <?php endif; ?>
                </ul>

                <!-- Mark as Ready Button -->
                <?php if ($view === 'active' && $row['status'] === 'in_kitchen'): ?>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit">Mark as Ready</button>
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
