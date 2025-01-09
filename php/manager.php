<?php
include 'config.php'; // Include database connection

// Default view: Active Orders
$view = 'active';
if (isset($_GET['view']) && $_GET['view'] === 'previous') {
    $view = 'previous';
}

// Default date filter: Today
$filter_date = date('Y-m-d'); // Today's date
if (isset($_GET['filter_date'])) {
    $filter_date = $_GET['filter_date']; // Use selected date
}

// Fetch active or previous orders
if ($view === 'active') {
    $sql = "SELECT * FROM orders WHERE status NOT IN ('delivered') ORDER BY created_at";
} else {
    $sql = "SELECT * FROM orders WHERE status = 'delivered' AND DATE(completed_at) = '$filter_date' ORDER BY completed_at DESC";
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
    $action = $_POST['action'];

    if ($action === 'ready') {
        $update_sql = "UPDATE orders SET status = 'ready_for_delivery' WHERE id = $order_id";
    } elseif ($action === 'assign') {
        $update_sql = "UPDATE orders SET status = 'out_for_delivery' WHERE id = $order_id";
    } elseif ($action === 'deliver') {
        $update_sql = "UPDATE orders SET status = 'delivered', completed_at = NOW() WHERE id = $order_id";
    }
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

<!-- Toggle between Active and Previous Orders -->
<div class="order-toggle">
    <a href="manager.php?view=active" class="<?= $view === 'active' ? 'active' : '' ?>">Active Orders</a>
    <a href="manager.php?view=previous" class="<?= $view === 'previous' ? 'active' : '' ?>">Previous Orders</a>
</div>

<?php if ($view === 'previous'): ?>
    <!-- Date Filter -->
    <form method="GET" class="date-filter">
        <input type="hidden" name="view" value="previous">
        <label for="filter_date">Filter by Date:</label>
        <input type="date" id="filter_date" name="filter_date" value="<?= htmlspecialchars($filter_date) ?>">
        <button type="submit">Filter</button>
    </form>
<?php endif; ?>

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

                <!-- Status Update Buttons -->
                <?php if ($row['status'] === 'in_kitchen'): ?>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="ready">
                        <button type="submit">Order Ready</button>
                    </form>
                <?php elseif ($row['status'] === 'ready_for_delivery'): ?>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="assign">
                        <button type="submit">Ready for Delivery</button>
                    </form>
                <?php elseif ($row['status'] === 'out_for_delivery'): ?>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="deliver">
                        <button type="submit">Delivered</button>
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
        <?= $view === 'active' ? 'No active orders.' : 'No previous orders for the selected date.' ?>
    </div>
<?php endif; ?>
</body>
</html>
