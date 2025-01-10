document.addEventListener('DOMContentLoaded', () => {
    const cart = [];
    const cartCounter = document.getElementById('cart-count');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const checkoutButton = document.getElementById('checkout-btn');

    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const name = button.dataset.name;
            const price = parseFloat(button.dataset.price);

            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id, name, price, quantity: 1 });
            }

            updateCart();
        });
    });

    function updateCart() {
        cartItemsContainer.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p>De winkelwagen is leeg.</p>';
        } else {
            cart.forEach(item => {
                total += item.price * item.quantity;

                const cartItem = document.createElement('div');
                cartItem.classList.add('cart-item');
                cartItem.innerHTML = `
                    <p>${item.name} (x${item.quantity})</p>
                    <p>€${(item.price * item.quantity).toFixed(2)}</p>
                `;
                cartItemsContainer.appendChild(cartItem);
            });
        }

        cartTotal.textContent = `€${total.toFixed(2)}`;
        cartCounter.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        document.getElementById('cart').classList.remove('hidden');
    }

    checkoutButton.addEventListener('click', () => {
        fetch('../php/store_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(cart),
        }).then(() => {
            window.location.href = 'checkout.php';
        });
    });

    // Popup logic
    const popup = document.getElementById('delivery-pickup-popup');
    const menuSection = document.getElementById('menu-section');
    const pickupBtn = document.getElementById('pickup-btn');
    const deliveryBtn = document.getElementById('delivery-btn');

    pickupBtn.addEventListener('click', () => {
        popup.classList.add('hidden');
        menuSection.classList.remove('hidden');
    });

    deliveryBtn.addEventListener('click', () => {
        popup.classList.add('hidden');
        menuSection.classList.remove('hidden');
    });
});
