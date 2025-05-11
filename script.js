const bookingForm = document.getElementById("bookingForm");
const paymentForm = document.getElementById("paymentForm");
const bookingPage = document.getElementById("bookingPage");
const paymentPage = document.getElementById("paymentPage");
const methodSelect = document.getElementById("method");
const cardTypeContainer = document.getElementById("cardTypeContainer");
const paymentDetails = document.getElementById("paymentDetails");
const confirmation = document.getElementById("confirmation");

let bookingData = {}; // temporary storage

bookingForm.addEventListener("submit", function (e) {
  e.preventDefault();

  // collect booking info
  bookingData = {
    firstName: document.getElementById("firstName").value.trim(),
    lastName: document.getElementById("lastName").value.trim(),
    datetime: document.getElementById("datetime").value,
    email: document.getElementById("email").value.trim(),
    phone: document.getElementById("phone").value.trim(),
    seats: document.getElementById("seats").value,
    notes: document.getElementById("notes").value.trim()
  };

  bookingPage.classList.add("hidden");
  paymentPage.classList.remove("hidden");
});

methodSelect.addEventListener("change", function () {
  const method = this.value;
  paymentDetails.innerHTML = "";
  cardTypeContainer.classList.add("hidden");

  if (method === "gcash" || method === "maya" || method === "seabank") {
    paymentDetails.innerHTML = `
      <label for="mobile">Mobile Number:</label>
      <input type="tel" id="mobile" required pattern="[0-9]{10,15}" />
    `;
  } else if (method === "card") {
    cardTypeContainer.classList.remove("hidden");
    paymentDetails.innerHTML = `
      <label for="cardNumber">Card Number:</label>
      <input type="text" id="cardNumber" required pattern="[0-9]{16}" />

      <label for="expiry">Expiry Date:</label>
      <input type="month" id="expiry" required />

      <label for="cvv">CVV:</label>
      <input type="text" id="cvv" required pattern="[0-9]{3}" />
    `;
  }
});

document.getElementById("paymentForm").addEventListener("submit", function (e) {
    e.preventDefault();
  
    // Hide payment section
    document.getElementById("paymentPage").style.display = "none";
  
    // Fill in ticket details
    const name = document.getElementById("firstName").value + " " + document.getElementById("lastName").value;
    const datetime = document.getElementById("datetime").value;
    const seats = document.getElementById("seats").value;
  
    document.getElementById("ticketName").textContent = name;
    document.getElementById("ticketDateTime").textContent = datetime;
    document.getElementById("ticketSeats").textContent = seats;
    document.getElementById("ticketStatus").textContent = "Pending";
  
    // Show ticket section
    document.getElementById("ticket").style.display = "block";
  });
  
