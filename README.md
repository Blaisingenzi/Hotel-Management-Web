# Luxury Hotel Rwanda - Hotel Management System

A complete hotel management system built with PHP, MySQL, HTML, CSS, and JavaScript. This system provides a modern, responsive interface for hotel bookings and administration.

## 🌟 Features

### Frontend Features
- **Responsive Design** - Works perfectly on desktop, tablet, and mobile devices
- **Auto-rotating Hero Carousel** - Showcases hotel amenities and services
- **Interactive Room Booking** - Real-time price calculation and form validation
- **Modern UI/UX** - Professional design with smooth animations and transitions
- **Mobile Navigation** - Hamburger menu for mobile devices

### Backend Features
- **Complete Booking System** - Full reservation management with payment processing
- **Admin Dashboard** - Comprehensive management interface
- **Database Integration** - MySQL database with proper relationships
- **Form Validation** - Client-side and server-side validation
- **Session Management** - Secure admin authentication

### Admin Features
- **Booking Management** - View, confirm, and cancel reservations
- **Revenue Tracking** - Real-time financial statistics
- **Room Management** - Track room availability and status
- **Contact Management** - Handle customer inquiries
- **Newsletter Subscription Management** - Manage newsletter subscribers with search, export, and analytics
- **Export Functionality** - Export booking data and subscriber lists

## 🏗️ Project Structure

```
hotel_management/
├── index.php              # Main homepage
├── rooms.php              # Room listings and details
├── booking.php            # Booking form
├── booking_success.php    # Booking confirmation
├── contact.html           # Contact form
├── contact_success.html   # Contact confirmation
├── terms.html             # Terms and conditions
├── cancellation.html      # Cancellation policy
├── admin.php              # Admin dashboard
├── admin_login.php        # Admin authentication
├── admin_logout.php       # Admin logout
├── submit_booking.php     # Booking processing
├── submit_contact.php     # Contact form processing
├── view_booking.php       # View booking details
├── update_booking.php     # Update booking information
├── view_messages.php      # View all contact messages
├── view_subscribers.php   # Newsletter subscribers management
├── export_subscribers.php # Export subscriber data
├── database_setup.sql     # Database schema
├── style.css              # Main stylesheet
├── script.js              # JavaScript functionality
└── README.md              # Project documentation
```

## 🚀 Installation

### Prerequisites
- XAMPP/WAMP/MAMP server
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web browser

### Setup Instructions

1. **Clone/Download the Project**
   ```bash
   # Place the hotel_management folder in your web server directory
   # For XAMPP: C:\xampp\htdocs\hotel_management\
   ```

2. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database or import the `database_setup.sql` file
   - The script will create all necessary tables and sample data

3. **Configure Database Connection**
   - Update database credentials in PHP files if needed:
     - `admin.php`
     - `admin_login.php`
     - `submit_booking.php`
     - `submit_contact.php`
     - `view_booking.php`
     - `update_booking.php`
     - `view_messages.php`
     - `view_subscribers.php`
     - `export_subscribers.php`
   - **Note**: If using MAMP, change password from `""` to `"root"` in admin_login.php

4. **Access the Application**
   - Frontend: http://localhost/hotel_management/
   - Admin Panel: http://localhost/hotel_management/admin_login.php
   - Admin Credentials: username: `admin`, password: `admin123`

## 📊 Database Schema

### Tables

1. **bookings** - Stores all reservation data
   - booking_id, guest details, room info, dates, pricing, status, admin_notes

2. **contact_messages** - Customer inquiries and newsletter subscriptions
   - name, email, phone, subject, message, newsletter_subscription, status, timestamp

3. **admin_users** - Admin authentication
   - username, password, email

4. **rooms** - Room inventory management
   - room_number, type, price, status, amenities, capacity, floor_number

## 🎨 Design Features

### Color Scheme
- **Primary Blue**: #1e3c72 (Deep Blue)
- **Secondary Blue**: #2a5298 (Medium Blue)
- **Accent Gold**: #ffd700 (Golden Yellow)
- **Success Green**: #28a745
- **Error Red**: #dc3545
- **Maroon**: #8B0000 (Primary brand color)

### Currency
- **Rwandan Francs (RWF)** - All prices displayed in local currency
- **Room Rates**: Standard (RWF 120,000), Deluxe (RWF 180,000), Suite (RWF 280,000)

### Typography
- **Font Family**: Segoe UI, Tahoma, Geneva, Verdana, sans-serif
- **Responsive Design**: Mobile-first approach
- **Smooth Animations**: CSS transitions and transforms

## 🔧 Technical Features

### Frontend Technologies
- **HTML5** - Semantic markup
- **CSS3** - Modern styling with Flexbox and Grid
- **JavaScript (ES6+)** - Interactive functionality
- **Responsive Design** - Mobile-first approach

