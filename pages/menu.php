<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration
include('../php/config.php');

// Fetch menu items from the database
$sql = "SELECT name, description, image FROM menu_items";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - M&Z Restaurant</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../script/script.js" defer></script>
</head>
<body>

<!-- Header Section -->
<header>
    <nav class="navbar">
        <div class="logo">
            <img src="../images/Blue%20and%20White%20Circle%20Surfing%20Club%20Logo.png" alt="M&Z Logo">
        </div>
        <ul class="nav-links">
            <li><a href="../index.html" class="active">Home</a></li>
            <li><a href="menu.php" class="btn-menukaart">Menukaart</a></li>
            <li><a href="#order">Bestellen</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
</header>

<!-- Menu Section -->
<section class="menu">
    <div class="menu-header">
        <h1>Our Menu</h1>
        <p>Explore our delicious dishes</p>
    </div>
    <div class="menu-items">
        <?php
        // Check if there are menu items and display them
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="menu-item">';
                echo '<img src="../images/' . $row['image'] . '" alt="' . $row['name'] . '">';
                echo '<h2>' . $row['name'] . '</h2>';
                echo '<p>' . $row['description'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No menu items available.</p>';
        }
        ?>
    </div>
</section>

</body>
</html>
