// Script.js met winkelwagen en menu functionaliteiten

document.addEventListener('DOMContentLoaded', () => {
    // Burger Menu Functionaliteit
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    // Popup Functionaliteit voor Bezorging of Afhalen
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const pickupButton = document.getElementById('pickup');
    const deliveryButton = document.getElementById('delivery');
    const validatePostcodeButton = document.getElementById('validate-postcode');
    const postcodeInput = document.getElementById('postcode');
    const allowedPostcodes = ['2511', '2512', '2513', '2514'];
    const popup = document.getElementById('order-popup');
    let orderType = '';

    if (popup) {
        popup.classList.remove('hidden');
    }

    if (pickupButton) {
        pickupButton.addEventListener('click', () => {
            orderType = 'Afhalen';
            goToStep2();
        });
    }

    if (deliveryButton) {
        deliveryButton.addEventListener('click', () => {
            orderType = 'Bezorging';
            goToStep2();
        });
    }

    if (validatePostcodeButton) {
        validatePostcodeButton.addEventListener('click', () => {
            const postcode = postcodeInput?.value.trim();
            if (!postcode) {
                alert('Voer uw postcode in.');
                return;
            }

            const isAllowed = allowedPostcodes.some(allowed => postcode.startsWith(allowed));
            if (!isAllowed) {
                alert('Helaas, wij leveren niet op uw locatie.');
                return;
            }

            alert(`Uw bestelling is ingesteld voor ${orderType}`);
            window.location.href = 'menu.php';
        });
    }

    function goToStep2() {
        step1?.classList.add('hidden');
        step2?.classList.remove('hidden');
    }

    // Winkelwagen Functionaliteit
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
            fetch('../php/store_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(cart),
            }).then(() => {
                window.location.href = 'checkout.php';
            });
        });
    }

    // Tabs Functionaliteit
    const tabs = document.querySelectorAll('.tab');
    const categories = document.querySelectorAll('.menu-category');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            categories.forEach(category => category.classList.add('hidden'));

            const selectedCategory = document.getElementById(tab.dataset.category);
            if (selectedCategory) {
                selectedCategory.classList.remove('hidden');
            }

            tabs.forEach(tab => tab.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    if (tabs.length > 0) {
        tabs[0].click();
    }

    // Carousel Functionaliteit
    const carouselTrack = document.querySelector('.carousel-track');
    if (carouselTrack) {
        const cards = Array.from(carouselTrack.children);
        const cardWidth = cards[0].offsetWidth + 20;
        let isDragging = false;
        let startX = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;

        const createInfiniteCards = () => {
            const totalCards = cards.length * 3;
            for (let i = 0; i < totalCards; i++) {
                const cardClone = cards[i % cards.length].cloneNode(true);
                carouselTrack.appendChild(cardClone);
            }

            const carouselWidth = totalCards * cardWidth;
            carouselTrack.style.width = `${carouselWidth}px`;
        };

        const dragStart = (event) => {
            isDragging = true;
            startX = getPositionX(event);
            prevTranslate = currentTranslate;
            carouselTrack.style.transition = "none";
        };

        const getPositionX = (event) => {
            return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
        };

        const dragMove = (event) => {
            if (!isDragging) return;
            const currentX = getPositionX(event);
            currentTranslate = prevTranslate + (currentX - startX);
            carouselTrack.style.transform = `translateX(${currentTranslate}px)`;
        };

        const dragEnd = () => {
            if (!isDragging) return;
            isDragging = false;

            if (currentTranslate > 0) {
                currentTranslate = -(carouselTrack.offsetWidth / 3);
            } else if (currentTranslate < -(carouselTrack.offsetWidth / 3) * 2) {
                currentTranslate = -(carouselTrack.offsetWidth / 3);
            }

            carouselTrack.style.transition = "transform 0.3s ease";
            carouselTrack.style.transform = `translateX(${currentTranslate}px)`;
        };

        const resetDraggingState = () => {
            isDragging = false;
        };

        carouselTrack.addEventListener('mousedown', (event) => {
            event.preventDefault();
            dragStart(event);
        });

        carouselTrack.addEventListener('mousemove', dragMove);
        carouselTrack.addEventListener('mouseup', dragEnd);
        carouselTrack.addEventListener('mouseleave', dragEnd);
        carouselTrack.addEventListener('touchstart', dragStart);
        carouselTrack.addEventListener('touchmove', dragMove);
        carouselTrack.addEventListener('touchend', dragEnd);

        document.addEventListener('mouseup', resetDraggingState);
        document.addEventListener('touchend', resetDraggingState);

        createInfiniteCards();
    }
});


