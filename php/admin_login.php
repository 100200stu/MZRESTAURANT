<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded credentials (for now)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['logged_in'] = true;
        header('Location: admin_dashboard.php');
        exit;
    } else {
        echo "Invalid credentials!";
    }
}
?>
