<?php
class Database {
    private $pdo;

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

    public function insertCategory($name) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
    }

    public function insertMenuItem($name, $category_id, $description, $price, $img_url) {
        $stmt = $this->pdo->prepare("
            INSERT INTO menu_items (category_id, name, description, price, img_url)
            VALUES (:category_id, :name, :description, :price, :img_url)
        ");
        $stmt->execute([
            ':category_id' => $category_id,
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':img_url' => $img_url
        ]);
    }

    public function getCategories() {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMenuItems() {
        $stmt = $this->pdo->query("SELECT * FROM menu_items");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
