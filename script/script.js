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
