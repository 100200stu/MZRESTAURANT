<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../php/config.php');

// Get the form data from the POST request
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];  // Capture the phone number
$payment_method = $_POST['payment_method'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

// Check if cart is empty
if (empty($cart)) {
    die("The cart is empty. Please add items to the cart before proceeding.");
}

// Calculate the total price
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$btw = $total * 0.21;  // 21% VAT
$total_with_btw = $total + $btw;

// Apply discount if available
if (isset($_SESSION['voucher'])) {
    $discount = $total_with_btw * $_SESSION['voucher']['discount'];
    $total_with_btw -= $discount;
}


// Prepare the SQL statement to insert the order into the database
$sql = "INSERT INTO orders (customer_name, customer_address, customer_phone, order_type, status, created_at)
        VALUES (?, ?, ?, ?, 'pending', NOW())";
$stmt = $conn->prepare($sql);

// Bind the parameters to the SQL statement
$stmt->bind_param("ssss", $name, $address, $phone, $payment_method);

// Execute the query
if ($stmt->execute()) {
    echo "<h1>Bedankt voor je bestelling!</h1>";
    echo "<p>Je bestelling is succesvol geplaatst.</p>";
    // Clear the cart after successful order
    unset($_SESSION['cart']);
} else {
    echo "<h1>Er is een fout opgetreden.</h1>";
    echo "<p>Error details: " . $stmt->error . "</p>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
