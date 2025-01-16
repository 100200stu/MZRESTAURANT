<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gegevens Invullen</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>
<header>
    <h1>Vul jouw gegevens in</h1>
</header>
<main>
    <form action="submit_order.php" method="POST">
        <label for="name">Naam:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="address">Adres:</label>
        <textarea id="address" name="address" required></textarea>

        <label for="phone">Telefoonnummer:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="payment-method">Betalingsmethode:</label>
        <select id="payment-method" name="payment_method" required>
            <option value="ideal">iDEAL</option>
            <option value="creditcard">Creditcard</option>
            <option value="paypal">PayPal</option>
        </select>

        <button type="submit">Plaats Bestelling</button>
    </form>
</main>
</body>
</html>
