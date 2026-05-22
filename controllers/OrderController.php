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

        $database = new Database();
        $db = $database->getConnection();
        $mealModel = new MealModel($db);
        $orderModel = new OrderModel($db);

        foreach ($cart as $item) {
            if ($item == "Şefin Günlük Menüsü") {
                $totalPrice += $mealModel->getDailyMenuPrice($today);
            } else {
                $totalPrice += $mealModel->getMealPriceByName($item);
            }
        }

        if ($totalPrice > 0) {

            try {
                $orderModel->createOrder($userId, $totalPrice);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Siparişiniz başarıyla alındı!\nÖdenecek tutar: ' . $totalPrice . '₺'
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
