<?php

/**
 * Controller tarafından View'a gönderilen değişkenlerin tanımları:
 * @var float $dailyMenuPrice
 * @var string $dailyMenu
 * @var string $corbalar
 * @var string $anaYemekler
 * @var string $zeytinyaglilar
 * @var string $mezeler
 * @var string $tatlilar
 * @var string $icecekler
 * @var array $data
 */
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLATERRA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/script.js" defer></script>
</head>

<body>
    <div class="toast-container" id="toastContainer">

        <?php if (isset($_SESSION['basari_mesaji'])): ?>
            <div class="toast success">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo htmlspecialchars($_SESSION['basari_mesaji']); ?></span>
            </div>
            <?php unset($_SESSION['basari_mesaji']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['hata_mesaji'])): ?>
            <div class="toast error">
                <i class="fa-solid fa-circle-xmark"></i>
                <span><?php echo htmlspecialchars($_SESSION['hata_mesaji']); ?></span>
            </div>
            <?php unset($_SESSION['hata_mesaji']); ?>
        <?php endif; ?>

    </div>
    <header>
        <div class="navbar">
            <div class="logo">
                PLATERRA
                <a href="#"></a>
            </div>

            <div class="nav-right">
                <a href="#">Anasayfa</a>
                <a href="#menuSection">Menü</a>
                <a href="#about">Hakkımızda</a>
                <a href="#contact">İletişim</a>

                <div class="dropdown">
                    <div class="cart-icon-wrapper">
                        <i class="fa-solid fa-cart-shopping icon" onclick="toggleDropdown('cartMenu')"></i>
                        <span id="cart-count">0</span>
                    </div>

                    <div class="dropdown-menu" id="cartMenu">
                        <p id="empty-cart">Sepetiniz boş</p>
                        <ul id="cart-items"></ul>
                        <div id="cart-total-display" style="display: none; padding: 12px 0; color: #d6b98c; font-weight: 700; text-align: right; font-size: 17px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px;">
                            Toplam: 0 ₺
                        </div>
                        <button onclick="completeOrder()" style="display:none;">Siparişi Tamamla</button>
                    </div>
                </div>

                <div class="dropdown">
                    <i class="fa-solid fa-user icon" onclick="toggleDropdown('userMenu')"></i>
                    <div class="dropdown-menu" id="userMenu">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="#" style="color: #d6b98c; font-weight: 600; cursor: default;">
                                👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <hr style="border-color: rgba(255,255,255,0.1); margin: 8px 0;">

                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                <a href="index.php?route=admin" style="color: #60a5fa; font-weight:500;">
                                    <i class="fa-solid fa-screwdriver-wrench"></i> Admin Paneli
                                </a>
                            <?php endif; ?>

                            <a href="index.php?route=logout" style="color: #ef4444; font-weight:500;">
                                <i class="fa-solid fa-sign-out-alt"></i> Çıkış Yap
                            </a>
                        <?php else: ?>
                            <a href="#" onclick="openModal('loginModal')">Giriş Yap</a>
                            <a href="#" onclick="openModal('registerModal')">Kayıt Ol</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="hero">
        <div>
            <h1>Premium Catering Deneyimi</h1>
            <p>Toplantılar, davetler, özel organizasyonlar ve unutulmaz anlar için özenle hazırlanan seçkin menülerimizle sofralarınıza zarafet ve lezzeti bir arada taşıyoruz...</p>
            <button class="btn" onclick="scrollToMenu()">Menüye Göz At</button>
        </div>
        <div class="hero-image"></div>
    </section>

    <?php if (isset($_SESSION['user_id'])): ?>
        <section class="premium-menu hidden-menu" id="gununMenusu">
            <div class="premium-overlay"></div>
            <div class="premium-content">
                <div class="premium-left">
                    <span class="premium-badge">Günün Özel Menüsü</span>
                    <h2>Şefin Günlük Seçimi</h2>
                    <p>Günlük olarak özenle hazırlanan premium catering menümüz, özel davetleriniz için şık sunum ve eşsiz tat deneyimi sunar.</p>
                </div>

                <div class="premium-right">
                    <?php if ($dailyMenu):
                        // Anahtarlar veritabanı sorgusundaki AS kelimesinden sonraki isimlerle birebir aynı olmalı
                        $kategoriler = [
                            '🍲 Çorba' => 'Corba',
                            '🍆 Ana Yemek' => 'Ana_Yemek',
                            '🥗 Zeytinyağlı' => 'Zeytinyagli',
                            '🧆 Meze' => 'Meze',
                            '🍮 Tatlı' => 'Tatli',
                            '🥤 İçecek' => 'Icecek'
                        ];
                        foreach ($kategoriler as $etiket => $kolonAdi):
                            $yemekAdi = htmlspecialchars($dailyMenu[$kolonAdi] ?? 'Yok'); ?>
                            <div class="premium-item">
                                <span><?php echo $etiket; ?></span>
                                <h3><?php echo $yemekAdi; ?></h3>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="premium-item" style="grid-column: span 2; text-align: center;">
                            <h3>Bugün için şefin özel menüsü bulunmamaktadır.</h3>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="premium-buy">
                    <?php if ($dailyMenuPrice > 0): ?>
                        <button class="premium-buy-btn" onclick="addDailyMenu(<?php echo $dailyMenuPrice; ?>)">
                            Günün Menüsünü Satın Al <?php echo $dailyMenuPrice; ?> ₺
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="premium-menu" id="gununMenusuLocked">
            <div class="premium-overlay"></div>
            <div class="locked-menu-content">
                <i class="fa-solid fa-lock locked-icon"></i>
                <h2>Günün Özel Menüsü</h2>
                <p>Şefimizin bugün için hazırladığı özel menüyü görüntülemek ve sipariş vermek için lütfen giriş yapın.</p>
                <button class="btn" onclick="openModal('loginModal')">Giriş Yap / Kayıt Ol</button>
            </div>
        </section>
    <?php endif; ?>

    <section class="menu-section" id="menuSection">
        <h2 class="menu-main-title">Menü</h2>

        <div class="card corba-card clickable" onclick="toggleMenu(this)">
            <h3>Çorbalar</h3>
            <p>Sıcak başlangıçlar</p>
            <ul class="sub-menu">
                <?php foreach ($data['corbalar'] as $corba): ?>
                    <li onclick="addToCart('<?php echo htmlspecialchars($corba['meal_name']); ?>'
                        ,<?php echo $corba['price']; ?>)" style="display:flex; justify-content:space-between; align-items:center;">
                        <span><?php echo htmlspecialchars($corba['meal_name']); ?></span>
                        <span style="font-size:14px; opacity:0.8;"><?php echo round($corba['price']); ?> ₺</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card anayemek-card clickable" onclick="toggleMenu(this)">
            <h3>Ana Yemekler</h3>
            <p>Özel davetlere uygun ana tabaklar.</p>
            <ul class="sub-menu">
                <?php foreach ($data['anaYemekler'] as $yemek): ?>
                    <li onclick="addToCart('<?php echo htmlspecialchars($yemek['meal_name']); ?>', <?php echo $yemek['price']; ?>)" style="display:flex; justify-content:space-between; align-items:center;">
                        <span><?php echo htmlspecialchars($yemek['meal_name']); ?></span>
                        <span style="font-size:14px; opacity:0.8;"><?php echo round($yemek['price']); ?> ₺</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card zeytinyagli-card clickable" onclick="toggleMenu(this)">
            <h3>Zeytinyağlılar</h3>
            <p>Hafif ve sağlıklı seçenekler.</p>
            <ul class="sub-menu">
                <?php foreach ($data['zeytinyaglilar'] as $zeytinyagli): ?>
                    <li onclick="addToCart('<?php echo htmlspecialchars($zeytinyagli['meal_name']); ?>', <?php echo $zeytinyagli['price']; ?>)" style="display:flex; justify-content:space-between; align-items:center;">
                        <span><?php echo htmlspecialchars($zeytinyagli['meal_name']); ?></span>
                        <span style="font-size:14px; opacity:0.8;"><?php echo round($zeytinyagli['price']); ?> ₺</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card meze-card clickable" onclick="toggleMenu(this)">
            <h3>Mezeler</h3>
            <p>Lezzetli başlangıç alternatifleri.</p>
            <ul class="sub-menu">
                <?php foreach ($data['mezeler'] as $meze): ?>
                    <li onclick="addToCart('<?php echo htmlspecialchars($meze['meal_name']); ?>', <?php echo $meze['price']; ?>)" style="display:flex; justify-content:space-between; align-items:center;">
                        <span><?php echo htmlspecialchars($meze['meal_name']); ?></span>
                        <span style="font-size:14px; opacity:0.8;"><?php echo round($meze['price']); ?> ₺</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card tatli-card clickable" onclick="toggleMenu(this)">
            <h3>Tatlılar</h3>
            <p>Zarif sunumlarla final dokunuşu.</p>
            <ul class="sub-menu">
                <?php foreach ($data['tatlilar'] as $tatli): ?>
                    <li onclick="addToCart('<?php echo htmlspecialchars($tatli['meal_name']); ?>', <?php echo $tatli['price']; ?>)" style="display:flex; justify-content:space-between; align-items:center;">
                        <span><?php echo htmlspecialchars($tatli['meal_name']); ?></span>
                        <span style="font-size:14px; opacity:0.8;"><?php echo round($tatli['price']); ?> ₺</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card icecek-card clickable" onclick="toggleMenu(this)">
            <h3>İçecekler</h3>
            <p>Menülere eşlik eden içecek alternatifleri.</p>
            <ul class="sub-menu">
                <?php foreach ($data['icecekler'] as $icecek): ?>
                    <li onclick="addToCart('<?php echo htmlspecialchars($icecek['meal_name']); ?>', <?php echo $icecek['price']; ?>)" style="display:flex; justify-content:space-between; align-items:center;">
                        <span><?php echo htmlspecialchars($icecek['meal_name']); ?></span>
                        <span style="font-size:14px; opacity:0.8;"><?php echo round($icecek['price']); ?> ₺</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <section id="about">
        <h2 class="section-title">Hakkımızda</h2>
        <div class="banner">
            <div class="banner-text">
                PLATERRA olarak özel davetler, şirket organizasyonları
                ve toplu yemek hizmetlerinde premium catering deneyimi sunuyoruz.
                <br>
                Şık sunum anlayışımız, kaliteli malzeme seçimimiz ve profesyonel
                mutfak ekibimiz ile her etkinliği unutulmaz hale getiriyoruz.
            </div>
            <div class="banner-image"></div>
        </div>
    </section>

    <section>
        <h2 class="section-title">Galeri</h2>
        <div class="gallery">
            <div class="gallery-item one"></div>
            <div class="gallery-item two"></div>
            <div class="gallery-item three"></div>
            <div class="gallery-item four"></div>
            <div class="gallery-item five"></div>
            <div class="gallery-item six"></div>
        </div>
    </section>

    <div class="modal-overlay" id="registerModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('registerModal')">&times;</span>
            <h2>Kayıt Ol</h2>
            <form class="auth-form" method="POST" action="index.php">
                <input type="hidden" name="form_type" value="register">
                <label for="reg_fullname">Ad Soyad</label>
                <input type="text" id="reg_full_name" name="full_name" required placeholder="Ad ve Soyad Giriniz">
                <label for="reg_email">E-posta Adresi</label>
                <input type="email" id="reg_email" name="email" required placeholder="ornek@mail.com">
                <label for="reg_password">Şifre</label>
                <input type="password" id="reg_password" name="password" required placeholder="••••••••">
                <button type="submit" class="btn">Hesap Oluştur</button>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="loginModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('loginModal')">&times;</span>
            <h2>Giriş Yap</h2>
            <form class="auth-form" method="POST" action="index.php">
                <input type="hidden" name="form_type" value="login">
                <label for="login_email">E-posta Adresi</label>
                <input type="email" id="login_email" name="email" required placeholder="ornek@mail.com">
                <label for="login_password">Şifre</label>
                <input type="password" id="login_password" name="password" required placeholder="••••••••">
                <button type="submit" class="btn">Giriş Yap</button>
                <p style="text-align: center; margin-top: 25px; font-size: 14px; color: #d1d5db;">
                    Hesabın yok mu?
                    <a href="#" onclick="closeModal('loginModal'); openModal('registerModal');" style="color: #d6b98c; text-decoration: none; font-weight: 600;">Hemen Kayıt Ol</a>
                </p>
            </form>
        </div>
    </div>

    <footer id="contact">
        <h2>İletişim</h2>
        <p><i class="fa-solid fa-phone"></i> +90 555 555 55 55</p>
        <p><i class="fa-solid fa-envelope"></i> info@platerra.com</p>
    </footer>

</body>

</html>