<?php
session_start();
include('../php/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $menu_item_id = intval($_POST['menu_item_id']);
    $session_id = session_id();

    if ($_POST['action'] === 'add') {
        $sql = "INSERT INTO cart (session_id, menu_item_id, quantity) VALUES (?, ?, 1)
                ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $session_id, $menu_item_id);
        $stmt->execute();
        echo "Item toegevoegd aan winkelwagen!";
    }
}
?>
