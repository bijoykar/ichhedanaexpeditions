<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Manage Blog Posts';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$blogModel = new BlogPost();
$currentAdminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
$success = '';
$error = '';

// Handle blog post actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_post'])) {
        $id = (int)$_POST['post_id'];
        
        try {
            $stmt = $db->prepare("DELETE FROM blog_posts WHERE id = ?");
            $stmt->execute([$id]);
            $success = 'Blog post deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting post: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_status'])) {
        $id = (int)$_POST['post_id'];
        $status = sanitize($_POST['status']);
        
        try {
            $updateData = ['status' => $status];
            
            // If publishing, set published_at
            if ($status == 'published') {
                $stmt = $db->prepare("SELECT published_at FROM blog_posts WHERE id = ?");
                $stmt->execute([$id]);
                $post = $stmt->fetch();
                
                if (!$post['published_at']) {
                    $updateData['published_at'] = date('Y-m-d H:i:s');
                }
            }
            
            $setClause = implode(', ', array_map(function($key) { return "$key = ?"; }, array_keys($updateData)));
            $stmt = $db->prepare("UPDATE blog_posts SET $setClause WHERE id = ?");
            $stmt->execute(array_merge(array_values($updateData), [$id]));
            
            $success = 'Post status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating status: ' . $e->getMessage();
        }
    }
}

// Fetch all blog posts with author info
$sql = "SELECT p.*, a.full_name as author_name 
        FROM blog_posts p 
        LEFT JOIN admin_users a ON p.author_id = a.id 
        ORDER BY p.created_at DESC";
$posts = $db->query($sql)->fetchAll();

// Get categories
$categories = $blogModel->getCategories();
?>

<style>
.admin-content {
    padding: 30px;
}

