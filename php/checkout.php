<?php
session_start();

// Haal de winkelwagen uit de sessie
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Bereken totaal en BTW
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
$btw = $total * 0.21; // 21% BTW
$total_with_btw = $total + $btw;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afrekenen - M&Z Restaurant</title>
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>
<header>
    <h1>Afrekenen</h1>
</header>
<main>
    <h2>Jouw bestelling</h2>
    <table>
        <thead>
        <tr>
            <th>Product</th>
            <th>Aantal</th>
            <th>Prijs</th>
            <th>Totaal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cart as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']); ?></td>
                <td><?= htmlspecialchars($item['quantity']); ?></td>
                <td>€<?= number_format($item['price'], 2); ?></td>
                <td>€<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>BTW (21%): €<?= number_format($btw, 2); ?></h3>
    <h3>Totaal: €<?= number_format($total_with_btw, 2); ?></h3>

    <!-- Voucher code -->
    <form action="process_voucher.php" method="POST" id="voucher-form">
        <label for="voucher">Voucher code:</label>
        <input type="text" id="voucher" name="voucher">
        <button type="submit">Pas Voucher Toe</button>
    </form>

    <!-- Naar gegevens invullen -->
    <form action="fill_details.php" method="GET">
        <button type="submit">Volgende Stap</button>
    </form>
</main>
</body>
</html>
