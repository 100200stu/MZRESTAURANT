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
    if (!carouselTrack) {
        console.warn('carousel-track element not found');
        return; // Exit if carousel-track is not found
    }

    const cards = Array.from(carouselTrack.children);
    const cardWidth = cards[0].offsetWidth + 20; // Card width plus gap
    let isDragging = false;
    let startX = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;

    // Function to create infinite cards by repeating the order
    const createInfiniteCards = () => {
        const totalCards = cards.length * 3; // Triple the cards to allow seamless scrolling
        for (let i = 0; i < totalCards; i++) {
            const cardClone = cards[i % cards.length].cloneNode(true); // Repeat in the same order
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
});
function scrollToCategory(categoryId) {
    const element = document.getElementById(categoryId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}



