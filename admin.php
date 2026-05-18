<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Güvenlik Kontrolü: Giriş yapılmamışsa veya kullanıcı admin değilse index.php'ye yönlendir
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit();
}

require_once 'connect.php';

// --- BACKEND İŞLEMLERİ (CRUD) ---

// 1. Yemek Ekleme İşlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_meal') {
    $category_id = $_POST['category_id'];
    $meal_name = trim($_POST['meal_name']);
    $price = floatval($_POST['price']);

    if (!empty($meal_name) && $price > 0) {
        $stmt = $pdo->prepare("INSERT INTO meals (category_id, meal_name, price) VALUES (:cat, :name, :price)");
        $stmt->execute([
            ':cat' => $category_id,
            ':name' => $meal_name,
            ':price' => $price
        ]);
        header("Location: admin.php?success=Yemek başarıyla eklendi");
        exit();
    }
}

// 2. Yemek Silme İşlemi
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM meals WHERE meal_id = :id");
    $stmt->execute([':id' => $delete_id]);
    header("Location: admin.php?success=Yemek başarıyla silindi");
    exit();
}

// --- İSTATİSTİK VERİLERİNİ ÇEKME (Dashboard) ---
$total_revenue = $pdo->query("SELECT SUM(total_price) FROM orders")->fetchColumn() ?? 0;
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_meals = $pdo->query("SELECT COUNT(*) FROM meals")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// Form için kategorileri çekiyoruz
$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();

// Yemekleri listelemek için çekiyoruz
$meals = $pdo->query("SELECT m.*, c.category_name FROM meals m JOIN categories c ON m.category_id = c.category_id ORDER BY m.meal_id DESC")->fetchAll();

// Siparişleri veren kullanıcıların ad ve e-posta bilgileriyle birlikte çekiyoruz (JOIN)
$orders = $pdo->query("SELECT o.*, u.full_name, u.email FROM orders o JOIN users u ON o.user_id = u.user_id ORDER BY o.order_date DESC")->fetchAll();

