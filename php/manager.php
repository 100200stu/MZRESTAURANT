<?php
include 'config.php'; // Include the database connection

// Fetch all orders
$sql = "SELECT orders.order_id, customer_name, address, delivery_time, item_name, quantity, status 
        FROM orders 
        JOIN order_items ON orders.order_id = order_items.order_id";
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
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<h1>Manager Dashboard</h1>
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
            <th>Item</th>
            <th>Aantal</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                <td><?= htmlspecialchars($order['address']) ?></td>
                <td><?= htmlspecialchars($order['delivery_time']) ?></td>
                <td><?= htmlspecialchars($order['item_name']) ?></td>
                <td><?= htmlspecialchars($order['quantity']) ?></td>
                <td><?= htmlspecialchars($order['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>
