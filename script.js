document.getElementById('reservationForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();
    const phoneNumber = document.getElementById('phoneNumber').value.trim();
    const responseMessage = document.getElementById('responseMessage');

    const nameRegex = /^[a-zA-Z\s]+$/;
    if (!nameRegex.test(firstName)) {
        responseMessage.textContent = 'First name can only contain letters and spaces.';
        responseMessage.style.color = 'red';
        return;
    }
    if (!nameRegex.test(lastName)) {
        responseMessage.textContent = 'Last name can only contain letters and spaces.';
        responseMessage.style.color = 'red';
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        responseMessage.textContent = 'Please enter a valid email address.';
        responseMessage.style.color = 'red';
        return;
    }

    const phoneRegex = /^09\d{9}$/;
    if (!phoneRegex.test(phoneNumber)) {
        responseMessage.textContent = 'Phone number must start with 09 and be 11 digits long.';
        responseMessage.style.color = 'red';
        return;
    }

    // If all validations pass, proceed with the fetch request
    const formData = new FormData(this);
    try {
        const response = await fetch('http://localhost/project-registration-form/db.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        if (result.success) {
            responseMessage.textContent = 'Reservation submitted successfully!';
            responseMessage.style.color = 'green';
        } else {
            responseMessage.textContent = result.message || 'Failed to submit reservation. Please try again.';
            responseMessage.style.color = 'red';
        }
    } catch (error) {
        responseMessage.textContent = `Error: ${error.message}`;
        responseMessage.style.color = 'red';
    }
});