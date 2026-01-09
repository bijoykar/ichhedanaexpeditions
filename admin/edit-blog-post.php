<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Edit Blog Post';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$currentAdminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
$success = '';
$error = '';

// Get post ID
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$postId) {
    header('Location: blog-posts.php');
    exit;
}

// Fetch post data with author
$stmt = $db->prepare("SELECT p.*, a.full_name as author_name 
                      FROM blog_posts p 
                      LEFT JOIN admin_users a ON p.author_id = a.id 
                      WHERE p.id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: blog-posts.php');
    exit;
}

// Check for success message
if (isset($_GET['success'])) {
    $success = 'Blog post created successfully! You can now add images and more details.';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post'])) {
    $title = sanitize($_POST['title']);
    $slug = sanitize($_POST['slug']);
    $excerpt = sanitize($_POST['excerpt']);
    $content = $_POST['content']; // Allow HTML
    $category = sanitize($_POST['category']);
    $tags = sanitize($_POST['tags']);
    $meta_title = sanitize($_POST['meta_title']);
    $meta_description = sanitize($_POST['meta_description']);
    $meta_keywords = sanitize($_POST['meta_keywords']);
    $status = sanitize($_POST['status']);
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    // Handle published_at
    $published_at = $post['published_at'];
    if ($status == 'published' && !$published_at) {
        $published_at = date('Y-m-d H:i:s');
    }
    
    try {
        $sql = "UPDATE blog_posts SET
                    title = ?, slug = ?, excerpt = ?, content = ?, category = ?,
                    tags = ?, meta_title = ?, meta_description = ?, meta_keywords = ?,
                    status = ?, featured = ?, published_at = ?
                WHERE id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $title, $slug, $excerpt, $content, $category, $tags,
            $meta_title, $meta_description, $meta_keywords, $status,
            $featured, $published_at, $postId
        ]);
        
        $success = 'Blog post updated successfully!';
        
        // Refresh post data
        $stmt = $db->prepare("SELECT p.*, a.full_name as author_name 
                              FROM blog_posts p 
                              LEFT JOIN admin_users a ON p.author_id = a.id 
                              WHERE p.id = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch();
        
    } catch (Exception $e) {
        $error = 'Error updating post: ' . $e->getMessage();
    }
}
?>

