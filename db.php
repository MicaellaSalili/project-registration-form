<?php
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type'); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle CORS preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); 
    exit;
}

// Database configuration
$host = 'localhost';
$dbname = 'reservation_db';
$username = 'root';
$password = '';

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the bookings table if it doesn't exist
    $createBookingsTable = "
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
        )
    ";
    $pdo->exec($createBookingsTable);

    // Create the payment_details table if it doesn't exist
    $createPaymentDetailsTable = "
        CREATE TABLE IF NOT EXISTS payment_details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            booking_id INT NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            mobile VARCHAR(15) NULL,
            card_number VARCHAR(16) NULL,
            expiry_date VARCHAR(7) NULL,
            cvv VARCHAR(4) NULL,
            card_type ENUM('credit', 'debit') NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
        )
    ";
    $pdo->exec($createPaymentDetailsTable);

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Read JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Log received data for debugging
        error_log("Received data: " . print_r($data, true));

        // Validate required booking fields
        if (
            !isset($data['firstName']) ||
            !isset($data['lastName']) ||
            !isset($data['datetime']) ||
            !isset($data['email']) ||
            !isset($data['phone']) ||
            !isset($data['hours']) ||
            !isset($data['studentStatus']) ||
            !isset($data['totalPrice']) ||
            !isset($data['payment_method'])
        ) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required booking fields']);
            exit;
        }

        // Validate payment-specific fields
        if (in_array($data['payment_method'], ['gcash', 'maya', 'seabank']) && !isset($data['mobile'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mobile number is required for selected payment method']);
            exit;
        }
        if ($data['payment_method'] === 'card' && (!isset($data['card_number']) || !isset($data['expiry_date']) || !isset($data['cvv']))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Card details are required for card payment']);
            exit;
        }

        // Start a transaction
        $pdo->beginTransaction();

        try {
            // Insert into bookings table
            $stmt = $pdo->prepare("
                INSERT INTO bookings (
                    first_name, last_name, datetime, email, phone, hours, student_status, notes, 
                    total_price, payment_method
                ) VALUES (
                    :first_name, :last_name, :datetime, :email, :phone, :hours, :student_status, :notes, 
                    :total_price, :payment_method
                )
            ");
            $stmt->execute([
                ':first_name' => $data['firstName'],
                ':last_name' => $data['lastName'],
                ':datetime' => $data['datetime'],
                ':email' => $data['email'],
                ':phone' => $data['phone'],
                ':hours' => $data['hours'],
                ':student_status' => $data['studentStatus'],
                ':notes' => $data['notes'] ?? null,
                ':total_price' => $data['totalPrice'],
                ':payment_method' => $data['payment_method']
            ]);

            // Get the last inserted booking ID
            $bookingId = $pdo->lastInsertId();

            // Insert into payment_details table
            $stmt = $pdo->prepare("
                INSERT INTO payment_details (
                    booking_id, payment_method, mobile, card_number, expiry_date, cvv, card_type
                ) VALUES (
                    :booking_id, :payment_method, :mobile, :card_number, :expiry_date, :cvv, :card_type
                )
            ");
            $stmt->execute([
                ':booking_id' => $bookingId,
                ':payment_method' => $data['payment_method'],
                ':mobile' => $data['mobile'] ?? null,
                ':card_number' => $data['card_number'] ?? null,
                ':expiry_date' => $data['expiry_date'] ?? null,
                ':cvv' => $data['cvv'] ?? null,
                ':card_type' => $data['card_type'] ?? null
            ]);

            // Commit the transaction
            $pdo->commit();

            // Respond with success
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Booking and payment details successfully saved']);
        } catch (Exception $e) {
            // Rollback the transaction on error
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Transaction error: ' . $e->getMessage()]);
            exit;
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// Close the database connection
$pdo = null;
?>