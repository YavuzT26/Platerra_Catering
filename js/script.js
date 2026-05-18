/* ======================================================= */
/* AKILLI KAPANMA SİSTEMİ (MODAL VE AÇILIR MENÜLER İÇİN)   */
/* ======================================================= */

// 1. EKRANIN HERHANGİ BİR YERİNE TIKLAMA KONTROLÜ
window.addEventListener('click', function (event) {

    // Modalın (Kayıt Formu) dışındaki siyah arka plana tıklanırsa kapat
    if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = "none";
    }

    if (event.target.closest('#cart-items') || event.target.closest('.cart-item button')) {
        return;
    }

    // Tıklanan yer açılır menü (dropdown) veya ikonu DEĞİLSE menüleri gizle
    if (!event.target.closest('.dropdown')) {
        let dropdowns = document.getElementsByClassName("dropdown-menu");
        for (let i = 0; i < dropdowns.length; i++) {
            dropdowns[i].style.display = "none";
        }
    }
});

// 2. LİNKLERE TIKLAMA KONTROLÜ (Senin yaşadığın Anasayfa sorununun çözümü)
document.querySelectorAll('.nav-right a').forEach(link => {
    link.addEventListener('click', () => {

        // KRİTİK NOKTA: Eğer tıklanan link bir modal açma linkiyse (Kayıt Ol / Giriş Yap), aşağıdaki kapatma kodlarını ÇALIŞTIRMA!
        if (link.getAttribute('onclick') && link.getAttribute('onclick').includes('openModal')) {
            return;
        }
        // Açık olan tüm küçük menüleri (Sepet, Kullanıcı) kapat
        let dropdowns = document.getElementsByClassName("dropdown-menu");
        for (let i = 0; i < dropdowns.length; i++) {
            dropdowns[i].style.display = "none";
        }

        // Açık olan tüm büyük formları (Kayıt Ol) kapat
        let modals = document.getElementsByClassName("modal-overlay");
        for (let i = 0; i < modals.length; i++) {
            modals[i].style.display = "none";
        }
    });
});

// 3. SCROLL (KAYDIRMA) KONTROLÜ
window.addEventListener('scroll', () => {
    // Sayfa aşağı kaydırıldığında açık unutulan küçük menüleri gizle
    let dropdowns = document.getElementsByClassName("dropdown-menu");
    for (let i = 0; i < dropdowns.length; i++) {
        dropdowns[i].style.display = "none";
    }
});

// Modal Açma
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    // Modalı açarken arkada açık kalan kullanıcı menüsünü gizle
    document.getElementById('userMenu').style.display = 'none';
}

// Modal Kapatma
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Modalın dışındaki siyah arka plana tıklayınca kapatma özelliği
window.onclick = function (event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = "none";
    }
}

function toggleDropdown(id) {

    const targetMenu = document.getElementById(id);

    // Diğer tüm dropdown menülerini bul ve kapat (Aynı anda açılmalarını engeller)
    const allDropdowns = document.getElementsByClassName("dropdown-menu");
    for (let i = 0; i < allDropdowns.length; i++) {
        if (allDropdowns[i].id !== id) {
            allDropdowns[i].style.display = "none";
        }
    }

    // Hedef menünün durumunu değiştir
    if (targetMenu.style.display === "block") {
        targetMenu.style.display = "none";
    } else {
        targetMenu.style.display = "block";
    }
}

/* MENÜ AÇ KAPA */

function toggleMenu(card) {

    const menu = card.querySelector(".sub-menu");

    menu.classList.toggle("active");
}

/* SEPET */

let cart = [];

function addToCart(name, price) {

    cart.push({ name: name, price: parseFloat(price) });

    updateCart();
}

function removeFromCart(index) {

    cart.splice(index, 1);

    updateCart();
}
function addDailyMenu() {
    cart.push({ name: "Şefin Günlük Menüsü", price: 450.0 });
    updateCart();
    alert("Şefin günlük menüsü sepete eklendi!");
}

function updateCart() {

    const cartItems = document.getElementById("cart-items");

    const emptyText = document.getElementById("empty-cart");

    const cartCount = document.getElementById("cart-count");

    const cartTotalDisplay = document.getElementById("cart-total-display") // Toplam div'i;

    cartItems.innerHTML = "";

    cartCount.innerText = cart.length;

    let total = 0;

    if (cart.length === 0) {

        emptyText.style.display = "block";
        if (cartTotalDisplay) cartTotalDisplay.style.display = "none";
    }

    else {

        emptyText.style.display = "none";
        if (cartTotalDisplay) cartTotalDisplay.style.display = "block";

        cart.forEach((item, index) => {
            total += item.price;
            cartItems.innerHTML += `
            
            <li class="cart-item">
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <span style="font-weight:500;">${item.name}</span>
                    <span style="font-size:12px; color:#d6b98c;">${item.price} ₺</span>
                </div>
                <button onclick="removeFromCart(${index})">X</button>
            </li>
            
            `;
        });
        if (cartTotalDisplay) cartTotalDisplay.innerText = `Toplam: ${total} ₺`;
    }
}
//    Sipariş Tamamla
//    Modern Fetch API ile async await kullanıldı

async function completeOrder() {
    if (cart.length === 0) {
        alert('Sepetiniz boş!');
        return;
    }

    try {
        // Fiyatları değil isimleri gönderiyoruz ki kullanıcı değişim yapamasın
        const itemsToSend = cart.map(item => item.name);

        const response = await fetch('checkout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cart: itemsToSend })
        });

        if (!response.ok) {
            throw new Error(`HTTP hatası! Durum: ${response.status}`);
        }


        const data = await response.json();


        if (data.status === 'success') {
            alert(data.message);
            cart = [];
            updateCart();
            document.getElementById('cartMenu').style.display = 'none';
        } else {
            alert(data.message);
            if (data.redirect === 'login') {
                openModal('loginModal');
            }
        }

    } catch (error) {
        console.error('Sipariş hatası:', error);
        alert("Sipariş işlenirken bir sunucu hatası oluştu. Lütfen bağlantınızı kontrol edip tekrar deneyiniz!");
    }


}
// kullanıcı giriş sistemi

let userLoggedIn = false;

function loginUser() {

    userLoggedIn = true;

    document
        .getElementById("gununMenusu")
        .style.display = "flex";

    alert("Giriş başarılı. Günün özel menüsü açıldı.");
}

function logoutUser() {

    userLoggedIn = false;

    document
        .getElementById("gununMenusu")
        .style.display = "none";
}
function addDailyMenu() {

    cart.push("👨‍🍳 Şefin Günlük Menüsü");

    updateCart();

    alert("Şefin günlük menüsü sepete eklendi!");
}
/*function scrollToMenu(){

    document.getElementById("menu").scrollIntoView({
        behavior: "smooth"
    });*/


function scrollToMenu() {

    document.getElementById("menuSection")
        .scrollIntoView({
            behavior: "smooth"
        });

}


