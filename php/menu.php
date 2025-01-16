<?php
session_start();
include '../php/config.php'; // Include the database configuration





// Fetch categories
$categories_sql = "SELECT * FROM categories";
$categories_result = $conn->query($categories_sql);

// Fetch menu items
$menu_items_sql = "SELECT menu_items.id, menu_items.name, menu_items.description, menu_items.price, menu_items.image, categories.name AS category 
                   FROM menu_items 
                   JOIN categories ON menu_items.category_id = categories.id";
$menu_items_result = $conn->query($menu_items_sql);

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_item_id'], $_POST['quantity'])) {
    $menu_item_id = intval($_POST['menu_item_id']);
    $quantity = intval($_POST['quantity']);

    // Validate input
    if ($quantity > 0) {
        // Check if the item already exists in the cart
        if (isset($_SESSION['cart'][$menu_item_id])) {
            $_SESSION['cart'][$menu_item_id]['quantity'] += $quantity; // Increment quantity
        } else {
            // Fetch item details from the database
            $item_sql = "SELECT id, name, price FROM menu_items WHERE id = ?";
            $stmt = $conn->prepare($item_sql);
            $stmt->bind_param('i', $menu_item_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $item = $result->fetch_assoc();
                $_SESSION['cart'][$menu_item_id] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $quantity,
                ];
            }
        }
    }
    // Redirect to avoid form resubmission
    header('Location: menu.php');
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&Z Restaurant</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../script/cart.js" defer></script>
    <script src="../script/script.js" defer></script>

</head>
<body>
<!-- Winkelwagen Teller -->
<div class="cart-counter">
    <span id="cart-count"><?= $total_items; ?></span>
</div>

<!-- Winkelwagen -->
<div id="cart" class="cart hidden">
    <h2>Winkelwagen</h2>
    <div id="cart-items">
        <?php if ($cart_items): ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <p><?= htmlspecialchars($item['name']); ?> (x<?= $item['quantity']; ?>)</p>
                    <p>€<?= number_format($item['price'] * $item['quantity'], 2); ?></p>
                </div>
                <?php $total_price += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>De winkelwagen is leeg.</p>
        <?php endif; ?>
    </div>
    <div class="cart-summary">
        <p>Totaal: <span id="cart-total">€<?= number_format($total_price, 2); ?></span></p>
        <button id="checkout-btn">Afrekenen</button>
    </div>
</div>

<header>
    <nav class="navbar">
        <div class="logo">
            <img src="../images/Blue and White Circle Surfing Club Logo.png" alt="M&Z Logo">
        </div>
        <!-- Hamburger Icon -->
        <div class="hamburger" id="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
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

<!-- Popup voor Bezorging of Afhalen -->
<div id="delivery-pickup-popup" class="popup">
    <div class="popup-content" id="step-1">
        <h2>Kies een optie</h2>
        <button class="btn-primary" id="pickup-btn">Afhalen</button>
        <button class="btn-secondary" id="delivery-btn">Bezorging</button>
    </div>

    <!-- Postcode invoeren -->
    <div class="popup-content hidden" id="step-2">
        <h2>Voer uw postcode in</h2>
        <div class="input-container">
            <input type="text" id="postcode" placeholder=" " class="modern-input">
            <label for="postcode" class="modern-label">Postcode</label>
        </div>
        <div id="message-container" class="hidden"></div>
        <button class="btn-primary" id="validate-postcode">Ga Verder</button>
    </div>
</div>

<!-- Menu Sectie -->
<main id="menu-section" class="hidden">
    <nav class="menu-tabs">
        <div class="menu-tabs-container">
            <button class="tab" id="show-all" data-category="all">Alle Gerechten</button>
            <?php while ($category = $categories_result->fetch_assoc()): ?>
                <button class="tab" data-category="<?= strtolower(str_replace(' ', '-', $category['name'])); ?>">
                    <?= $category['name']; ?>
                </button>
            <?php endwhile; ?>
        </div>
    </nav>

    <?php
    $categories_result->data_seek(0); // Reset pointer
    while ($category = $categories_result->fetch_assoc()): ?>
        <section id="<?= strtolower(str_replace(' ', '-', $category['name'])); ?>" class="menu-category">
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
                            <button class="add-to-cart-btn" data-id="<?= $item['id']; ?>" data-name="<?= $item['name']; ?>" data-price="<?= $item['price']; ?>">Add to Cart</button>
                        </div>
                    <?php endif;
                endwhile; ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>


</body>
</html>
