-- Create the database
CREATE DATABASE IF NOT EXISTS bookings;

-- Use the created database
USE bookings;

-- Create the bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    datetime DATETIME NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    hours INT NOT NULL,
    student_status ENUM('yes', 'no') NOT NULL,
    notes TEXT,
    total_price DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('Pending', 'Completed', 'Failed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the payment_details table
CREATE TABLE IF NOT EXISTS payment_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    mobile VARCHAR(15) NULL,
    card_number VARCHAR(16) NULL,
    expiry_date VARCHAR(7) NULL, -- Format: YYYY-MM
    cvv VARCHAR(4) NULL,
    card_type ENUM('credit', 'debit') NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);