// Sadece normal müşterileri (admin olmayanları) listelemek için çekiyoruz
$customers = $pdo->query("SELECT * FROM users WHERE is_admin = 0 ORDER BY user_id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLATERRA - Yönetim Paneli</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: white;
            padding-bottom: 60px;
        }

        /* Navbar */
        .admin-nav {
            background: #020617;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .logo {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 2px;
            font-style: italic;
            color: white;
            text-decoration: none;
        }

        /* Nav Buton Grubu Ayarları */
        .nav-right {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-right a {
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Müşteri Ekranı Buton Stili (Altın Sarısı Transparan) */
        .btn-view-site {
            color: #d6b98c;
            background: rgba(214, 185, 140, 0.1);
            border: 1px solid rgba(214, 185, 140, 0.2);
        }

        .btn-view-site:hover {
            background: #d6b98c;
            color: #020617;
        }

        /* Güvenli Çıkış Buton Stili (Kırmızı Transparan) */
        .btn-logout {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-logout:hover {
            background: #ef4444;
            color: white;
        }

        .container {
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1,
        h2 {
            font-family: 'Playfair Display', serif;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        h1 {
            color: #d6b98c;
            border-bottom: 1px solid rgba(214, 185, 140, 0.2);
            padding-bottom: 15px;
        }

        /* Bildirim Mesajı */
        .alert {
            background: rgba(214, 185, 140, 0.15);
            border: 1px solid #d6b98c;
            color: #d6b98c;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 30px;
            font-weight: 500;
        }

        /* İstatistik Kartları */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #020617;
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px 25px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: 0.4s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(214, 185, 140, 0.05);
            border-color: rgba(214, 185, 140, 0.2);
        }

        .stat-icon {
            font-size: 35px;
            color: #d6b98c;
            background: rgba(214, 185, 140, 0.1);
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-info span {
            display: block;
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-info h3 {
            font-size: 28px;
            font-weight: 700;
            color: white;
        }

        /* Grid Yapıları */
        .management-grid,
        .two-column-grid {
            display: grid;
            gap: 30px;
            align-items: start;
        }

        .two-column-grid {
            grid-template-columns: 1fr 1fr;
            margin-bottom: 40px;
        }

        .management-grid {
            grid-template-columns: 1fr 2fr;
        }

        @media(max-width: 1000px) {

            .management-grid,
            .two-column-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Form Alanı */
        .card {
            background: #020617;
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 35px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #d1d5db;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            color: white;
            font-family: inherit;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #d6b98c;
            background: rgba(255, 255, 255, 0.08);
        }

        /* Kategori Seçim Alanı Koyu Tema Uyumu */
        .form-group select option {
            background: #020617;
            color: white;
            padding: 10px;
        }

        .btn {
            width: 100%;
            background: #d6b98c;
            color: #020617;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(214, 185, 140, 0.2);
        }

        .btn:hover {
            background: white;
            transform: translateY(-2px);
        }

        /* Tablo Yapısı */
        .table-wrapper {
            background: #020617;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 35px;
            padding: 25px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 15px;
        }

        th,
        td {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        th {
            color: #d6b98c;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .btn-delete {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: 0.3s;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
        }
    </style>
</head>

<body>

    <nav class="admin-nav">
        <a href="index.php" class="logo">PLATERRA <span style="font-size:14px; font-weight:300; color:#d6b98c;">Admin Panel</span></a>
        <div class="nav-right">
            <a href="index.php" class="btn-view-site"><i class="fa-solid fa-eye"></i> Müşteri Ekranı</a>
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-sign-out-alt"></i> Güvenli Çıkış</a>
        </div>
    </nav>

    <div class="container">
        <h1>Sistem Kontrol & Firma Durum Paneli</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert">
                <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
                <div class="stat-info">
                    <span>Toplam Ciro</span>
                    <h3><?php echo number_format($total_revenue, 2, ',', '.'); ?> ₺</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-utensils"></i></div>
                <div class="stat-info">
                    <span>Yemek Çeşidi</span>
                    <h3><?php echo $total_meals; ?> Adet</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-list"></i></div>
                <div class="stat-info">
                    <span>Aktif Kategori</span>
                    <h3><?php echo $total_categories; ?> Grubu</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-truck-ramp-box"></i></div>
                <div class="stat-info">
                    <span>Gelen Sipariş</span>
                    <h3><?php echo $total_orders; ?> Sipariş</h3>
                </div>
            </div>
        </div>

        <div class="two-column-grid">

            <div class="table-wrapper">
                <h2><i class="fa-solid fa-receipt" style="color:#d6b98c; margin-right:10px;"></i> Son Siparişler</h2>
                <table style="margin-top:20px;">
                    <thead>
                        <tr>
                            <th>Sipariş ID</th>
                            <th>Müşteri Bilgisi</th>
                            <th>Tarih</th>
                            <th>Toplam Tutar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($orders) > 0): ?>
                            <?php foreach ($orders as $ord): ?>
                                <tr>
                                    <td style="color:#9ca3af;">#<?php echo $ord['order_id']; ?></td>
                                    <td>
                                        <div style="font-weight: 500; color:white;"><?php echo htmlspecialchars($ord['full_name']); ?></div>
                                        <div style="font-size: 12px; color: #9ca3af;"><?php echo htmlspecialchars($ord['email']); ?></div>
                                    </td>
                                    <td style="font-size: 14px; color: #d1d5db;">
                                        <?php echo date('d.m.Y H:i', strtotime($ord['order_date'])); ?>
                                    </td>
                                    <td style="font-weight:600; color: #d6b98c;">
                                        <?php echo number_format($ord['total_price'], 2, ',', '.'); ?> ₺
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align:center; color:#9ca3af; padding: 25px;">Henüz verilmiş bir sipariş bulunmuyor.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-wrapper">
                <h2><i class="fa-solid fa-users" style="color:#d6b98c; margin-right:10px;"></i> Kayıtlı Müşteriler</h2>
                <table style="margin-top:20px;">
                    <thead>
                        <tr>
                            <th>Müşteri ID</th>
                            <th>Ad Soyad</th>
                            <th>E-posta Adresi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($customers) > 0): ?>
                            <?php foreach ($customers as $cust): ?>
                                <tr>
                                    <td style="color:#9ca3af;">#<?php echo $cust['user_id']; ?></td>
                                    <td style="font-weight: 500; color:white;"><?php echo htmlspecialchars($cust['full_name']); ?></td>
                                    <td style="color: #d1d5db;"><?php echo htmlspecialchars($cust['email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align:center; color:#9ca3af; padding: 25px;">Sistemde kayıtlı müşteri bulunmuyor.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

        <div class="management-grid">
            <div class="card">
                <h2>Yeni Yemek Ekle</h2>
                <form action="admin.php" method="POST" style="margin-top:20px;">
                    <input type="hidden" name="action" value="add_meal">

                    <div class="form-group">
                        <label for="meal_name">Yemek Adı</label>
                        <input type="text" id="meal_name" name="meal_name" required placeholder="Örn: Kremalı Mantar Çorbası">
                    </div>

                    <div class="form-group">
                        <label for="category_id">Kategori Seçimi</label>
                        <select id="category_id" name="category_id" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['category_id']; ?>">
                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Birim Fiyatı (₺)</label>
                        <input type="number" id="price" name="price" step="0.01" required placeholder="Örn: 135.00">
                    </div>

                    <button type="submit" class="btn"><i class="fa-solid fa-plus"></i> Menüye Dahil Et</button>
                </form>
            </div>

            <div class="table-wrapper">
                <h2>Menü İçerik Listesi</h2>
                <table style="margin-top:20px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Yemek Adı</th>
                            <th>Kategori</th>
                            <th>Fiyat</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($meals as $meal): ?>
                            <tr>
                                <td style="color:#9ca3af;">#<?php echo $meal['meal_id']; ?></td>
                                <td style="font-weight: 500; color:white;"><?php echo htmlspecialchars($meal['meal_name']); ?></td>
                                <td><span style="background: rgba(214,185,140,0.1); color:#d6b98c; padding:4px 10px; border-radius:6px; font-size:13px;"><?php echo htmlspecialchars($meal['category_name']); ?></span></td>
                                <td style="font-weight:600;"><?php echo round($meal['price']); ?> ₺</td>
                                <td>
                                    <a href="admin.php?delete_id=<?php echo $meal['meal_id']; ?>" class="btn-delete" onclick="return confirm('Bu yemeği menüden kaldırmak istediğinize emin misiniz?');">
                                        <i class="fa-solid fa-trash-can"></i> Sil
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

</html>