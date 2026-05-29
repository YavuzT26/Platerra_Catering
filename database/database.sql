-- Host: 127.0.0.1    Database: catering_db

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `categories` WRITE;
INSERT INTO `categories` VALUES (2,'Ana Yemek'),(1,'Çorba'),(6,'İçecek'),(4,'Meze'),(5,'Tatlı'),(3,'Zeytinyağlı');
UNLOCK TABLES;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `meals`;
CREATE TABLE `meals` (
  `meal_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `meal_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`meal_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `meals_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `meals` WRITE;
INSERT INTO `meals` VALUES (1,1,'Mercimek Çorbası',120.00),(2,1,'Ezogelin Çorbası',125.00),(3,1,'Domates Çorbası',130.00),(4,1,'Tavuk Suyu Çorbası',140.00),(5,1,'Yayla Çorbası',120.00),(6,2,'Karnıyarık',250.00),(7,2,'Nohut Yemeği',200.00),(8,2,'Kabak Dolması',230.00),(9,2,'Sebzeli Türlü',220.00),(10,2,'Ispanak Yemeği',210.00),(11,2,'Fırın Makarna',190.00),(12,2,'Pırasa Yemeği',200.00),(13,2,'Kuru Fasulye',210.00),(14,3,'Yaprak Sarma',180.00),(15,3,'Barbunya',170.00),(16,3,'Taze Fasulye',165.00),(17,3,'Enginar',190.00),(18,4,'Haydari',90.00),(19,4,'Patlıcan Salatası',100.00),(20,4,'Rus Salatası',95.00),(21,4,'Yoğurtlu Semizotu',90.00),(22,5,'Sütlaç',110.00),(23,5,'Kemalpaşa Tatlısı',120.00),(24,5,'Kabak Tatlısı',115.00),(25,5,'Fırın Sütlaç',125.00),(26,5,'Aşure',130.00),(27,5,'Muhallebi',100.00),(28,5,'Kazandibi',135.00),(29,5,'İrmik Helvası',120.00),(30,6,'Kola',50.00),(31,6,'Ayran',40.00),(32,6,'Limonata',60.00),(33,6,'Soğuk Çay',55.00),(34,6,'Meyve Suyu',65.00);
/* Yeni Eklenen Yemekler*/
INSERT INTO `meals` (`category_id`, `meal_name`, `price`) VALUES
-- Kategori 1: Çorbalar
(1, 'Kremalı Mantar Çorbası', 135.00),
(1, 'Düğün Çorbası', 145.00),
(1, 'Balkabağı Çorbası', 130.00),

-- Kategori 2: Ana Yemekler (Premium Dokunuşlar)
(2, 'Hünkar Beğendi', 320.00),
(2, 'Fırında Kuzu İncik', 380.00),
(2, 'Izgara Somon', 350.00),
(2, 'Piliç Topkapı', 260.00),
(2, 'Dana Rosto', 310.00),
(2, 'Et Çökertme Kebabı', 330.00),

-- Kategori 3: Zeytinyağlılar
(3, 'İmambayıldı', 185.00),
(3, 'Kereviz', 175.00),
(3, 'Bamya', 195.00),

-- Kategori 4: Mezeler
(4, 'Humus', 95.00),
(4, 'Muhammara (Cevizli Biber)', 105.00),
(4, 'Fava', 90.00),
(4, 'Girit Ezmesi', 110.00),
(4, 'Babagannuş', 100.00),

-- Kategori 5: Tatlılar
(5, 'Tiramisu', 150.00),
(5, 'Profiterol', 140.00),
(5, 'Trileçe', 130.00),
(5, 'Ayva Tatlısı', 125.00),
(5, 'Creme Brulee', 160.00),

-- Kategori 6: İçecekler
(6, 'Reyhan Şerbeti', 65.00),
(6, 'Naneli Limonata', 65.00);
UNLOCK TABLES;


DROP TABLE IF EXISTS `dailymenu`;
CREATE TABLE `dailymenu` (
  `menu_id` int NOT NULL AUTO_INCREMENT,
  `menu_date` date NOT NULL,
  `soup_id` int DEFAULT NULL,
  `main_course_id` int DEFAULT NULL,
  `olive_oil_id` int DEFAULT NULL,
  `appetizer_id` int DEFAULT NULL,
  `dessert_id` int DEFAULT NULL,
  `drink_id` int DEFAULT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `soup_id` (`soup_id`),
  KEY `main_course_id` (`main_course_id`),
  KEY `olive_oil_id` (`olive_oil_id`),
  KEY `appetizer_id` (`appetizer_id`),
  KEY `dessert_id` (`dessert_id`),
  KEY `drink_id` (`drink_id`),
  CONSTRAINT `dailymenu_ibfk_1` FOREIGN KEY (`soup_id`) REFERENCES `meals` (`meal_id`),
  CONSTRAINT `dailymenu_ibfk_2` FOREIGN KEY (`main_course_id`) REFERENCES `meals` (`meal_id`),
  CONSTRAINT `dailymenu_ibfk_3` FOREIGN KEY (`olive_oil_id`) REFERENCES `meals` (`meal_id`),
  CONSTRAINT `dailymenu_ibfk_4` FOREIGN KEY (`appetizer_id`) REFERENCES `meals` (`meal_id`),
  CONSTRAINT `dailymenu_ibfk_5` FOREIGN KEY (`dessert_id`) REFERENCES `meals` (`meal_id`),
  CONSTRAINT `dailymenu_ibfk_6` FOREIGN KEY (`drink_id`) REFERENCES `meals` (`meal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `dailymenu` WRITE;
INSERT INTO `dailymenu` VALUES (27,'2026-05-16',1,6,14,18,26,32),(34,'2026-05-17',2,7,15,19,27,31),(35,'2026-05-18',3,8,16,20,28,33),(36,'2026-05-19',4,9,17,21,29,34),(37,'2026-05-20',5,10,14,18,30,34),(38,'2026-05-21',1,11,15,19,23,32),(39,'2026-05-22',2,12,16,20,24,31);
UNLOCK TABLES;



DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;






-- Kullanıcılara admin yetki alanı ekleme
ALTER TABLE `users` ADD COLUMN `is_admin` TINYINT(1) NOT NULL DEFAULT 0;

-- Sipariş detayları için tablomuz
CREATE TABLE IF NOT EXISTS `order_items` (
  `item_id`   INT            NOT NULL AUTO_INCREMENT,
  `order_id`  INT            NOT NULL,
  `meal_id`   INT                NULL DEFAULT NULL,
  `meal_name` VARCHAR(255)   NOT NULL,
  `price`     DECIMAL(10,2)  NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `order_id` (`order_id`),
  KEY `meal_id`  (`meal_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders`  (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`meal_id`)  REFERENCES `meals`   (`meal_id`)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 

