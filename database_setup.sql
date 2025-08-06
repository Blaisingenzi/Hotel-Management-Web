-- Database setup for Luxury Hotel Rwanda
-- Run this script in phpMyAdmin or MySQL command line
-- Updated: Removed card payment fields (card_number, card_holder, expiry_date, cvv)
-- from bookings table to simplify the booking process

-- Create database
CREATE DATABASE IF NOT EXISTS hotel_management_db;
USE hotel_management_db;

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    room_type ENUM('standard', 'deluxe', 'suite') NOT NULL,
    guests INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    special_requests TEXT,
    arrival_time VARCHAR(20),
    room_rate DECIMAL(10,2) NOT NULL,
    nights INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    tax DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create contact_messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    newsletter_subscription BOOLEAN DEFAULT FALSE,
    status ENUM('read', 'unread') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create admin_users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) UNIQUE NOT NULL,
    room_type ENUM('standard', 'deluxe', 'suite') NOT NULL,
    floor_number INT NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    amenities TEXT,
    status ENUM('available', 'occupied', 'maintenance', 'reserved') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES
('admin', 'admin123', 'admin@luxuryhotelrwanda.com');

-- Insert sample rooms
INSERT INTO rooms (room_number, room_type, floor_number, price_per_night, capacity, amenities) VALUES
('101', 'standard', 1, 120000.00, 2, 'King bed, WiFi, TV, AC'),
('102', 'standard', 1, 120000.00, 2, 'Twin beds, WiFi, TV, AC'),
('201', 'deluxe', 2, 180000.00, 2, 'King bed, WiFi, TV, AC, Balcony'),
('202', 'deluxe', 2, 180000.00, 2, 'King bed, WiFi, TV, AC, Balcony'),
('301', 'suite', 3, 280000.00, 4, 'King bed, Living room, WiFi, TV, AC, Butler service'),
('302', 'suite', 3, 280000.00, 4, 'King bed, Living room, WiFi, TV, AC, Butler service');

-- Insert sample bookings
INSERT INTO bookings (
    booking_id, first_name, last_name, email, phone, address, room_type, guests,
    check_in, check_out, room_rate, nights, subtotal, tax, total_amount, status
) VALUES
('HOTEL20240001', 'John', 'Doe', 'john.doe@email.com', '+250788123456', 'Kigali, Rwanda', 'deluxe', 2,
 '2024-02-15', '2024-02-18', 180000.00, 3, 540000.00, 97200.00, 637200.00, 'confirmed'),
('HOTEL20240002', 'Alice', 'Smith', 'alice.smith@email.com', '+250788123458', 'Kigali, Rwanda', 'standard', 1,
 '2024-02-20', '2024-02-22', 120000.00, 2, 240000.00, 43200.00, 283200.00, 'pending'),
('HOTEL20240003', 'Michael', 'Johnson', 'michael.johnson@email.com', '+250788123460', 'Kigali, Rwanda', 'suite', 3,
 '2024-02-25', '2024-02-28', 280000.00, 3, 840000.00, 151200.00, 991200.00, 'confirmed');

-- Insert sample contact messages
INSERT INTO contact_messages (name, email, phone, subject, message) VALUES
('Sarah Wilson', 'sarah.wilson@email.com', '+250788123462', 'Room Availability', 'I would like to know about room availability for next month.'),
('David Brown', 'david.brown@email.com', '+250788123464', 'Special Request', 'Do you provide airport pickup service?'),
('Emma Davis', 'emma.davis@email.com', '+250788123466', 'Conference Facilities', 'I need information about your conference room facilities.');

-- Display the created tables
SHOW TABLES;

-- Display sample bookings
SELECT booking_id, first_name, last_name, room_type, status, total_amount FROM bookings;

-- Display sample rooms
SELECT room_number, room_type, price_per_night, status FROM rooms; 