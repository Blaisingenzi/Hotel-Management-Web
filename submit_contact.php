<?php
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

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters long";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    } elseif (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters long";
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, newsletter_subscription, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $phone, $subject, $message, $newsletter]);
            
            // Redirect to success page
            header("Location: contact_success.html");
            exit();
            
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    // If there are errors, redirect back to contact form with error message
    if (!empty($errors)) {
        $errorMessage = implode(", ", $errors);
        header("Location: contact.html?error=" . urlencode($errorMessage));
        exit();
    }
} else {
    // If not POST request, redirect to contact page
    header("Location: contact.html");
    exit();
}
?> 