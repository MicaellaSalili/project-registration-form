const bookingForm = document.getElementById("bookingForm");
const paymentForm = document.getElementById("paymentForm");
const bookingPage = document.getElementById("bookingPage");
const paymentPage = document.getElementById("paymentPage");
const methodSelect = document.getElementById("method");
const cardTypeContainer = document.getElementById("cardTypeContainer");
const paymentDetails = document.getElementById("paymentDetails");
const confirmation = document.getElementById("confirmation");
const hoursSelect = document.getElementById("hours");
const priceDisplay = document.createElement("p"); // Price display

let bookingData = {}; // temporary storage
let totalPrice = 0; // Variable to store the total price

// Add student radio buttons
const studentContainer = document.createElement("div");
studentContainer.id = "studentStatusContainer";
studentContainer.innerHTML = `
  <p>Are you a student? <span style="color: red;">*</span></p>
  <label>
    <input type="radio" name="studentStatus" value="yes" required /> Yes
  </label>
  <label>
    <input type="radio" name="studentStatus" value="no" required /> No
  </label>
`;
hoursSelect.insertAdjacentElement("afterend", studentContainer);
studentContainer.insertAdjacentElement("afterend", priceDisplay);

// Calculate price dynamically
function calculatePrice() {
  const hours = parseInt(hoursSelect.value, 10);
  const isStudent = document.querySelector('input[name="studentStatus"]:checked')?.value === "yes";

  if (!isNaN(hours)) {
    if (isStudent) {
      // Rates for students
      switch (hours) {
        case 1:
          totalPrice = 50;
          break;
        case 2:
          totalPrice = 70;
          break;
        case 3:
          totalPrice = 100;
          break;
        case 5:
          totalPrice = 120;
          break;
        case 8:
          totalPrice = 180;
          break;
        default:
          totalPrice = 0; // Default case if hours don't match
      }
    } else {
      // Rates for non-students
      switch (hours) {
        case 1:
          totalPrice = 60;
          break;
        case 2:
          totalPrice = 85;
          break;
        case 3:
          totalPrice = 120;
          break;
        case 5:
          totalPrice = 145;
          break;
        case 8:
          totalPrice = 220;
          break;
        default:
          totalPrice = 0; // Default case if hours don't match
      }
    }

    priceDisplay.textContent = `Total Price: ₱${totalPrice}`;
  } else {
    priceDisplay.textContent = "";
  }
}

// Add event listeners for dynamic price calculation
hoursSelect.addEventListener("change", calculatePrice);
studentContainer.addEventListener("change", calculatePrice);

bookingForm.addEventListener("submit", function (e) {
  e.preventDefault();

  // Collect booking info
  bookingData = {
    firstName: document.getElementById("firstName").value.trim(),
    lastName: document.getElementById("lastName").value.trim(),
    datetime: document.getElementById("datetime").value,
    email: document.getElementById("email").value.trim(),
    phone: document.getElementById("phone").value.trim(),
    hours: hoursSelect.value,
    studentStatus: document.querySelector('input[name="studentStatus"]:checked')?.value,
    notes: document.getElementById("notes").value.trim(),
    totalPrice: totalPrice // Pass the calculated price
  };

  // Display the total price in the payment section
  const paymentSummary = document.createElement("p");
  paymentSummary.id = "paymentSummary";
  paymentSummary.textContent = `Total Amount to Pay: ₱${totalPrice}`;
  paymentDetails.innerHTML = ""; // Clear previous details
  paymentDetails.appendChild(paymentSummary);

  // Proceed to payment page
  bookingPage.classList.add("hidden");
  paymentPage.classList.remove("hidden");
});

methodSelect.addEventListener("change", function () {
  const method = this.value;
  paymentDetails.innerHTML = ""; // Clear previous details
  cardTypeContainer.classList.add("hidden");

  // Display the total amount to pay
  const paymentSummary = document.createElement("p");
  paymentSummary.id = "paymentSummary";
  paymentSummary.textContent = `Total Amount to Pay: ₱${totalPrice}`;
  paymentDetails.appendChild(paymentSummary);

  // Add specific payment method details
  if (method === "gcash" || method === "maya" || method === "seabank") {
    paymentDetails.innerHTML += `
      <label for="mobile">Mobile Number:</label>
      <input type="tel" id="mobile" required pattern="[0-9]{10,15}" />
    `;
  } else if (method === "card") {
    cardTypeContainer.classList.remove("hidden");
    paymentDetails.innerHTML += `
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
  const paymentMethod = document.getElementById("method").value;

  document.getElementById("ticketName").textContent = name;
  document.getElementById("ticketDateTime").textContent = datetime;
  document.getElementById("ticketStatus").textContent = "Pending";
  document.getElementById("ticketPaymentMethod").textContent = paymentMethod;

  // Show ticket section
  document.getElementById("ticket").style.display = "block";
});

