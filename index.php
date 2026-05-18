<?php
require_once "functions.php";
require_once "auth.php";
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

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- DOĞRU CSS -->
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>

<body>

    <!-- NAVBAR -->

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

                <!-- SEPET -->

                <div class="dropdown">

                    <div class="cart-icon-wrapper">

                        <i class="fa-solid fa-cart-shopping icon"
                            onclick="toggleDropdown('cartMenu')"></i>

                        <span id="cart-count">0</span>

                    </div>

                    <div class="dropdown-menu" id="cartMenu">

                        <p id="empty-cart">
                            Sepetiniz boş
                        </p>

                        <ul id="cart-items"></ul>
                        <div id="cart-total-display" style="display: none; padding: 12px 0; color: #d6b98c; font-weight: 700; text-align: right; font-size: 17px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px;">
                            T oplam: 0 ₺
                        </div>

                        <button onclick="completeOrder()" style="display:none;">Siparişi Tamamla</button>

                    </div>

                </div>

                <!-- KULLANICI -->

                <!--Kullanıcı girişine göre profil kısmını değiştiriyor-->
                <div class="dropdown">
                    <i class="fa-solid fa-user icon" onclick="toggleDropdown('userMenu')"></i>

                    <div class="dropdown-menu" id="userMenu">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="#" style="color: #d6b98c; font-weight: 600; cursor: default;">
                                👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <hr style="border-color: rgba(255,255,255,0.1); margin: 8px 0;">

                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                <a href="admin.php" style="color: #60a5fa; font-weight:500;">
                                    <i class="fa-solid fa-screwdriver-wrench"></i> Admin Paneli
                                </a>
                            <?php endif; ?>

                            <a href="logout.php" style="color: #ef4444; font-weight:500;">
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

    <!-- HERO -->

    <section class="hero">

        <div>

            <h1>
                Premium Catering Deneyimi
            </h1>

            <p>
                Toplantılar, davetler, özel organizasyonlar ve unutulmaz anlar için özenle hazırlanan seçkin menülerimizle sofralarınıza zarafet ve lezzeti bir arada taşıyoruz. Taze ve kaliteli malzemelerle hazırlanan yemeklerimiz, şık sunum anlayışımız ve profesyonel servis ekibimiz sayesinde misafirlerinize her detayında fark yaratan bir deneyim sunuyoruz. Samimi atmosferi, kusursuz hizmet anlayışı ve her sofrada aynı özeni hissettiren yaklaşımımızla özel günlerinizi unutulmaz anılara dönüştürüyoruz.
            </p>

            <button class="btn" onclick="scrollToMenu()">
                Menüye Göz At
            </button>

        </div>

        <div class="hero-image"></div>

    </section>

    <!-- MENÜ -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <section class="premium-menu hidden-menu" id="gununMenusu">

            <div class="premium-overlay"></div>

            <div class="premium-content">

                <div class="premium-left">

                    <span class="premium-badge">
                        Günün Özel Menüsü
                    </span>

                    <h2>
                        Şefin Günlük Seçimi
                    </h2>

                    <p>
                        Günlük olarak özenle hazırlanan premium catering menümüz,
                        özel davetleriniz için şık sunum ve eşsiz tat deneyimi sunar.
                    </p>

                </div>

                <div class="premium-right">

                    <?php dailyMenu(date('Y-m-d')) ?>
                    <!-- Database'e Haziran sonuna kadar olacak şekilde günlük menüler eklenmeli -->

                    <!--PHP ile Dinamik Hale Getirildi-->

                    <!-- 
                <div class="premium-item">
                    <span>🍲 Çorba</span>
                    <h3>Mercimek Çorbası</h3>
                </div>

                <div class="premium-item">
                    <span>🍆 Ana Yemek</span>
                    <h3>Karnıyarık</h3>
                </div>

                <div class="premium-item">
                    <span>🥗 Zeytinyağlı</span>
                    <h3>Yaprak Sarma</h3>
                </div>

                <div class="premium-item">
                    <span>🧆 Meze</span>
                    <h3>Haydari</h3>
                </div>

                <div class="premium-item">
                    <span>🍮 Tatlı</span>
                    <h3>Fırın Sütlaç</h3>
                </div>

                <div class="premium-item">
                    <span>🥤 İçecek</span>
                    <h3>Ayran</h3>
                </div> -->

                </div>
                <div class="premium-buy">
                    <button class="premium-buy-btn" onclick="addDailyMenu()">
                        Günün Menüsünü Satın Al
                    </button>
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
        <!-- ÇORBA -->

        <div class="card corba-card clickable"
            onclick="toggleMenu(this)">

            <h3>Çorbalar</h3>

            <p>Sıcak başlangıçlar</p>

            <ul class="sub-menu">

                <?php
                menuyuGetir(1);
                ?>

                <!--   PHP ile dinamik hale getirildi! -->
                <!--
                <li onclick="addToCart('Mercimek Çorbası')">Mercimek Çorbası</li>

                <li onclick="addToCart('Ezogelin Çorbası')">Ezogelin Çorbası</li>

                <li onclick="addToCart('Domates Çorbası')">Domates Çorbası</li>

                <li onclick="addToCart('Tavuk Suyu Çorbası')">Tavuk Suyu Çorbası</li>

                <li onclick="addToCart('Yayla Çorbası')">Yayla Çorbası</li> 
                -->
            </ul>

        </div>

        <!-- ANA YEMEK -->
        <!-- !!!!!!!Menü Çeşitlendirilecek!!!!!!!-->

        <div class="card anayemek-card clickable"
            onclick="toggleMenu(this)">

            <h3>Ana Yemekler</h3>

            <p>Özel davetlere uygun ana tabaklar.</p>
            <ul class="sub-menu">

                <?php
                menuyuGetir(2);
                ?>

                <!--   PHP ile dinamik hale getirildi! -->

                <!-- 
                <li onclick="addToCart('Karnıyarık')">Karnıyarık</li>

                <li onclick="addToCart('Nohut Yemeği')">Nohut Yemeği</li>

                <li onclick="addToCart('Kabak Dolması')">Kabak Dolması</li>

                <li onclick="addToCart('Sebzeli Türlü')">Sebzeli Türlü</li>

                <li onclick="addToCart('Ispanak Yemeği')">Ispanak Yemeği</li>

                <li onclick="addToCart('Fırın Makarna')">Fırın Makarna</li>

                <li onclick="addToCart('Pırasa Yemeği')">Pırasa Yemeği</li>

                <li onclick="addToCart('Kuru Fasulye')">Kuru Fasulye</li>
                -->

            </ul>

        </div>

        <!-- ZEYTİNYAĞLI -->

        <div class="card zeytinyagli-card clickable"
            onclick="toggleMenu(this)">

            <h3>Zeytinyağlılar</h3>

            <p>Hafif ve sağlıklı seçenekler.</p>

            <ul class="sub-menu">

                <?php
                menuyuGetir(3);
                ?>

                <!--   PHP ile dinamik hale getirildi! -->

                <!-- 
                <li onclick="addToCart('Yaprak Sarma')">Yaprak Sarma</li>

                <li onclick="addToCart('Barbunya')">Barbunya</li>

                <li onclick="addToCart('Taze Fasulye')">Taze Fasulye</li>

                <li onclick="addToCart('Enginar')">Enginar</li> 
                -->

            </ul>

        </div>

        <!-- MEZE -->

        <div class="card meze-card clickable"
            onclick="toggleMenu(this)">

            <h3>Mezeler</h3>

            <p>Lezzetli başlangıç alternatifleri.</p>

            <ul class="sub-menu">

                <?php
                menuyuGetir(4);
                ?>

                <!--   PHP ile dinamik hale getirildi! -->

                <!-- 
                <li onclick="addToCart('Haydari')">Haydari</li>

                <li onclick="addToCart('Patlıcan Salatası')">Patlıcan Salatası</li>

                <li onclick="addToCart('Rus Salatası')">Rus Salatası</li>

                <li onclick="addToCart('Yoğurtlu Semizotu')">Yoğurtlu Semizotu</li> 
                -->

            </ul>

        </div>

        <!-- TATLI -->

        <div class="card tatli-card clickable"
            onclick="toggleMenu(this)">

            <h3>Tatlılar</h3>

            <p>Zarif sunumlarla final dokunuşu.</p>

            <ul class="sub-menu">


                <?php
                menuyuGetir(5);
                ?>

                <!--   PHP ile dinamik hale getirildi! -->

                <!-- 
                <li onclick="addToCart('Sütlaç')">Sütlaç</li>

                <li onclick="addToCart('Kemalpaşa Tatlısı')">Kemalpaşa Tatlısı</li>

                <li onclick="addToCart('Kabak Tatlısı')">Kabak Tatlısı</li>

                <li onclick="addToCart('Fırın Sütlaç')">Fırın Sütlaç</li>

                <li onclick="addToCart('Aşure')">Aşure</li>

                <li onclick="addToCart('Muhallebi')">Muhallebi</li>

                <li onclick="addToCart('Kazandibi')">Kazandibi</li>

                <li onclick="addToCart('İrmik Helvası')">İrmik Helvası</li> 
                -->
            </ul>

        </div>

        <!-- İÇECEK -->

        <div class="card icecek-card clickable"
            onclick="toggleMenu(this)">

            <h3>İçecekler</h3>

            <p>Menülere eşlik eden içecek alternatifleri.</p>

            <ul class="sub-menu">

                <?php
                menuyuGetir(6);
                ?>
                <!--   PHP ile dinamik hale getirildi! -->

                <!-- 
                <li onclick="addToCart('Kola')">Kola</li>

                <li onclick="addToCart('Ayran')">Ayran</li>

                <li onclick="addToCart('Limonata')">Limonata</li>

                <li onclick="addToCart('Soğuk Çay')">Soğuk Çay</li>

                <li onclick="addToCart('Meyve Suyu')">Meyve Suyu</li> 
                -->
            </ul>

        </div>

    </section>

    <!-- HAKKIMIZDA -->

    <section id="about">

        <h2 class="section-title">
            Hakkımızda
        </h2>

        <div class="banner">

            <div class="banner-text">

                PLATERRA olarak özel davetler, şirket organizasyonları
                ve toplu yemek hizmetlerinde premium catering deneyimi sunuyoruz.

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
    <!--Register-->
    <div class="modal-overlay" id="registerModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('registerModal')">&times;</span>

            <h2>Kayıt Ol</h2>

            <form class="auth-form" method="POST" action="index.php">
                <input type="hidden" name="form_type" value="register">

                <label for="reg_fullname">Ad Soyad</label>
                <input type="text" id=reg_full_name name="full_name" required placeholder="Ad ve Soyad Giriniz">

                <label for="reg_email">E-posta Adresi</label>
                <input type="email" id=reg_email name="email" required placeholder="ornek@mail.com">

                <label for=reg_password>Şifre</label>
                <input type="password" id=reg_password name="password" required placeholder="••••••••">

                <button type="submit" class="btn">Hesap Oluştur</button>
            </form>
        </div>
    </div>

    <!-- Login -->
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
                    <a href="#" onclick="closeModal('loginModal'); openModal('registerModal');" style="color: #d6b98c; text-decoration: none; font-weight: 600;">
                        Hemen Kayıt Ol
                    </a>
                </p>
            </form>
        </div>
    </div>

    <!-- FOOTER -->

    <footer id="contact">

        <h2>İletişim</h2>

        <p>
            <i class="fa-solid fa-phone"></i>
            +90 555 555 55 55
        </p>

        <p>
            <i class="fa-solid fa-envelope"></i>
            info@platerra.com
        </p>

    </footer>


</body>

</html>