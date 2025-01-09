<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration
include('../php/config.php');

// Fetch categories
$categories_sql = "SELECT * FROM categories";
$categories_result = $conn->query($categories_sql);

// Fetch menu items
$menu_items_sql = "SELECT menu_items.id, menu_items.name, menu_items.description, menu_items.price, menu_items.image, categories.name AS category 
                   FROM menu_items 
                   JOIN categories ON menu_items.category_id = categories.id";
$menu_items_result = $conn->query($menu_items_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&Z Restaurant</title>
    <link rel="stylesheet" href="../css/menu.css">
    <script src="../script/cart.js" defer></script>
    <script src="../script/script.js" defer></script>
</head>
<body>
<!-- Winkelwagen Teller -->
<div class="cart-counter">
    <span id="cart-count">0</span>
</div>

<!-- Header Section -->
<header>
    <nav class="navbar">
        <div class="logo">
            <img src="../images/Blue and White Circle Surfing Club Logo.png" alt="M&Z Logo">
        </div>
        <div class="burger-menu" id="burger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-links" id="nav-links">
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="PDF/menu.pdf" target="_blank">Menukaart</a></li>
            <li><a href="bestellen.php">Bestellen</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
</header>

<!-- Popup voor Bezorging of Afhalen -->
<div id="delivery-pickup-popup" class="popup">
    <div class="popup-content">
        <h2>Kies een optie</h2>
        <button class="btn-primary" id="pickup-btn">Afhalen</button>
        <button class="btn-secondary" id="delivery-btn">Bezorging</button>
    </div>
</div>

<!-- Category Tabs -->
<nav class="menu-tabs">
    <div class="menu-tabs-container">
        <?php while ($category = $categories_result->fetch_assoc()): ?>
            <button class="tab" data-category="<?= strtolower(str_replace(' ', '-', $category['name'])); ?>">
                <?= $category['name']; ?>
            </button>
        <?php endwhile; ?>
    </div>
</nav>

<!-- Menu Section -->
<main>
    <?php
    $categories_result->data_seek(0); // Reset pointer
    while ($category = $categories_result->fetch_assoc()): ?>
        <section id="<?= strtolower(str_replace(' ', '-', $category['name'])); ?>">
            <h2><?= $category['name']; ?></h2>
            <div class="menu-items">
                <?php
                $menu_items_result->data_seek(0); // Reset pointer
                while ($item = $menu_items_result->fetch_assoc()):
                    if ($item['category'] === $category['name']): ?>
                        <div class="menu-item">
                            <img src="../images/<?= $item['image']; ?>" alt="<?= $item['name']; ?>">
                            <h3><?= $item['name']; ?></h3>
                            <p><?= $item['description']; ?></p>
                            <p class="price">€<?= number_format($item['price'], 2); ?></p>
                            <button class="add-to-cart-btn" data-id="<?= $item['id']; ?>">Add to Cart</button>
                        </div>
                    <?php endif;
                endwhile; ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>

<!-- Winkelwagen Sectie -->
<div id="cart" class="cart hidden">
    <h2>Winkelwagen</h2>
    <div id="cart-items">
        <p>De winkelwagen is leeg.</p>
    </div>
    <div class="cart-summary">
        <p>Totaal: <span id="cart-total">€0.00</span></p>
        <a href="checkout.php" id="checkout-btn">Afrekenen</a>
    </div>
</div>

<script>
    // Functie om popup te tonen
    document.addEventListener('DOMContentLoaded', () => {
        const popup = document.getElementById('delivery-pickup-popup');
        const pickupBtn = document.getElementById('pickup-btn');
        const deliveryBtn = document.getElementById('delivery-btn');

        popup.classList.remove('hidden');

        pickupBtn.addEventListener('click', () => {
            popup.classList.add('hidden');
            alert('Je hebt gekozen voor Afhalen');
        });

        deliveryBtn.addEventListener('click', () => {
            popup.classList.add('hidden');
            alert('Je hebt gekozen voor Bezorging');
        });
    });
</script>
</body>
</html>
