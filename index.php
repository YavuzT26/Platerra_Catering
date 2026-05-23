<?php
// index.php (Ana dizindeki Front Controller / Yönlendirici)

// 1. Tüm sistemde geçerli olacak oturumu (Session) burada başlatıyoruz
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/** 
 * CSRF token üretimini eklemiş olduk,
 *bin2hex(random_bytes(32)) methodu ile 64 karakterlik tahmin edilemez bir token oluşturduk
 */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// Controller dosyalarını sisteme dahil ettiğimiz kısım
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
        $controller->index();
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
        $controller = new OrderController();
        $controller->checkout();
        break;

    case 'admin':
        $controller = new AdminController();
        $controller->index();
        break;
    case 'admin_action':
        $controller = new AdminController();
        $controller->handleAction();
        break;
    default:
        // Tanımlanmamış bir rota gelirse varsayılan olarak anasayfayı aç
        $controller = new HomeController();
        $controller->index();
        break;
}
