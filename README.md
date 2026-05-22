# PLATERRA - Premium Catering Yönetim Sistemi 🍲✨

PLATERRA, özel davetler, şirket organizasyonları ve toplu yemek hizmetleri sunan kurumsal bir catering firması için geliştirilmiş, **Model-View-Controller (MVC)** mimarisine sahip, modüler ve güvenli bir Full-Stack web uygulamasıdır.

---

## 🚀 Özellikler

### 👤 Müşteri Ekranı (Frontend)
* **Dinamik Menü Listeleme:** Çorbalar, Ana Yemekler, Zeytinyağlılar, Mezeler, Tatlılar ve İçecekler kategorilerinde güncel menülerin şık kart tasarımlarıyla listelenmesi.
* **Günün Özel Menüsü (Şefin Seçimi):** Sadece giriş yapmış kullanıcıların görebileceği, o güne özel belirlenmiş avantajlı premium menü alanı.
* **Akıllı Sepet Sistemi:** Sayfa yenilenmeden (AJAX/Fetch API) sepete ürün ekleme, çıkarma ve anlık toplam fiyat hesaplama.
* **Güvenli Kimlik Doğrulama:** Session (Oturum) kontrollü Kayıt Ol, Giriş Yap ve Güvenli Çıkış mekanizmaları.
* **Şık Bildirimler:** Başarılı ve hatalı işlemlerde sağ üst köşede beliren ve otomatik kaybolan modern Toast bildirimleri.

### 👑 Yönetim Paneli (Admin - Backend)
* **Firma Durum Paneli (Dashboard):** Toplam ciro, sistemdeki aktif yemek çeşidi, kategori sayısı ve alınan toplam sipariş miktarının anlık takibi.
* **Sipariş Geçmişi:** Son alınan siparişlerin, siparişi veren müşteri bilgileri ve tarih detaylarıyla birlikte listelenmesi.
* **Müşteri Yönetimi:** Yönetim paneli üzerinden yeni müşteri hesabı tanımlama ve mevcut müşterileri (sipariş geçmişleriyle birlikte güvenli bir şekilde) sistemden kaldırma.
* **Menü Yönetimi:** Sisteme yeni yemek/fiyat ekleme, yemek arama/filtreleme ve menüden ürün silme modülleri.

---

## 🛠️ Kullanılan Teknolojiler

* **Backend:** PHP 8.x (Saf MVC Mimarisi, Front Controller Deseni)
* **Veritabanı:** MySQL / MariaDB (PDO Sürücüsü, Hazırlanmış İfadeler - Prepared Statements, Güvenli Transaction Yapısı)
* **Frontend:** HTML5, CSS3 (Grid & Flexbox sistemleri, modern blur ve gradient efektleri), Nesne Yönelimli JavaScript (Async/Await Fetch API)
* **İkonlar & Fontlar:** FontAwesome v6, Google Fonts (Inter & Playfair Display)

---

## 📁 Proje Klasör Yapısı

```text
platerra/
│
├── config/
│   └── Database.php          # PDO Veritabanı bağlantı sınıfı
│
├── controllers/
│   ├── HomeController.php    # Anasayfa ve menü veri akışı kontrolü
│   ├── AuthController.php    # Giriş, kayıt ve çıkış işlemleri
│   ├── OrderController.php   # Fetch API ile gelen siparişlerin işlenmesi
│   └── AdminController.php   # Yönetim paneli aksiyonları ve yetki kontrolü
│
├── models/
│   ├── MealModel.php         # Yemek ve günün menüsü SQL sorguları
│   ├── UserModel.php         # Kullanıcı kayıt ve e-posta kontrol sorguları
│   ├── OrderModel.php        # Sipariş oluşturma sorguları
│   └── AdminModel.php        # Dashboard istatistikleri ve yönetim sorguları
│
├── views/
│   ├── home.php              # Müşteri arayüzü (Mutfak & Menü ekranı)
│   └── admin.php             # Yönetim paneli arayüzü
│
├── public/
│   ├── css/
│   │   └── style.css         # Kurumsal arayüz stilleri
│   └── js/
│       └── script.js         # Sepet dinamikleri ve Fetch API işlemleri
│
├── database.sql              # Veritabanı şeması ve test verileri
└── index.php                 # Front Controller / Sistemin tek giriş noktası (Router)
