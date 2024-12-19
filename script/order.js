document.addEventListener('DOMContentLoaded', () => {
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const pickupButton = document.getElementById('pickup');
    const deliveryButton = document.getElementById('delivery');
    const validatePostcodeButton = document.getElementById('validate-postcode');
    const postcodeInput = document.getElementById('postcode');
    const messageContainer = document.getElementById('message-container');
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

    // Valideer postcode en toon melding
    validatePostcodeButton.addEventListener('click', () => {
        const postcode = postcodeInput.value.trim();

        if (!postcode) {
            showMessage('Voer uw postcode in.', 'error');
            return;
        }

        const isAllowed = allowedPostcodes.some(allowed => postcode.startsWith(allowed));
        if (!isAllowed) {
            showMessage('Helaas, wij leveren niet op uw locatie.', 'error');
            return;
        }

        // Succes: Toon succesbericht en navigeer
        showMessage(`Uw bestelling is ingesteld voor ${orderType}. U wordt doorgestuurd...`, 'success');
        setTimeout(() => {
            window.location.href = 'menu.php';
        }, 2000); // Wacht 2 seconden voordat je doorstuurt
    });

    // Ga naar stap 2
    function goToStep2() {
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
    }

    // Toon melding in de pagina
    function showMessage(message, type) {
        messageContainer.textContent = message;
        messageContainer.className = ''; // Reset class
        messageContainer.classList.add(type); // Voeg 'success' of 'error' toe
        messageContainer.classList.remove('hidden'); // Maak zichtbaar
    }
});
