<?php
session_start();
include('../php/config.php');

// Retrieve form data
$customer_name = $_POST['name'];
$customer_address = $_POST['address'] ?? null; // Address can be NULL for pickup orders
$customer_phone = $_POST['phone'];
$order_type = $_POST['order_type']; // 'afhalen' or 'bezorgen'
$status = $order_type === 'afhalen' ? 'ready_for_pickup' : 'pending';
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Check if cart is empty
if (empty($cart)) {
    die("<h1>Error: The cart is empty. Please add items before placing an order.</h1>");
}

// Insert order into `orders` table
$sql = "INSERT INTO orders (customer_name, customer_address, customer_phone, order_type, status, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $customer_name, $customer_address, $customer_phone, $order_type, $status);

if ($stmt->execute()) {
    $order_id = $stmt->insert_id; // Get the ID of the newly inserted order

    // Insert items into `order_items` table
    $item_sql = "INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)";
    $item_stmt = $conn->prepare($item_sql);

    foreach ($cart as $item) {
        $item_stmt->bind_param("iii", $order_id, $item['id'], $item['quantity']);
        $item_stmt->execute();
    }

    // Clear the cart session after successful order placement
    unset($_SESSION['cart']);

    echo "<h1>Thank you for your order, $customer_name!</h1>";
    echo "<p>Your order has been successfully placed.</p>";
} else {
    echo "<h1>An error occurred while placing your order.</h1>";
}

// Close statements and connection
$stmt->close();
$conn->close();
?>
