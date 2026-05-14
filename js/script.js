function toggleDropdown(id){

    const menu = document.getElementById(id);

    if(menu.style.display === "block"){
        menu.style.display = "none";
    }

    else{
        menu.style.display = "block";
    }
}

/* MENÜ AÇ KAPA */

function toggleMenu(card){

    const menu = card.querySelector(".sub-menu");

    menu.classList.toggle("active");
}

/* SEPET */

let cart = [];

function addToCart(product){

    cart.push(product);

    updateCart();
}

function removeFromCart(index){

    cart.splice(index,1);

    updateCart();
}

function updateCart(){

    const cartItems = document.getElementById("cart-items");

    const emptyText = document.getElementById("empty-cart");

    const cartCount = document.getElementById("cart-count");

    cartItems.innerHTML = "";

    cartCount.innerText = cart.length;

    if(cart.length === 0){

        emptyText.style.display = "block";
    }

    else{

        emptyText.style.display = "none";

        cart.forEach((item,index)=>{

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

function loginUser(){

    userLoggedIn = true;

    document
    .getElementById("gununMenusu")
    .style.display = "flex";

    alert("Giriş başarılı. Günün özel menüsü açıldı.");
}

function logoutUser(){

    userLoggedIn = false;

    document
    .getElementById("gununMenusu")
    .style.display = "none";
}
function addDailyMenu(){

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