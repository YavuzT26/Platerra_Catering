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
    <link rel="stylesheet" href="public/css/admin.css">
    <script src="public/js/admin.js" defer> </script>

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

        <div class="toast-container" id="toastContainer">
            <?php if (isset($_GET['success'])): ?>
                <div class="toast success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?php echo htmlspecialchars($_GET['success']); ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="toast error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span><?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
        </div>
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
                        <th>İşlem </th>
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
                                            <?php echo nl2br(htmlspecialchars($ord['item_names'])); ?>
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

                                <!-- Sipariş İptali -->
                                <td>
                                    <form method="POST" action="index.php?route=admin_action" id="cancelOrderForm_<?php echo $ord['order_id']; ?>" style="display:inline;">
                                        <!-- Arka planda işlenecek işlem adı -->
                                        <input type="hidden" name="action" value="cancel_order">

                                        <!-- İptal edilecek siparişin ID'si -->
                                        <input type="hidden" name="cancel_order_id" value="<?php echo $ord['order_id']; ?>">

                                        <!-- Güvenlik için CSRF Token -->
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <!-- Onay modülünü tetikleyen buton -->
                                        <button type="button" class="btn-delete" onclick="openConfirmModal('cancelOrderForm_<?php echo $ord['order_id']; ?>', '#<?php echo $ord['order_id']; ?> numaralı siparişi iptal etmek istediğinize emin misiniz?')">
                                            <i class="fa-solid fa-ban"></i> İptal Et
                                        </button>
                                    </form>
                                </td>
                                <!-- Sipariş İptali -->
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
                                        <form method="POST" action="index.php?route=admin_action" id="deleteCustomerForm_<?php echo $cust['user_id']; ?>" style="display:inline;">
                                            <input type="hidden" name="action" value="delete_customer">
                                            <input type="hidden" name="delete_customer_id" value="<?php echo $cust['user_id']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <button type="button" class="btn-delete" onclick="openConfirmModal('deleteCustomerForm_<?php echo $cust['user_id']; ?>', 'Bu müşteriyi ve ilişkili sipariş geçmişini silmek istediğinizden emin misiniz?')">
                                                <i class="fa-solid fa-user-minus"></i> Kaldır
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
                                    <form method="POST" action="index.php?route=admin_action" id="deleteMealForm_<?php echo $meal['meal_id']; ?>" style="display:inline;">
                                        <input type="hidden" name="action" value="delete_meal">
                                        <input type="hidden" name="delete_id" value="<?php echo $meal['meal_id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <button type="button" class="btn-delete" onclick="openConfirmModal('deleteMealForm_<?php echo $meal['meal_id']; ?>', 'Bu yemeği menüden kaldırmak istediğinize emin misiniz?')">
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

    <div class="modal-overlay" id="customConfirmModal">
        <div class="modal-content">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <h2>Emin misiniz?</h2>
            <p id="confirmModalText"></p>

            <div class="modal-btn-group">
                <button type="button" class="btn btn-cancel" onclick="closeConfirmModal()">Vazgeç</button>
                <button type="button" class="btn btn-danger" id="confirmSuccessBtn">Evet, Sil</button>
            </div>
        </div>
    </div>
    <footer style="text-align:center; padding: 40px 0;">
        <a href="#" onclick="yukariCik(event)" style="color:#d6b98c; text-decoration:none; font-weight:600; font-size:15px; display:inline-flex; align-items:center; gap:8px;">
            <i class="fa-solid fa-arrow-up"></i> Başlangıca Dön
        </a>
    </footer>
</body>

</html>