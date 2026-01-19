<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Admin Dashboard';
require_once __DIR__ . '/includes/admin-header.php';

// Get statistics
$db = Database::getInstance()->getConnection();

$stats = [];
$stats['tours'] = $db->query("SELECT COUNT(*) as count FROM tours WHERE status = 'published'")->fetch()['count'];
$stats['all_tours'] = $db->query("SELECT COUNT(*) as count FROM tours")->fetch()['count'];
$stats['destinations'] = $db->query("SELECT COUNT(*) as count FROM destinations WHERE status = 'published'")->fetch()['count'];
$stats['blog_posts'] = $db->query("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'")->fetch()['count'];
$stats['gallery_images'] = $db->query("SELECT COUNT(*) as count FROM gallery WHERE status = 'published'")->fetch()['count'];
$stats['reviews'] = $db->query("SELECT COUNT(*) as count FROM reviews WHERE status = 'approved'")->fetch()['count'];
$stats['pending_reviews'] = $db->query("SELECT COUNT(*) as count FROM reviews WHERE status = 'pending'")->fetch()['count'];
$stats['contact_messages'] = $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE status = 'new'")->fetch()['count'];
$stats['total_bookings'] = $db->query("SELECT COUNT(*) as count FROM tour_bookings")->fetch()['count'];

