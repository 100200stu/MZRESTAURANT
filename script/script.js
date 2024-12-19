<<<<<<< Updated upstream
document.addEventListener('DOMContentLoaded', () => {
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const pickupButton = document.getElementById('pickup');
    const deliveryButton = document.getElementById('delivery');
    const validatePostcodeButton = document.getElementById('validate-postcode');
    const postcodeInput = document.getElementById('postcode');
    const allowedPostcodes = ['2511', '2512', '2513', '2514']; // Voeg jouw postcodes toe
    let orderType = '';

    // Toon pop-up direct bij laden van de pagina
    const popup = document.getElementById('order-popup');
    popup.classList.remove('hidden');

    // Kies Afhalen of Bezorging
    pickupButton.addEventListener('click', () => {
        orderType = 'Afhalen';
        goToStep2();
    });

    deliveryButton.addEventListener('click', () => {
        orderType = 'Bezorging';
        goToStep2();
    });

    // Valideer postcode en navigeer
    validatePostcodeButton.addEventListener('click', () => {
        const postcode = postcodeInput.value.trim();
        if (!postcode) {
            alert('Voer uw postcode in.');
            return;
        }

        const isAllowed = allowedPostcodes.some(allowed => postcode.startsWith(allowed));
        if (!isAllowed) {
            alert('Helaas, wij leveren niet op uw locatie.');
            return;
        }

        // Succes: navigeer naar menu.php
        alert(`Uw bestelling is ingesteld voor ${orderType}`);
        window.location.href = 'menu.php';
    });

    // Ga naar stap 2
    function goToStep2() {
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
    }
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
