<<<<<<< Updated upstream
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
=======
const carouselTrack = document.querySelector('.carousel-track');
const cards = Array.from(carouselTrack.children);

let isDragging = false;
let startX = 0;
let currentTranslate = 0;
let prevTranslate = 0;
let cardWidth = cards[0].offsetWidth + 20; // Card width plus gap
let totalCards = cards.length;

// Clone first and last card for infinite effect
const setupInfiniteLoop = () => {
    const firstCard = cards[0].cloneNode(true);
    const lastCard = cards[cards.length - 1].cloneNode(true);

    carouselTrack.appendChild(firstCard); // Clone first card to the end
    carouselTrack.insertBefore(lastCard, carouselTrack.firstChild); // Clone last card to the beginning

    currentTranslate = -cardWidth; // Offset position to account for the cloned card
    carouselTrack.style.transform = `translateX(${currentTranslate}px)`;
};

// Start dragging
const dragStart = (event) => {
    isDragging = true;
    startX = getPositionX(event);
    prevTranslate = currentTranslate;
    carouselTrack.style.transition = "none"; // Disable animation during dragging
};

// Get current X position
const getPositionX = (event) => {
    return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
};

// While dragging
const dragMove = (event) => {
    if (!isDragging) return;
    const currentX = getPositionX(event);
    currentTranslate = prevTranslate + (currentX - startX);
    carouselTrack.style.transform = `translateX(${currentTranslate}px)`;
};

// End dragging
const dragEnd = () => {
    isDragging = false;
    const movedBy = currentTranslate - prevTranslate;

    // Determine direction based on drag distance
    if (movedBy < -50) {
        // Swiped left
        currentTranslate -= cardWidth;
    } else if (movedBy > 50) {
        // Swiped right
        currentTranslate += cardWidth;
    }

    carouselTrack.style.transition = "transform 0.3s ease"; // Smooth snap
    carouselTrack.style.transform = `translateX(${currentTranslate}px)`;

    handleInfiniteScroll();
};

// Handle infinite loop logic
const handleInfiniteScroll = () => {
    // Total cards include the cloned cards, so we adjust accordingly
    const maxTranslate = -cardWidth * (totalCards);
    const minTranslate = -cardWidth;

    if (currentTranslate <= maxTranslate) {
        // If at the end (last cloned card), jump to the first real card
        currentTranslate = -cardWidth;
        carouselTrack.style.transition = "none"; // Remove animation for seamless jump
        carouselTrack.style.transform = `translateX(${currentTranslate}px)`;
    }

    if (currentTranslate >= 0) {
        // If at the beginning (first cloned card), jump to the last real card
        currentTranslate = maxTranslate + cardWidth;
        carouselTrack.style.transition = "none"; // Remove animation for seamless jump
        carouselTrack.style.transform = `translateX(${currentTranslate}px)`;
    }
};

// Event listeners
carouselTrack.addEventListener('mousedown', dragStart);
carouselTrack.addEventListener('mousemove', dragMove);
carouselTrack.addEventListener('mouseup', dragEnd);
carouselTrack.addEventListener('mouseleave', dragEnd);

carouselTrack.addEventListener('touchstart', dragStart);
carouselTrack.addEventListener('touchmove', dragMove);
carouselTrack.addEventListener('touchend', dragEnd);

// Initialize carousel
setupInfiniteLoop();
>>>>>>> Stashed changes
