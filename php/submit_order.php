<?php
session_start();
include('../php/config.php');

// Haal gegevens uit het formulier
$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$payment_method = $_POST['payment_method'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

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

// Sla bestelling op in de database
$sql = "INSERT INTO orders (user_name, user_email, user_address, payment_method, total_price, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssd", $name, $email, $address, $payment_method, $total_with_btw);

if ($stmt->execute()) {
    echo "<h1>Bedankt voor je bestelling!</h1>";
    echo "<p>Je bestelling is succesvol geplaatst.</p>";
} else {
    echo "<h1>Er is een fout opgetreden.</h1>";
}

$stmt->close();
$conn->close();
?>
