<?php
include 'config.php'; // Include the database connection

// Fetch orders for the chef to prepare
$sql = "SELECT o.id, o.customer_name, o.order_type, oi.menu_item_id, mi.name AS menu_item_name, oi.quantity 
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN menu_items mi ON oi.menu_item_id = mi.id
        WHERE o.status = 'in_kitchen'
        ORDER BY o.id";

$result = $conn->query($sql);

// Handle status update (mark as ready_for_delivery)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $update_sql = "UPDATE orders SET status = 'ready_for_delivery' WHERE id = $order_id";
    $conn->query($update_sql);
    header("Location: chef.php"); // Refresh the page
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<h1>Chef Dashboard</h1>
<?php if ($result && $result->num_rows > 0): ?>
    <?php
    $current_order_id = null;
    while ($row = $result->fetch_assoc()):
        if ($current_order_id !== $row['id']): ?>
            <?php if ($current_order_id !== null): ?>
                </ul>
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?= $current_order_id ?>">
                    <button type="submit">Mark as Ready</button>
                </form>
            <?php endif; ?>
            <h2>Order #<?= $row['id'] ?> (<?= ucfirst($row['order_type']) ?>)</h2>
            <p>Customer: <?= htmlspecialchars($row['customer_name']) ?></p>
            <ul>
            <?php $current_order_id = $row['id']; ?>
        <?php endif; ?>
        <li><?= htmlspecialchars($row['quantity']) ?> x <?= htmlspecialchars($row['menu_item_name']) ?></li>
    <?php endwhile; ?>
    </ul>
    <form method="POST">
        <input type="hidden" name="order_id" value="<?= $current_order_id ?>">
        <button type="submit">Mark as Ready</button>
    </form>
<?php else: ?>
    <div class="message">Er zijn geen bestellingen in de keuken.</div>
<?php endif; ?>
</body>
</html>
