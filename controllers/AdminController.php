<?php

require_once 'config/Database.php';
require_once 'models/AdminModel.php';
require_once 'models/MealModel.php';
require_once 'models/UserModel.php';

class AdminController
{

    private AdminModel $adminModel;
    private MealModel $mealModel;
    private UserModel $userModel;


    public function __construct()
    {
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            header("Location: index.php");
            exit();
        }


        $db = Database::getConnection();
        $this->adminModel = new AdminModel($db);
        $this->mealModel = new MealModel($db);
        $this->userModel = new UserModel($db);
    }
    public function index()
    {
        $stats = $this->adminModel->getDashboardStats();
        $orders = $this->adminModel->getAllOrders();
        $customers = $this->adminModel->getAllCustomers();
        $categories = $this->adminModel->getAllCategories();
        $meals = $this->mealModel->getAllMeals();


        require_once 'views/admin.php';
    }

    public function handleAction()
    {
        // Token kontrolü ile eşleşme olursa işleme girer.
        // hash_equals() mantığı, eğer == veya === kullanılırsa sistem Zamanlama Saldırılarına açıktır, hash_equals() bunun önüne geçer. 
        if (
            !isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            header("Location: index.php?route=admin&error=" . urlencode("Geçersiz güvenlik tokeni. Lütfen tekrar deneyin."));
            exit();
        }
        //Yemek ekleme kısmı
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_meal') {
            $categoryId = $_POST['category_id'];
            $mealName = trim($_POST['meal_name']);
            $price = floatval($_POST['price']);

            if (!empty($mealName) && $price > 0) {
                $this->adminModel->addMeal($categoryId, $mealName, $price);
                header("Location: index.php?route=admin&success=" . urlencode("Yemek başarıyla eklendi."));
                exit();
            }
        }
        //Müşteri ekleme kısmı
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_customer') {
            $fullName = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            if (strlen($password) < 8) {
                $_SESSION['hata_mesaji'] = "Şifre en az 8 karakter olmalıdır.";
                header("Location: index.php");
                exit();
            }

            if (!empty($fullName) && !empty($email) && !empty($password)) {
                $existingUser = $this->userModel->getUserByEmail($email);

                if ($existingUser) {
                    header("Location: index.php?route=admin&error=" . urlencode("Bu e-posta adresi zaten kullanılıyor."));
                    exit();
                } else {
                    $this->userModel->createUser($fullName, $email, $password);
                    header("Location: index.php?route=admin&success=" . urlencode("Yeni müşteri başarıyla eklendi."));
                    exit();
                }
            }
        }

        //Yemek silme kısmı 

        // POST olarak düzenlendi
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_meal') {
            $deleteId = intval($_POST['delete_id']);
            $this->adminModel->deleteMeal($deleteId);
            header("Location: index.php?route=admin&success=" . urlencode("Yemek başarıyla silindi."));
            exit();
        }

        //Müşteri silme kısmı

        // POST olarak düzenlendi
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_customer') {
            $customerId = intval($_POST['delete_customer_id']);
            $result = $this->adminModel->deleteCustomer($customerId);

            if ($result == true) {
                header("Location: index.php?route=admin&success=" . urlencode("Müşteri ve sipariş geçmişi silindi."));
            } else {
                header("Location: index.php?route=admin&error=" . urlencode("Silme hatası: " . $result));
            }
            exit();
        }

        // Sipariş iptal kısmı 
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'cancel_order') {

            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                header("Location: index.php?route=admin&error=" . urlencode("Güvenlik doğrulaması başarısız oldu."));
                exit();
            }
            $orderId = isset($_POST['cancel_order_id']) ? intval($_POST['cancel_order_id']) : 0;

            if ($orderId > 0) {
                $isCancelled = $this->adminModel->cancelOrder($orderId);

                if ($isCancelled) {
                    header("Location: index.php?route=admin&success=" . urlencode("#{$orderId} numaralı sipariş başarıyla iptal edildi."));
                    exit();
                } else {
                    header("Location: index.php?route=admin&error=" . urlencode("Sipariş iptal edilirken sistemsel bir hata oluştu."));
                    exit();
                }
            } else {
                header("Location: index.php?route=admin&error=" . urlencode("Geçersiz sipariş numarası."));
                exit();
            }
        }
    }
}