### Backend Technologies
- **PHP 7.4+** - Server-side processing
- **MySQL** - Database management
- **PDO** - Secure database connections
- **Session Management** - User authentication

### Security Features
- **SQL Injection Prevention** - Prepared statements
- **XSS Protection** - Input sanitization
- **Form Validation** - Client and server-side validation
- **Session Security** - Secure admin authentication
- **Input Sanitization** - All user inputs are properly sanitized
- **CSRF Protection** - Form submission validation

## 📱 Responsive Design

The system is fully responsive and optimized for:
- **Desktop** (1200px+)
- **Tablet** (768px - 1199px)
- **Mobile** (320px - 767px)

## 🎯 Key Functionalities

### Customer Features
1. **Browse Rooms** - View different room types and amenities
2. **Check Availability** - Real-time availability checking
3. **Book Rooms** - Complete booking process with payment
4. **View Confirmation** - Booking confirmation with details
5. **Contact Support** - Send inquiries and messages
6. **Newsletter Subscription** - Subscribe to hotel updates and promotions
7. **Legal Information** - Access terms and cancellation policies

### Admin Features
1. **Dashboard Overview** - Statistics and quick actions
2. **Booking Management** - View and manage all reservations
3. **Revenue Tracking** - Financial reports and analytics
4. **Room Management** - Track room status and availability
5. **Customer Support** - Handle customer inquiries
6. **Newsletter Management** - Manage subscribers, export lists, and analytics
7. **Message Management** - View, respond to, and manage contact messages
8. **Booking Details** - View and update individual booking information

## 📧 Newsletter Subscription System

### Features
- **Contact Form Integration** - Newsletter subscription checkbox in contact form
- **Admin Dashboard** - Newsletter subscribers statistics and management
- **Subscriber Management** - View, search, and manage all subscribers
- **Export Functionality** - Export subscribers to CSV or get email list
- **Analytics** - Track subscriber growth (total, today, this week, this month)
- **Email Integration** - Direct email links to subscribers
- **Search & Filter** - Find specific subscribers quickly
- **Pagination** - Handle large subscriber lists efficiently

### Admin Capabilities
- **View All Subscribers** - Complete subscriber list with details
- **Search Subscribers** - Search by name, email, or subject
- **Export Data** - Download CSV file or copy email list
- **Remove Subscribers** - Unsubscribe users from newsletter
- **Statistics Dashboard** - Real-time subscriber analytics
- **Email Management** - Direct email links to subscribers

## 🛠️ Customization

### Adding New Room Types
1. Update the `$roomTypes` array in `booking.php`
2. Add corresponding prices in `script.js`
3. Update the database schema if needed

### Modifying Styling
1. Edit `style.css` for visual changes
2. Update color variables for brand consistency
3. Modify responsive breakpoints as needed

### Adding Features
1. Create new PHP files for additional functionality
2. Update navigation menu in header
3. Add corresponding database tables if required

### Newsletter Management
1. Customize email templates in export functions
2. Modify subscriber analytics queries
3. Add additional export formats as needed
4. Customize subscriber management interface

## 📈 Performance Optimization

- **Optimized Images** - Compressed and responsive images
- **Minified CSS/JS** - Reduced file sizes
- **Efficient Queries** - Optimized database queries with proper indexing
- **Caching** - Browser caching for static assets
- **Pagination** - Efficient handling of large datasets
- **Search Optimization** - Fast search functionality with database indexes

## 🔒 Security Considerations

- **Input Validation** - All user inputs are validated
- **SQL Injection Protection** - Prepared statements used
- **XSS Prevention** - Output sanitization
- **Session Security** - Secure session management
- **Password Protection** - Admin authentication
- **Data Privacy** - Newsletter subscription data protection
- **Access Control** - Admin-only access to sensitive data
- **CSRF Protection** - Form submission validation

## 🚀 Deployment

### Local Development
1. Use XAMPP/WAMP for local development
2. Configure virtual hosts if needed
3. Set up SSL certificates for HTTPS

### Production Deployment
1. Upload files to web server
2. Configure database on production server
3. Update database connection settings
4. Set up SSL certificates
5. Configure email settings for notifications

## 📞 Support

For technical support or questions:
- **Email**: support@luxuryhotelrwanda.com
- **Phone**: +250 788 123 456
- **Documentation**: Check this README file

## 📄 License

This project is created for educational and demonstration purposes.

## 🎉 Credits

- **Design**: Modern hotel management interface
- **Development**: Full-stack PHP application
- **Database**: MySQL with proper relationships
- **Frontend**: Responsive HTML/CSS/JavaScript

---

**Luxury Hotel Rwanda** - Experience the perfect blend of luxury, comfort, and Rwandan hospitality! 🏨✨ 