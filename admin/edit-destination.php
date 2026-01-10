<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Edit Destination';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$success = '';
$error = '';

// Get destination ID
$destinationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$destinationId) {
    header('Location: destinations.php');
    exit;
}

// Fetch destination data
$stmt = $db->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->execute([$destinationId]);
$destination = $stmt->fetch();

if (!$destination) {
    header('Location: destinations.php');
    exit;
}

// Get tour count for this destination
$stmt = $db->prepare("SELECT COUNT(*) FROM tours WHERE destination_id = ?");
$stmt->execute([$destinationId]);
$tourCount = $stmt->fetchColumn();

// Check for success message
if (isset($_GET['success'])) {
    $success = 'Destination created successfully! You can now add images and more details.';
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_image']) && isset($_FILES['featured_image'])) {
    $file = $_FILES['featured_image'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $filename = $file['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            // Create upload directory if not exists
            $uploadDir = DESTINATION_UPLOAD_DIR;
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename
            $newFilename = 'destination_' . $destinationId . '_' . time() . '.' . $ext;
            $destination_path = $uploadDir . '/' . $newFilename;
            
            if (move_uploaded_file($file['tmp_name'], $destination_path)) {
                // Delete old image if exists
                if ($destination['featured_image'] && file_exists(DESTINATION_UPLOAD_DIR . '/' . $destination['featured_image'])) {
                    unlink(DESTINATION_UPLOAD_DIR . '/' . $destination['featured_image']);
                }
                
                // Update database
                $stmt = $db->prepare("UPDATE destinations SET featured_image = ? WHERE id = ?");
                $stmt->execute([$newFilename, $destinationId]);
                
                $success = 'Image uploaded successfully!';
                
                // Refresh destination data
                $stmt = $db->prepare("SELECT * FROM destinations WHERE id = ?");
                $stmt->execute([$destinationId]);
                $destination = $stmt->fetch();
            } else {
                $error = 'Failed to move uploaded file.';
            }
        } else {
            $error = 'Invalid file type. Allowed: ' . implode(', ', $allowed);
        }
    } else {
        $error = 'Upload error: ' . $file['error'];
    }
}

