<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'connect.php';

// Kontrol; POST isteği var mı?
// Kayıt ol, Veritabanına ekleme  
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type'])) {
    //
    if ($_POST['form_type'] == 'register') {
        $fullName = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        try {
            $kontrolStmt = $pdo->prepare("SELECT user_id FROM users WHERE email= :email");
            $kontrolStmt->execute([
                ":email" => $email
            ]);
            if ($kontrolStmt->rowCount() > 0) {
                echo "<script> alert('Girdiğiniz bilgiler hatalıdır!');</script>";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (full_name,email,password) VALUES (:name,:email,:password)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ":name" => $fullName,
                    ":email" => $email,
                    ":password" => $hashedPassword
                ]);
                echo "<script>alert('Hesabınız başarıyla oluşturuldu!');</script>";
            }
        } catch (\PDOException $e) {
            echo "<script> alert('Kayıt Hatası: " . addslashes($e->getMessage()) . "');</script>";
        }
        header("Location: index.php");
        exit();
    }

    // Login İşlemleri

    if ($_POST["form_type"] == 'login') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute([
            ":email" => $email
        ]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['basari_mesaji'] = "Hoşgeldin " . $user['full_name'];
        } else {
            $_SESSION['hata_mesaji'] = "Hatalı e-posta veya şifre girdiniz!";
        }

        header("Location: index.php");
        exit();
    }
}
if (isset($_SESSION['basari_mesaji'])) {
    echo "<script>alert('" . $_SESSION['basari_mesaji'] . "');</script>";
    unset($_SESSION['basari_mesaji']);
}

// Eğer hatalı bir işlem varsa mesajı ver ve session'dan sil
if (isset($_SESSION['hata_mesaji'])) {
    echo "<script>alert('" . $_SESSION['hata_mesaji'] . "');</script>";
    unset($_SESSION['hata_mesaji']);
}
