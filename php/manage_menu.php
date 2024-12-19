<?php
// Include your database configuration
include('../config.php');

// Fetch menu items from the database
$sql = "SELECT id, name, description, image FROM menu_items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - M&Z Restaurant</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../script/script.js" defer></script>
</head>
<body>

<!-- Admin Header Section -->
<header>
    <nav class="navbar">
        <div class="logo">
            <img src="../images/Blue and White Circle Surfing Club Logo.png" alt="M&Z Logo">
        </div>
        <ul class="nav-links">
            <li><a href="../index.html">Home</a></li>
            <li><a href="admin_dashboard.php">Admin</a></li>
        </ul>
    </nav>
</header>

<!-- Manage Menu Section -->
<section class="manage-menu">
    <div class="menu-header">
        <h1>Manage Menu Items</h1>
        <p>Add, edit, or delete menu items.</p>
    </div>

    <div class="menu-items">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="menu-item">';
                echo '<img src="../images/' . $row['image'] . '" alt="' . $row['name'] . '">';
                echo '<h2>' . $row['name'] . '</h2>';
                echo '<p>' . $row['description'] . '</p>';
                echo '<a href="edit_menu_item.php?id=' . $row['id'] . '" class="btn btn-secondary">Edit</a>';
                echo '<a href="delete_menu_item.php?id=' . $row['id'] . '" class="btn btn-danger">Delete</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No menu items found</p>';
        }
        ?>
    </div>

    <!-- Button to Add New Menu Item -->
    <a href="add_menu_item.php" class="btn btn-primary">Add New Item</a>
</section>

</body>
</html>