// Handle image deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image'])) {
    if ($destination['featured_image'] && file_exists(DESTINATION_UPLOAD_DIR . '/' . $destination['featured_image'])) {
        unlink(DESTINATION_UPLOAD_DIR . '/' . $destination['featured_image']);
    }
    
    $stmt = $db->prepare("UPDATE destinations SET featured_image = NULL WHERE id = ?");
    $stmt->execute([$destinationId]);
    
    $success = 'Image deleted successfully!';
    
    // Refresh destination data
    $stmt = $db->prepare("SELECT * FROM destinations WHERE id = ?");
    $stmt->execute([$destinationId]);
    $destination = $stmt->fetch();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_destination'])) {
    $name = sanitize($_POST['name']);
    $slug = sanitize($_POST['slug']);
    $region = sanitize($_POST['region']);
    $country = sanitize($_POST['country']);
    $description = $_POST['description']; // Allow HTML
    $climate_info = $_POST['climate_info'];
    $best_time_to_visit = sanitize($_POST['best_time_to_visit']);
    $wildlife_info = $_POST['wildlife_info'];
    $meta_title = sanitize($_POST['meta_title']);
    $meta_description = sanitize($_POST['meta_description']);
    $meta_keywords = sanitize($_POST['meta_keywords']);
    $status = sanitize($_POST['status']);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $display_order = (int)$_POST['display_order'];
    
    try {
        $sql = "UPDATE destinations SET
                    name = ?, slug = ?, region = ?, country = ?, description = ?,
                    climate_info = ?, best_time_to_visit = ?, wildlife_info = ?,
                    meta_title = ?, meta_description = ?, meta_keywords = ?,
                    status = ?, featured = ?, display_order = ?
                WHERE id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $name, $slug, $region, $country, $description, $climate_info,
            $best_time_to_visit, $wildlife_info, $meta_title, $meta_description,
            $meta_keywords, $status, $featured, $display_order, $destinationId
        ]);
        
        $success = 'Destination updated successfully!';
        
        // Refresh destination data
        $stmt = $db->prepare("SELECT * FROM destinations WHERE id = ?");
        $stmt->execute([$destinationId]);
        $destination = $stmt->fetch();
        
    } catch (Exception $e) {
        $error = 'Error updating destination: ' . $e->getMessage();
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

.form-group textarea.large {
    min-height: 200px;
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
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-content">
    <div class="form-header">
        <h1><i class="fas fa-edit"></i> Edit Destination</h1>
        <div class="header-actions">
            <?php if ($destination['status'] == 'published' && $destination['slug']): ?>
            <a href="<?php echo SITE_URL; ?>/destination-details.php?slug=<?php echo $destination['slug']; ?>" 
               class="btn btn-success" target="_blank">
                <i class="fas fa-eye"></i> View Destination
            </a>
            <?php endif; ?>
            <a href="destinations.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Destinations
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
                <!-- Basic Information -->
                <div class="form-card">
                    <h2>Basic Information</h2>
                    
                    <div class="form-group">
                        <label>Destination Name <span class="required">*</span></label>
                        <input type="text" name="name" required 
                               value="<?php echo htmlspecialchars($destination['name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Slug <span class="required">*</span></label>
                        <input type="text" name="slug" required
                               value="<?php echo htmlspecialchars($destination['slug']); ?>">
                        <small>URL: <?php echo SITE_URL; ?>/destination-details.php?slug=<?php echo htmlspecialchars($destination['slug']); ?></small>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Region <span class="required">*</span></label>
                            <input type="text" name="region" required 
                                   value="<?php echo htmlspecialchars($destination['region']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Country <span class="required">*</span></label>
                            <input type="text" name="country" required 
                                   value="<?php echo htmlspecialchars($destination['country']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Best Time to Visit</label>
                        <input type="text" name="best_time_to_visit" 
                               value="<?php echo htmlspecialchars($destination['best_time_to_visit']); ?>">
                    </div>
                </div>
                
                <!-- Description -->
                <div class="form-card">
                    <h2>Description</h2>
                    
                    <div class="form-group">
                        <label>Full Description <span class="required">*</span></label>
                        <textarea name="description" class="large" required><?php echo htmlspecialchars($destination['description']); ?></textarea>
                    </div>
                </div>
                
                <!-- Climate & Wildlife -->
                <div class="form-card">
                    <h2>Climate & Wildlife Information</h2>
                    
                    <div class="form-group">
                        <label>Climate Information</label>
                        <textarea name="climate_info"><?php echo htmlspecialchars($destination['climate_info']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Wildlife Information</label>
                        <textarea name="wildlife_info"><?php echo htmlspecialchars($destination['wildlife_info']); ?></textarea>
                    </div>
                </div>
                
                <!-- SEO Settings -->
                <div class="form-card">
                    <h2>SEO Settings</h2>
                    
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" 
                               value="<?php echo htmlspecialchars($destination['meta_title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea name="meta_description"><?php echo htmlspecialchars($destination['meta_description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords" 
                               value="<?php echo htmlspecialchars($destination['meta_keywords']); ?>">
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
                            <option value="draft" <?php echo $destination['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $destination['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="featured" id="featured" 
                               <?php echo $destination['featured'] ? 'checked' : ''; ?>>
                        <label for="featured">Featured Destination</label>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" min="0"
                               value="<?php echo $destination['display_order']; ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update_destination" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Update Destination
                        </button>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Statistics</h3>
                    <div class="stats-box">
                        <div class="stats-item">
                            <div class="stats-icon">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <div class="stats-content">
                                <div class="stats-number"><?php echo $tourCount; ?></div>
                                <div class="stats-label">Associated Tours</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Featured Image</h3>
                    <?php if ($destination['featured_image']): ?>
                    <div class="current-image" style="margin-bottom: 15px;">
                        <img src="<?php echo UPLOAD_URL . '/destinations/' . $destination['featured_image']; ?>" 
                             alt="Featured Image" 
                             style="width: 100%; height: auto; border-radius: 8px; margin-bottom: 10px;">
                        <form method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this image?');">
                            <button type="submit" name="delete_image" class="btn btn-secondary btn-sm" 
                                    style="width: 100%; justify-content: center;">
                                <i class="fas fa-trash"></i> Delete Image
                            </button>
                        </form>
                    </div>
                    <?php else: ?>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">No featured image uploaded</p>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data" style="margin: 0;">
                        <div class="form-group" style="margin-bottom: 10px;">
                            <input type="file" name="featured_image" accept="image/*" 
                                   id="featuredImageInput" style="display: none;" 
                                   onchange="document.getElementById('uploadImageBtn').disabled = false;">
                            <button type="button" class="btn btn-secondary btn-sm" 
                                    onclick="document.getElementById('featuredImageInput').click();"
                                    style="width: 100%; justify-content: center;">
                                <i class="fas fa-image"></i> Choose Image
                            </button>
                        </div>
                        <button type="submit" name="upload_image" id="uploadImageBtn" 
                                class="btn btn-primary btn-sm" disabled
                                style="width: 100%; justify-content: center;">
                            <i class="fas fa-upload"></i> Upload Image
                        </button>
                    </form>
                </div>
                
                <div class="sidebar-card">
                    <h3>Destination Info</h3>
                    <div class="info-box">
                        <div class="info-box-item">
                            <span class="info-box-label">ID:</span>
                            <span><?php echo $destination['id']; ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Created:</span>
                            <span><?php echo date('M d, Y', strtotime($destination['created_at'])); ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Updated:</span>
                            <span><?php echo date('M d, Y', strtotime($destination['updated_at'])); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Quick Actions</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php if ($destination['status'] == 'published' && $destination['slug']): ?>
                        <a href="<?php echo SITE_URL; ?>/destination-details.php?slug=<?php echo $destination['slug']; ?>" 
                           class="btn btn-success btn-sm" target="_blank" style="justify-content: center;">
                            <i class="fas fa-external-link-alt"></i> View on Site
                        </a>
                        <?php endif; ?>
                        <?php if ($tourCount > 0): ?>
                        <a href="tours.php" class="btn btn-secondary btn-sm" style="justify-content: center;">
                            <i class="fas fa-mountain"></i> View Tours (<?php echo $tourCount; ?>)
                        </a>
                        <?php endif; ?>
                        <a href="destinations.php" class="btn btn-secondary btn-sm" style="justify-content: center;">
                            <i class="fas fa-list"></i> All Destinations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
