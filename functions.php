<?php
include "connect.php";

function menuyuGetir($category_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT meal_name FROM meals WHERE category_id =:category_id");
    $stmt->execute([":category_id" => $category_id]);

    $yemekAdi = $stmt->fetchAll();

    foreach ($yemekAdi as $yAdi) {
        $yemekAdi = htmlspecialchars($yAdi['meal_name']);
        echo "<li onclick=\"addToCart('$yemekAdi')\">$yemekAdi</li>\n";
    }
};

function dailyMenu($tarih)
{
    global $pdo;
    $sql = "SELECT 
                dm.menu_date,
                s.meal_name AS corba,
                mc.meal_name AS ana_yemek,
                oo.meal_name AS zeytinyagli,
                a.meal_name AS meze,
                des.meal_name AS tatli,
                dr.meal_name AS icecek
            FROM dailymenu dm
            LEFT JOIN meals s ON dm.soup_id = s.meal_id
            LEFT JOIN meals mc ON dm.main_course_id = mc.meal_id
            LEFT JOIN meals oo ON dm.olive_oil_id = oo.meal_id
            LEFT JOIN meals a ON dm.appetizer_id = a.meal_id
            LEFT JOIN meals des ON dm.dessert_id = des.meal_id
            LEFT JOIN meals dr ON dm.drink_id = dr.meal_id
            WHERE dm.menu_date= :bugun LIMIT 1
            ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['bugun' => $tarih]);
    $dailyMenu = $stmt->fetch();

    if ($dailyMenu) {
        $kategoriler = [
            '🍲 Çorba' => 'corba',
            '🍆 Ana Yemek' => 'ana_yemek',
            '🥗 Zeytinyağlı' => 'zeytinyagli',
            '🧆 Meze' => 'meze',
            '🍮 Tatlı' => 'tatli',
            '🥤 İçecek' => 'icecek'
        ];
        foreach ($kategoriler as $etiket => $kolonAdi) {
            $yemekAdi = htmlspecialchars($dailyMenu[$kolonAdi] ?? 'Yok');
            echo "<div class=\"premium-item\">\n";
            echo "    <span>$etiket</span>\n";
            echo "    <h3>$yemekAdi</h3>\n";
            echo "</div>";
        }
    } else {

        echo "<div class=\"premium-item\" style=\"grid-column: span 2; text-align: center;\">\n";
        echo "<h3>Bugün için şefin özel menüsü bulunmamaktadır.</h3>\n";
        echo "</div>\n";
    }
};
