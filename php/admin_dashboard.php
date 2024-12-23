<?php
// Include your database configuration
include('../config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - M&Z Restaurant</title>
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
            <li><a href="../php/menu.php">Menukaart</a></li>
            <li><a href="../php/order.php">Bestellen</a></li>
            <li><a href="../php/contact.php">Contact</a></li>
            <li><a href="admin_dashboard.php" class="active">Admin</a></li>
        </ul>
    </nav>
</header>

<!-- Admin Dashboard Section -->
<section class="admin-dashboard">
    <div class="admin-header">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>Manage restaurant settings, menu, orders, and reports.</p>
    </div>

    <div class="admin-buttons">
        <a href="../php/manage_menu.php" class="btn btn-primary">Manage Menu</a>
        <a href="../php/manage_orders.php" class="btn btn-primary">Manage Orders</a>
        <a href="../php/view_reports.php" class="btn btn-primary">View Reports</a>
        <a href="../php/manage_users.php" class="btn btn-primary">Manage Users</a>
        <a href="../php/settings.php" class="btn btn-primary">Settings</a>
    </div>
</section>

</body>
</html>
