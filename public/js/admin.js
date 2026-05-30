
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
        }, 2500);
    });
});