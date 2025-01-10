
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
