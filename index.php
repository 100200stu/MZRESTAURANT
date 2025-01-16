<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&Z Restaurant</title>
    <link rel="stylesheet" href="./css/styles.css">
    <script src="script/script.js" defer></script>
</head>
<body>
<!-- Header Section -->
<header>
    <nav class="navbar">
        <div class="logo">
            <img src="images/Blue and White Circle Surfing Club Logo.png" alt="M&Z Logo">
        </div>
        <!-- Hamburger Icon -->
        <div class="hamburger" id="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <!-- Navigation Links -->
        <ul class="nav-links" id="nav-links">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="PDF/menu.pdf" target="_blank">Menukaart</a></li>
            <li><a href="php/menu.php">Bestel</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
</header>


<!-- Hero Section -->
<section class="hero">
    <video autoplay loop muted class="hero-video">
        <source src="https://100880.stu.sd-lab.nl/mzsnackbar/videos/IMG_2096.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="hero-content">
        <h1>Welkom bij <br> M&Z Restaurant</h1>
        <div class="hero-buttons">
            <a href="php/menu.php" class="btn btn-primary">Bestellen</a>
            <a href="PDF/menu.pdf" target="_blank" class="btn btn-secondary">Menu</a> <!-- Updated to open PDF -->
        </div>
    </div>
</section>

<!-- Best Dishes Section -->
<section class="best-dishes">
    <div class="dishes-header">
        <h1 class="dishes-title">Onze beste gerechten</h1>
        <p class="dishes-subtitle">Probeer onze populaire gerechten!</p>
    </div>
    <div class="carousel">
        <div class="carousel-track">
            <!-- Original cards -->
            <div class="dish-card">
                <img src="images/burger.png" alt="Milkshake">
                <h2>Milkshake</h2>
            </div>
            <div class="dish-card">
                <img src="images/burger.png" alt="Wrap">
                <h2>Wrap</h2>
            </div>
            <div class="dish-card">
                <img src="images/burger.png" alt="Burger">
                <h2>Burger</h2>
            </div>
            <div class="dish-card">
                <img src="images/burger.png" alt="Fries">
                <h2>Fries</h2>
            </div>
            <div class="dish-card">
                <img src="images/burger.png" alt="Nuggets">
                <h2>Nuggets</h2>
            </div>
            <!-- Duplicate cards for seamless looping -->
            <div class="dish-card">
                <img src="images/burger.png" alt="Milkshake">
                <h2>Milkshake</h2>
            </div>
            <div class="dish-card">
                <img src="images/burger.png" alt="Wrap">
                <h2>Wrap</h2>
            </div>
        </div>
    </div>

    <!-- Button -->
    <a href="PDF/menu.pdf" target="_blank" class="btn-menukaart">Menukaart</a> <!-- Updated to open PDF -->
</section>
</body>
</html>
