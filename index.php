<?php
// index.php (Ana dizindeki Front Controller / Yönlendirici)

// 1. Tüm sistemde geçerli olacak oturumu (Session) burada başlatıyoruz
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. HomeController dosyasını sisteme dahil ediyoruz
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/OrderController.php';
require_once 'controllers/AdminController.php';

$route = 'home';
if (isset($_GET['route'])) {
    $route = $_GET['route'];
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type'])) {
    $route = $_POST['form_type'];
}

switch ($route) {
    case 'home':
        $controller = new HomeController();
        $controller->index(); // HomeController içindeki index() metodunu tetikler
        break;

    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;

    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'checkout':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->checkout();
        break;

    case 'admin':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;
    case 'admin_action':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->handleAction();
        break;
    default:
        // Tanımlanmamış bir rota gelirse varsayılan olarak anasayfayı aç
        $controller = new HomeController();
        $controller->index();
        break;
}
