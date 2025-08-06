<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "hotel_management_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get booking ID from URL
$bookingId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$bookingId) {
    header("Location: admin.php");
    exit();
}

// Fetch booking details
$sql = "SELECT * FROM bookings WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$bookingId]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    header("Location: admin.php");
    exit();
}

$errors = [];
$successMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $roomType = $_POST['room_type'] ?? '';
    $guests = (int)($_POST['guests'] ?? 0);
    $checkIn = $_POST['check_in'] ?? '';
    $checkOut = $_POST['check_out'] ?? '';
    $specialRequests = trim($_POST['special_requests'] ?? '');
    $arrivalTime = trim($_POST['arrival_time'] ?? '');
    $status = $_POST['status'] ?? '';
    $adminNotes = trim($_POST['admin_notes'] ?? '');
    
    // Validation
    if (empty($firstName)) $errors[] = "First name is required";
    if (empty($lastName)) $errors[] = "Last name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($roomType)) $errors[] = "Room type is required";
    if ($guests <= 0) $errors[] = "Number of guests must be greater than 0";
    if (empty($checkIn)) $errors[] = "Check-in date is required";
    if (empty($checkOut)) $errors[] = "Check-out date is required";
    if (empty($status)) $errors[] = "Status is required";
    
    // Validate dates
    if ($checkIn && $checkOut) {
        $checkInDate = new DateTime($checkIn);
        $checkOutDate = new DateTime($checkOut);
        
        if ($checkOutDate <= $checkInDate) {
            $errors[] = "Check-out date must be after check-in date";
        }
    }
    
    // Calculate pricing if no errors
    if (empty($errors)) {
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
        
        try {
            // Update booking
            $updateSql = "UPDATE bookings SET 
                first_name = ?, last_name = ?, email = ?, phone = ?, address = ?,
                room_type = ?, guests = ?, check_in = ?, check_out = ?, special_requests = ?,
                arrival_time = ?, status = ?, admin_notes = ?, room_rate = ?, nights = ?,
                subtotal = ?, tax = ?, total_amount = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";
            
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                $firstName, $lastName, $email, $phone, $address,
                $roomType, $guests, $checkIn, $checkOut, $specialRequests,
                $arrivalTime, $status, $adminNotes, $roomRate, $nights,
                $subtotal, $tax, $total, $bookingId
            ]);
            
            $successMessage = "Booking updated successfully!";
            
            // Refresh booking data
            $stmt->execute([$bookingId]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            
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
    <title>Update Booking - Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="nav-container">
                <h1 class="logo">LUXURY HOTEL RWANDA - Admin</h1>
                <div class="admin-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="admin_logout.php" class="logout-link">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="update-booking-section">
            <div class="container">
                <div class="page-header">
                    <h2>Update Booking</h2>
                    <div class="header-actions">
                        <a href="admin.php" class="btn-secondary">← Back to Dashboard</a>
                        <a href="view_booking.php?id=<?php echo $booking['id']; ?>" class="btn-secondary">View Booking</a>
                    </div>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($successMessage): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="update-booking-form">
                    <div class="form-grid">
                        <!-- Guest Information -->
                        <div class="form-section">
                            <h3>Guest Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">First Name *</label>
                                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($booking['first_name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name *</label>
                                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($booking['last_name']); ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($booking['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone *</label>
                                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($booking['phone']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address *</label>
                                <textarea id="address" name="address" rows="3" required><?php echo htmlspecialchars($booking['address']); ?></textarea>
                            </div>
                        </div>

                        <!-- Room Information -->
                        <div class="form-section">
                            <h3>Room Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="room_type">Room Type *</label>
                                    <select id="room_type" name="room_type" required>
                                        <option value="">Select Room Type</option>
                                        <option value="standard" <?php echo $booking['room_type'] === 'standard' ? 'selected' : ''; ?>>Standard Room</option>
                                        <option value="deluxe" <?php echo $booking['room_type'] === 'deluxe' ? 'selected' : ''; ?>>Deluxe Room</option>
                                        <option value="suite" <?php echo $booking['room_type'] === 'suite' ? 'selected' : ''; ?>>Executive Suite</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="guests">Number of Guests *</label>
                                    <select id="guests" name="guests" required>
                                        <option value="">Select</option>
                                        <option value="1" <?php echo $booking['guests'] == 1 ? 'selected' : ''; ?>>1 Guest</option>
                                        <option value="2" <?php echo $booking['guests'] == 2 ? 'selected' : ''; ?>>2 Guests</option>
                                        <option value="3" <?php echo $booking['guests'] == 3 ? 'selected' : ''; ?>>3 Guests</option>
                                        <option value="4" <?php echo $booking['guests'] == 4 ? 'selected' : ''; ?>>4 Guests</option>
                                        <option value="5" <?php echo $booking['guests'] >= 5 ? 'selected' : ''; ?>>5+ Guests</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="check_in">Check-in Date *</label>
                                    <input type="date" id="check_in" name="check_in" value="<?php echo $booking['check_in']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="check_out">Check-out Date *</label>
                                    <input type="date" id="check_out" name="check_out" value="<?php echo $booking['check_out']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="form-section">
                            <h3>Additional Information</h3>
                            <div class="form-group">
                                <label for="special_requests">Special Requests</label>
                                <textarea id="special_requests" name="special_requests" rows="3"><?php echo htmlspecialchars($booking['special_requests'] ?? ''); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="arrival_time">Expected Arrival Time</label>
                                <input type="text" id="arrival_time" name="arrival_time" value="<?php echo htmlspecialchars($booking['arrival_time'] ?? ''); ?>" placeholder="e.g., 2:00 PM">
                            </div>
                        </div>

                        <!-- Admin Information -->
                        <div class="form-section">
                            <h3>Admin Information</h3>
                            <div class="form-group">
                                <label for="status">Booking Status *</label>
                                <select id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="admin_notes">Admin Notes</label>
                                <textarea id="admin_notes" name="admin_notes" rows="3" placeholder="Add any admin notes..."><?php echo htmlspecialchars($booking['admin_notes'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Update Booking</button>
                        <a href="view_booking.php?id=<?php echo $booking['id']; ?>" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Luxury Hotel Rwanda. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 