CREATE DATABASE reservation_db;

USE reservation_db;

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    date_time DATETIME NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    seats_booked ENUM('solo', 'duo', 'grouped') NOT NULL,
    notes TEXT
);
