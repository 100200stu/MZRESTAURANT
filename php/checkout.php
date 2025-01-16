<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming the session has cart data
    $cart = $_SESSION['cart'];
    $order_method = $_SESSION['order_method']; // 'pickup' or 'delivery'

    $customer_name = htmlspecialchars($_POST['customer_name']);
    $customer_phone = htmlspecialchars($_POST['customer_phone']);
    $customer_address = ($order_method === 'delivery') ? htmlspecialchars($_POST['customer_address']) : null;

    // Insert the order
    $insert_order_sql = "INSERT INTO orders (customer_name, customer_address, customer_phone, order_type, status, created_at)
                         VALUES (?, ?, ?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($insert_order_sql);
    $stmt->bind_param('ssss', $customer_name, $customer_address, $customer_phone, $order_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    foreach ($cart as $item) {
        $insert_item_sql = "INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_item_sql);
        $stmt->bind_param('iii', $order_id, $item['id'], $item['quantity']);
        $stmt->execute();
    }

    // Clear cart and session
    unset($_SESSION['cart'], $_SESSION['order_method']);
    header('Location: order_success.php');
    exit;
}
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
    <form action="../view/fill_details.php" method="GET">
        <button type="submit">Volgende Stap</button>
    </form>
</main>
</body>
</html>
