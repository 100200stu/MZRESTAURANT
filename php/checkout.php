<?php
session_start();

// Haal de winkelwagen uit de sessie
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Check if cart is empty
if (empty($cart)) {
    // Display empty cart message with similar style as index.php page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Winkelwagen - M&Z Restaurant</title>
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="stylesheet" href="../css/checkout.css">
    </head>
    <body>
    <!-- Header Section -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="../images/Blue and White Circle Surfing Club Logo.png" alt="M&Z Logo">
            </div>
            <!-- Back Arrow Icon -->
            <div class="back-arrow">
                <a href="../php/menu.php">
                    <img src="../images/back-arrow.png" alt="Back" style="width: 30px; height: auto;">
                </a>
            </div>
            <!-- Navigation Links -->
            <ul class="nav-links" id="nav-links">
                <li><a href="../index.php" class="active">Home</a></li>
                <li><a href="../PDF/menu.pdf" target="_blank">Menukaart</a></li>
                <li><a href="menu.php">Bestel</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section for Empty Cart -->
    <section class="hero">
        <video autoplay loop muted class="hero-video">
            <source src="https://100880.stu.sd-lab.nl/mzsnackbar/videos/IMG_2096.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="hero-content">
            <h1>Je winkelwagen is leeg</h1>
            <p>Je moet eerst producten toevoegen aan je winkelwagen voordat je kunt afrekenen.</p>
            <div class="hero-buttons">
                <a href="../php/menu.php" class="btn btn-primary">Terug naar menu</a>
            </div>
        </div>
    </section>
    </body>
    </html>
    <?php
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
    <script src="../script/script.js" defer></script>
</head>
<body>

<header>
    <h1>Afrekenen</h1>
    <nav class="navbar">
        <!-- Back Arrow Icon -->
        <div class="back-arrow">
            <a href="javascript:history.back()">
                <img src="../images/back-arrow.png" alt="Back" style="width: 30px; height: auto;">
            </a>
        </div>
        <!-- Navigation Links -->
        <ul class="nav-links" id="nav-links">
            <li><a href="index.html" class="active">Home</a></li>
            <li><a href="PDF/menu.pdf" target="_blank">Menukaart</a></li>
            <li><a href="php/menu.php">Bestellen</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
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
</body>
</html>

