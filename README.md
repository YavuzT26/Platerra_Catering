# PLATERRA - Catering Yönetim Sistemi 🍲

PLATERRA, kurumsal bir catering firması için geliştirilmiş, **MVC (Model-View-Controller)** mimarisine sahip tam kapsamlı (Full-Stack) bir web uygulamasıdır.

---

## 🛠️ Öne Çıkan Özellikler

* **Kullanıcı Arayüzü:** Kategorize edilmiş dinamik menü listesi, Fetch API tabanlı anlık sepet güncellemeleri ve şefin günlük özel menü alanı.
* **Yönetim Paneli (Admin):** Toplam ciro ve sipariş takibi, menüye yeni yemek ekleme/silme ve müşteri hesabı yönetimi.
* **Güvenlik:** `PDO Prepared Statements` ile SQL Injection koruması, `password_hash()` ile şifre kriptolama ve `is_admin` session yetki kontrolü.

---

## 📁 Proje Klasör Yapısı

```text
platerra/
├── config/          # Veritabanı bağlantı sınıfı (Database.php)
├── controllers/     # İstekleri karşılayan ve yönlendiren kontrolörler
├── models/          # Veritabanı SQL sorgularını çalıştıran modeller
├── views/           # Kullanıcıya gösterilen arayüz dosyaları (home, admin)
├── public/          # CSS ve JavaScript (Arayüz stilleri ve sepet dinamikleri)
├── database.sql     # Veritabanı şeması ve hazır test verileri
└── index.php        # Sistemin tek giriş noktası (Front Controller / Router)
