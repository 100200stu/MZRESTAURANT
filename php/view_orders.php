<?php
require_once 'db_connectie.php';

$db = new Database();
$orders = $db->getOrders();

echo "<h1>Orders</h1>";
if (is_array($orders)) {
    foreach ($orders as $order) {
        echo "Order ID: " . $order['id'] . "<br>";
        echo "Item: " . $order['item_name'] . "<br>";
        echo "Quantity: " . $order['quantity'] . "<br>";
        echo "Price: €" . $order['price'] . "<br>";
        echo "Total: €" . $order['total_price'] . "<br>";
        echo "Date: " . $order['order_date'] . "<br><hr>";
    }
} else {
    echo "Error: " . $orders;
}
?>
