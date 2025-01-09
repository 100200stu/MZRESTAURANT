<?php
include 'config.php'; // Include the database connection

// Fetch orders for delivery
$sql = "SELECT order_id, customer_name, address, delivery_time, COUNT(item_id) AS item_count 
        FROM orders 
        JOIN order_items ON orders.order_id = order_items.order_id 
        WHERE status = 'ready_for_delivery' 
        GROUP BY order_id";
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
    <title>Bezorger Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<h1>Bezorger Dashboard</h1>
<?php if (empty($orders)): ?>
    <div class="message">Er zijn nog geen bestellingen.</div>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Order ID</th>
            <th>Naam</th>
            <th>Adres</th>
            <th>Bezorgtijd</th>
            <th>Aantal Items</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                <td><?= htmlspecialchars($order['address']) ?></td>
                <td><?= htmlspecialchars($order['delivery_time']) ?></td>
                <td><?= htmlspecialchars($order['item_count']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>
