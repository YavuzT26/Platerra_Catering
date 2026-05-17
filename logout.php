<?php
// 1. Mevcut oturumu yakala
session_start();

// 2. Oturumun içindeki tüm verileri (user_id, user_name vb.) temizle
session_unset();

// 3. Oturumu tamamen yok et
session_destroy();

// 4. Kullanıcıyı temiz bir şekilde anasayfaya geri fırlat
header("Location: index.php");
exit();
