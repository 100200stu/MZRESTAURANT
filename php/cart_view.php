<?php
session_start();
include('../php/config.php');

// Haal winkelwagen-items op
$session_id = session_id();
$sql = "SELECT cart.*, menu_items.name, menu_items.price 
        FROM cart 
        JOIN menu_items ON cart.menu_item_id = menu_items.id 
        WHERE session_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelwagen</title>
    <link rel="stylesheet" href="../css/menu.css">
</head>
<body>
<h1>Winkelwagen</h1>
<?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
        <tr>
            <th>Naam</th>
            <th>Aantal</th>
            <th>Prijs</th>
            <th>Verwijderen</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()):
            $total_price += $row['price'] * $row['quantity'];
            ?>
            <tr>
                <td><?= $row['name']; ?></td>
                <td><?= $row['quantity']; ?></td>
                <td>€<?= number_format($row['price'] * $row['quantity'], 2); ?></td>
                <td>
                    <form method="POST" action="../php/cart.php">
                        <input type="hidden" name="menu_item_id" value="<?= $row['menu_item_id']; ?>">
                        <input type="hidden" name="action" value="remove">
                        <button type="submit">Verwijderen</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <h3>Totaal: €<?= number_format($total_price, 2); ?></h3>
    <form method="POST" action="checkout.php">
        <button type="submit">Afrekenen</button>
    </form>
<?php else: ?>
    <p>Je winkelwagen is leeg.</p>
<?php endif; ?>
</body>
</html>
