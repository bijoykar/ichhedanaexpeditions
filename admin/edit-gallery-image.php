<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Edit Gallery Image';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$destinationModel = new Destination();
$tourModel = new Tour();
$success = '';
$error = '';

// Get image ID
$imageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$imageId) {
    header('Location: gallery.php');
    exit;
}

// Fetch image data
$stmt = $db->prepare("SELECT g.*, d.name as destination_name 
                      FROM gallery g 
                      LEFT JOIN destinations d ON g.destination_id = d.id 
                      WHERE g.id = ?");
$stmt->execute([$imageId]);
$image = $stmt->fetch();

if (!$image) {
    header('Location: gallery.php');
    exit;
}

// Get all destinations and tours for dropdowns
$destinations = $destinationModel->all();
$tours = $tourModel->all();

// Check for success message
if (isset($_GET['success'])) {
    $success = 'Gallery image created successfully! You can now upload the actual image file.';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_image'])) {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category = sanitize($_POST['category']);
    $destination_id = $_POST['destination_id'] ? (int)$_POST['destination_id'] : null;
    $tour_id = $_POST['tour_id'] ? (int)$_POST['tour_id'] : null;
    $photographer = sanitize($_POST['photographer']);
    $camera_settings = sanitize($_POST['camera_settings']);
    $location = sanitize($_POST['location']);
    $taken_date = $_POST['taken_date'] ? sanitize($_POST['taken_date']) : null;
    $display_order = (int)$_POST['display_order'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    $show_in_carousel = isset($_POST['show_in_carousel']) ? 1 : 0;
    $carousel_order = (int)$_POST['carousel_order'];
    $status = sanitize($_POST['status']);
    
    try {
        $sql = "UPDATE gallery SET
                    title = ?, description = ?, category = ?, destination_id = ?,
                    tour_id = ?, photographer = ?, camera_settings = ?, location = ?,
                    taken_date = ?, display_order = ?, featured = ?, show_in_carousel = ?,
                    carousel_order = ?, status = ?
                WHERE id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $title, $description, $category, $destination_id, $tour_id,
            $photographer, $camera_settings, $location, $taken_date,
            $display_order, $featured, $show_in_carousel, $carousel_order, $status, $imageId
        ]);
        
        $success = 'Gallery image updated successfully!';
        
        // Refresh image data
        $stmt = $db->prepare("SELECT g.*, d.name as destination_name 
                              FROM gallery g 
                              LEFT JOIN destinations d ON g.destination_id = d.id 
                              WHERE g.id = ?");
        $stmt->execute([$imageId]);
        $image = $stmt->fetch();
        
    } catch (Exception $e) {
        $error = 'Error updating image: ' . $e->getMessage();
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

.form-actions .btn {
    width: 100%;
    justify-content: center;
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

.current-image {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 15px;
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
.form-group input[type="date"],
.form-group input[type="number"],
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

.form-group small {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #6b7280;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
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

@media (max-width: 992px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-content">
    <div class="form-header">
        <h1><i class="fas fa-edit"></i> Edit Gallery Image</h1>
        <div class="header-actions">
            <a href="gallery.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Gallery
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
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-layout">
            <div class="form-main">
                <!-- Current Image -->
                <div class="form-card">
                    <h2>Current Image</h2>
                    
                    <?php if ($image['image_path'] && $image['image_path'] != 'placeholder.jpg'): ?>
                    <img src="<?php echo UPLOAD_URL . '/gallery/' . $image['image_path']; ?>" 
                         alt="<?php echo htmlspecialchars($image['title'] ?? ''); ?>" 
                         class="current-image">
                    <?php else: ?>
                    <div class="current-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;">
                        <i class="fas fa-image" style="font-size: 64px; opacity: 0.5;"></i>
                    </div>
                    <?php endif; ?>
                    
                    <small style="color: #6b7280;">Upload new image to replace (feature not yet implemented)</small>
                </div>
                
                <!-- Basic Information -->
                <div class="form-card">
                    <h2>Basic Information</h2>
                    
                    <div class="form-group">
                        <label>Title <span class="required">*</span></label>
                        <input type="text" name="title" required 
                               value="<?php echo htmlspecialchars($image['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description"><?php echo htmlspecialchars($image['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="">Select Category</option>
                            <option value="Wildlife" <?php echo $image['category'] == 'Wildlife' ? 'selected' : ''; ?>>Wildlife</option>
                            <option value="Birds" <?php echo $image['category'] == 'Birds' ? 'selected' : ''; ?>>Birds</option>
                            <option value="Landscape" <?php echo $image['category'] == 'Landscape' ? 'selected' : ''; ?>>Landscape</option>
                            <option value="Flora" <?php echo $image['category'] == 'Flora' ? 'selected' : ''; ?>>Flora</option>
                            <option value="Adventure" <?php echo $image['category'] == 'Adventure' ? 'selected' : ''; ?>>Adventure</option>
                            <option value="Culture" <?php echo $image['category'] == 'Culture' ? 'selected' : ''; ?>>Culture</option>
                            <option value="People" <?php echo $image['category'] == 'People' ? 'selected' : ''; ?>>People</option>
                        </select>
                    </div>
                </div>
                
                <!-- Location & Association -->
                <div class="form-card">
                    <h2>Location & Association</h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Destination</label>
                            <select name="destination_id">
                                <option value="">Select Destination</option>
                                <?php foreach ($destinations as $dest): ?>
                                <option value="<?php echo $dest['id']; ?>"
                                        <?php echo $image['destination_id'] == $dest['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dest['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Tour</label>
                            <select name="tour_id">
                                <option value="">Select Tour</option>
                                <?php foreach ($tours as $tour): ?>
                                <option value="<?php echo $tour['id']; ?>"
                                        <?php echo $image['tour_id'] == $tour['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tour['title']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" 
                               value="<?php echo htmlspecialchars($image['location'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Date Taken</label>
                        <input type="date" name="taken_date" 
                               value="<?php echo $image['taken_date'] ?? ''; ?>">
                    </div>
                </div>
                
                <!-- Photography Details -->
                <div class="form-card">
                    <h2>Photography Details</h2>
                    
                    <div class="form-group">
                        <label>Photographer</label>
                        <input type="text" name="photographer" 
                               value="<?php echo htmlspecialchars($image['photographer'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Camera Settings</label>
                        <input type="text" name="camera_settings" 
                               value="<?php echo htmlspecialchars($image['camera_settings'] ?? ''); ?>">
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
                            <option value="draft" <?php echo $image['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $image['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="featured" id="featured" 
                               <?php echo $image['featured'] ? 'checked' : ''; ?>>
                        <label for="featured">Featured Image</label>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" min="0"
                               value="<?php echo $image['display_order']; ?>">
                        <small>Lower numbers appear first</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update_image" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Image
                        </button>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Home Page Carousel</h3>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="show_in_carousel" id="show_in_carousel"
                               <?php echo $image['show_in_carousel'] ? 'checked' : ''; ?>>
                        <label for="show_in_carousel">Show in Home Banner</label>
                    </div>
                    
                    <div class="form-group">
                        <label>Carousel Order</label>
                        <input type="number" name="carousel_order" min="0"
                               value="<?php echo $image['carousel_order']; ?>">
                        <small>Sequence in home page slider</small>
                    </div>
                    
                    <div class="help-text">
                        <small><i class="fas fa-info-circle"></i> Enable to display this image in the home page hero banner carousel.</small>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Image Info</h3>
                    <div class="info-box">
                        <div class="info-box-item">
                            <span class="info-box-label">ID:</span>
                            <span><?php echo $image['id']; ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Created:</span>
                            <span><?php echo date('M d, Y', strtotime($image['created_at'])); ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Updated:</span>
                            <span><?php echo date('M d, Y', strtotime($image['updated_at'])); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Quick Actions</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="gallery.php" class="btn btn-secondary btn-sm" style="justify-content: center;">
                            <i class="fas fa-images"></i> All Images
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
