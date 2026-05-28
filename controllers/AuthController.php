<?php

require_once 'config/Database.php';
require_once 'models/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->userModel = new UserModel($db);
    }

    //Kayıt Ol kısmımız
    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fullname = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $existingUser = $this->userModel->getUserByEmail($email);

            if ($existingUser) {
                $_SESSION['hata_mesaji'] = "Girdiğiniz bilgiler hatalıdır.";
            } else {
                $this->userModel->CreateUser($fullname, $email, $password);
                $_SESSION['basari_mesaji'] = "Hesabınız başarıyla oluşturuldu.";
            }

            header("Location: index.php");
            exit();
        }
    }

    public function login()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $_SESSION['basari_mesaji'] = "Hoşgeldin " . $user['full_name'];

                if ($user['is_admin'] == 1) {
                    header("Location: index.php?route=admin");
                    exit();
                }
            } else {
                $_SESSION['hata_mesaji'] = "Hatalı e-posta veya şifre girdiniz!";
            }
            header("Location: index.php");
            exit();
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
