document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const popup = document.getElementById('delivery-pickup-popup');
    const menuSection = document.getElementById('menu-section');
    const pickupBtn = document.getElementById('pickup-btn');
    const deliveryBtn = document.getElementById('delivery-btn');
    const validatePostcode = document.getElementById('validate-postcode');
    const postcodeInput = document.getElementById('postcode');
    const allowedPostcodes = ['2490', '2491', '2492', '2493', '2599'];

    let deliveryMethod = '';

    if (popup) {
        popup.classList.remove('hidden');
    }

    pickupBtn?.addEventListener('click', () => {
        deliveryMethod = 'Afhalen';
        popup.classList.add('hidden');
        menuSection.classList.remove('hidden');
    });

    deliveryBtn?.addEventListener('click', () => {
        deliveryMethod = 'Bezorging';
        step1?.classList.add('hidden');
        step2?.classList.remove('hidden');
    });

    validatePostcode?.addEventListener('click', () => {
        const postcode = postcodeInput.value.trim().replace(/\s+/g, '');
        const numericPart = postcode.slice(0, 4);

        if (/^[1-9][0-9]{3}[A-Za-z]{2}$/.test(postcode) && allowedPostcodes.includes(numericPart)) {
            popup.classList.add('hidden');
            menuSection.classList.remove('hidden');
            alert(`Postcode geaccepteerd (${deliveryMethod}): ${postcode}`);
        } else {
            alert('Voer een geldige postcode in Den Haag in.');
        }
    });

    const tabs = document.querySelectorAll('.tab');
    const categories = document.querySelectorAll('.menu-category');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const category = tab.dataset.category;

            if (category === 'all') {
                categories.forEach(cat => cat.classList.remove('hidden'));
            } else {
                categories.forEach(cat => cat.classList.add('hidden'));

                const selectedCategory = document.getElementById(category);
                if (selectedCategory) {
                    selectedCategory.classList.remove('hidden');
                    selectedCategory.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            tabs.forEach(tab => tab.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    if (tabs.length > 0) {
        tabs[0].click();
    }

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

    if (checkoutButton) {
        checkoutButton.addEventListener('click', () => {
            fetch('checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    cart: cart,              // Pass cart items
                    deliveryMethod: deliveryMethod  // Pass the delivery method
                }),
            }).then(() => {
                window.location.href = 'checkout.php'; // Redirect to checkout page
            });
        });
    }
});
