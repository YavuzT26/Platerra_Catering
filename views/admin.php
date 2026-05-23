<?php

/** 

 * @var array $stats
 * @var array $orders
 * @var array $customers
 * @var array $meals
 * @var array $categories
 */

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

        .btn-view-site {
            color: #d6b98c;
            background: rgba(214, 185, 140, 0.1);
            border: 1px solid rgba(214, 185, 140, 0.2);
        }

        .btn-view-site:hover {
            background: #d6b98c;
            color: #020617;
        }

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
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        h1 {
            color: #d6b98c;
            border-bottom: 1px solid rgba(214, 185, 140, 0.2);
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .section-separator {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            margin: 40px 0;
        }

        .alert {
            background: rgba(214, 185, 140, 0.15);
            border: 1px solid #d6b98c;
            color: #d6b98c;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
            color: #ef4444;
        }

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

        .management-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            align-items: start;
        }

        @media(max-width: 1100px) {
            .management-grid {
                grid-template-columns: 1fr;
            }
        }

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
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
        }

        .search-container {
            position: relative;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .search-container i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #d6b98c;
            font-size: 16px;
        }

        .search-container input {
            padding-left: 45px;
        }
    </style>
</head>

<body>

    <nav class="admin-nav">
        <a href="index.php" class="logo">PLATERRA <span style="font-size:14px; font-weight:300; color:#d6b98c;">Admin Panel</span></a>
        <div class="nav-right">
            <a href="index.php" class="btn-view-site"><i class="fa-solid fa-eye"></i> Müşteri Ekranı</a>
            <a href="index.php?route=logout" class="btn-logout"><i class="fa-solid fa-sign-out-alt"></i> Güvenli Çıkış</a>
        </div>
    </nav>

    <div class="container">
        <h1>Sistem Kontrol & Firma Durum Paneli</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
                <div class="stat-info"><span>Toplam Ciro</span>
                    <h3><?php echo number_format($stats['total_revenue'], 2, ',', '.'); ?> ₺</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-utensils"></i></div>
                <div class="stat-info"><span>Çeşit</span>
                    <h3><?php echo $stats['total_meals']; ?> Adet</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-list"></i></div>
                <div class="stat-info"><span>Aktif Kategori</span>
                    <h3><?php echo $stats['total_categories']; ?> Kategori</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-truck-ramp-box"></i></div>
                <div class="stat-info"><span>Gelen Sipariş</span>
                    <h3><?php echo $stats['total_orders']; ?> Sipariş</h3>
                </div>
            </div>
        </div>

        <div class="table-wrapper" style="margin-bottom: 40px;">
            <h2><i class="fa-solid fa-receipt" style="color:#d6b98c; margin-right:10px;"></i> Son Alınan Siparişler</h2>
            <table style="margin-top:20px;">
                <thead>
                    <tr>
                        <th>Sipariş ID</th>
                        <th>Müşteri Bilgisi</th>
                        <th>Sipariş Tarihi</th>
                        <th>Sipariş İçeriği</th> <!--Yeni Sütun-->
                        <th>Toplam Ödenen Tutar</th>
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
                                <td style="font-size: 14px; color: #d1d5db;"><?php echo date('d.m.Y H:i', strtotime($ord['order_date'])); ?></td>

                                <!--Sipariş içeriği-->
                                <td style="font-size:13px; color:#d1d5db; max-width:260px;">
                                    <?php if (!empty($ord['item_names'])): ?>
                                        <span title="<?php echo htmlspecialchars($ord['item_names']); ?>">
                                            <?php echo htmlspecialchars($ord['item_names']); ?>
                                        </span>
                                        <div style="margin-top:4px; font-size:11px; color:#6b7280;">
                                            <?php echo $ord['item_count']; ?> kalem
                                        </div>
                                    <?php else: ?>
                                        <span style="color:#6b7280; font-style:italic;">Detay yok</span>
                                    <?php endif; ?>
                                </td>
                                <!--Sipariş içeriği-->

                                <td style="font-weight:600; color: #d6b98c;"><?php echo number_format($ord['total_price'], 2, ',', '.'); ?> ₺</td>
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

        <div class="section-separator"></div>

        <div class="management-grid">
            <div class="card">
                <h2><i class="fa-solid fa-user-plus" style="color:#d6b98c; margin-right:10px;"></i> Yeni Müşteri Kaydet</h2>


                <form action="index.php?route=admin_action" method="POST" style="margin-top:20px;">

                    <input type="hidden" name="action" value="add_customer">
                    <!--
                        Token kontrolü eklendi.    
                    -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class=" form-group">
                        <label for="full_name">Ad Soyad</label>
                        <input type="text" id="full_name" name="full_name" required placeholder="Müşterinin Adı ve Soyadı">
                    </div>

                    <div class="form-group">
                        <label for="email">E-posta Adresi</label>
                        <input type="email" id="email" name="email" required placeholder="ornek@mail.com">
                    </div>

                    <div class="form-group">
                        <label for="password">Hesap Şifresi</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn"><i class="fa-solid fa-check"></i> Hesabı Oluştur</button>
                </form>


            </div>

            <div class="table-wrapper">
                <h2><i class="fa-solid fa-users" style="color:#d6b98c; margin-right:10px;"></i> Sistemdeki Kayıtlı Müşteriler</h2>
                <table style="margin-top:20px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad Soyad</th>
                            <th>E-posta Adresi</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($customers) > 0): ?>
                            <?php foreach ($customers as $cust): ?>
                                <tr>
                                    <td style="color:#9ca3af;">#<?php echo $cust['user_id']; ?></td>
                                    <td style="font-weight: 500; color:white;"><?php echo htmlspecialchars($cust['full_name']); ?></td>
                                    <td style="color: #d1d5db;"><?php echo htmlspecialchars($cust['email']); ?></td>


                                    <td>
                                        <!-- 
                                            <a href="index.php?route=admin_action&delete_customer_id=X">

                                            Link yerine form ekleyip POST ile Token kontrolü sağladık
                                        -->
                                        <form method="POST" action="index.php?route=admin_action"
                                            onsubmit="return confirm('Bu müşteriyi silmek istediğinizden emin misiniz?');"
                                            style="display:inline;">

                                            <input type="hidden" name="action" value="delete_customer">
                                            <input type="hidden" name="delete_customer_id" value="<?php echo $cust['user_id']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <button type="submit" class="btn-delete">
                                                <i class="fa-solid fa-user-minus">
                                                </i> Kaldır
                                            </button>

                                        </form>
                                    </td>


                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align:center; color:#9ca3af; padding: 25px;">Kayıtlı müşteri bulunmuyor.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section-separator"></div>

        <div class="management-grid">
            <div class="card">
                <h2><i class="fa-solid fa-plus" style="color:#d6b98c; margin-right:10px;"></i> Yeni Yemek Ekle</h2>
                <form action="index.php?route=admin_action" method="POST" style="margin-top:20px;">
                    <input type="hidden" name="action" value="add_meal">
                    <!-- Token kontrolü ekledik-->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="form-group">
                        <label for="meal_name">Yemek Adı</label>
                        <input type="text" id="meal_name" name="meal_name" required placeholder="Örn: Kremalı Mantar Çorbası">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori Seçimi</label>
                        <select id="category_id" name="category_id" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Birim Fiyatı (₺)</label>
                        <input type="number" id="price" name="price" step="0.01" required placeholder="Örn: 135.00">
                    </div>
                    <button type="submit" class="btn"><i class="fa-solid fa-utensils"></i> Menüye Dahil Et</button>
                </form>
            </div>

            <div class="table-wrapper">
                <h2><i class="fa-solid fa-folder-open" style="color:#d6b98c; margin-right:10px;"></i> Menü İçerik Listesi</h2>
                <div class="search-container form-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="menuSearch" onkeyup="filterMeals()" placeholder="Yemek adı veya kategoriye göre hızlı ara...">
                </div>
                <table style="margin-top:10px;" id="mealsTable">
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

                                    <!-- <a href="index.php?route=admin_action&delete_id=X">
                                        
                                        Link yerine Form ekleyip POST ile Token kontrolü sağladık. 
                                        -->
                                    <form method="POST" action="index.php?route=admin_action" onsubmit="return confirm('Bu yemeği silmek istediğinize emin misiniz?');" style="display:inline;">
                                        <input type="hidden" name="action" value="delete_meal">
                                        <input type="hidden" name="delete_id" value="<?php echo $meal['meal_id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <button type="submit" class="btn-delete">
                                            <i class="fa-solid fa-trash-can"></i> Sil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterMeals() {
            const input = document.getElementById('menuSearch');
            const filter = input.value.toLowerCase().trim();
            const table = document.getElementById('mealsTable');
            const tr = table.getElementsByTagName('tr');
            for (let i = 1; i < tr.length; i++) {
                const tdMealName = tr[i].getElementsByTagName('td')[1];
                const tdCategory = tr[i].getElementsByTagName('td')[2];
                if (tdMealName && tdCategory) {
                    const mealText = tdMealName.textContent || tdMealName.innerText;
                    const catText = tdCategory.textContent || tdCategory.innerText;
                    if (mealText.toLowerCase().indexOf(filter) > -1 || catText.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>

</html>