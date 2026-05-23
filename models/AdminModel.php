<?php

class AdminModel
{

    private \PDO $connection;

    public function __construct(\PDO $database)
    {
        $this->connection = $database;
    }

    public function getDashboardStats()
    {

        $stats = [];
        $stats['total_revenue'] = $this->connection->query("SELECT SUM(total_price) FROM orders")->fetchColumn() ?? 0;
        $stats['total_orders'] = $this->connection->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $stats['total_meals'] = $this->connection->query("SELECT COUNT(*) FROM meals")->fetchColumn();
        $stats['total_categories'] = $this->connection->query("SELECT COUNT(*)FROM categories")->fetchColumn();

        return $stats;
    }

    public function getAllOrders()
    {

        $sql = "SELECT 
                    o.*,
                    u.full_name,
                    u.email,
                    COUNT(oi.item_id) AS item_count,
                    GROUP_CONCAT(oi.meal_name ORDER BY oi.item_id SEPARATOR ', ') AS item_names
                FROM orders o
                JOIN  users       u  ON o.user_id  = u.user_id
                LEFT JOIN order_items oi ON o.order_id = oi.order_id
                GROUP BY o.order_id
                ORDER BY o.order_date DESC";
        return $this->connection->query($sql)->fetchAll();
    }

    public function getOrderItems(int $orderId): array
    {
        $sql = "SELECT 
                meal_name,
                price
               FROM
                order_items
               WHERE 
                order_id=:id
               ORDER BY 
                item_id ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':id' => $orderId
        ]);
        return $stmt->fetchAll();
    }
    public function getAllCustomers(): array
    {
        $sql = "SELECT 
                * 
               FROM 
                users 
               WHERE 
                is_admin =0 
               ORDER BY user_id DESC";
        return $this->connection->query($sql)->fetchAll();
    }

    public function getAllCategories()
    {
        $sql = "SELECT 
                *
               FROM
                categories
               ORDER BY 
                category_name ASC";
        return $this->connection->query($sql)->fetchAll();
    }

    public function addMeal(int $categoryId, string $mealName, float  $price)
    {
        $sql = "INSERT INTO
                meals (category_id,meal_name,price)
              VALUES 
                (:id,:name,:price)";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':id' => $categoryId,
            ':name' => $mealName,
            ':price' => $price
        ]);

        return $stmt;
    }
    public function deleteMeal(int $id)
    {
        $sql = "DELETE FROM meals WHERE meal_id=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt;
    }

    public function deleteCustomer(int $id)
    {
        try {
            $this->connection->beginTransaction();

            $sql1 = "DELETE FROM orders WHERE user_id=:id";
            $stmt1 = $this->connection->prepare($sql1);
            $stmt1->execute([':id' => $id]);

            $sql2 = "DELETE FROM users WHERE user_id=:id AND is_admin=0";
            $stmt2 = $this->connection->prepare($sql2);
            $stmt2->execute([':id' => $id]);

            $this->connection->commit();
            return true;
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            return $e->getMessage();
        }
    }
}