document.addEventListener('DOMContentLoaded', () => {
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const popup = document.getElementById('delivery-pickup-popup');
    const menuSection = document.getElementById('menu-section');
    const pickupBtn = document.getElementById('pickup-btn');
    const deliveryBtn = document.getElementById('delivery-btn');
    const validatePostcode = document.getElementById('validate-postcode');
    const postcodeInput = document.getElementById('postcode');
    const messageContainer = document.getElementById('message-container');

    let deliveryMethod = '';

    // Stap 1: Kies bezorgen of afhalen
    pickupBtn.addEventListener('click', () => {
        deliveryMethod = 'pickup';
        popup.classList.add('hidden'); // Popup verbergen
        menuSection.classList.remove('hidden'); // Menu tonen
    });

    deliveryBtn.addEventListener('click', () => {
        deliveryMethod = 'delivery';
        step1.classList.add('hidden'); // Stap 1 verbergen
        step2.classList.remove('hidden'); // Postcode-invoer tonen
    });

    // Stap 2: Postcode validatie
    validatePostcode.addEventListener('click', () => {
        const postcode = postcodeInput.value.trim().replace(/\s+/g, '');
        const numericPart = parseInt(postcode.slice(0, 4));

        if (/^[1-9][0-9]{3}[A-Za-z]{2}$/.test(postcode) && numericPart >= 2490 && numericPart <= 2599) {
            popup.classList.add('hidden'); // Popup verbergen
            menuSection.classList.remove('hidden'); // Menu tonen
            alert(`Postcode geaccepteerd (${deliveryMethod}): ${postcode}`);
        } else {
            messageContainer.textContent = 'Voer een geldige postcode in Den Haag in.';
            messageContainer.classList.remove('hidden');
            messageContainer.classList.add('error');
        }
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const popup = document.getElementById('delivery-pickup-popup');
    const menuSection = document.getElementById('menu-section');
    const pickupBtn = document.getElementById('pickup-btn');
    const deliveryBtn = document.getElementById('delivery-btn');
    const validatePostcode = document.getElementById('validate-postcode');
    const postcodeInput = document.getElementById('postcode');
    const messageContainer = document.getElementById('message-container');
    const allowedPostcodes = ['2490', '2491', '2492', '2493', '2494', '2495', '2599'];

    let deliveryMethod = '';

    // Stap 1: Kies bezorgen of afhalen
    pickupBtn?.addEventListener('click', () => {
        deliveryMethod = 'Afhalen';
        popup.classList.add('hidden'); // Popup verbergen
        menuSection.classList.remove('hidden'); // Menu tonen
    });

    deliveryBtn?.addEventListener('click', () => {
        deliveryMethod = 'Bezorging';
        step1.classList.add('hidden'); // Stap 1 verbergen
        step2.classList.remove('hidden'); // Stap 2 tonen
    });

    // Stap 2: Postcode validatie
    validatePostcode?.addEventListener('click', () => {
        const postcode = postcodeInput.value.trim().replace(/\s+/g, '');
        const numericPart = postcode.slice(0, 4);

        if (/^[1-9][0-9]{3}[A-Za-z]{2}$/.test(postcode) && allowedPostcodes.includes(numericPart)) {
            // Popup verbergen en menu tonen
            popup.classList.add('hidden');
            menuSection.classList.remove('hidden');
            alert(`Postcode geaccepteerd (${deliveryMethod}): ${postcode}`);
        } else {
            // Toon foutbericht
            if (messageContainer) {
                messageContainer.textContent = 'Voer een geldige postcode in Den Haag in.';
                messageContainer.classList.remove('hidden');
                messageContainer.classList.add('error');
            }
        }
    });

    // Tabs functionaliteit (Categorieën koppelen)
    const tabs = document.querySelectorAll('.tab');
    const categories = document.querySelectorAll('.menu-category');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Verberg alle categorieën
            categories.forEach(category => category.classList.add('hidden'));

            // Toon de geselecteerde categorie
            const selectedCategory = document.getElementById(tab.dataset.category);
            if (selectedCategory) {
                selectedCategory.classList.remove('hidden');
            }

            // Markeer de actieve tab
            tabs.forEach(tab => tab.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    // Toon standaard de eerste categorie
    if (tabs.length > 0) {
        tabs[0].click();
    }
});
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.tab');
    const categories = document.querySelectorAll('.menu-category');
    const showAllButton = document.getElementById('show-all');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const category = tab.dataset.category;

            if (category === 'all') {
                // Toon alle categorieën
                categories.forEach(category => category.classList.remove('hidden'));
            } else {
                // Verberg alle categorieën
                categories.forEach(category => category.classList.add('hidden'));

                // Toon de geselecteerde categorie
                const selectedCategory = document.getElementById(category);
                if (selectedCategory) {
                    selectedCategory.classList.remove('hidden');
                }
            }

            // Markeer de actieve tab
            tabs.forEach(tab => tab.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    // Toon standaard de eerste categorie
    if (tabs.length > 0) {
        tabs[0].click();
    }
});
