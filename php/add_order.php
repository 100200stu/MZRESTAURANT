<?php
require_once 'db_connectie.php';

header('Content-Type: application/json');

// Decode incoming JSON request
$orderData = json_decode(file_get_contents('php://input'), true);

if ($orderData) {
    $item_name = $orderData['item_name'];
    $quantity = $orderData['quantity'];
    $price = $orderData['price'];
    $total_price = $orderData['total_price'];

    // Insert the order into the database
    $db = new Database();
    $result = $db->insertOrder($item_name, $quantity, $price, $total_price);

    if ($result === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $result]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid order data']);
}
?>
