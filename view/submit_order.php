<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../php/config.php');

$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$payment_method = $_POST['payment_method'];
$phone = $_POST['phone'];  // Capture the phone number
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

// Check if cart is empty
if (empty($cart)) {
    die("The cart is empty. Please add items to the cart before proceeding.");
}

// Bereken het totaalbedrag
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$btw = $total * 0.21;
$total_with_btw = $total + $btw;

// Pas korting toe
if (isset($_SESSION['voucher'])) {
    $discount = $total_with_btw * $_SESSION['voucher']['discount'];
    $total_with_btw -= $discount;
}

// Debugging: Print out the total price and other values
var_dump($name, $email, $address, $payment_method, $total_with_btw);

$sql = "INSERT INTO orders (customer_name, customer_address, customer_phone, order_type, status, created_at) 
        VALUES (?, ?, ?, ?, 'pending', NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $address, $phone, $payment_method);

// Execute the query
if ($stmt->execute()) {
    echo "<h1>Bedankt voor je bestelling!</h1>";
    echo "<p>Je bestelling is succesvol geplaatst.</p>";
} else {
    echo "<h1>Er is een fout opgetreden.</h1>";
    echo "<p>Error details: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>
