let cart = [];
let cartTotal = 0;

document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', () => {
        const menuItemId = button.getAttribute('data-id');
        const itemName = button.parentElement.querySelector('h3').textContent;
        const itemPrice = parseFloat(button.parentElement.querySelector('.price').textContent.replace('€', ''));

        // Voeg item toe aan winkelwagen
        const existingItem = cart.find(item => item.id === menuItemId);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ id: menuItemId, name: itemName, price: itemPrice, quantity: 1 });
        }

        // Update totaal en winkelwagen
        updateCart();
    });
});

function updateCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = '';

    cartTotal = 0;

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p>De winkelwagen is leeg.</p>';
    } else {
        cart.forEach(item => {
            cartTotal += item.price * item.quantity;

            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item');
            cartItem.innerHTML = `
                <p>${item.name} (x${item.quantity})</p>
                <p>€${(item.price * item.quantity).toFixed(2)}</p>
            `;
            cartItemsContainer.appendChild(cartItem);
        });
    }

    // Update totaal
    document.getElementById('cart-total').textContent = `€${cartTotal.toFixed(2)}`;
    document.getElementById('cart').classList.remove('hidden');
}
document.getElementById('checkout-btn').addEventListener('click', () => {
    fetch('../php/store_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(cart),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'checkout.php'; // Ga naar checkout-pagina
            } else {
                alert('Er is een fout opgetreden bij het opslaan van de winkelwagen.');
            }
        });
});