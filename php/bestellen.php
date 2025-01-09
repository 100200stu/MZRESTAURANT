<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&Z Restaurant - Bestellen</title>
    <link rel="stylesheet" href="../css/bestellen.css">
    <script src="../script/order.js" defer></script>
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">
            <img src="/images/Blue and White Circle Surfing Club Logo.png" alt="M&Z Logo">
        </div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="#order" class="active">Bestellen</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
</header>

<div class="popup">
    <div class="popup-content">
        <h2>Kies een optie</h2>
        <button class="btn-primary" onclick="location.href='menu.php?method=pickup'">Afhalen</button>
        <button class="btn-secondary" onclick="location.href='menu.php?method=delivery'">Bezorging</button>
    </div>
</div>

<footer>
    <p>&copy; 2025 M&Z Restaurant</p>
</footer>
</body>
</html>
