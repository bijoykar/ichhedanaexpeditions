<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Edit Tour';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$destinationModel = new Destination();
$success = '';
$error = '';

// Get tour ID
$tourId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$tourId) {
    header('Location: tours.php');
    exit;
}

// Fetch tour data
$stmt = $db->prepare("SELECT * FROM tours WHERE id = ?");
$stmt->execute([$tourId]);
$tour = $stmt->fetch();

if (!$tour) {
    header('Location: tours.php');
    exit;
}

// Get all destinations for dropdown
$destinations = $destinationModel->all();

// Check for success message
if (isset($_GET['success'])) {
    $success = 'Tour created successfully! You can now add images and more details.';
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
            $uploadDir = TOUR_UPLOAD_DIR;
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename
            $newFilename = 'tour_' . $tourId . '_' . time() . '.' . $ext;
            $destination = $uploadDir . '/' . $newFilename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Delete old image if exists
                if ($tour['featured_image'] && file_exists(TOUR_UPLOAD_DIR . '/' . $tour['featured_image'])) {
                    unlink(TOUR_UPLOAD_DIR . '/' . $tour['featured_image']);
                }
                
                // Update database
                $stmt = $db->prepare("UPDATE tours SET featured_image = ? WHERE id = ?");
                $stmt->execute([$newFilename, $tourId]);
                
                $success = 'Image uploaded successfully!';
                
                // Refresh tour data
                $stmt = $db->prepare("SELECT * FROM tours WHERE id = ?");
                $stmt->execute([$tourId]);
                $tour = $stmt->fetch();
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
    if ($tour['featured_image'] && file_exists(TOUR_UPLOAD_DIR . '/' . $tour['featured_image'])) {
        unlink(TOUR_UPLOAD_DIR . '/' . $tour['featured_image']);
    }
    
    $stmt = $db->prepare("UPDATE tours SET featured_image = NULL WHERE id = ?");
    $stmt->execute([$tourId]);
    
    $success = 'Image deleted successfully!';
    
    // Refresh tour data
    $stmt = $db->prepare("SELECT * FROM tours WHERE id = ?");
    $stmt->execute([$tourId]);
    $tour = $stmt->fetch();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_tour'])) {
    $title = sanitize($_POST['title']);
    $slug = sanitize($_POST['slug']);
    $destination_id = $_POST['destination_id'] ? (int)$_POST['destination_id'] : null;
    $short_description = sanitize($_POST['short_description']);
    $full_description = $_POST['full_description']; // Allow HTML
    $itinerary = $_POST['itinerary']; // Allow HTML
    $duration_days = (int)$_POST['duration_days'];
    $duration_nights = (int)$_POST['duration_nights'];
    $start_date = $_POST['start_date'] ? sanitize($_POST['start_date']) : null;
    $end_date = $_POST['end_date'] ? sanitize($_POST['end_date']) : null;
    $price = $_POST['price'] ? (float)$_POST['price'] : null;
    $max_participants = (int)$_POST['max_participants'];
    $difficulty_level = sanitize($_POST['difficulty_level']);
    $included_services = $_POST['included_services']; // Allow HTML
    $excluded_services = $_POST['excluded_services']; // Allow HTML
    $accommodation_details = $_POST['accommodation_details']; // Allow HTML
    $photography_highlights = $_POST['photography_highlights']; // Allow HTML
    $meta_title = sanitize($_POST['meta_title']);
    $meta_description = sanitize($_POST['meta_description']);
    $meta_keywords = sanitize($_POST['meta_keywords']);
    $status = sanitize($_POST['status']);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $display_order = (int)$_POST['display_order'];
    
    try {
        $sql = "UPDATE tours SET
                    title = ?, slug = ?, destination_id = ?, short_description = ?,
                    full_description = ?, itinerary = ?, duration_days = ?, duration_nights = ?,
                    start_date = ?, end_date = ?, price = ?, max_participants = ?,
                    difficulty_level = ?, included_services = ?, excluded_services = ?,
                    accommodation_details = ?, photography_highlights = ?, meta_title = ?,
                    meta_description = ?, meta_keywords = ?, status = ?, featured = ?,
                    display_order = ?
                WHERE id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $title, $slug, $destination_id, $short_description, $full_description,
            $itinerary, $duration_days, $duration_nights, $start_date, $end_date,
            $price, $max_participants, $difficulty_level, $included_services,
            $excluded_services, $accommodation_details, $photography_highlights,
            $meta_title, $meta_description, $meta_keywords, $status, $featured,
            $display_order, $tourId
        ]);
        
        $success = 'Tour updated successfully!';
        
        // Refresh tour data
        $stmt = $db->prepare("SELECT * FROM tours WHERE id = ?");
        $stmt->execute([$tourId]);
        $tour = $stmt->fetch();
        
    } catch (Exception $e) {
        $error = 'Error updating tour: ' . $e->getMessage();
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
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
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

.form-sidebar {
    display: flex;
    flex-direction: column;
    gap: 0;
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
.form-group input[type="date"],
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
    border-color: #228B22;
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

.form-grid.three-col {
    grid-template-columns: repeat(3, 1fr);
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

.help-text {
    background: #eff6ff;
    border-left: 4px solid #228B22;
    padding: 15px;
    border-radius: 6px;
    font-size: 13px;
    color: #374151;
    line-height: 1.6;
}

.help-text strong {
    color: #228B22;
    display: block;
    margin-bottom: 5px;
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
        <h1><i class="fas fa-edit"></i> Edit Tour</h1>
        <div class="header-actions">
            <?php if ($tour['status'] == 'published' && $tour['slug']): ?>
            <a href="<?php echo SITE_URL; ?>/tour-details.php?slug=<?php echo $tour['slug']; ?>" 
               class="btn btn-success" target="_blank">
                <i class="fas fa-eye"></i> View Tour
            </a>
            <?php endif; ?>
            <a href="tours.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Tours
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
                        <label>Tour Title <span class="required">*</span></label>
                        <input type="text" name="title" required 
                               value="<?php echo htmlspecialchars($tour['title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Slug <span class="required">*</span></label>
                        <input type="text" name="slug" required
                               value="<?php echo htmlspecialchars($tour['slug']); ?>">
                        <small>URL: <?php echo SITE_URL; ?>/tour-details.php?slug=<?php echo htmlspecialchars($tour['slug']); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Destination</label>
                        <select name="destination_id">
                            <option value="">Select Destination</option>
                            <?php foreach ($destinations as $dest): ?>
                            <option value="<?php echo $dest['id']; ?>"
                                    <?php echo $tour['destination_id'] == $dest['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dest['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Short Description <span class="required">*</span></label>
                        <textarea name="short_description" required><?php echo htmlspecialchars($tour['short_description']); ?></textarea>
                    </div>
                </div>
                
                <!-- Tour Details -->
                <div class="form-card">
                    <h2>Tour Details</h2>
                    
                    <div class="form-grid three-col">
                        <div class="form-group">
                            <label>Days <span class="required">*</span></label>
                            <input type="number" name="duration_days" required min="1" 
                                   value="<?php echo $tour['duration_days']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Nights <span class="required">*</span></label>
                            <input type="number" name="duration_nights" required min="0" 
                                   value="<?php echo $tour['duration_nights']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Difficulty</label>
                            <select name="difficulty_level">
                                <option value="easy" <?php echo $tour['difficulty_level'] == 'easy' ? 'selected' : ''; ?>>Easy</option>
                                <option value="moderate" <?php echo $tour['difficulty_level'] == 'moderate' ? 'selected' : ''; ?>>Moderate</option>
                                <option value="challenging" <?php echo $tour['difficulty_level'] == 'challenging' ? 'selected' : ''; ?>>Challenging</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" 
                                   value="<?php echo $tour['start_date']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" 
                                   value="<?php echo $tour['end_date']; ?>">
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Price (₹)</label>
                            <input type="number" name="price" step="0.01" min="0" 
                                   value="<?php echo $tour['price']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Max Participants</label>
                            <input type="number" name="max_participants" min="1"
                                   value="<?php echo $tour['max_participants']; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Full Description -->
                <div class="form-card">
                    <h2>Full Description</h2>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="full_description" class="large"><?php echo htmlspecialchars($tour['full_description']); ?></textarea>
                    </div>
                </div>
                
                <!-- Itinerary -->
                <div class="form-card">
                    <h2>Itinerary</h2>
                    
                    <div class="form-group">
                        <label>Day-by-Day Itinerary</label>
                        <textarea name="itinerary" class="large"><?php echo htmlspecialchars($tour['itinerary']); ?></textarea>
                    </div>
                </div>
                
                <!-- Inclusions & Exclusions -->
                <div class="form-card">
                    <h2>Services & Accommodation</h2>
                    
                    <div class="form-group">
                        <label>Included Services</label>
                        <textarea name="included_services"><?php echo htmlspecialchars($tour['included_services']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Excluded Services</label>
                        <textarea name="excluded_services"><?php echo htmlspecialchars($tour['excluded_services']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Accommodation Details</label>
                        <textarea name="accommodation_details"><?php echo htmlspecialchars($tour['accommodation_details']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Photography Highlights</label>
                        <textarea name="photography_highlights"><?php echo htmlspecialchars($tour['photography_highlights']); ?></textarea>
                    </div>
                </div>
                
                <!-- SEO -->
                <div class="form-card">
                    <h2>SEO Settings</h2>
                    
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" 
                               value="<?php echo htmlspecialchars($tour['meta_title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea name="meta_description"><?php echo htmlspecialchars($tour['meta_description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords" 
                               value="<?php echo htmlspecialchars($tour['meta_keywords']); ?>">
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
                            <option value="draft" <?php echo $tour['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $tour['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="archived" <?php echo $tour['status'] == 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="featured" id="featured" 
                               <?php echo $tour['featured'] ? 'checked' : ''; ?>>
                        <label for="featured">Featured Tour</label>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" min="0"
                               value="<?php echo $tour['display_order']; ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update_tour" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Update Tour
                        </button>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Tour Info</h3>
                    <div class="info-box">
                        <div class="info-box-item">
                            <span class="info-box-label">ID:</span>
                            <span><?php echo $tour['id']; ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Created:</span>
                            <span><?php echo date('M d, Y', strtotime($tour['created_at'])); ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Updated:</span>
                            <span><?php echo date('M d, Y', strtotime($tour['updated_at'])); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Featured Image</h3>
                    <?php if ($tour['featured_image']): ?>
                    <div class="current-image" style="margin-bottom: 15px;">
                        <img src="<?php echo UPLOAD_URL . '/tours/' . $tour['featured_image']; ?>" 
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
                    <h3>Quick Actions</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php if ($tour['status'] == 'published' && $tour['slug']): ?>
                        <a href="<?php echo SITE_URL; ?>/tour-details.php?slug=<?php echo $tour['slug']; ?>" 
                           class="btn btn-success btn-sm" target="_blank" style="justify-content: center;">
                            <i class="fas fa-external-link-alt"></i> View on Site
                        </a>
                        <?php endif; ?>
                        <a href="tours.php" class="btn btn-secondary btn-sm" style="justify-content: center;">
                            <i class="fas fa-list"></i> All Tours
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
