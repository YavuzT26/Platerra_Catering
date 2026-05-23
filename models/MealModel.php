<?php
class MealModel
{
    private \PDO $connection;

    //Sınıf başlatıldığında veritabanı bağlantısını içine alır.
    public function __construct(\PDO $database)
    {
        $this->connection = $database;
    }

    //Kategoriye göre yemekleri getirme fonksiyonu saf veri döndürüyoruz 
    public function getMealsByCategory(int $category_id)
    {
        $sql = "SELECT meal_name,price FROM meals WHERE category_id=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':id' => $category_id
        ]);
        return $stmt->fetchAll();
    }

    // Sadece price yerine artık meal_id'de gönderiyoruz
    // array|false mantığı ise olası geri dönüş tipleri
    public function getMealByName(string $mealName): array|false
    {
        $sql = "SELECT meal_id,price FROM meals WHERE meal_name=:name";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':name' => $mealName
        ]);

        return $stmt->fetch();
    }
    // 
    public function getMealPriceByName(string $mealName): float
    {
        $result = $this->getMealByName($mealName);

        return $result ? (float)$result['price'] : 0;
    }


    // Admin Sayfasında Kullanacağız
    public function getAllMeals()
    {

        $sql = "SELECT 
            m.*,
            c.category_name
          FROM
            meals m
          JOIN 
            categories c
          ON 
            m.category_id=c.category_id
          ORDER BY 
            m.meal_id DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getDailyMenu(string $date)
    {

        $sql = "SELECT 
            dm.menu_date,
            s.meal_name AS Corba,
            mc.meal_name AS Ana_Yemek,
            oo.meal_name AS Zeytinyagli,
            a.meal_name AS Meze,
            des.meal_name AS Tatli,
            dr.meal_name AS Icecek
           FROM
            dailymenu dm
           LEFT JOIN meals s ON dm.soup_id = s.meal_id
           LEFT JOIN meals mc ON dm.main_course_id = mc.meal_id
           LEFT JOIN meals oo ON dm.olive_oil_id = oo.meal_id
           LEFT JOIN meals a ON dm.appetizer_id = a.meal_id
           LEFT JOIN meals des ON dm.dessert_id = des.meal_id
           LEFT JOIN meals dr ON dm.drink_id = dr.meal_id
           WHERE dm.menu_date = :bugun LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':bugun' => $date
        ]);

        return $stmt->fetch();
    }

    public function getDailyMenuPrice(string $date)
    {
        // COALESCE mantığı eğer o verinin değerini çekemezse değerini 0 yapar.
        $sql = "SELECT 
                    (
                      COALESCE(s.price,0) 
                    + COALESCE(mc.price,0)
                    + COALESCE(oo.price,0) 
                    + COALESCE(a.price,0) 
                    + COALESCE(des.price,0) 
                    + COALESCE(dr.price,0)
                    ) AS total_price
                  FROM dailymenu dm
                  LEFT JOIN meals s ON dm.soup_id = s.meal_id
                  LEFT JOIN meals mc ON dm.main_course_id = mc.meal_id
                  LEFT JOIN meals oo ON dm.olive_oil_id = oo.meal_id
                  LEFT JOIN meals a ON dm.appetizer_id = a.meal_id
                  LEFT JOIN meals des ON dm.dessert_id = des.meal_id
                  LEFT JOIN meals dr ON dm.drink_id = dr.meal_id
                  WHERE dm.menu_date = :bugun LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':bugun' => $date
        ]);

        $result = $stmt->fetch();

        return $result ? (float)$result['total_price'] : 0;
    }
}
