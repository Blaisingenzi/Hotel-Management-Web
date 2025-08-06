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

// Get bookings
$sql = "SELECT * FROM bookings ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get contact messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$contactMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get newsletter subscribers
$sql = "SELECT * FROM contact_messages WHERE newsletter_subscription = 1 ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$newsletterSubscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$totalBookings = count($bookings);
$totalMessages = count($contactMessages);
$totalSubscribers = count($newsletterSubscribers);
$pendingBookings = 0;
$confirmedBookings = 0;
$cancelledBookings = 0;
$totalRevenue = 0;

foreach ($bookings as $booking) {
    switch ($booking['status']) {
        case 'pending':
            $pendingBookings++;
            break;
        case 'confirmed':
            $confirmedBookings++;
            $totalRevenue += $booking['total_amount'];
            break;
        case 'cancelled':
            $cancelledBookings++;
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Luxury Hotel Rwanda</title>
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
        <section class="admin-dashboard">
            <div class="container">
                <div class="dashboard-header">
                    <h2>Admin Dashboard</h2>
                    <p>Manage hotel bookings and operations</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <h3>Total Bookings</h3>
                            <span class="stat-number"><?php echo $totalBookings; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">💰</div>
                        <div class="stat-content">
                            <h3>Total Revenue</h3>
                            <span class="stat-number">RWF <?php echo number_format($totalRevenue); ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">⏳</div>
                        <div class="stat-content">
                            <h3>Pending</h3>
                            <span class="stat-number"><?php echo $pendingBookings; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">✅</div>
                        <div class="stat-content">
                            <h3>Confirmed</h3>
                            <span class="stat-number"><?php echo $confirmedBookings; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">❌</div>
                        <div class="stat-content">
                            <h3>Cancelled</h3>
                            <span class="stat-number"><?php echo $cancelledBookings; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📧</div>
                        <div class="stat-content">
                            <h3>Contact Messages</h3>
                            <span class="stat-number"><?php echo $totalMessages; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📬</div>
                        <div class="stat-content">
                            <h3>Newsletter Subscribers</h3>
                            <span class="stat-number"><?php echo $totalSubscribers; ?></span>
                        </div>
                    </div>
                </div>

                <div class="bookings-section">
                    <h3>Recent Bookings</h3>
                    
                    <?php if (empty($bookings)): ?>
                        <div class="no-bookings">
                            <p>No bookings found.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Guest Name</th>
                                        <th>Room Type</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Guests</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                            <td><?php echo ucfirst(htmlspecialchars($booking['room_type'])); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($booking['check_in'])); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($booking['check_out'])); ?></td>
                                            <td><?php echo htmlspecialchars($booking['guests']); ?></td>
                                            <td>RWF <?php echo number_format($booking['total_amount']); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $booking['status']; ?>">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="view_booking.php?id=<?php echo $booking['id']; ?>" class="btn-view">View</a>
                                                <a href="update_booking.php?id=<?php echo $booking['id']; ?>" class="btn-edit">Update</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="contact-messages-section">
                    <h3>Recent Contact Messages</h3>
                    
                    <?php if (empty($contactMessages)): ?>
                        <div class="no-messages">
                            <p>No contact messages found.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($contactMessages, 0, 5) as $message): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($message['name']); ?></td>
                                            <td><?php echo htmlspecialchars($message['email']); ?></td>
                                            <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($message['message'], 0, 50)) . (strlen($message['message']) > 50 ? '...' : ''); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($message['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (count($contactMessages) > 5): ?>
                            <div class="view-all-messages">
                                <a href="view_messages.php" class="btn-secondary">View All Messages</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="newsletter-subscribers-section">
                    <h3>Newsletter Subscribers</h3>
                    
                    <?php if (empty($newsletterSubscribers)): ?>
                        <div class="no-subscribers">
                            <p>No newsletter subscribers found.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Subject</th>
                                        <th>Subscription Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($newsletterSubscribers, 0, 10) as $subscriber): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($subscriber['name']); ?></td>
                                            <td><?php echo htmlspecialchars($subscriber['email']); ?></td>
                                            <td><?php echo htmlspecialchars($subscriber['phone'] ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($subscriber['subject']); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($subscriber['created_at'])); ?></td>
                                            <td>
                                                <a href="mailto:<?php echo htmlspecialchars($subscriber['email']); ?>" class="btn-view">Email</a>
                                                <a href="view_messages.php?search=<?php echo urlencode($subscriber['email']); ?>" class="btn-edit">View Messages</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (count($newsletterSubscribers) > 10): ?>
                            <div class="view-all-subscribers">
                                <a href="view_subscribers.php" class="btn-secondary">View All Subscribers</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="admin-actions">
                    <h3>Quick Actions</h3>
                    <div class="action-buttons">
                        <a href="view_messages.php" class="btn-secondary">View All Messages</a>
                        <a href="view_subscribers.php" class="btn-secondary">View All Subscribers</a>
                        <a href="index.php" class="btn-secondary">View Website</a>
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