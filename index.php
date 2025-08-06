<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Hotel Rwanda - Premium Accommodation</title>
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
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="rooms.php">Rooms</a></li>
                    <li><a href="booking.php">Book Now</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="admin_login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero Section with Auto Carousel -->
        <section class="hero-carousel">
            <div class="carousel-container">
                <div class="carousel-slide active">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h1>Welcome to Luxury Hotel Rwanda</h1>
                            <h2>Experience Unparalleled Comfort</h2>
                            <p>Discover the perfect blend of luxury, comfort, and Rwandan hospitality. Book your stay with us and experience world-class accommodation in the heart of Kigali.</p>
                            <div class="hero-buttons">
                                <a href="booking.php" class="btn-primary">Book Now</a>
                                <a href="rooms.php" class="btn-outline">View Rooms</a>
                            </div>
                        </div>
                        <div class="hero-image">
                            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Luxury Hotel">
                        </div>
                    </div>
                </div>
                
                <div class="carousel-slide">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h1>Premium Rooms & Suites</h1>
                            <h2>Luxury Redefined</h2>
                            <p>From elegant standard rooms to opulent presidential suites, we offer accommodations that exceed your expectations with modern amenities and stunning views.</p>
                            <div class="hero-buttons">
                                <a href="rooms.php" class="btn-primary">Explore Rooms</a>
                                <a href="booking.php" class="btn-outline">Check Availability</a>
                            </div>
                        </div>
                        <div class="hero-image">
                            <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Luxury Room">
                        </div>
                    </div>
                </div>
                
                <div class="carousel-slide">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h1>World-Class Amenities</h1>
                            <h2>Everything You Need</h2>
                            <p>Enjoy our spa, swimming pool, fine dining restaurants, conference facilities, and 24/7 room service. Your comfort is our priority.</p>
                            <div class="hero-buttons">
                                <a href="rooms.php" class="btn-primary">View Rooms</a>
                                <a href="booking.php" class="btn-outline">Book Now</a>
                            </div>
                        </div>
                        <div class="hero-image">
                            <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Hotel Amenities">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="carousel-dots">
                <span class="dot active" data-slide="0"></span>
                <span class="dot" data-slide="1"></span>
                <span class="dot" data-slide="2"></span>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">🏨</div>
                        <div class="stat-content">
                            <h3>150+</h3>
                            <p>Luxury Rooms</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">⭐</div>
                        <div class="stat-content">
                            <h3>5-Star</h3>
                            <p>Rated Hotel</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <h3>10,000+</h3>
                            <p>Happy Guests</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🏆</div>
                        <div class="stat-content">
                            <h3>15+</h3>
                            <p>Awards Won</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Room Types Section -->
        <section id="rooms" class="rooms-section">
            <div class="container">
                <div class="section-header">
                    <h2>Our Room Types</h2>
                    <p>Choose from our selection of luxurious accommodations</p>
                </div>
                <div class="rooms-grid">
                    <div class="room-card">
                        <div class="room-image">
                            <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Standard Room">
                        </div>
                        <div class="room-content">
                            <h3>Standard Room</h3>
                            <p>Comfortable and elegant rooms with modern amenities</p>
                            <ul>
                                <li>King or Twin beds</li>
                                <li>City view</li>
                                <li>Free WiFi</li>
                                <li>Room service</li>
                            </ul>
                            <div class="room-price">
                                <span class="price">RWF 120,000</span>
                                <span class="per-night">per night</span>
                            </div>
                            <a href="booking.php?room=standard" class="room-btn">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="room-card featured">
                        <div class="room-image">
                            <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Deluxe Room">
                        </div>
                        <div class="room-content">
                            <h3>Deluxe Room</h3>
                            <p>Spacious rooms with premium amenities and city views</p>
                            <ul>
                                <li>King bed</li>
                                <li>Balcony with view</li>
                                <li>Premium WiFi</li>
                                <li>Mini bar</li>
                            </ul>
                            <div class="room-price">
                                <span class="price">RWF 180,000</span>
                                <span class="per-night">per night</span>
                            </div>
                            <a href="booking.php?room=deluxe" class="room-btn">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="room-card">
                        <div class="room-image">
                            <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Suite">
                        </div>
                        <div class="room-content">
                            <h3>Executive Suite</h3>
                            <p>Luxurious suites with separate living area and premium services</p>
                            <ul>
                                <li>Separate bedroom & living room</li>
                                <li>Panoramic city view</li>
                                <li>Butler service</li>
                                <li>Premium amenities</li>
                            </ul>
                            <div class="room-price">
                                <span class="price">RWF 280,000</span>
                                <span class="per-night">per night</span>
                            </div>
                            <a href="booking.php?room=suite" class="room-btn">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- Booking Section -->
        <section id="booking" class="booking-section">
            <div class="container">
                <div class="booking-content">
                    <div class="booking-text">
                        <h2>Book Your Stay</h2>
                        <p>Check availability and reserve your perfect room</p>
                    </div>
                    <div class="booking-form">
                        <form action="check_availability.php" method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="check_in">Check-in Date</label>
                                    <input type="date" id="check_in" name="check_in" required>
                                </div>
                                <div class="form-group">
                                    <label for="check_out">Check-out Date</label>
                                    <input type="date" id="check_out" name="check_out" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="guests">Number of Guests</label>
                                    <select id="guests" name="guests" required>
                                        <option value="">Select</option>
                                        <option value="1">1 Guest</option>
                                        <option value="2">2 Guests</option>
                                        <option value="3">3 Guests</option>
                                        <option value="4">4 Guests</option>
                                        <option value="5+">5+ Guests</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="room_type">Room Type</label>
                                    <select id="room_type" name="room_type" required>
                                        <option value="">Select Room Type</option>
                                        <option value="standard">Standard Room</option>
                                        <option value="deluxe">Deluxe Room</option>
                                        <option value="suite">Executive Suite</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit">Check Availability</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>



        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready for Your Perfect Stay?</h2>
                    <p>Book now and experience luxury hospitality at its finest</p>
                    <a href="booking.php" class="btn-primary">Book Your Room</a>
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