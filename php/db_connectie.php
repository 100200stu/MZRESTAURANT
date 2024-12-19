<?php
class Database {
    private $pdo;

    // Load database configuration and establish connection
    public function __construct() {
        $config = include('config.php');
        try {
            $dsn = "mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'];
            $this->pdo = new PDO($dsn, $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Insert a new order
    public function insertOrder($item_name, $quantity, $price, $total_price) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (item_name, quantity, price, total_price)
                VALUES (:item_name, :quantity, :price, :total_price)
            ");
            $stmt->execute([
                ':item_name' => $item_name,
                ':quantity' => $quantity,
                ':price' => $price,
                ':total_price' => $total_price
            ]);
            return true;
        } catch (PDOException $e) {
            return "Error inserting order: " . $e->getMessage();
        }
    }

    // Fetch all orders
    public function getOrders() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error fetching orders: " . $e->getMessage();
        }
    }
}
?>
