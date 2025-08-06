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

// Handle subscriber deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subscriber'])) {
    $subscriberId = (int)$_POST['subscriber_id'];

    try {
        $deleteSql = "UPDATE contact_messages SET newsletter_subscription = 0 WHERE id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->execute([$subscriberId]);

        $successMessage = "Subscriber removed successfully!";
    } catch(PDOException $e) {
        $errorMessage = "Error removing subscriber: " . $e->getMessage();
    }
}

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query with search
$whereConditions = ["newsletter_subscription = 1"];
$params = [];

if (!empty($search)) {
    $whereConditions[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ?)";
    $searchParam = "%$search%";
    $params = [$searchParam, $searchParam, $searchParam];
}

$whereClause = "WHERE " . implode(" AND ", $whereConditions);

// Get subscribers with pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

// Get total count for pagination
$countSql = "SELECT COUNT(*) FROM contact_messages $whereClause";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalSubscribers = $countStmt->fetchColumn();
$totalPages = ceil($totalSubscribers / $perPage);

// Get subscribers
$sql = "SELECT * FROM contact_messages $whereClause ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$statsSql = "SELECT
    COUNT(*) as total,
    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today,
    COUNT(CASE WHEN DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as this_week,
    COUNT(CASE WHEN DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 END) as this_month
    FROM contact_messages WHERE newsletter_subscription = 1";
$statsStmt = $pdo->query($statsSql);
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Subscribers - Admin Dashboard</title>
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
        <section class="view-subscribers-section">
            <div class="container">
                <div class="page-header">
                    <h2>Newsletter Subscribers</h2>
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

                <?php if (isset($_GET['email_list'])): ?>
                    <div class="email-list-section">
                        <h3>Email List</h3>
                        <div class="email-list-container">
                            <textarea readonly class="email-list-textarea"><?php echo htmlspecialchars($_GET['email_list']); ?></textarea>
                            <div class="email-list-actions">
                                <button onclick="copyEmailList()" class="btn-primary">Copy to Clipboard</button>
                                <span class="copy-status" id="copyStatus"></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📬</div>
                        <div class="stat-content">
                            <h3>Total Subscribers</h3>
                            <span class="stat-number"><?php echo $stats['total']; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📅</div>
                        <div class="stat-content">
                            <h3>Today</h3>
                            <span class="stat-number"><?php echo $stats['today']; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <h3>This Week</h3>
                            <span class="stat-number"><?php echo $stats['this_week']; ?></span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📈</div>
                        <div class="stat-content">
                            <h3>This Month</h3>
                            <span class="stat-number"><?php echo $stats['this_month']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="search-filter-section">
                    <form method="GET" class="search-form">
                        <div class="search-row">
                            <div class="search-group">
                                <input type="text" name="search" placeholder="Search subscribers..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <button type="submit" class="btn-primary">Search</button>
                            <?php if (!empty($search)): ?>
                                <a href="view_subscribers.php" class="btn-secondary">Clear</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Subscribers List -->
                <div class="subscribers-section">
                    <?php if (empty($subscribers)): ?>
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
                                    <?php foreach ($subscribers as $subscriber): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($subscriber['name']); ?></td>
                                            <td><?php echo htmlspecialchars($subscriber['email']); ?></td>
                                            <td><?php echo htmlspecialchars($subscriber['phone'] ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($subscriber['subject']); ?></td>
                                            <td><?php echo date('M j, Y \a\t g:i A', strtotime($subscriber['created_at'])); ?></td>
                                            <td>
                                                <a href="mailto:<?php echo htmlspecialchars($subscriber['email']); ?>" class="btn-action">Email</a>
                                                <a href="view_messages.php?search=<?php echo urlencode($subscriber['email']); ?>" class="btn-action">View Messages</a>
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to remove this subscriber?');">
                                                    <input type="hidden" name="subscriber_id" value="<?php echo $subscriber['id']; ?>">
                                                    <button type="submit" name="delete_subscriber" class="btn-action delete">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link">← Previous</a>
                                <?php endif; ?>

                                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
                                       class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link">Next →</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Export Section -->
                <div class="export-section">
                    <h3>Export Subscribers</h3>
                    <div class="export-actions">
                        <a href="export_subscribers.php?format=csv" class="btn-primary">Export to CSV</a>
                        <a href="export_subscribers.php?format=email" class="btn-secondary">Get Email List</a>
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

    <script>
        function copyEmailList() {
            const textarea = document.querySelector('.email-list-textarea');
            const copyStatus = document.getElementById('copyStatus');
            
            textarea.select();
            textarea.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                document.execCommand('copy');
                copyStatus.textContent = 'Copied!';
                copyStatus.style.color = '#28a745';
                
                setTimeout(() => {
                    copyStatus.textContent = '';
                }, 2000);
            } catch (err) {
                copyStatus.textContent = 'Failed to copy';
                copyStatus.style.color = '#dc3545';
            }
        }
    </script>
</body>
</html> 