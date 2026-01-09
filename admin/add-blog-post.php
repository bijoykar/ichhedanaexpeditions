<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Add New Blog Post';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$currentAdminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post'])) {
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
    $published_at = null;
    
    // Generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }
    
    // Set published_at if publishing
    if ($status == 'published') {
        $published_at = date('Y-m-d H:i:s');
    }
    
    try {
        $sql = "INSERT INTO blog_posts (
                    title, slug, author_id, excerpt, content, category, tags,
                    meta_title, meta_description, meta_keywords, status, featured, published_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $title, $slug, $currentAdminId, $excerpt, $content, $category, $tags,
            $meta_title, $meta_description, $meta_keywords, $status, $featured, $published_at
        ]);
        
        $success = 'Blog post added successfully!';
        // Redirect to edit page with ID
        $postId = $db->lastInsertId();
        header("Location: edit-blog-post.php?id=$postId&success=1");
        exit;
        
    } catch (Exception $e) {
        $error = 'Error adding post: ' . $e->getMessage();
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

.alert {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
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

.help-text {
    background: #eff6ff;
    border-left: 4px solid #667eea;
    padding: 15px;
    border-radius: 6px;
    font-size: 13px;
    color: #374151;
    line-height: 1.6;
}

.help-text strong {
    color: #667eea;
    display: block;
    margin-bottom: 5px;
}

@media (max-width: 992px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-content">
    <div class="form-header">
        <h1><i class="fas fa-plus-circle"></i> Add New Blog Post</h1>
        <a href="blog-posts.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Posts
        </a>
    </div>
    
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
                               placeholder="e.g., Wildlife Photography Tips for Beginners">
                        <small>The main title of your blog post</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" 
                               placeholder="Leave blank to auto-generate from title">
                        <small>URL-friendly version (e.g., wildlife-photography-tips-beginners)</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Excerpt <span class="required">*</span></label>
                        <textarea name="excerpt" required 
                                  placeholder="A brief summary (2-3 sentences) that appears in post listings"></textarea>
                        <small>Keep it concise and engaging (recommended: 150-160 characters)</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="">Select Category</option>
                            <option value="Wildlife">Wildlife</option>
                            <option value="Photography Tips">Photography Tips</option>
                            <option value="Travel Stories">Travel Stories</option>
                            <option value="Travel Tips">Travel Tips</option>
                            <option value="Bird Watching">Bird Watching</option>
                            <option value="Conservation">Conservation</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Tags</label>
                        <input type="text" name="tags" 
                               placeholder="wildlife, photography, bird watching">
                        <small>Comma-separated tags</small>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="form-card">
                    <h2>Post Content</h2>
                    
                    <div class="form-group">
                        <label>Full Content <span class="required">*</span></label>
                        <textarea name="content" class="large" required
                                  placeholder="Write your blog post content here..."></textarea>
                        <small>Full article content with HTML formatting</small>
                    </div>
                </div>
                
                <!-- SEO Settings -->
                <div class="form-card">
                    <h2>SEO Settings</h2>
                    
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" 
                               placeholder="Leave blank to use post title">
                        <small>Recommended: 50-60 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea name="meta_description" 
                                  placeholder="Brief description for search engines..."></textarea>
                        <small>Recommended: 150-160 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords" 
                               placeholder="wildlife, photography, bird watching, nature">
                        <small>Comma-separated keywords</small>
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
                            <option value="draft" selected>Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="featured" id="featured">
                        <label for="featured">Featured Post</label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="add_post" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Create Post
                        </button>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Writing Tips</h3>
                    <div class="help-text">
                        <strong>Best Practices:</strong>
                        • Write engaging headlines<br>
                        • Use clear subheadings<br>
                        • Include relevant images<br>
                        • Keep paragraphs short<br>
                        • Add internal links<br>
                        • Optimize for SEO
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
