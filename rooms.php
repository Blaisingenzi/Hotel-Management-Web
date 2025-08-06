<?php
session_start();

// Database connection
$host = 'localhost';
$db_name = 'hotel_management_db';
$db_user = 'root';
$db_password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch rooms from database
$stmt = $pdo->query("SELECT * FROM rooms ORDER BY room_type, room_number");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group rooms by type
$roomTypes = [];
foreach ($rooms as $room) {
    $type = $room['room_type'];
    if (!isset($roomTypes[$type])) {
        $roomTypes[$type] = [];
    }
    $roomTypes[$type][] = $room;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms - Hotel Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="nav-container">
                <h1 class="logo">LUXURY HOTEL RWANDA</h1>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="rooms.php" class="active">Rooms</a></li>
                    <li><a href="booking.php">Book Now</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="admin_login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Rooms Hero -->
    <section class="rooms-hero">
        <div class="container">
            <h1>Our Rooms</h1>
            <p>Discover our carefully designed rooms and suites, each offering comfort, luxury, and exceptional service for your perfect stay.</p>
        </div>
    </section>

    <?php foreach ($roomTypes as $type => $typeRooms): ?>
    <section class="room-type-section">
        <div class="container">
            <div class="room-type-header">
                <h2><?php echo ucfirst($type); ?> Rooms</h2>
                <p>
                    <?php if ($type === 'standard'): ?>
                        Comfortable and cozy rooms perfect for business travelers and families. Features modern amenities and a peaceful atmosphere.
                    <?php elseif ($type === 'deluxe'): ?>
                        Spacious rooms with premium amenities and stunning views. Ideal for those seeking extra comfort and luxury.
                    <?php elseif ($type === 'suite'): ?>
                        Our most luxurious accommodation featuring separate living areas, premium services, and exclusive amenities.
                    <?php endif; ?>
                </p>
            </div>

            <div class="room-type-info">
                <div class="info-grid">
                    <div class="info-item">
                        <h4>Starting Price</h4>
                        <p>RWF <?php echo number_format($typeRooms[0]['price_per_night']); ?></p>
                    </div>
                    <div class="info-item">
                        <h4>Capacity</h4>
                        <p>Up to <?php echo $typeRooms[0]['capacity']; ?> guests</p>
                    </div>
                    <div class="info-item">
                        <h4>Available Rooms</h4>
                        <p><?php echo count($typeRooms); ?> rooms</p>
                    </div>
                    <div class="info-item">
                        <h4>Floor</h4>
                        <p>Floor <?php echo $typeRooms[0]['floor_number']; ?></p>
                    </div>
                </div>
            </div>

            <div class="rooms-grid">
                <?php foreach ($typeRooms as $room): ?>
                <div class="room-card">
                    <div class="room-image <?php echo $type; ?>">
                        <div class="room-status <?php echo ($room['status'] ?? 'available') === 'available' ? 'status-available' : 'status-occupied'; ?>">
                            <?php echo ($room['status'] ?? 'available') === 'available' ? 'Available' : 'Occupied'; ?>
                        </div>
                    </div>
                    <div class="room-details">
                        <div class="room-number">Room <?php echo htmlspecialchars($room['room_number']); ?></div>
                        <div class="room-price">RWF <?php echo number_format($room['price_per_night']); ?> / night</div>
                        <ul class="room-amenities">
                            <?php 
                            $amenities = explode(', ', $room['amenities']);
                            foreach ($amenities as $amenity): 
                            ?>
                            <li><?php echo htmlspecialchars(trim($amenity)); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="book-room-btn" 
                                onclick="bookRoom('<?php echo $room['room_number']; ?>', '<?php echo $type; ?>', <?php echo $room['price_per_night']; ?>)"
                                <?php echo ($room['status'] ?? 'available') === 'occupied' ? 'disabled' : ''; ?>>
                            <?php echo ($room['status'] ?? 'available') === 'available' ? 'Book This Room' : 'Currently Occupied'; ?>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endforeach; ?>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h3>Ready to Book Your Stay?</h3>
            <p>Experience luxury and comfort at Hotel Rwanda. Book your room today and enjoy our world-class service.</p>
            <a href="booking.php" class="btn btn-primary">Book Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Hotel Rwanda</h3>
                    <p>Your home away from home in the heart of Rwanda.</p>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Phone: +250 788 123 456</p>
                    <p>Email: info@hotelrwanda.com</p>
                </div>
                <div class="footer-section">
                    <h4>Address</h4>
                    <p>123 Hotel Street<br>Kigali, Rwanda</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Hotel Rwanda. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function bookRoom(roomNumber, roomType, price) {
            // Redirect to booking page with room pre-selected
            window.location.href = `booking.php?room_type=${roomType}&room_number=${roomNumber}&price=${price}`;
        }
    </script>
    <script src="script.js"></script>
</body>
</html> 