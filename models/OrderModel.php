<?php
class OrderModel
{

    private \PDO $connection;

    public function __construct(\PDO $database)
    {
        $this->connection = $database;
    }

    // DEĞİŞİKLİK 1: createOrder() artık $items parametresi de alıyor.
    // ESKİ HAL: createOrder($userId, $totalPrice)
    //           → sadece orders tablosuna tek satır yazıyordu.
    // YENİ HAL: createOrder($userId, $totalPrice, $items)
    //           → önce orders'a yazar, dönen order_id ile
    //             her yemek için order_items'a ayrı satır ekler.
    //
    // DEĞİŞİKLİK 2: Transaction kullanıldı.
    //   Yemek kaydetme sırasında hata olursa siparişin kendisi de geri alınır.
    //   Yarım kayıt oluşmaz.
    //
    // $items dizisinin beklenen formatı:
    //   [
    //     ['meal_id' => 3,    'meal_name' => 'Domates Çorbası',   'price' => 130.00],
    //     ['meal_id' => null, 'meal_name' => 'Şefin Günlük Menüsü', 'price' => 855.00],
    //   ]
    public function createOrder(int $userId, float $totalPrice, array $items): bool
    {
        try {
            $this->connection->beginTransaction();
            $sql = "INSERT INTO 
                orders (user_id,total_price)
              VALUES 
                (:id,:price)";
            $stmt = $this->connection->prepare($sql);

            $stmt->execute([
                ':id' => $userId,
                ':price' => $totalPrice
            ]);

            // Yeni oluşturulan siparişin ID'sini aldığımız kısım
            $orderId = (int)$this->connection->lastInsertId();

            $itemSql = "INSERT INTO
                        order_items (order_id,meal_id,meal_name,price)
                      VALUES
                        (:order_id,:meal_id,:meal_name,:price)";
            $itemStmt = $this->connection->prepare($itemSql);

            foreach ($items as $item) {
                $itemStmt->execute([
                    ':order_id' => $orderId,
                    ':meal_id' => $item['meal_id'],
                    ':meal_name' => $item['meal_name'],
                    ':price' => $item['price']
                ]);
            }
            $this->connection->commit();
            return true;
        } catch (\PDOException $e) {
            $this->connection->rollback();
            throw $e;
        }
    }
}
