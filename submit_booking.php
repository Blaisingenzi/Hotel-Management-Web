<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_management_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $roomType = $_POST['room_type'] ?? '';
    $guests = $_POST['guests'] ?? '';
    $checkIn = $_POST['check_in'] ?? '';
    $checkOut = $_POST['check_out'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $specialRequests = $_POST['special_requests'] ?? '';
    $arrivalTime = $_POST['arrival_time'] ?? '';
    
    // Validate required fields
    $errors = [];
    
    if (empty($roomType)) $errors[] = "Room type is required";
    if (empty($guests)) $errors[] = "Number of guests is required";
    if (empty($checkIn)) $errors[] = "Check-in date is required";
    if (empty($checkOut)) $errors[] = "Check-out date is required";
    if (empty($firstName)) $errors[] = "First name is required";
    if (empty($lastName)) $errors[] = "Last name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($address)) $errors[] = "Address is required";
    
    // Validate dates
    if ($checkIn && $checkOut) {
        $checkInDate = new DateTime($checkIn);
        $checkOutDate = new DateTime($checkOut);
        $today = new DateTime();
        
        if ($checkInDate < $today) {
            $errors[] = "Check-in date cannot be in the past";
        }
        
        if ($checkOutDate <= $checkInDate) {
            $errors[] = "Check-out date must be after check-in date";
        }
    }
    
    // Calculate pricing
    $roomPrices = [
        'standard' => 120000,
        'deluxe' => 180000,
        'suite' => 280000
    ];
    
    $roomRate = $roomPrices[$roomType] ?? 0;
    $nights = 0;
    $subtotal = 0;
    $tax = 0;
    $total = 0;
    
    if ($checkIn && $checkOut) {
        $checkInDate = new DateTime($checkIn);
        $checkOutDate = new DateTime($checkOut);
        $nights = $checkInDate->diff($checkOutDate)->days;
        $subtotal = $roomRate * $nights;
        $tax = $subtotal * 0.18; // 18% tax
        $total = $subtotal + $tax;
    }
    
    // Generate booking ID
    $bookingId = 'HOTEL' . date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    if (empty($errors)) {
        try {
            // Insert booking into database
            $sql = "INSERT INTO bookings (
                booking_id, first_name, last_name, email, phone, address, 
                room_type, guests, check_in, check_out, special_requests, 
                arrival_time, room_rate, nights, subtotal, tax, total_amount, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $bookingId, $firstName, $lastName, $email, $phone, $address,
                $roomType, $guests, $checkIn, $checkOut, $specialRequests,
                $arrivalTime, $roomRate, $nights, $subtotal, $tax, $total
            ]);
            
            // Redirect to success page
            header("Location: booking_success.php?booking_id=" . $bookingId);
            exit();
            
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Submission - Luxury Hotel Rwanda</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 20px;
        }
        
        .error-form {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        
        .error-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .error-header h1 {
            color: #dc3545;
            margin-bottom: 0.5rem;
        }
        
        .error-list {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .error-list ul {
            margin: 0;
            padding-left: 1.5rem;
        }
        
        .back-btn {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s ease;
            text-align: center;
            width: 100%;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-form">
            <div class="error-header">
                <h1>Booking Error</h1>
                <p>Please correct the following errors:</p>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <a href="booking.php" class="back-btn">← Back to Booking Form</a>
        </div>
    </div>
</body>
</html> 