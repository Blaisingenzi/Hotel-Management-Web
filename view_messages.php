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

// Handle message deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $messageId = (int)$_POST['message_id'];
    
    try {
        $deleteSql = "DELETE FROM contact_messages WHERE id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->execute([$messageId]);
        
        $successMessage = "Message deleted successfully!";
    } catch(PDOException $e) {
        $errorMessage = "Error deleting message: " . $e->getMessage();
    }
}

// Handle message marking as read/unread
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_read'])) {
    $messageId = (int)$_POST['message_id'];
    $currentStatus = $_POST['current_status'];
    $newStatus = $currentStatus === 'unread' ? 'read' : 'unread';
    
    try {
        $updateSql = "UPDATE contact_messages SET status = ? WHERE id = ?";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([$newStatus, $messageId]);
        
        $successMessage = "Message status updated successfully!";
    } catch(PDOException $e) {
        $errorMessage = "Error updating message status: " . $e->getMessage();
    }
}

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

// Build query with search and filter
$whereConditions = [];
$params = [];

if (!empty($search)) {
    $whereConditions[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
}

if (!empty($statusFilter)) {
    $whereConditions[] = "status = ?";
    $params[] = $statusFilter;
}

$whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";

// Get messages with pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Get total count for pagination
$countSql = "SELECT COUNT(*) FROM contact_messages $whereClause";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalMessages = $countStmt->fetchColumn();
$totalPages = ceil($totalMessages / $perPage);

// Get messages
$sql = "SELECT * FROM contact_messages $whereClause ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$statsSql = "SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN status = 'unread' OR status IS NULL THEN 1 END) as unread,
    COUNT(CASE WHEN status = 'read' THEN 1 END) as `read`
    FROM contact_messages";
$statsStmt = $pdo->query($statsSql);
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Messages - Admin Dashboard</title>
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
        <section class="view-messages-section">
            <div class="container">
                <div class="page-header">
                    <h2>Contact Messages</h2>
                    <div class="header-actions">
                        <a href="admin.php" class="btn-secondary">← Back to Dashboard</a>
                    </div>
                </div>

                <?php if (isset($successMessage)): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($errorMessage)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>

                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📧</div>
                        <div class="stat-content">
                            <h3>Total Messages</h3>
                            <span class="stat-number"><?php echo $stats['total']; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📬</div>
                        <div class="stat-content">
                            <h3>Unread</h3>
                            <span class="stat-number"><?php echo $stats['unread']; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📭</div>
                        <div class="stat-content">
                            <h3>Read</h3>
                            <span class="stat-number"><?php echo $stats['read']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="search-filter-section">
                    <form method="GET" class="search-form">
                        <div class="search-row">
                            <div class="search-group">
                                <input type="text" name="search" placeholder="Search messages..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="filter-group">
                                <select name="status">
                                    <option value="">All Status</option>
                                    <option value="unread" <?php echo $statusFilter === 'unread' ? 'selected' : ''; ?>>Unread</option>
                                    <option value="read" <?php echo $statusFilter === 'read' ? 'selected' : ''; ?>>Read</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-primary">Search</button>
                            <?php if (!empty($search) || !empty($statusFilter)): ?>
                                <a href="view_messages.php" class="btn-secondary">Clear</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Messages List -->
                <div class="messages-section">
                    <?php if (empty($messages)): ?>
                        <div class="no-messages">
                            <p>No messages found.</p>
                        </div>
                    <?php else: ?>
                        <div class="messages-grid">
                            <?php foreach ($messages as $message): ?>
                                <div class="message-card <?php echo ($message['status'] ?? 'unread') === 'unread' ? 'unread' : 'read'; ?>">
                                    <div class="message-header">
                                        <div class="message-info">
                                            <h4><?php echo htmlspecialchars($message['subject']); ?></h4>
                                            <div class="message-meta">
                                                <span class="sender"><?php echo htmlspecialchars($message['name']); ?></span>
                                                <span class="email"><?php echo htmlspecialchars($message['email']); ?></span>
                                                <span class="date"><?php echo date('M j, Y \a\t g:i A', strtotime($message['created_at'])); ?></span>
                                            </div>
                                        </div>
                                        <div class="message-status">
                                            <span class="status-badge status-<?php echo $message['status'] ?? 'unread'; ?>">
                                                <?php echo ucfirst($message['status'] ?? 'unread'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="message-content">
                                        <div class="message-preview">
                                            <?php echo htmlspecialchars(substr($message['message'], 0, 150)) . (strlen($message['message']) > 150 ? '...' : ''); ?>
                                        </div>
                                        
                                        <?php if (strlen($message['message']) > 150): ?>
                                            <div class="message-full" style="display: none;">
                                                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                            </div>
                                            <button class="toggle-message-btn" onclick="toggleMessage(this)">Read More</button>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="message-actions">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                            <input type="hidden" name="current_status" value="<?php echo $message['status'] ?? 'unread'; ?>">
                                            <button type="submit" name="toggle_read" class="btn-action">
                                                <?php echo ($message['status'] ?? 'unread') === 'unread' ? 'Mark as Read' : 'Mark as Unread'; ?>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                            <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                            <button type="submit" name="delete_message" class="btn-action delete">Delete</button>
                                        </form>
                                        
                                        <?php if ($message['phone']): ?>
                                            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="btn-action">Reply</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>" class="page-link">← Previous</a>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>" 
                                       class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($page < $totalPages): ?>
                                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>" class="page-link">Next →</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Luxury Hotel Rwanda. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleMessage(button) {
            const messageCard = button.closest('.message-card');
            const preview = messageCard.querySelector('.message-preview');
            const full = messageCard.querySelector('.message-full');
            
            if (full.style.display === 'none') {
                preview.style.display = 'none';
                full.style.display = 'block';
                button.textContent = 'Show Less';
            } else {
                preview.style.display = 'block';
                full.style.display = 'none';
                button.textContent = 'Read More';
            }
        }
    </script>
</body>
</html> 