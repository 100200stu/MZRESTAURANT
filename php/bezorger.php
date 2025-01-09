<?php
include 'config.php'; // Include the database connection

// Fetch orders ready for delivery
$sql = "SELECT id, customer_name, customer_address, order_type, status 
        FROM orders
        WHERE status IN ('ready_for_delivery', 'out_for_delivery')
        AND order_type = 'bezorgen'
        ORDER BY created_at";

$result = $conn->query($sql);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $update_sql = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
    $conn->query($update_sql);
    header("Location: bezorger.php"); // Refresh the page
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
    <table>
        <thead>
        <tr>
            <th>Order ID</th>
            <th>Naam</th>
            <th>Adres</th>
            <th>Status</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= htmlspecialchars($row['customer_address']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <?php if ($row['status'] === 'ready_for_delivery'): ?>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="status" value="out_for_delivery">
                            <button type="submit">Mark as Out for Delivery</button>
                        </form>
                    <?php elseif ($row['status'] === 'out_for_delivery'): ?>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit">Mark as Delivered</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="message">Er zijn geen bestellingen voor bezorging.</div>
<?php endif; ?>
</body>
</html>
