<?php
session_start();

$bookingId = $_GET['booking_id'] ?? '';

if (empty($bookingId)) {
    header("Location: index.php");
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
    
    // Get booking details
    $sql = "SELECT * FROM bookings WHERE booking_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$bookingId]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        header("Location: index.php");
        exit();
    }
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Luxury Hotel Rwanda</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .success-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-card {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
        
        .success-header h1 {
            color: #28a745;
            margin-bottom: 0.5rem;
        }
        
        .success-header p {
            color: #666;
            margin-bottom: 2rem;
        }
        
        .booking-details {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin: 2rem 0;
            text-align: left;
        }
        
        .booking-details h3 {
            color: #1e3c72;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #333;
        }
        
        .detail-value {
            color: #666;
        }
        
        .price-breakdown {
            background: #e8f5e8;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
        }
        
        .price-row.total {
            font-weight: bold;
            font-size: 1.2rem;
            color: #1e3c72;
            border-top: 2px solid #28a745;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3);
        }
        
        .btn-print {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        
        @media (max-width: 768px) {
            .success-card {
                padding: 2rem;
            }
            
            .detail-row {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">✅</div>
            
            <div class="success-header">
                <h1>Booking Confirmed!</h1>
                <p>Thank you for choosing Luxury Hotel Rwanda. Your booking has been successfully confirmed.</p>
            </div>
            
            <div class="booking-details">
                <h3>Booking Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['booking_id']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Guest Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['email']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['phone']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Room Type:</span>
                    <span class="detail-value"><?php echo ucfirst(htmlspecialchars($booking['room_type'])); ?> Room</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Number of Guests:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['guests']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Check-in Date:</span>
                    <span class="detail-value"><?php echo date('F j, Y', strtotime($booking['check_in'])); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Check-out Date:</span>
                    <span class="detail-value"><?php echo date('F j, Y', strtotime($booking['check_out'])); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Number of Nights:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['nights']); ?></span>
                </div>
                
                <?php if (!empty($booking['special_requests'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Special Requests:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['special_requests']); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="price-breakdown">
                    <h4>Price Breakdown</h4>
                    
                    <div class="price-row">
                        <span>Room Rate (per night):</span>
                        <span>RWF <?php echo number_format($booking['room_rate']); ?></span>
                    </div>
                    
                    <div class="price-row">
                        <span>Subtotal:</span>
                        <span>RWF <?php echo number_format($booking['subtotal']); ?></span>
                    </div>
                    
                    <div class="price-row">
                        <span>Tax (18%):</span>
                        <span>RWF <?php echo number_format($booking['tax']); ?></span>
                    </div>
                    
                    <div class="price-row total">
                        <span>Total Amount:</span>
                        <span>RWF <?php echo number_format($booking['total_amount']); ?></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #28a745; font-weight: bold;">
                        <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                    </span>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="index.php" class="btn-home">← Back to Home</a>
                <button onclick="window.print()" class="btn-print">🖨️ Print Confirmation</button>
            </div>
            
            <div style="margin-top: 2rem; padding: 1rem; background: #e8f5e8; border-radius: 8px;">
                <p style="margin: 0; color: #155724; font-size: 0.9rem;">
                    <strong>Important:</strong> A confirmation email has been sent to <?php echo htmlspecialchars($booking['email']); ?>. 
                    Please check your email for additional details and instructions.
                </p>
            </div>
        </div>
    </div>
</body>
</html> 