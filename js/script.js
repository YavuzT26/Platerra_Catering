/* ======================================================= */
/* AKILLI KAPANMA SİSTEMİ (MODAL VE AÇILIR MENÜLER İÇİN)   */
/* ======================================================= */

// 1. EKRANIN HERHANGİ BİR YERİNE TIKLAMA KONTROLÜ
window.addEventListener('click', function (event) {

    // Modalın (Kayıt Formu) dışındaki siyah arka plana tıklanırsa kapat
    if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = "none";
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

    const menu = document.getElementById(id);

    if (menu.style.display === "block") {
        menu.style.display = "none";
    }

    else {
        menu.style.display = "block";
    }
}

/* MENÜ AÇ KAPA */

function toggleMenu(card) {

    const menu = card.querySelector(".sub-menu");

    menu.classList.toggle("active");
}

/* SEPET */

let cart = [];

function addToCart(product) {

    cart.push(product);

    updateCart();
}

function removeFromCart(index) {

    cart.splice(index, 1);

    updateCart();
}

function updateCart() {

    const cartItems = document.getElementById("cart-items");

    const emptyText = document.getElementById("empty-cart");

    const cartCount = document.getElementById("cart-count");

    cartItems.innerHTML = "";

    cartCount.innerText = cart.length;

    if (cart.length === 0) {

        emptyText.style.display = "block";
    }

    else {

        emptyText.style.display = "none";

        cart.forEach((item, index) => {

            cartItems.innerHTML += `
            
            <li class="cart-item">

                ${item}

                <button onclick="removeFromCart(${index})">
                    X
                </button>

            </li>
            
            `;
        });
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


//    Sipariş Tamamla
//    Modern Fetch API ile async await kullanıldı

async function completeOrder() {
    if (cart.length === 0) {
        alert('Sepetiniz boş!');
        return;
    }

    try {

        const response = await fetch('checkout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cart: cart })
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