.posts-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.posts-header h1 {
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
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
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

.filters-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 20px 25px;
    margin-bottom: 25px;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.filter-group select,
.filter-group input {
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
}

.stats-cards {
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
}

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.stat-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-card-icon.purple {
    background: rgba(102, 126, 234, 0.1);
    color: #228B22;
}

.stat-card-icon.green {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stat-card-icon.orange {
    background: rgba(251, 146, 60, 0.1);
    color: #fb923c;
}

.stat-card-icon.blue {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.stat-card-number {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 5px;
}

.stat-card-label {
    font-size: 14px;
    color: #6b7280;
}

.posts-table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.posts-table {
    width: 100%;
    border-collapse: collapse;
}

.posts-table thead {
    background: #f9fafb;
}

.posts-table th {
    padding: 16px 20px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.posts-table td {
    padding: 16px 20px;
    border-top: 1px solid #f3f4f6;
    color: #374151;
}

.posts-table tbody tr:hover {
    background: #f9fafb;
}

.post-info {
    display: flex;
    gap: 15px;
}

.post-image {
    width: 80px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
}

.post-details h4 {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 5px 0;
}

.post-details p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-published {
    background: #d1fae5;
    color: #065f46;
}

.badge-draft {
    background: #fef3c7;
    color: #92400e;
}

.badge-featured {
    background: #dbeafe;
    color: #1e40af;
}

.category-badge {
    background: #f3e8ff;
    color: #6b21a8;
}

.post-meta {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.post-meta-item {
    font-size: 13px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 6px;
}

.post-meta-item i {
    color: #228B22;
    width: 14px;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.status-dropdown {
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    background: white;
    cursor: pointer;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
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
    .posts-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .posts-table {
        font-size: 13px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="admin-content">
    <div class="posts-header">
        <h1><i class="fas fa-blog"></i> Manage Blog Posts</h1>
        <div class="header-actions">
            <a href="<?php echo SITE_URL; ?>/blogs.php" class="btn btn-secondary" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Blog Page
            </a>
            <a href="add-blog-post.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Post
            </a>
        </div>
    </div>
    
    <?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span><?php echo $success; ?></span>
    </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo $error; ?></span>
    </div>
    <?php endif; ?>
    
    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon purple">
                    <i class="fas fa-blog"></i>
                </div>
            </div>
            <div class="stat-card-number"><?php echo count($posts); ?></div>
            <div class="stat-card-label">Total Posts</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo count(array_filter($posts, function($p) { return $p['status'] == 'published'; })); ?>
            </div>
            <div class="stat-card-label">Published</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon orange">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo count(array_filter($posts, function($p) { return $p['status'] == 'draft'; })); ?>
            </div>
            <div class="stat-card-label">Drafts</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon blue">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo array_sum(array_column($posts, 'views')); ?>
            </div>
            <div class="stat-card-label">Total Views</div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filters-card">
        <div class="filters-grid">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" id="searchInput" placeholder="Search posts..." onkeyup="filterTable()">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="statusFilter" onchange="filterTable()">
                    <option value="">All Statuses</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Category</label>
                <select id="categoryFilter" onchange="filterTable()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat['category']); ?>">
                        <?php echo htmlspecialchars($cat['category']); ?> (<?php echo $cat['count']; ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    
    <?php if (!empty($posts)): ?>
    <div class="posts-table-card">
        <table class="posts-table" id="postsTable">
            <thead>
                <tr>
                    <th>Post</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                <tr data-status="<?php echo $post['status']; ?>" data-category="<?php echo htmlspecialchars($post['category']); ?>">
                    <td>
                        <div class="post-info">
                            <?php if ($post['featured_image']): ?>
                            <img src="<?php echo UPLOAD_URL . '/blog/' . $post['featured_image']; ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>" 
                                 class="post-image">
                            <?php else: ?>
                            <div class="post-image"></div>
                            <?php endif; ?>
                            <div class="post-details">
                                <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                                <p>
                                    <?php if ($post['featured']): ?>
                                    <span class="badge badge-featured"><i class="fas fa-star"></i> Featured</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php echo $post['author_name'] ? htmlspecialchars($post['author_name']) : '<span style="color: #9ca3af;">—</span>'; ?>
                    </td>
                    <td>
                        <?php if ($post['category']): ?>
                        <span class="badge category-badge">
                            <?php echo htmlspecialchars($post['category']); ?>
                        </span>
                        <?php else: ?>
                        <span style="color: #9ca3af;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="post-meta-item">
                            <i class="fas fa-eye"></i>
                            <?php echo number_format($post['views']); ?>
                        </div>
                    </td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <select name="status" class="status-dropdown badge-<?php echo $post['status']; ?>" 
                                    onchange="this.form.submit()">
                                <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td>
                        <div style="font-size: 13px; color: #6b7280;">
                            <?php 
                            if ($post['published_at']) {
                                echo date('M d, Y', strtotime($post['published_at']));
                            } else {
                                echo '—';
                            }
                            ?>
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit-blog-post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <?php if ($post['status'] == 'published' && $post['slug']): ?>
                            <a href="<?php echo SITE_URL; ?>/blog-details.php?slug=<?php echo $post['slug']; ?>" 
                               class="btn btn-success btn-sm" target="_blank">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <?php endif; ?>
                            <button class="btn btn-danger btn-sm" 
                                    onclick="confirmDelete(<?php echo $post['id']; ?>, '<?php echo htmlspecialchars($post['title']); ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="posts-table-card">
        <div class="empty-state">
            <i class="fas fa-blog"></i>
            <h3>No blog posts found</h3>
            <p>Start by adding your first blog post</p>
            <a href="add-blog-post.php" class="btn btn-primary" style="margin-top: 20px;">
                <i class="fas fa-plus"></i> Add New Post
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" action="" style="display: none;">
    <input type="hidden" id="delete_post_id" name="post_id">
    <input type="hidden" name="delete_post" value="1">
</form>

<script>
function confirmDelete(postId, postTitle) {
    if (confirm('Are you sure you want to delete "' + postTitle + '"? This action cannot be undone.')) {
        document.getElementById('delete_post_id').value = postId;
        document.getElementById('deleteForm').submit();
    }
}

function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    const table = document.getElementById('postsTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        const status = row.getAttribute('data-status').toLowerCase();
        const category = row.getAttribute('data-category').toLowerCase();
        
        let showRow = true;
        
        // Search filter
        if (searchInput && !text.includes(searchInput)) {
            showRow = false;
        }
        
        // Status filter
        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }
        
        // Category filter
        if (categoryFilter && category !== categoryFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    }
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
