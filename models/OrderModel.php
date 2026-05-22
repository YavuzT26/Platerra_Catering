<?php
class OrderModel
{

    private $connection;

    public function __construct($database)
    {
        $this->connection = $database;
    }

    public function createOrder($userId, $totalPrice)
    {
        $sql = "INSERT INTO 
                orders (user_id,total_price)
              VALUES 
                (:id,:price)";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id' => $userId,
            ':price' => $totalPrice
        ]);
    }
}
