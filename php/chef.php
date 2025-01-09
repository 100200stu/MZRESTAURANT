<?php
include 'config.php'; // Include the database connection

// Fetch orders for the kitchen
$sql = "SELECT orders.order_id, item_name, quantity 
        FROM orders 
        JOIN order_items ON orders.order_id = order_items.order_id 
        WHERE status = 'in_kitchen'";
$result = $conn->query($sql);

$orders = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<h1>Chef Dashboard</h1>
<?php if (empty($orders)): ?>
    <div class="message">Er zijn nog geen bestellingen.</div>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Order ID</th>
            <th>Item</th>
            <th>Aantal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars($order['item_name']) ?></td>
                <td><?= htmlspecialchars($order['quantity']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>
