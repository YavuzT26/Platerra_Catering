<?php

require_once 'config/Database.php';
require_once 'models/MealModel.php';
require_once 'models/OrderModel.php';

class OrderController
{

    public function checkout()
    {

        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Sipariş vermek için giriş yapın.',
                'redirect' => 'login'
            ]);
            exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['cart']) || empty($data['cart'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Sepetiniz boş.'
            ]);
            exit();
        }

        $cart = $data['cart'];
        $userId = $_SESSION['user_id'];
        $totalPrice = 0;
        $today = date('Y-m-d');

        $db = Database::getConnection();
        $mealModel = new MealModel($db);
        $orderModel = new OrderModel($db);

        // OrderModel'e aktarılacak olan dizi 
        $items = [];
        foreach ($cart as $itemName) {
            if ($itemName == "Şefin Günlük Menüsü") {

                $price = $mealModel->getDailyMenuPrice($today);

                $dailyMenu = $mealModel->getDailyMenu($today);
                $menuDetails = "";

                if ($dailyMenu) {
                    $meals = [
                        $dailyMenu['Corba'] ?? null,
                        $dailyMenu['Ana_Yemek'] ?? null,
                        $dailyMenu['Zeytinyagli'] ?? null,
                        $dailyMenu['Meze'] ?? null,
                        $dailyMenu['Tatli'] ?? null,
                        $dailyMenu['Icecek'] ?? null
                    ];
                    $validMeals = array_filter($meals);
                    if (!empty($validMeals)) {
                        $menuDetails = "\n" . implode(",\n", $validMeals);
                    }
                }
                $items[] = [
                    'meal_id' => null,
                    'meal_name' => "\nŞefin Günlük Menüsü " . $menuDetails,
                    'price' => $price
                ];
            } else {

                $meal = $mealModel->getMealByName($itemName);

                if ($meal) {
                    $items[] = [
                        'meal_id' => $meal['meal_id'],
                        'meal_name' => $itemName,
                        'price' => (float)$meal['price']
                    ];
                }
            }
        }
        // Toplam Fiyat
        foreach ($items as $item) {
            $totalPrice += $item['price'];
        }


        if ($totalPrice > 0) {

            try {
                $orderModel->createOrder($userId, $totalPrice, $items);
                echo json_encode([
                    'status' => 'success',
                    'message' => "Siparişiniz başarıyla alındı!\n\nÖdenecek tutar: " . $totalPrice . '₺'
                ]);
            } catch (\PDOException $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Sipariş oluşturulurken bir hata meydana geldi.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Siparişteki ürünler veritabanında bulunamadı.'
            ]);
        }
        exit();
    }
}
