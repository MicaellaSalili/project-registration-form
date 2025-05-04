# Registration / Reservation Form

A web-based application that allows users to register and book reservations. Built using HTML, CSS, PHP, and MySQL, this system is ideal for events, appointments, or any booking-based service.

## Features

- User project registration form
- Reservation booking system
- Input validation and error handling
- Data stored in MySQL database
- Backend integration with PHP
- Responsive and user-friendly interface

## Technologies Used

- Frontend: HTML, CSS, JavaScript 
- Backend: PHP
- Database: MySQL

## Setup Instructions

1. **Clone the repository:**

   ```bash
   git clone https://github.com/MicaellaSalili/project-registration-form.git
   ```

2. **Place the project in the XAMPP `htdocs` directory:**

   ```
   C:\xampp\htdocs\project-registration-form
   ```

3. **Set up the database:**

   - Open [phpMyAdmin](http://localhost/phpmyadmin).
   - Create a new database named `reservation_db`.
   - Import the `database.sql` file from the project folder:
     - Go to the `Import` tab in phpMyAdmin.
     - Select the `database.sql` file.
     - Click **Go** to execute the SQL script.

4. **Configure the backend:**

   - Open the `db.php` file in the project folder:
     ```
     C:\xampp\htdocs\project-registration-form\db.php
     ```
   - Verify the database connection settings:
     ```php
     $host = 'localhost';
     $dbname = 'reservation_db'; // Database name
     $username = 'root'; // Default XAMPP username
     $password = ''; // Default XAMPP password (empty)
     ```
   - Save the file.

5. **Run the backend:**

   - Start XAMPP and ensure the **Apache** and **MySQL** services are running.
   - Test the backend by navigating to:
     ```
     http://localhost/project-registration-form/db.php
     ```

6. **Run the frontend:**

   - Open your browser and navigate to:
     ```
     http://localhost/project-registration-form/index.html
     ```

7. **Submit a reservation:**

   - Fill out the form fields:
     - First Name
     - Last Name
     - Date and Time
     - Email
     - Phone Number
     - Seats Booked
     - Notes (optional)
     - Payment Method and Details (based on the selected method)
   - Click **Submit**.

8. **Verify data submission:**

   - Check the database:
     - Open [phpMyAdmin](http://localhost/phpmyadmin).
     - Select the `reservation_db` database.
     - Open the `bookings` table to verify the submitted data.
   - Check the `log.txt` file:
     - Open the `log.txt` file in the project folder to view logged POST data:
       ```
       C:\xampp\htdocs\project-registration-form\log.txt
       ```

## Troubleshooting

1. **Database Connection Error:**
   - Ensure the database name, username, and password in `db.php` are correct.
   - Verify that the MySQL service is running in XAMPP.

2. **Form Not Submitting:**
   - Check the browser console for errors.
   - Ensure the backend (`db.php`) is accessible via `http://localhost/project-registration-form/db.php`.

3. **CORS Issues:**
   - Ensure the `db.php` file includes the following headers:
     ```php
     header('Access-Control-Allow-Origin: *');
     header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
     header('Access-Control-Allow-Headers: Content-Type');
     ```

## Project Structure

```
project-registration-form/
├── index.html          # Frontend HTML file
├── style.css           # Stylesheet for the frontend
├── script.js           # JavaScript for form validation and dynamic behavior
├── db.php              # Backend PHP file for handling requests
├── database.sql        # SQL script to set up the database
├── log.txt             # Log file for debugging
```