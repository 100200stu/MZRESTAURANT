<?php
session_start();

header('Content-Type: application/json');

// Haal de POST-data op
$cart = json_decode(file_get_contents('php://input'), true);

if ($cart) {
    $_SESSION['cart'] = $cart;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