<style>
.admin-content {
    padding: 30px;
    max-width: 1400px;
    margin: 0 auto;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.form-header h1 {
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

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
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

.form-layout {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 25px;
}

.form-main {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 25px;
}

.form-card h2 {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 20px 0;
    padding-bottom: 15px;
    border-bottom: 2px solid #f3f4f6;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-group label .required {
    color: #ef4444;
}

.form-group input[type="text"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-group textarea.large {
    min-height: 300px;
}

.form-group small {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #6b7280;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: #f9fafb;
    border-radius: 8px;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-group label {
    margin: 0 !important;
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 12px;
    padding-top: 20px;
    border-top: 2px solid #f3f4f6;
}

.sidebar-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 25px;
    margin-bottom: 25px;
}

.sidebar-card h3 {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 15px 0;
}

.info-box {
    background: #f9fafb;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.info-box-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 13px;
    color: #374151;
}

.info-box-label {
    font-weight: 600;
    color: #6b7280;
}

.stats-box {
    background: #eff6ff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.stats-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
}

.stats-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #667eea;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.stats-content {
    flex: 1;
}

.stats-number {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
}

.stats-label {
    font-size: 12px;
    color: #6b7280;
}

@media (max-width: 992px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-content">
    <div class="form-header">
        <h1><i class="fas fa-edit"></i> Edit Blog Post</h1>
        <div class="header-actions">
            <?php if ($post['status'] == 'published' && $post['slug']): ?>
            <a href="<?php echo SITE_URL; ?>/blog-details.php?slug=<?php echo $post['slug']; ?>" 
               class="btn btn-success" target="_blank">
                <i class="fas fa-eye"></i> View Post
            </a>
            <?php endif; ?>
            <a href="blog-posts.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Posts
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
    
    <form method="POST" action="">
        <div class="form-layout">
            <div class="form-main">
                <!-- Basic Information -->
                <div class="form-card">
                    <h2>Basic Information</h2>
                    
                    <div class="form-group">
                        <label>Post Title <span class="required">*</span></label>
                        <input type="text" name="title" required 
                               value="<?php echo htmlspecialchars($post['title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Slug <span class="required">*</span></label>
                        <input type="text" name="slug" required
                               value="<?php echo htmlspecialchars($post['slug']); ?>">
                        <small>URL: <?php echo SITE_URL; ?>/blog-details.php?slug=<?php echo htmlspecialchars($post['slug']); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Excerpt <span class="required">*</span></label>
                        <textarea name="excerpt" required><?php echo htmlspecialchars($post['excerpt']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="">Select Category</option>
                            <option value="Wildlife" <?php echo $post['category'] == 'Wildlife' ? 'selected' : ''; ?>>Wildlife</option>
                            <option value="Photography Tips" <?php echo $post['category'] == 'Photography Tips' ? 'selected' : ''; ?>>Photography Tips</option>
                            <option value="Travel Stories" <?php echo $post['category'] == 'Travel Stories' ? 'selected' : ''; ?>>Travel Stories</option>
                            <option value="Travel Tips" <?php echo $post['category'] == 'Travel Tips' ? 'selected' : ''; ?>>Travel Tips</option>
                            <option value="Bird Watching" <?php echo $post['category'] == 'Bird Watching' ? 'selected' : ''; ?>>Bird Watching</option>
                            <option value="Conservation" <?php echo $post['category'] == 'Conservation' ? 'selected' : ''; ?>>Conservation</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Tags</label>
                        <input type="text" name="tags" 
                               value="<?php echo htmlspecialchars($post['tags']); ?>">
                    </div>
                </div>
                
                <!-- Content -->
                <div class="form-card">
                    <h2>Post Content</h2>
                    
                    <div class="form-group">
                        <label>Full Content <span class="required">*</span></label>
                        <textarea name="content" class="large" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                </div>
                
                <!-- SEO Settings -->
                <div class="form-card">
                    <h2>SEO Settings</h2>
                    
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" 
                               value="<?php echo htmlspecialchars($post['meta_title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea name="meta_description"><?php echo htmlspecialchars($post['meta_description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords" 
                               value="<?php echo htmlspecialchars($post['meta_keywords']); ?>">
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="form-sidebar">
                <div class="sidebar-card">
                    <h3>Publish</h3>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="featured" id="featured" 
                               <?php echo $post['featured'] ? 'checked' : ''; ?>>
                        <label for="featured">Featured Post</label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update_post" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Update Post
                        </button>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Statistics</h3>
                    <div class="stats-box">
                        <div class="stats-item">
                            <div class="stats-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="stats-content">
                                <div class="stats-number"><?php echo number_format($post['views']); ?></div>
                                <div class="stats-label">Total Views</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Post Info</h3>
                    <div class="info-box">
                        <div class="info-box-item">
                            <span class="info-box-label">ID:</span>
                            <span><?php echo $post['id']; ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Author:</span>
                            <span><?php echo htmlspecialchars($post['author_name']); ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Created:</span>
                            <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Updated:</span>
                            <span><?php echo date('M d, Y', strtotime($post['updated_at'])); ?></span>
                        </div>
                        <?php if ($post['published_at']): ?>
                        <div class="info-box-item">
                            <span class="info-box-label">Published:</span>
                            <span><?php echo date('M d, Y', strtotime($post['published_at'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Quick Actions</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php if ($post['status'] == 'published' && $post['slug']): ?>
                        <a href="<?php echo SITE_URL; ?>/blog-details.php?slug=<?php echo $post['slug']; ?>" 
                           class="btn btn-success btn-sm" target="_blank" style="justify-content: center;">
                            <i class="fas fa-external-link-alt"></i> View on Site
                        </a>
                        <?php endif; ?>
                        <a href="blog-posts.php" class="btn btn-secondary btn-sm" style="justify-content: center;">
                            <i class="fas fa-list"></i> All Posts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
