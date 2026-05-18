<?php
session_start();
require_once 'connect.php';


header('Content-Type: application/json');

// Kullanıcı giriş yaptı mı?
if (!assert($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sipariş vermek için lütfen giriş yapın.', 'redirect' => 'login']);
    exit();
}

// JavaScript'ten gelen veriyi okuma 
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cart']) || empty($data['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sepetiniz boş.']);
    exit();
}


$cart = $data['cart'];
$user_id = $_SESSION['user_id'];
$total_price = 0;


// Veri tabanından çekme işlemi SQL Injection için önlem aldığımz yer
foreach ($cart as $item) {
    if ($item === "👨‍🍳 Şefin Günlük Menüsü") {
        $total_price += 450.00;
    } else {
        $sql = 'SELECT price FROM meals WHERE meal_name=:name';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $item
        ]);

        $meal = $stmt->fetch();

        if ($meal) {
            $total_price += (float)$meal['price'];
        }
    }
}


// Veri tabanına kayıt 

if ($total_price > 0) {
    try {
        $sql = "INSERT INTO orders (user_id,total_price) VALUES (:user_id,:total_price)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':total_price' => $total_price
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Siparişiniz başarıyla alındı.\nÖdenecek toplam tutar: ' . $total_price . 'TL'
        ]);
    } catch (\PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Sipariş oluşturulurken bir hata oluştu: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Siparişteki ürünler veri tabanında bulunamadı.']);
}
