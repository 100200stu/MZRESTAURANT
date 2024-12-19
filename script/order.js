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
