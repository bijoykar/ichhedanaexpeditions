<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Add New Destination';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_destination'])) {
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
    
    // Generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }
    
    // Handle image upload
    $featured_image = null;
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['featured_image'];
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
            $newFilename = 'destination_' . time() . '_' . uniqid() . '.' . $ext;
            $destination_path = $uploadDir . '/' . $newFilename;
            
            if (move_uploaded_file($file['tmp_name'], $destination_path)) {
                $featured_image = $newFilename;
            } else {
                $error = 'Failed to upload image.';
            }
        } else {
            $error = 'Invalid file type. Allowed: ' . implode(', ', $allowed);
        }
    }
    
    if (!$error) {
        try {
            $sql = "INSERT INTO destinations (
                        name, slug, region, country, description, climate_info,
                        best_time_to_visit, wildlife_info, featured_image, meta_title, 
                        meta_description, meta_keywords, status, featured, display_order
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    )";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $name, $slug, $region, $country, $description, $climate_info,
                $best_time_to_visit, $wildlife_info, $featured_image, $meta_title, 
                $meta_description, $meta_keywords, $status, $featured, $display_order
            ]);
            
            $success = 'Destination added successfully!';
            // Redirect to edit page with ID
            $destinationId = $db->lastInsertId();
            header("Location: edit-destination.php?id=$destinationId&success=1");
            exit;
            
        } catch (Exception $e) {
            $error = 'Error adding destination: ' . $e->getMessage();
        }
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
        <h1><i class="fas fa-plus-circle"></i> Add New Destination</h1>
        <a href="destinations.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Destinations
        </a>
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
                <!-- Basic Information -->
                <div class="form-card">
                    <h2>Basic Information</h2>
                    
                    <div class="form-group">
                        <label>Destination Name <span class="required">*</span></label>
                        <input type="text" name="name" required 
                               placeholder="e.g., Kaziranga National Park">
                        <small>The main name of the destination</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" 
                               placeholder="Leave blank to auto-generate from name">
                        <small>URL-friendly version (e.g., kaziranga-national-park)</small>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Region <span class="required">*</span></label>
                            <input type="text" name="region" required 
                                   placeholder="e.g., Assam">
                            <small>State or region name</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Country <span class="required">*</span></label>
                            <input type="text" name="country" required value="India">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Best Time to Visit</label>
                        <input type="text" name="best_time_to_visit" 
                               placeholder="e.g., November to April">
                        <small>Recommended months or seasons</small>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="form-card">
                    <h2>Description</h2>
                    
                    <div class="form-group">
                        <label>Full Description <span class="required">*</span></label>
                        <textarea name="description" class="large" required
                                  placeholder="Detailed description of the destination, its significance, what makes it special..."></textarea>
                        <small>Comprehensive overview of the destination</small>
                    </div>
                </div>
                
                <!-- Featured Image -->
                <div class="form-card">
                    <h2>Featured Image</h2>
                    
                    <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" name="featured_image" accept="image/*">
                        <small>Recommended size: 1200x900px. Formats: JPG, PNG, WebP, GIF</small>
                    </div>
                </div>
                
                <!-- Climate & Wildlife -->
                <div class="form-card">
                    <h2>Climate & Wildlife Information</h2>
                    
                    <div class="form-group">
                        <label>Climate Information</label>
                        <textarea name="climate_info" 
                                  placeholder="Weather patterns, temperature ranges, seasonal variations..."></textarea>
                        <small>Details about weather and climate conditions</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Wildlife Information</label>
                        <textarea name="wildlife_info" 
                                  placeholder="Flora and fauna found here, endangered species, bird species..."></textarea>
                        <small>Information about local wildlife and biodiversity</small>
                    </div>
                </div>
                
                <!-- SEO Settings -->
                <div class="form-card">
                    <h2>SEO Settings</h2>
                    
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" 
                               placeholder="Leave blank to use destination name">
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
                               placeholder="wildlife, national park, bird watching, photography">
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
                        <label for="featured">Featured Destination</label>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" value="0" min="0">
                        <small>Lower numbers appear first</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="add_destination" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Create Destination
                        </button>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Quick Tips</h3>
                    <div class="help-text">
                        <strong>Best Practices:</strong>
                        • Write engaging descriptions<br>
                        • Include climate details<br>
                        • Highlight unique wildlife<br>
                        • Specify best visiting time<br>
                        • Use proper SEO keywords<br>
                        • Add high-quality images
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
