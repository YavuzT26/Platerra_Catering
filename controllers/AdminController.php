<?php

require_once 'config/Database.php';
require_once 'models/AdminModel.php';
require_once 'models/MealModel.php';
require_once 'models/UserModel.php';

class AdminController
{

    private $adminModel;
    private $mealModel;
    private $userModel;


    public function __construct()
    {
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            header("Location: index.php");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();
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

            if (!empty($fullName) && !empty($email) && !empty($password)) {
                $existingUser = $this->userModel->getUserByEmail($email);

                if ($existingUser) {
                    header("Location: index.php?route=admin&error=" . urlencode("Bu e-posta adresi zaten kullanılıyor."));
                    exit();
                } else {
                    $this->userModel->CreateUser($fullName, $email, $password);
                    header("Location: index.php?route=admin&success=" . urlencode("Yeni müşteri başarıyla eklendi."));
                    exit();
                }
            }
        }

        //Yemek silme kısmı
        if (isset($_GET['delete_id'])) {
            $deleteId = intval($_GET['delete_id']);
            $this->adminModel->deleteMeal($deleteId);
            header("Location: index.php?route=admin&&success=" . urlencode("Yemek başarıyla silindi."));
            exit();
        }

        //Müşteri silme kısmı
        if (isset($_GET['delete_customer_id'])) {
            $customerId = intval($_GET['delete_customer_id']);
            $result = $this->adminModel->deleteCustomer($customerId);

            if ($result == true) {
                header("Location: index.php?route=admin&success=" . urlencode("Müşteri ve sipariş geçmişi silindi."));
            } else {
                header("Location: index.php?route=admin&error=" . urlencode("Silme hatası: " . $result));
            }
            exit();
        }
    }
}
