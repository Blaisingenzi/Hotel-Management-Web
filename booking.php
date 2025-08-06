<?php
// Get room type and details from URL parameters
$roomType = isset($_GET['room_type']) ? $_GET['room_type'] : '';
$roomNumber = isset($_GET['room_number']) ? $_GET['room_number'] : '';
$roomPrice = isset($_GET['price']) ? (int)$_GET['price'] : 0;

$roomTypes = [
    'standard' => ['name' => 'Standard Room', 'price' => 120000],
    'deluxe' => ['name' => 'Deluxe Room', 'price' => 180000],
    'suite' => ['name' => 'Executive Suite', 'price' => 280000]
];

// If room type is provided via URL, use that price
if ($roomType && $roomPrice > 0) {
    $roomTypes[$roomType]['price'] = $roomPrice;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Stay - Luxury Hotel Rwanda</title>
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
                    <li><a href="rooms.php">Rooms</a></li>
                    <li><a href="booking.php" class="active">Book Now</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="admin_login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="booking-section">
            <div class="container">
                <div class="booking-header">
                    <h2>Book Your Stay</h2>
                    <p>Complete the form below to reserve your perfect room</p>
                </div>

                <div class="booking-form-container">
                    <form id="bookingForm" action="submit_booking.php" method="POST">
                        <?php if ($roomNumber): ?>
                        <input type="hidden" name="room_number" value="<?php echo htmlspecialchars($roomNumber); ?>">
                        <?php endif; ?>
                        <!-- Room Selection -->
                        <div class="form-section">
                            <h3>Room Details</h3>
                            <?php if ($roomNumber): ?>
                            <div class="selected-room-info">
                                <p><strong>Selected Room:</strong> Room <?php echo htmlspecialchars($roomNumber); ?></p>
                            </div>
                            <?php endif; ?>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="room_type">Room Type *</label>
                                    <select id="room_type" name="room_type" required>
                                        <option value="">Select Room Type</option>
                                        <?php foreach ($roomTypes as $type => $details): ?>
                                            <option value="<?php echo $type; ?>" <?php echo ($roomType === $type) ? 'selected' : ''; ?>>
                                                <?php echo $details['name']; ?> - RWF <?php echo number_format($details['price']); ?>/night
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div id="roomTypeError" class="error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="guests">Number of Guests *</label>
                                    <select id="guests" name="guests" required>
                                        <option value="">Select</option>
                                        <option value="1">1 Guest</option>
                                        <option value="2">2 Guests</option>
                                        <option value="3">3 Guests</option>
                                        <option value="4">4 Guests</option>
                                        <option value="5+">5+ Guests</option>
                                    </select>
                                    <div id="guestsError" class="error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="form-section">
                            <h3>Stay Dates</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="check_in">Check-in Date *</label>
                                    <input type="date" id="check_in" name="check_in" required>
                                    <div id="checkInError" class="error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="check_out">Check-out Date *</label>
                                    <input type="date" id="check_out" name="check_out" required>
                                    <div id="checkOutError" class="error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Guest Information -->
                        <div class="form-section">
                            <h3>Guest Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">First Name *</label>
                                    <input type="text" id="first_name" name="first_name" required>
                                    <div id="firstNameError" class="error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name *</label>
                                    <input type="text" id="last_name" name="last_name" required>
                                    <div id="lastNameError" class="error"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" required>
                                    <div id="emailError" class="error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" required>
                                    <div id="phoneError" class="error"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address *</label>
                                <textarea id="address" name="address" rows="3" required></textarea>
                                <div id="addressError" class="error"></div>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div class="form-section">
                            <h3>Special Requests</h3>
                                                    <div class="form-group">
                            <label for="special_requests">Special Requests (Optional)</label>
                            <textarea id="special_requests" name="special_requests" rows="3" placeholder="Any special requests or preferences..."></textarea>
                        </div>
                        </div>



                        <!-- Terms and Conditions -->
                        <div class="form-section">
                            <div class="form-group checkbox-group">
                                <label>
                                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                                    I agree to the terms and conditions * <a href="terms.html" target="_blank">(Read Terms)</a>
                                </label>
                                <div id="agreeTermsError" class="error"></div>
                            </div>
                            <div class="form-group checkbox-group">
                                <label>
                                    <input type="checkbox" id="agree_cancellation" name="agree_cancellation" required>
                                    I understand the cancellation policy * <a href="cancellation.html" target="_blank">(Read Policy)</a>
                                </label>
                                <div id="agreeCancellationError" class="error"></div>
                            </div>
                        </div>

                        <!-- Price Summary -->
                        <div class="price-summary">
                            <h3>Price Summary</h3>
                            <div class="price-details">
                                <div class="price-row">
                                    <span>Room Rate (per night):</span>
                                    <span id="roomRate">RWF 0</span>
                                </div>
                                <div class="price-row">
                                    <span>Number of Nights:</span>
                                    <span id="nights">0</span>
                                </div>
                                <div class="price-row">
                                    <span>Subtotal:</span>
                                    <span id="subtotal">RWF 0</span>
                                </div>
                                <div class="price-row">
                                    <span>Tax (18%):</span>
                                    <span id="tax">RWF 0</span>
                                </div>
                                <div class="price-row total">
                                    <span>Total:</span>
                                    <span id="total">RWF 0</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">Confirm Booking</button>
                    </form>
                </div>

                <!-- Booking Information -->
                <div class="booking-info">
                    <h3>Booking Information</h3>
                    <div class="info-steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <h4>Complete Form</h4>
                            <p>Fill out the booking form with your details</p>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <h4>Confirmation</h4>
                            <p>Receive booking confirmation via email</p>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <h4>Check-in</h4>
                            <p>Welcome to Luxury Hotel Rwanda!</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Luxury Hotel Rwanda</h3>
                    <p>Experience the perfect blend of luxury, comfort, and Rwandan hospitality.</p>
                </div>
                <div class="footer-section">
                    <h4>Contact Info</h4>
                    <p>📍 Kigali, Rwanda</p>
                    <p>📞 +250 788 123 456</p>
                    <p>✉️ info@luxuryhotelrwanda.com</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="rooms.php">Rooms</a></li>
                        <li><a href="booking.php">Book Now</a></li>
                        <li><a href="admin_login.php">Admin</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Luxury Hotel Rwanda. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html> 