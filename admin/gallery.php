<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Manage Gallery';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$galleryModel = new Gallery($db);
$destinationModel = new Destination($db);
$success = '';
$error = '';

// Handle gallery actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_image'])) {
        $id = (int)$_POST['image_id'];
        
        try {
            // Get image path before deleting
            $stmt = $db->prepare("SELECT image_path, thumbnail_path FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            $image = $stmt->fetch();
            
            // Delete from database
            $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            
            // TODO: Delete physical files if needed
            
            $success = 'Image deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting image: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_status'])) {
        $id = (int)$_POST['image_id'];
        $status = sanitize($_POST['status']);
        
        try {
            $stmt = $db->prepare("UPDATE gallery SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = 'Image status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating status: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['toggle_featured'])) {
        $id = (int)$_POST['image_id'];
        $featured = (int)$_POST['featured'];
        
        try {
            $stmt = $db->prepare("UPDATE gallery SET featured = ? WHERE id = ?");
            $stmt->execute([$featured, $id]);
            $success = 'Featured status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating featured status: ' . $e->getMessage();
        }
    }
}

// Fetch all gallery images with destination info
$sql = "SELECT g.*, d.name as destination_name 
        FROM gallery g 
        LEFT JOIN destinations d ON g.destination_id = d.id 
        ORDER BY g.display_order ASC, g.created_at DESC";
$images = $db->query($sql)->fetchAll();

// Get categories - fetch as single column array
$categoriesQuery = "SELECT DISTINCT category FROM gallery 
                    WHERE category IS NOT NULL AND category != '' 
                    ORDER BY category ASC";
$stmt = $db->query($categoriesQuery);
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Get destinations for filter
$destinations = $destinationModel->all();
?>

<style>
.admin-content {
    padding: 30px;
}

.gallery-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.gallery-header h1 {
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
    color: #667eea;
}

.stat-card-icon.green {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stat-card-icon.orange {
    background: rgba(251, 146, 60, 0.1);
    color: #fb923c;
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

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.gallery-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.gallery-image-container {
    position: relative;
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
    background: #f3f4f6;
}

.placeholder-image {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.8);
    font-size: 48px;
}

.placeholder-image i {
    margin-bottom: 10px;
}

.placeholder-image span {
    font-size: 14px;
    font-weight: 600;
}

.gallery-card:hover .gallery-image {
    transform: scale(1.05);
}

.featured-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255, 193, 7, 0.95);
    color: #1a1a1a;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.carousel-badge {
    position: absolute;
    top: 50px;
    right: 12px;
    background: rgba(102, 126, 234, 0.95);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-published {
    background: rgba(16, 185, 129, 0.95);
    color: white;
}

.status-draft {
    background: rgba(107, 114, 128, 0.95);
    color: white;
}

.gallery-content {
    padding: 20px;
}

.gallery-title {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.gallery-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 15px;
}

.gallery-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #6b7280;
}

.gallery-meta-item i {
    color: #667eea;
    width: 14px;
}

.category-badge {
    display: inline-block;
    padding: 4px 10px;
    background: #f3e8ff;
    color: #6b21a8;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.gallery-actions {
    display: flex;
    gap: 8px;
    padding-top: 15px;
    border-top: 1px solid #f3f4f6;
}

.gallery-actions .btn {
    transition: all 0.2s ease;
}

.gallery-actions .btn:hover {
    transform: translateY(-1px);
}

