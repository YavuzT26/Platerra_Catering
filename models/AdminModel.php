<?php

class AdminModel
{

    private $connection;

    public function __construct($database)
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
            u.email
           FROM
            orders o
           JOIN users u
           ON o.user_id=u.user_id
           ORDER BY o.order_date DESC";
        return $this->connection->query($sql)->fetchAll();
    }

    public function getAllCustomers()
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

    public function addMeal($categoryId, $mealName, $price)
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
    public function deleteMeal($id)
    {
        $sql = "DELETE FROM meals WHERE meal_id=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt;
    }

    public function deleteCustomer($id)
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
