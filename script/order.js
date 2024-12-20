document.addEventListener('DOMContentLoaded', () => {
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    const pickupButton = document.getElementById('pickup');
    const deliveryButton = document.getElementById('delivery');
    const locationSelect = document.getElementById('location-select');
    const locationDetails = document.getElementById('location-details');
    const address = document.getElementById('address');
    const map = document.getElementById('map');
    const chooseLocationButton = document.getElementById('choose-location');
    const validatePostcodeButton = document.getElementById('validate-postcode');
    const postcodeInput = document.getElementById('postcode');
    const messageContainer = document.getElementById('message-container');

    // Definieer toegestane postcoderanges voor Den Haag
    const allowedPostcodeRanges = [
        { start: 2490, end: 2599 } // Postcodes voor Den Haag
    ];

    let orderType = '';

    // Toon de eerste stap
    step1.classList.remove('hidden');

    pickupButton.addEventListener('click', () => {
        orderType = 'Afhalen';
        goToStep2();
    });

    deliveryButton.addEventListener('click', () => {
        orderType = 'Bezorging';
        goToStep2();
    });

    locationSelect.addEventListener('change', () => {
        const selectedLocation = locationSelect.value;

        // Vestigingsdetails weergeven
        if (selectedLocation === 'den-haag') {
            address.textContent = 'Den Haag - Pluvierstraat 402';
            map.src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2463.2313091710947!2d4.2521268!3d52.0874592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5b1de05a017cd%3A0x5b27aa743a4319c0!2sPluvierstraat%20402%2C%202560%20Den%20Haag%2C%20Nederland!5e0!3m2!1sen!2snl!4v1700000000000!5m2!1sen!2snl';
        } else if (selectedLocation === 'rotterdam') {
            address.textContent = 'Rotterdam - Lijnbaan 45';
            map.src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2462.645404872817!2d4.4786165!3d51.922509';
        }

        // Maak de details zichtbaar
        locationDetails.classList.remove('hidden');
    });

    chooseLocationButton.addEventListener('click', () => {
        goToStep3();
    });

    validatePostcodeButton.addEventListener('click', () => {
        const postcode = postcodeInput.value.trim();

        if (!postcode) {
            showMessage('Voer uw postcode in.', 'error');
            return;
        }

        const isAllowed = isPostcodeAllowed(postcode);
        if (!isAllowed) {
            showMessage('Helaas, wij leveren niet op uw locatie.', 'error');
            return;
        }

        showMessage(`Uw bestelling is ingesteld voor ${orderType}. U wordt doorgestuurd...`, 'success');
        setTimeout(() => {
            window.location.href = 'menu.php';
        }, 2000);
    });

    function goToStep2() {
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
    }

    function goToStep3() {
        step2.classList.add('hidden');
        step3.classList.remove('hidden');
    }

    function showMessage(message, type) {
        messageContainer.textContent = message;
        messageContainer.className = '';
        messageContainer.classList.add(type);
        messageContainer.classList.remove('hidden');
    }

    function isPostcodeAllowed(postcode) {
        const numericPostcode = parseInt(postcode, 10);
        return allowedPostcodeRanges.some(range => numericPostcode >= range.start && numericPostcode <= range.end);
    }
});
