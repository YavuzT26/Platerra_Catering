/* ======================================================= */
/* AKILLI KAPANMA SİSTEMİ (MODAL VE AÇILIR MENÜLER İÇİN)   */
/* ======================================================= */

// 1. EKRANIN HERHANGİ BİR YERİNE TIKLAMA KONTROLÜ
window.addEventListener('click', function (event) {
    // Modalın dışındaki siyah arka plana tıklanırsa kapat
    if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = "none";
    }

    // Sepet içi tıklamaları veya silme butonlarını pas geç
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

// 2. LİNKLERE TIKLAMA KONTROLÜ
document.querySelectorAll('.nav-right a').forEach(link => {
    link.addEventListener('click', () => {
        // Eğer tıklanan link bir modal açma linkiyse, kapatma kodlarını çalıştırma
        if (link.getAttribute('onclick') && link.getAttribute('onclick').includes('openModal')) {
            return;
        }
        // Açık olan tüm küçük menüleri gizle
        let dropdowns = document.getElementsByClassName("dropdown-menu");
        for (let i = 0; i < dropdowns.length; i++) {
            dropdowns[i].style.display = "none";
        }

        // Açık olan tüm büyük formları kapat
        let modals = document.getElementsByClassName("modal-overlay");
        for (let i = 0; i < modals.length; i++) {
            modals[i].style.display = "none";
        }
    });
});

// 3. SCROLL (KAYDIRMA) KONTROLÜ
window.addEventListener('scroll', () => {
    let dropdowns = document.getElementsByClassName("dropdown-menu");
    for (let i = 0; i < dropdowns.length; i++) {
        dropdowns[i].style.display = "none";
    }
});

// Modal Açma
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.getElementById('userMenu').style.display = 'none';
}

// Modal Kapatma
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Dropdown Aç / Kapa
function toggleDropdown(id) {
    const targetMenu = document.getElementById(id);
    const allDropdowns = document.getElementsByClassName("dropdown-menu");

    for (let i = 0; i < allDropdowns.length; i++) {
        if (allDropdowns[i].id !== id) {
            allDropdowns[i].style.display = "none";
        }
    }

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

/* SEPET İŞLEMLERİ */
let cart = [];

function addToCart(name, price) {
    cart.push({ name: name, price: parseFloat(price) });
    updateCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

function addDailyMenu(dynamicPrice) {
    cart.push({ name: "Şefin Günlük Menüsü", price: parseFloat(dynamicPrice) });
    updateCart();
    showToast("Şefin günlük menüsü sepete eklendi!");
}

function updateCart() {
    const cartItems = document.getElementById("cart-items");
    const emptyText = document.getElementById("empty-cart");
    const cartCount = document.getElementById("cart-count");
    const cartTotalDisplay = document.getElementById("cart-total-display");
    const orderButton = document.querySelector("#cartMenu button:not(.cart-item button)");

    cartItems.innerHTML = "";
    cartCount.innerText = cart.length;
    let total = 0;

    if (cart.length === 0) {
        emptyText.style.display = "block";
        if (cartTotalDisplay) cartTotalDisplay.style.display = "none";
        if (orderButton) orderButton.style.display = "none";
    } else {
        emptyText.style.display = "none";
        if (cartTotalDisplay) cartTotalDisplay.style.display = "block";
        if (orderButton) orderButton.style.display = "block";

        cart.forEach((item, index) => {
            total += item.price;
            cartItems.innerHTML += `
            <li class="cart-item">
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <span style="font-weight:500;">${item.name}</span>
                    <span style="font-size:12px; color:#d6b98c;">${item.price} ₺</span>
                </div>
                <button onclick="removeFromCart(${index})">X</button>
            </li>`;
        });
        if (cartTotalDisplay) cartTotalDisplay.innerText = `Toplam: ${total} ₺`;
    }
}

/* SİPARİŞİ TAMAMLA (FETCH API) */
async function completeOrder() {
    if (cart.length === 0) {
        showToast('Sepetiniz boş!');
        return;
    }

    try {
        const itemsToSend = cart.map(item => item.name);
        const response = await fetch('index.php?route=checkout', {
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
            showToast(data.message);
            cart = [];
            updateCart();
            document.getElementById('cartMenu').style.display = 'none';
        } else {
            showToast(data.message);
            if (data.redirect === 'login') {
                openModal('loginModal');
            }
        }
    } catch (error) {
        console.error('Sipariş hatası:', error);
        showToast("Sipariş işlenirken bir sunucu hatası oluştu. Lütfen bağlantınızı kontrol edip tekrar deneyiniz!");
    }
}

// Sayfa kaydırma aksiyonu
function scrollToMenu() {
    document.getElementById("menuSection").scrollIntoView({
        behavior: "smooth"
    });
}

// Sepeti ilk yüklemede sıfırla
updateCart();

/* TOAST BİLDİRİMLERİ OTOMATİK GİZLEME */
document.addEventListener("DOMContentLoaded", function () {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 2500);
    });
});

/* DİNAMİK TOAST BİLDİRİM OLUŞTURUCU */
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;

    // Başarı veya hata durumuna göre ikon seçimi
    const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';

    toast.innerHTML = `
        <i class="fa-solid ${iconClass}"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);

    // 3.5 saniye sonra gizleme animasyonunu başlat
    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => {
            toast.remove();
        }, 500);
    }, 2500);
}