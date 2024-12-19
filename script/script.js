document.addEventListener('DOMContentLoaded', () => {
    // Carousel functionality
    const carouselTrack = document.querySelector('.carousel-track');
    const cards = Array.from(document.querySelectorAll('.dish-card'));

    // Clone cards for infinite scroll
    const cloneCards = () => {
        cards.forEach(card => {
            const cloneBefore = card.cloneNode(true);
            const cloneAfter = card.cloneNode(true);
            carouselTrack.appendChild(cloneAfter); // Add to the end
            carouselTrack.insertBefore(cloneBefore, carouselTrack.firstChild); // Add to the beginning
        });
    };

    // Initialize carousel
    cloneCards();

    let startPosition = -carouselTrack.scrollWidth / 3;
    carouselTrack.style.transform = `translateX(${startPosition}px)`;

    let scrollAmount = 0;
    const cardWidth = cards[0].offsetWidth + 20; // Width of a card plus gap

    // Smooth infinite scrolling
    const infiniteScroll = () => {
        scrollAmount -= 2; // Adjust speed here
        if (Math.abs(scrollAmount) >= carouselTrack.scrollWidth / 3) {
            scrollAmount = 0; // Reset scroll
        }
        carouselTrack.style.transform = `translateX(${startPosition + scrollAmount}px)`;
        requestAnimationFrame(infiniteScroll);
    };

    // Start infinite scrolling
    requestAnimationFrame(infiniteScroll);

    // Menu rendering
    const menuItems = [
        { id: 1, category: 'beef', name: 'Classic Beef Burger', price: 6.49, img: '../images/burger.png', desc: 'Juicy beef patty with fresh lettuce and tomato.' },
        { id: 2, category: 'chicken', name: 'Grilled Chicken Burger', price: 6.49, img: '../images/chicken-burger.png', desc: 'Grilled chicken breast with cheese.' },
        { id: 3, category: 'loaded', name: 'Loaded Fries', price: 5.99, img: '../images/fries.png', desc: 'Fries topped with cheese and bacon bits.' },
    ];

    const menuContainer = document.getElementById('menu-items');
    const popup = document.getElementById('popup-modal');
    const cart = document.getElementById('cart');
    const cartItems = [];

    const renderMenu = (category) => {
        menuContainer.innerHTML = '';
        menuItems
            .filter(item => item.category === category)
            .forEach(item => {
                const menuItem = document.createElement('div');
                menuItem.className = 'menu-item';
                menuItem.innerHTML = `
                    <img src="${item.img}" alt="${item.name}">
                    <h3>${item.name}</h3>
                    <p class="price">€${item.price.toFixed(2)}</p>
                `;
                menuItem.addEventListener('click', () => openPopup(item));
                menuContainer.appendChild(menuItem);
            });
    };

    const openPopup = (item) => {
        document.getElementById('popup-image').src = item.img;
        document.getElementById('popup-title').innerText = item.name;
        document.getElementById('popup-description').innerText = item.desc;
        document.getElementById('popup-price').innerText = `€${item.price.toFixed(2)}`;
        document.getElementById('add-to-cart-btn').onclick = () => addToCart(item);
        popup.classList.remove('hidden');
    };

    const addToCart = (item) => {
        cartItems.push(item);
        updateCart();
        popup.classList.add('hidden');
    };

    const updateCart = () => {
        const cartList = document.getElementById('cart-items');
        const total = document.getElementById('cart-total');
        cartList.innerHTML = '';
        let totalPrice = 0;
        cartItems.forEach(item => {
            totalPrice += item.price;
            const cartItem = document.createElement('li');
            cartItem.innerText = `${item.name} - €${item.price.toFixed(2)}`;
            cartList.appendChild(cartItem);
        });
        total.innerText = `€${totalPrice.toFixed(2)}`;
        cart.classList.remove('hidden');
    };

    document.getElementById('close-popup-btn').addEventListener('click', () => {
        popup.classList.add('hidden');
    });

    // Initial rendering
    renderMenu('beef');

    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelector('.tab.active').classList.remove('active');
            tab.classList.add('active');
            renderMenu(tab.dataset.category);
        });
    });
});
