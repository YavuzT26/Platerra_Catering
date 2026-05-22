<?php

require_once 'config/Database.php';
require_once 'models/MealModel.php';

class HomeController
{
    public function index()
    {
        // Veritabanı bağlantısını başlatma kısmı 
        $database = new Database();
        $db = $database->getConnection();

        // Modelimizi başlatıyoruz
        $mealModel = new MealModel($db);

        // Arayüzde kullanacağımız verileri modelden çekiyoruz

        $today = date('Y-m-d');

        $dailyMenu = $mealModel->getDailyMenu($today);
        $dailyMenuPrice = $mealModel->getDailyMenuPrice($today);


        // menuyuGetir() fonksiyonu yerine burada değişkenlere atadık
        $corbalar = $mealModel->getMealsByCategory(1);
        $anaYemekler = $mealModel->getMealsByCategory(2);
        $zeytinyaglilar = $mealModel->getMealsByCategory(3);
        $mezeler = $mealModel->getMealsByCategory(4);
        $tatlilar = $mealModel->getMealsByCategory(5);
        $icecekler = $mealModel->getMealsByCategory(6);

        // Çektiğimiz verileri require_once ile home.php de kullanılabilir hale getirdik 
        require_once 'views/home.php';
    }
}