.gallery-card:hover .gallery-actions {
    opacity: 1;
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
    .gallery-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-content">
    <div class="gallery-header">
        <h1><i class="fas fa-images"></i> Manage Gallery</h1>
        <div class="header-actions">
            <a href="<?php echo SITE_URL; ?>/gallery.php" class="btn btn-secondary" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Gallery Page
            </a>
            <a href="add-gallery-image.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Image
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
                    <i class="fas fa-images"></i>
                </div>
            </div>
            <div class="stat-card-number"><?php echo count($images); ?></div>
            <div class="stat-card-label">Total Images</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo count(array_filter($images, function($i) { return $i['status'] == 'published'; })); ?>
            </div>
            <div class="stat-card-label">Published</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon orange">
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo count(array_filter($images, function($i) { return $i['featured']; })); ?>
            </div>
            <div class="stat-card-label">Featured</div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filters-card">
        <div class="filters-grid">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" id="searchInput" placeholder="Search images..." onkeyup="filterGallery()">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="statusFilter" onchange="filterGallery()">
                    <option value="">All Statuses</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Category</label>
                <select id="categoryFilter" onchange="filterGallery()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                    <?php if ($cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>">
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label>Featured</label>
                <select id="featuredFilter" onchange="filterGallery()">
                    <option value="">All Images</option>
                    <option value="1">Featured Only</option>
                    <option value="0">Non-Featured</option>
                </select>
            </div>
        </div>
    </div>
    
    <?php if (!empty($images)): ?>
    <div class="gallery-grid" id="galleryGrid">
        <?php foreach ($images as $image): ?>
        <div class="gallery-card" 
             data-status="<?php echo $image['status']; ?>" 
             data-category="<?php echo htmlspecialchars($image['category']); ?>"
             data-featured="<?php echo $image['featured']; ?>"
             data-title="<?php echo htmlspecialchars($image['title']); ?>">
            <div class="gallery-image-container">
                <?php 
                $imagePath = UPLOAD_PATH . '/gallery/' . $image['image_path'];
                $imageUrl = UPLOAD_URL . '/gallery/' . $image['image_path'];
                
                // Check if image file exists
                $imageExists = $image['image_path'] && file_exists($imagePath);
                ?>
                
                <?php if ($imageExists): ?>
                    <img src="<?php echo $imageUrl; ?>" 
                         alt="<?php echo htmlspecialchars($image['title']); ?>" 
                         class="gallery-image">
                <?php else: ?>
                    <div class="placeholder-image">
                        <i class="fas fa-image"></i>
                        <span>No Image</span>
                    </div>
                <?php endif; ?>
                
                <span class="status-badge status-<?php echo $image['status']; ?>">
                    <?php echo ucfirst($image['status']); ?>
                </span>
                
                <?php if ($image['featured']): ?>
                <span class="featured-badge">
                    <i class="fas fa-star"></i> Featured
                </span>
                <?php endif; ?>
                
                <?php if ($image['show_in_carousel']): ?>
                <span class="carousel-badge">
                    <i class="fas fa-images"></i> Carousel #<?php echo $image['carousel_order']; ?>
                </span>
                <?php endif; ?>
            </div>
            
            <div class="gallery-content">
                <h3 class="gallery-title"><?php echo htmlspecialchars($image['title']); ?></h3>
                
                <div class="gallery-meta">
                    <?php if ($image['category']): ?>
                    <div class="gallery-meta-item">
                        <i class="fas fa-folder"></i>
                        <span class="category-badge"><?php echo htmlspecialchars($image['category']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($image['destination_name']): ?>
                    <div class="gallery-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo htmlspecialchars($image['destination_name']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($image['photographer']): ?>
                    <div class="gallery-meta-item">
                        <i class="fas fa-camera"></i>
                        <span><?php echo htmlspecialchars($image['photographer']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="gallery-meta-item">
                        <i class="fas fa-sort"></i>
                        <span>Order: <?php echo $image['display_order']; ?></span>
                    </div>
                </div>
                
                <div class="gallery-actions">
                    <a href="edit-gallery-image.php?id=<?php echo $image['id']; ?>" 
                       class="btn btn-primary btn-sm" style="flex: 1;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                        <input type="hidden" name="featured" value="<?php echo $image['featured'] ? 0 : 1; ?>">
                        <button type="submit" name="toggle_featured" 
                                class="btn btn-icon <?php echo $image['featured'] ? 'btn-warning' : 'btn-secondary'; ?>" 
                                title="<?php echo $image['featured'] ? 'Remove from Featured' : 'Mark as Featured'; ?>">
                            <i class="fas fa-star"></i>
                        </button>
                    </form>
                    
                    <button class="btn btn-danger btn-icon" 
                            onclick="confirmDelete(<?php echo $image['id']; ?>, '<?php echo htmlspecialchars($image['title']); ?>')"
                            title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-images"></i>
        <h3>No images found</h3>
        <p>Start by adding your first gallery image</p>
        <a href="add-gallery-image.php" class="btn btn-primary" style="margin-top: 20px;">
            <i class="fas fa-plus"></i> Add New Image
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" action="" style="display: none;">
    <input type="hidden" id="delete_image_id" name="image_id">
    <input type="hidden" name="delete_image" value="1">
</form>

<script>
function confirmDelete(imageId, imageTitle) {
    if (confirm('Are you sure you want to delete "' + imageTitle + '"? This action cannot be undone.')) {
        document.getElementById('delete_image_id').value = imageId;
        document.getElementById('deleteForm').submit();
    }
}

function filterGallery() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    const featuredFilter = document.getElementById('featuredFilter').value;
    const cards = document.querySelectorAll('.gallery-card');
    
    cards.forEach(card => {
        const title = card.getAttribute('data-title').toLowerCase();
        const status = card.getAttribute('data-status').toLowerCase();
        const category = card.getAttribute('data-category').toLowerCase();
        const featured = card.getAttribute('data-featured');
        
        let showCard = true;
        
        // Search filter
        if (searchInput && !title.includes(searchInput)) {
            showCard = false;
        }
        
        // Status filter
        if (statusFilter && status !== statusFilter) {
            showCard = false;
        }
        
        // Category filter
        if (categoryFilter && category !== categoryFilter) {
            showCard = false;
        }
        
        // Featured filter
        if (featuredFilter && featured !== featuredFilter) {
            showCard = false;
        }
        
        card.style.display = showCard ? '' : 'none';
    });
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