// Get recent activities
$recentTours = $db->query("SELECT * FROM tours ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentMessages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentReviews = $db->query("SELECT * FROM reviews ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<style>
.admin-content {
    padding: 30px;
}

.content-header {
    margin-bottom: 40px;
}

.content-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.content-header p {
    font-size: 16px;
    color: #6b7280;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-icon.gradient-blue {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
}

.stat-icon.gradient-green {
    background: linear-gradient(135deg, #00c9ff 0%, #92fe9d 100%);
}

.stat-icon.gradient-orange {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-icon.gradient-purple {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-icon.gradient-pink {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stat-icon.gradient-red {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
}

.stat-trend.up {
    color: #10b981;
    background: #d1fae5;
}

.stat-trend.down {
    color: #ef4444;
    background: #fee2e2;
}

.stat-content {
    margin-top: 12px;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

.stat-footer {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.stat-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #228B22;
    text-decoration: none;
    transition: gap 0.3s;
}

.stat-link:hover {
    gap: 10px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 24px;
}

.dashboard-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

.section-header h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead th {
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px;
    border-bottom: 2px solid #f3f4f6;
}

.table tbody td {
    padding: 16px 12px;
    border-bottom: 1px solid #f3f4f6;
    color: #374151;
}

.table tbody tr:hover {
    background: #f9fafb;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #d1fae5;
    color: #10b981;
}

.badge-warning {
    background: #fef3c7;
    color: #f59e0b;
}

.badge-danger {
    background: #fee2e2;
    color: #ef4444;
}

.badge-info {
    background: #dbeafe;
    color: #3b82f6;
}

.messages-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.message-item {
    padding: 16px;
    border-radius: 12px;
    background: #f9fafb;
    transition: all 0.3s;
    cursor: pointer;
}

.message-item:hover {
    background: #f3f4f6;
    transform: translateX(4px);
}

.message-item.unread {
    background: #eff6ff;
    border-left: 3px solid #228B22;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.message-header strong {
    font-weight: 600;
    color: #1a1a1a;
}

.message-date {
    font-size: 12px;
    color: #9ca3af;
}

.message-preview {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.5;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-outline {
    background: transparent;
    border: 1.5px solid #e5e7eb;
    color: #6b7280;
}

.btn-outline:hover {
    border-color: #228B22;
    color: #228B22;
    background: #f5f7ff;
}

.review-item {
    padding: 16px;
    border-radius: 12px;
    background: #f9fafb;
    margin-bottom: 12px;
}

.review-header-new {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.review-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #228B22;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}

.review-info h4 {
    font-size: 15px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.review-rating {
    color: #fbbf24;
}

.review-text {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .admin-content {
        padding: 20px;
    }
}
</style>

<div class="admin-content">
    <div class="content-header">
        <h1>Dashboard</h1>
        <p>Welcome back, <?php echo htmlspecialchars(getLoggedInUser()['full_name']); ?>! Here's what's happening today.</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon gradient-blue">
                    <i class="fas fa-route"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 12%
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $stats['tours']; ?></div>
                <div class="stat-label">Published Tours</div>
            </div>
            <div class="stat-footer">
                <a href="<?php echo ADMIN_URL; ?>/tours.php" class="stat-link">
                    View All Tours <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon gradient-green">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 5%
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $stats['destinations']; ?></div>
                <div class="stat-label">Active Destinations</div>
            </div>
            <div class="stat-footer">
                <a href="<?php echo ADMIN_URL; ?>/destinations.php" class="stat-link">
                    View Destinations <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon gradient-purple">
                    <i class="fas fa-images"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 8%
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $stats['gallery_images']; ?></div>
                <div class="stat-label">Gallery Images</div>
            </div>
            <div class="stat-footer">
                <a href="<?php echo ADMIN_URL; ?>/gallery.php" class="stat-link">
                    Manage Gallery <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon gradient-orange">
                    <i class="fas fa-blog"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 15%
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $stats['blog_posts']; ?></div>
                <div class="stat-label">Blog Posts</div>
            </div>
            <div class="stat-footer">
                <a href="<?php echo ADMIN_URL; ?>/blog-posts.php" class="stat-link">
                    View Blog <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon gradient-pink">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 20%
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $stats['reviews']; ?></div>
                <div class="stat-label">Customer Reviews</div>
            </div>
            <div class="stat-footer">
                <a href="<?php echo ADMIN_URL; ?>/reviews.php" class="stat-link">
                    Manage Reviews <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon gradient-red">
                    <i class="fas fa-envelope"></i>
                </div>
                <?php if ($stats['contact_messages'] > 0): ?>
                <div class="stat-trend down">
                    <i class="fas fa-exclamation-circle"></i> New
                </div>
                <?php endif; ?>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo $stats['contact_messages']; ?></div>
                <div class="stat-label">New Messages</div>
            </div>
            <div class="stat-footer">
                <a href="<?php echo ADMIN_URL; ?>/messages.php" class="stat-link">
                    View Messages <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Recent Tours</h2>
                <a href="<?php echo ADMIN_URL; ?>/tours.php" class="btn btn-outline">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tour Title</th>
                            <th>Start Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentTours)): ?>
                            <?php foreach ($recentTours as $tour): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($tour['title']); ?></strong></td>
                                <td><?php echo formatDate($tour['start_date']); ?></td>
                                <td><span class="badge badge-<?php echo $tour['status'] == 'published' ? 'success' : 'warning'; ?>"><?php echo ucfirst($tour['status']); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" style="text-align: center; color: #9ca3af;">No tours yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Recent Messages</h2>
                <a href="<?php echo ADMIN_URL; ?>/messages.php" class="btn btn-outline">
                    View All
                </a>
            </div>
            <div class="messages-list">
                <?php if (!empty($recentMessages)): ?>
                    <?php foreach ($recentMessages as $message): ?>
                    <div class="message-item <?php echo $message['status'] == 'new' ? 'unread' : ''; ?>">
                        <div class="message-header">
                            <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                            <span class="message-date"><?php echo formatDate($message['created_at'], DATETIME_FORMAT); ?></span>
                        </div>
                        <p class="message-preview"><?php echo truncateText($message['message'], 100); ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #9ca3af; padding: 20px;">No messages yet</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Recent Reviews</h2>
                <a href="<?php echo ADMIN_URL; ?>/reviews.php" class="btn btn-outline">
                    Moderate
                </a>
            </div>
            <div class="messages-list">
                <?php if (!empty($recentReviews)): ?>
                    <?php foreach ($recentReviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header-new">
                            <div class="review-avatar">
                                <?php echo strtoupper(substr($review['customer_name'], 0, 1)); ?>
                            </div>
                            <div class="review-info">
                                <h4><?php echo htmlspecialchars($review['customer_name']); ?></h4>
                                <div class="review-rating">
                                    <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                        <i class="fas fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <p class="review-text"><?php echo truncateText($review['review_text'], 120); ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #9ca3af; padding: 20px;">No reviews yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
