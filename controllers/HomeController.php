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

        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        if (!$mealModel->checkMenuExists($tomorrow)) {
            $this->generateTomorrowMenu($mealModel, $tomorrow);
        }
        // Arayüzde kullanacağımız verileri modelden çekiyoruz
        $today = date('Y-m-d');

        $dailyMenu = $mealModel->getDailyMenu($today);
        $dailyMenuPrice = $mealModel->getDailyMenuPrice($today);


        /* 
            Tekrar eden şekilde yazmak yerine foreach kullandık.

        $corbalar = $mealModel->getMealsByCategory(1);
        $anaYemekler = $mealModel->getMealsByCategory(2);
        $zeytinyaglilar = $mealModel->getMealsByCategory(3);
        $mezeler = $mealModel->getMealsByCategory(4);
        $tatlilar = $mealModel->getMealsByCategory(5);
        $icecekler = $mealModel->getMealsByCategory(6);
        
        */

        $data = [];
        $yemekId = [
            1 => 'corbalar',
            2 => 'anaYemekler',
            3 => 'zeytinyaglilar',
            4 => 'mezeler',
            5 => 'tatlilar',
            6 => 'icecekler'
        ];
        foreach ($yemekId as $categoryId => $columnName) {
            $data[$columnName] = $mealModel->getMealsByCategory($categoryId);
        }


        // Çektiğimiz verileri require_once ile home.php de kullanılabilir hale getirdik 
        require_once 'views/home.php';
    }
    private function generateTomorrowMenu(MealModel $mealModel, string $tomorrow)
    {


        $categories = [
            1 => 'soup_id',
            2 => 'main_course_id',
            3 => 'olive_oil_id',
            4 => 'appetizer_id',
            5 => 'dessert_id',
            6 => 'drink_id'
        ];

        $menuData = [':menu_date' => $tomorrow];

        $safeDays = (int)$tomorrow;

        foreach ($categories as $categoryId => $columnName) {
            $meal = $mealModel->getRandomMenu($categoryId, $columnName, $tomorrow, $safeDays);

            //Seçim Yapamadığı Senaryoda Kilitlenmemesi İçin
            if (!$meal) {
                $meal = $mealModel->getFallbackRandomMeal($categoryId);
            }

            $menuData[":$columnName"] = $meal ? $meal['meal_id'] : null;
        }

        if ($mealModel->insertDailyMenu($menuData)) {
            error_log("Platerra Catering: $tomorrow tarihi için menü kaydı başarıyla oluşturuldu.");
        } else {
            error_log("Platerra Catering: $tomorrow tarihi için menü kaydı sırasında hata meydana geldi.");
        }
    }
}
