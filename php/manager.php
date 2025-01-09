<?php
include 'config.php'; // Include the database connection

// Fetch all orders
$sql = "SELECT id, customer_name, customer_address, order_type, status 
        FROM orders
        ORDER BY created_at";

$result = $conn->query($sql);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $update_sql = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
    $conn->query($update_sql);
    header("Location: manager.php"); // Refresh the page
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
<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
        <tr>
            <th>Order ID</th>
            <th>Naam</th>
            <th>Adres</th>
            <th>Type</th>
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
                <td><?= ucfirst(htmlspecialchars($row['order_type'])) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <select name="status">
                            <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="in_kitchen" <?= $row['status'] === 'in_kitchen' ? 'selected' : '' ?>>In Kitchen</option>
                            <option value="ready_for_delivery" <?= $row['status'] === 'ready_for_delivery' ? 'selected' : '' ?>>Ready for Delivery</option>
                            <option value="out_for_delivery" <?= $row['status'] === 'out_for_delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                            <option value="delivered" <?= $row['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="message">Er zijn geen bestellingen.</div>
<?php endif; ?>
</body>
</html>
