<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Manage Reviews';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$success = '';
$error = '';

// Handle review actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_review'])) {
        $id = (int)$_POST['review_id'];
        
        try {
            $stmt = $db->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->execute([$id]);
            $success = 'Review deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting review: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_status'])) {
        $id = (int)$_POST['review_id'];
        $status = sanitize($_POST['status']);
        
        try {
            $stmt = $db->prepare("UPDATE reviews SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = 'Review status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating status: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['toggle_featured'])) {
        $id = (int)$_POST['review_id'];
        $featured = (int)$_POST['featured'];
        
        try {
            $stmt = $db->prepare("UPDATE reviews SET featured = ? WHERE id = ?");
            $stmt->execute([$featured, $id]);
            $success = 'Featured status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating featured status: ' . $e->getMessage();
        }
    }
}

// Fetch all reviews with tour info
$sql = "SELECT r.*, t.title as tour_title 
        FROM reviews r 
        LEFT JOIN tours t ON r.tour_id = t.id 
        ORDER BY r.created_at DESC";
$reviews = $db->query($sql)->fetchAll();

// Get statistics
$statsQuery = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN featured = 1 THEN 1 ELSE 0 END) as featured,
    AVG(rating) as avg_rating
    FROM reviews";
$stats = $db->query($statsQuery)->fetch();
?>

<style>
.admin-content {
    padding: 30px;
}

.reviews-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.reviews-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-warning {
    background: #fbbf24;
    color: #1a1a1a;
}

.btn-warning:hover {
    background: #f59e0b;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}

.btn-icon {
    padding: 8px 12px;
}

.alert {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.stat-card h3 {
    font-size: 14px;
    color: #6b7280;
    margin: 0 0 10px 0;
    font-weight: 600;
    text-transform: uppercase;
}

.stat-card .stat-value {
    font-size: 36px;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.filters-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.filter-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.filter-group input,
.filter-group select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
}

.reviews-table {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

thead th {
    padding: 16px;
    text-align: left;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
}

tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.3s ease;
}

tbody tr:hover {
    background: #f9fafb;
}

tbody td {
    padding: 16px;
    font-size: 14px;
    color: #374151;
}

.review-customer {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}

.customer-info h4 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
}

.customer-info p {
    margin: 2px 0 0 0;
    font-size: 12px;
    color: #6b7280;
}

.rating-stars {
    color: #fbbf24;
}

.review-excerpt {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

.featured-badge {
    background: #fef3c7;
    color: #92400e;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.empty-state i {
    font-size: 64px;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 20px;
    color: #6b7280;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .reviews-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-content">
    <div class="reviews-header">
        <h1><i class="fas fa-star"></i> Reviews Management</h1>
        <div class="header-actions">
            <a href="add-review.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Review
            </a>
        </div>
    </div>
    
    <?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $success; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Reviews</h3>
            <div class="stat-value"><?php echo $stats['total']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Approved</h3>
            <div class="stat-value"><?php echo $stats['approved']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Pending</h3>
            <div class="stat-value"><?php echo $stats['pending']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Featured</h3>
            <div class="stat-value"><?php echo $stats['featured']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Average Rating</h3>
            <div class="stat-value"><?php echo number_format($stats['avg_rating'], 1); ?></div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filters-section">
        <div class="filters-grid">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" id="searchInput" placeholder="Search reviews..." onkeyup="filterReviews()">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="statusFilter" onchange="filterReviews()">
                    <option value="">All Status</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Featured</label>
                <select id="featuredFilter" onchange="filterReviews()">
                    <option value="">All Reviews</option>
                    <option value="1">Featured Only</option>
                    <option value="0">Non-Featured</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Rating</label>
                <select id="ratingFilter" onchange="filterReviews()">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Reviews Table -->
    <?php if (!empty($reviews)): ?>
    <div class="reviews-table">
        <table id="reviewsTable">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Tour</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                <tr data-status="<?php echo $review['status']; ?>" 
                    data-featured="<?php echo $review['featured']; ?>"
                    data-rating="<?php echo $review['rating']; ?>"
                    data-customer="<?php echo htmlspecialchars($review['customer_name'] ?? ''); ?>">
                    <td>
                        <div class="review-customer">
                            <div class="customer-avatar">
                                <?php if ($review['customer_photo']): ?>
                                <img src="<?php echo UPLOAD_URL . '/reviews/' . $review['customer_photo']; ?>" 
                                     alt="<?php echo htmlspecialchars($review['customer_name'] ?? ''); ?>">
                                <?php else: ?>
                                <?php echo strtoupper(substr($review['customer_name'] ?? 'U', 0, 1)); ?>
                                <?php endif; ?>
                            </div>
                            <div class="customer-info">
                                <h4><?php echo htmlspecialchars($review['customer_name'] ?? ''); ?></h4>
                                <?php if ($review['customer_email']): ?>
                                <p><?php echo htmlspecialchars($review['customer_email']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><?php echo $review['tour_title'] ? htmlspecialchars($review['tour_title']) : '<em>No tour</em>'; ?></td>
                    <td>
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="<?php echo $i <= $review['rating'] ? 'fas' : 'far'; ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td>
                        <div class="review-excerpt" title="<?php echo htmlspecialchars($review['review_text'] ?? ''); ?>">
                            <?php echo htmlspecialchars($review['review_text'] ?? ''); ?>
                        </div>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($review['created_at'])); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <select name="status" onchange="this.form.submit()" class="status-badge status-<?php echo $review['status']; ?>">
                                <option value="approved" <?php echo $review['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="pending" <?php echo $review['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="rejected" <?php echo $review['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                        <?php if ($review['featured']): ?>
                        <span class="featured-badge">
                            <i class="fas fa-star"></i> Featured
                        </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit-review.php?id=<?php echo $review['id']; ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                <input type="hidden" name="featured" value="<?php echo $review['featured'] ? 0 : 1; ?>">
                                <button type="submit" name="toggle_featured" 
                                        class="btn btn-icon <?php echo $review['featured'] ? 'btn-warning' : 'btn-secondary'; ?>" 
                                        title="<?php echo $review['featured'] ? 'Remove from Featured' : 'Mark as Featured'; ?>">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>
                            
                            <button class="btn btn-danger btn-icon" 
                                    onclick="confirmDelete(<?php echo $review['id']; ?>, '<?php echo htmlspecialchars($review['customer_name'] ?? ''); ?>')"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-star"></i>
        <h3>No reviews found</h3>
        <p>Start by adding your first review</p>
        <a href="add-review.php" class="btn btn-primary" style="margin-top: 20px;">
            <i class="fas fa-plus"></i> Add Review
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
function filterReviews() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const featuredFilter = document.getElementById('featuredFilter').value;
    const ratingFilter = document.getElementById('ratingFilter').value;
    const rows = document.querySelectorAll('#reviewsTable tbody tr');
    
    rows.forEach(row => {
        const customer = row.dataset.customer.toLowerCase();
        const status = row.dataset.status;
        const featured = row.dataset.featured;
        const rating = row.dataset.rating;
        
        const matchesSearch = customer.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesFeatured = !featuredFilter || featured === featuredFilter;
        const matchesRating = !ratingFilter || rating === ratingFilter;
        
        if (matchesSearch && matchesStatus && matchesFeatured && matchesRating) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function confirmDelete(id, customerName) {
    if (confirm(`Are you sure you want to delete the review by "${customerName}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="review_id" value="${id}">
            <input type="hidden" name="delete_review" value="1">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
