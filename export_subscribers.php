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

// Get all newsletter subscribers
$sql = "SELECT name, email, phone, subject, created_at FROM contact_messages WHERE newsletter_subscription = 1 ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$format = $_GET['format'] ?? 'csv';

if ($format === 'csv') {
    // Export to CSV
    $filename = 'newsletter_subscribers_' . date('Y-m-d_H-i-s') . '.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    
    // Add CSV headers
    fputcsv($output, ['Name', 'Email', 'Phone', 'Subject', 'Subscription Date']);
    
    // Add data rows
    foreach ($subscribers as $subscriber) {
        fputcsv($output, [
            $subscriber['name'],
            $subscriber['email'],
            $subscriber['phone'] ?: 'N/A',
            $subscriber['subject'],
            date('Y-m-d H:i:s', strtotime($subscriber['created_at']))
        ]);
    }
    
    fclose($output);
    exit();
    
} elseif ($format === 'email') {
    // Display email list
    $emails = array_column($subscribers, 'email');
    $emailList = implode(', ', $emails);
    
    // Redirect back with email list
    header("Location: view_subscribers.php?email_list=" . urlencode($emailList));
    exit();
}
?> 