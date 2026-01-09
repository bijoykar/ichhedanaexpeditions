<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Add Gallery Image';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$destinationModel = new Destination();
$tourModel = new Tour();
$success = '';
$error = '';

// Get all destinations and tours for dropdowns
$destinations = $destinationModel->all();
$tours = $tourModel->all();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_image'])) {
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
    
    // For now, we'll use a placeholder for image_path
    // In production, you'd handle file upload here
    $image_path = 'placeholder.jpg'; // TODO: Implement file upload
    $thumbnail_path = null;
    
    try {
        $sql = "INSERT INTO gallery (
                    title, description, image_path, thumbnail_path, category,
                    destination_id, tour_id, photographer, camera_settings,
                    location, taken_date, display_order, featured, show_in_carousel,
                    carousel_order, status
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $title, $description, $image_path, $thumbnail_path, $category,
            $destination_id, $tour_id, $photographer, $camera_settings,
            $location, $taken_date, $display_order, $featured, $show_in_carousel,
            $carousel_order, $status
        ]);
        
        $success = 'Gallery image added successfully!';
        $imageId = $db->lastInsertId();
        header("Location: edit-gallery-image.php?id=$imageId&success=1");
        exit;
        
    } catch (Exception $e) {
        $error = 'Error adding image: ' . $e->getMessage();
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

.alert-warning {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fcd34d;
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

.upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    background: #f9fafb;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #667eea;
    background: #eff6ff;
}

.upload-area i {
    font-size: 48px;
    color: #9ca3af;
    margin-bottom: 15px;
}

.upload-area p {
    color: #6b7280;
    margin: 5px 0;
}

.upload-area .note {
    font-size: 12px;
    color: #9ca3af;
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
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-content">
    <div class="form-header">
        <h1><i class="fas fa-plus-circle"></i> Add Gallery Image</h1>
        <a href="gallery.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Gallery
        </a>
    </div>
    
    <div class="alert alert-warning">
        <i class="fas fa-info-circle"></i>
        <span>Note: File upload functionality needs to be implemented. Currently using placeholder images.</span>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo $error; ?></span>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-layout">
            <div class="form-main">
                <!-- Upload Image -->
                <div class="form-card">
                    <h2>Upload Image</h2>
                    
                    <div class="upload-area" onclick="document.getElementById('imageUpload').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p><strong>Click to upload image</strong></p>
                        <p class="note">JPG, PNG or WEBP (max 5MB)</p>
                        <input type="file" id="imageUpload" name="image" accept="image/*" style="display: none;">
                    </div>
                </div>
                
                <!-- Basic Information -->
                <div class="form-card">
                    <h2>Basic Information</h2>
                    
                    <div class="form-group">
                        <label>Image Title <span class="required">*</span></label>
                        <input type="text" name="title" required 
                               placeholder="e.g., One-Horned Rhino at Kaziranga">
                        <small>Descriptive title for the image</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" 
                                  placeholder="Detailed description of the image, story behind it..."></textarea>
                        <small>Optional description for display purposes</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="">Select Category</option>
                            <option value="Wildlife">Wildlife</option>
                            <option value="Birds">Birds</option>
                            <option value="Landscape">Landscape</option>
                            <option value="Flora">Flora</option>
                            <option value="Adventure">Adventure</option>
                            <option value="Culture">Culture</option>
                            <option value="People">People</option>
                        </select>
                        <small>Categorize your image for easy filtering</small>
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
                                <option value="<?php echo $dest['id']; ?>">
                                    <?php echo htmlspecialchars($dest['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <small>Link to a destination</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Tour</label>
                            <select name="tour_id">
                                <option value="">Select Tour</option>
                                <?php foreach ($tours as $tour): ?>
                                <option value="<?php echo $tour['id']; ?>">
                                    <?php echo htmlspecialchars($tour['title']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <small>Link to a tour</small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" 
                               placeholder="e.g., Kaziranga National Park, Assam">
                        <small>Specific location where photo was taken</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Date Taken</label>
                        <input type="date" name="taken_date">
                        <small>When was this photo captured</small>
                    </div>
                </div>
                
                <!-- Photography Details -->
                <div class="form-card">
                    <h2>Photography Details</h2>
                    
                    <div class="form-group">
                        <label>Photographer</label>
                        <input type="text" name="photographer" 
                               placeholder="e.g., John Doe">
                        <small>Name of the photographer</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Camera Settings</label>
                        <input type="text" name="camera_settings" 
                               placeholder="e.g., Canon EOS 5D, f/5.6, 1/500s, ISO 400">
                        <small>Camera, aperture, shutter speed, ISO, etc.</small>
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
                        <label for="featured">Featured Image</label>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" value="0" min="0">
                        <small>Lower numbers appear first</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="add_image" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Add Image
                        </button>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Home Page Carousel</h3>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="show_in_carousel" id="show_in_carousel">
                        <label for="show_in_carousel">Show in Home Banner</label>
                    </div>
                    
                    <div class="form-group">
                        <label>Carousel Order</label>
                        <input type="number" name="carousel_order" value="0" min="0">
                        <small>Sequence in home page slider</small>
                    </div>
                    
                    <div class="help-text">
                        <small><i class="fas fa-info-circle"></i> Enable to display this image in the home page hero banner carousel.</small>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Image Guidelines</h3>
                    <div class="help-text">
                        <strong>Best Practices:</strong>
                        • High resolution (min 1920px)<br>
                        • Landscape orientation preferred<br>
                        • Proper exposure & focus<br>
                        • Compress for web<br>
                        • Add descriptive titles<br>
                        • Include camera settings
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('imageUpload').addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        const fileName = e.target.files[0].name;
        const uploadArea = e.target.closest('.upload-area');
        uploadArea.querySelector('strong').textContent = 'Selected: ' + fileName;
    }
});
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
