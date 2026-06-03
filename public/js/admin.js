
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

let activeFormToSubmit = null;

function openConfirmModal(formId, message) {
    activeFormToSubmit = document.getElementById(formId);
    document.getElementById('confirmModalText').innerText = message;
    document.getElementById('customConfirmModal').style.display = 'flex';
}

function closeConfirmModal() {
    document.getElementById('customConfirmModal').style.display = 'none';
    activeFormToSubmit = null;
}

// Onay modalındaki "Evet, Sil" butonuna basıldığında formu gönderir
document.getElementById('confirmSuccessBtn').addEventListener('click', function () {
    if (activeFormToSubmit) {
        activeFormToSubmit.submit();
    }
});

// Dışarı tıklayınca modalın kapanması kuralı
window.addEventListener('click', function (event) {
    const confirmModal = document.getElementById('customConfirmModal');
    if (event.target === confirmModal) {
        closeConfirmModal();
    }
});
// Admin panelindeki bildirimleri otomatik kapatma
document.addEventListener("DOMContentLoaded", function () {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.transition = "opacity 0.5s ease, transform 0.5s ease";
            toast.style.opacity = "0";
            toast.style.transform = "translateX(120%)";
            setTimeout(() => toast.remove(), 500);
        }, 3500);
    });
});

// Admin panelindeki bildirimleri otomatik kapatma
document.addEventListener("DOMContentLoaded", function () {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        // İlerleme çubuğu animasyonu ile senkronize olması için 3.5 saniye bekle
        setTimeout(() => {
            toast.classList.add('fade-out'); // Sağa kayarak çıkma animasyonunu başlat

            // CSS çıkış animasyonu (0.5s) bittikten sonra elementi HTML'den sil
            setTimeout(() => {
                toast.remove();
            }, 500);

        }, 3500);
    });
});


const navbar = document.querySelector('.admin-nav');

function navbarBosluguAyarla() {
    let navbarYukseklik = navbar.offsetHeight;
    document.body.style.paddingTop = navbarYukseklik + "px";
}
window.addEventListener('load', navbarBosluguAyarla);
window.addEventListener('resize', navbarBosluguAyarla);

let oncekiScrollPozisyonu = window.pageYOffset;

window.onscroll = function () {

    let anlikScrollPozisyonu = window.pageYOffset;

    let navbarYukseklik = navbar.offsetHeight;

    if (oncekiScrollPozisyonu > anlikScrollPozisyonu) {
        navbar.style.top = "0";
    } else {
        navbar.style.top = `-${navbarYukseklik}px`;
    }
    oncekiScrollPozisyonu = anlikScrollPozisyonu;
}

function yukariCik(event) {
    // 1. Linkin varsayılan davranışını durdurur (URL'nin sonuna '#' eklenmesini engeller)
    event.preventDefault();

    // 2. Sayfanın en üstüne (top: 0) yumuşak bir şekilde (smooth) kaydırır
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}