document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        hamburger.classList.toggle('active');
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const pickupButton = document.getElementById('pickup');
    const deliveryButton = document.getElementById('delivery');
    const validatePostcodeButton = document.getElementById('validate-postcode');
    const postcodeInput = document.getElementById('postcode');
    const allowedPostcodes = ['2511', '2512', '2513', '2514'];
    let orderType = '';

    const popup = document.getElementById('order-popup');
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

            alert('Uw bestelling is ingesteld voor ${orderType}');
            window.location.href = 'menu.php';
        });
    }

    function goToStep2() {
        step1?.classList.add('hidden');
        step2?.classList.remove('hidden');
    }
});


document.addEventListener('DOMContentLoaded', () => {
    const carouselTrack = document.querySelector('.carousel-track');
    const cards = Array.from(carouselTrack.children);
    const cardWidth = cards[0].offsetWidth + 20; // Card width plus gap
    let isDragging = false;
    let startX = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;

    // Function to create infinite cards by repeating the order
    const createInfiniteCards = () => {
        // Duplicate the original set of cards for seamless scrolling
        const totalCards = cards.length * 3; // Triple the cards to allow seamless scrolling

        // Append the duplicate cards to the carousel (just repeating the order)
        for (let i = 0; i < totalCards; i++) {
            const cardClone = cards[i % cards.length].cloneNode(true); // Repeat in the same order
            carouselTrack.appendChild(cardClone);
        }

        // Set the width of the carousel to the new total width
        const carouselWidth = totalCards * cardWidth;
        carouselTrack.style.width = `${carouselWidth}px`;
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
        if (!isDragging) return; // Stop if not dragging
        const currentX = getPositionX(event);
        currentTranslate = prevTranslate + (currentX - startX);
        carouselTrack.style.transform = `translateX(${currentTranslate}px)`;
    };

    // End dragging
    const dragEnd = () => {
        if (!isDragging) return;
        isDragging = false;

        // Reset the translation value if the carousel overflows on the left or right
        if (currentTranslate > 0) {
            currentTranslate = -(carouselTrack.offsetWidth / 3); // Start scrolling from the second set of cards
        } else if (currentTranslate < -(carouselTrack.offsetWidth / 3) * 2) {
            currentTranslate = -(carouselTrack.offsetWidth / 3); // Loop back to the second set of cards
        }

        carouselTrack.style.transition = "transform 0.3s ease"; // Smooth transition
        carouselTrack.style.transform = `translateX(${currentTranslate}px)`;
    };

    // Reset dragging state on mouseup/touchend outside the carousel
    const resetDraggingState = () => {
        isDragging = false;
    };

    // Event listeners for dragging
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

    // Reset dragging state on mouseup/touchend outside the carousel
    document.addEventListener('mouseup', resetDraggingState);
    document.addEventListener('touchend', resetDraggingState);

    // Initialize the infinite cards setup
    createInfiniteCards();
});

