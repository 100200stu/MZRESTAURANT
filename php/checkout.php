<?php
session_start();

// Haal de winkelwagen uit de sessie
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Check if cart is empty
if (empty($cart)) {
    echo "<h1>Je winkelwagen is leeg.</h1>";
    echo "<p>Je moet eerst producten toevoegen aan je winkelwagen voordat je kunt afrekenen.</p>";
    exit;
}

// Bereken totaal en BTW
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$btw = $total * 0.09; // 21% BTW
$total_with_btw = $total + $btw;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afrekenen - M&Z Restaurant</title>
    <link rel="stylesheet" href="../css/styles.css">
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
    <form action="../view/process_voucher.php" method="POST" id="voucher-form">
        <label for="voucher">Voucher code:</label>
        <input type="text" id="voucher" name="voucher">
        <button type="submit">Pas Voucher Toe</button>
    </form>

    <!-- Naar gegevens invullen -->
    <form action="../view/fill_details.php" method="POST">
        <button type="submit">Volgende Stap</button>
    </form>
</main>
<footer class="footer">
    <div class="footer-content">
        <div class="social-links">

            <a href="https://www.instagram.com/mzsnackbar/" target="_blank" class="social-icon">
                <img src="images/instagram-icon.png" alt="Instagram M&Z Restaurant">
            </a>
            <a href="https://www.instagram.com/mzsnackbar/" target="_blank" class="social-icon">
                <img src="images/TikTok-icon.png" alt="TikTok M&Z Restaurant">
            </a>
        </div>
        <p class="copyright">
            &copy; 2025 M&Z Restaurant. Alle rechten voorbehouden.
        </p>
    </div>
</footer>

</body>
</html>

