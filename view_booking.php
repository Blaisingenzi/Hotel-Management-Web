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

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = $_POST['status'];
    $adminNotes = $_POST['admin_notes'] ?? '';
    
    $updateSql = "UPDATE bookings SET status = ?, admin_notes = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([$newStatus, $adminNotes, $bookingId]);
    
    // Refresh booking data
    $stmt->execute([$bookingId]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $successMessage = "Booking status updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booking - Admin Dashboard</title>
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
        <section class="view-booking-section">
            <div class="container">
                <div class="page-header">
                    <h2>View Booking Details</h2>
                    <div class="header-actions">
                        <a href="admin.php" class="btn-secondary">← Back to Dashboard</a>
                        <a href="update_booking.php?id=<?php echo $booking['id']; ?>" class="btn-primary">Edit Booking</a>
                    </div>
                </div>

                <?php if (isset($successMessage)): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>

                <div class="booking-details-grid">
                    <!-- Booking Information -->
                    <div class="detail-card">
                        <h3>Booking Information</h3>
                        <div class="detail-row">
                            <span class="label">Booking ID:</span>
                            <span class="value"><?php echo htmlspecialchars($booking['booking_id']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Status:</span>
                            <span class="value">
                                <span class="status-badge status-<?php echo $booking['status']; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Created:</span>
                            <span class="value"><?php echo date('F j, Y \a\t g:i A', strtotime($booking['created_at'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Last Updated:</span>
                            <span class="value"><?php echo date('F j, Y \a\t g:i A', strtotime($booking['updated_at'])); ?></span>
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div class="detail-card">
                        <h3>Guest Information</h3>
                        <div class="detail-row">
                            <span class="label">Name:</span>
                            <span class="value"><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Email:</span>
                            <span class="value"><?php echo htmlspecialchars($booking['email']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Phone:</span>
                            <span class="value"><?php echo htmlspecialchars($booking['phone']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Address:</span>
                            <span class="value"><?php echo htmlspecialchars($booking['address']); ?></span>
                        </div>
                    </div>

                    <!-- Room Information -->
                    <div class="detail-card">
                        <h3>Room Information</h3>
                        <div class="detail-row">
                            <span class="label">Room Type:</span>
                            <span class="value"><?php echo ucfirst(htmlspecialchars($booking['room_type'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Number of Guests:</span>
                            <span class="value"><?php echo htmlspecialchars($booking['guests']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Check-in Date:</span>
                            <span class="value"><?php echo date('F j, Y', strtotime($booking['check_in'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Check-out Date:</span>
                            <span class="value"><?php echo date('F j, Y', strtotime($booking['check_out'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Number of Nights:</span>
                            <span class="value"><?php echo htmlspecialchars($booking['nights']); ?></span>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="detail-card">
                        <h3>Pricing Information</h3>
                        <div class="detail-row">
                            <span class="label">Room Rate (per night):</span>
                            <span class="value">RWF <?php echo number_format($booking['room_rate']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Subtotal:</span>
                            <span class="value">RWF <?php echo number_format($booking['subtotal']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Tax (18%):</span>
                            <span class="value">RWF <?php echo number_format($booking['tax']); ?></span>
                        </div>
                        <div class="detail-row total-row">
                            <span class="label">Total Amount:</span>
                            <span class="value">RWF <?php echo number_format($booking['total_amount']); ?></span>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="detail-card">
                        <h3>Additional Information</h3>
                        <div class="detail-row">
                            <span class="label">Special Requests:</span>
                            <span class="value">
                                <?php echo $booking['special_requests'] ? htmlspecialchars($booking['special_requests']) : 'None'; ?>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Arrival Time:</span>
                            <span class="value">
                                <?php echo $booking['arrival_time'] ? htmlspecialchars($booking['arrival_time']) : 'Not specified'; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    <div class="detail-card">
                        <h3>Admin Actions</h3>
                        <form method="POST" class="status-update-form">
                            <div class="form-group">
                                <label for="status">Update Status:</label>
                                <select id="status" name="status" required>
                                    <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="admin_notes">Admin Notes:</label>
                                <textarea id="admin_notes" name="admin_notes" rows="3" placeholder="Add any notes about this booking..."><?php echo htmlspecialchars($booking['admin_notes'] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" name="update_status" class="btn-primary">Update Status</button>
                        </form>
                    </div>
                </div>
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