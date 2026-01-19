<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Add New Tour';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$destinationModel = new Destination();
$success = '';
$error = '';

// Get all destinations for dropdown
$destinations = $destinationModel->all();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_tour'])) {
    $title = sanitize($_POST['title']);
    $slug = sanitize($_POST['slug']);
    $destination_id = (int)$_POST['destination_id'];
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
    
    // Generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }
    
    try {
        $sql = "INSERT INTO tours (
                    title, slug, destination_id, short_description, full_description,
                    itinerary, duration_days, duration_nights, start_date, end_date,
                    price, max_participants, difficulty_level, included_services,
                    excluded_services, accommodation_details, photography_highlights,
                    meta_title, meta_description, meta_keywords, status, featured, display_order
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $title, $slug, $destination_id, $short_description, $full_description,
            $itinerary, $duration_days, $duration_nights, $start_date, $end_date,
            $price, $max_participants, $difficulty_level, $included_services,
            $excluded_services, $accommodation_details, $photography_highlights,
            $meta_title, $meta_description, $meta_keywords, $status, $featured, $display_order
        ]);
        
        $success = 'Tour added successfully!';
        // Redirect to edit page with ID
        $tourId = $db->lastInsertId();
        header("Location: edit-tour.php?id=$tourId&success=1");
        exit;
        
    } catch (Exception $e) {
        $error = 'Error adding tour: ' . $e->getMessage();
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
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
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
        <h1><i class="fas fa-plus-circle"></i> Add New Tour</h1>
        <a href="tours.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Tours
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
                        <label>Tour Title <span class="required">*</span></label>
                        <input type="text" name="title" required 
                               placeholder="e.g., Wildlife Photography Tour to Kaziranga">
                        <small>This will be the main title of your tour</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" 
                               placeholder="Leave blank to auto-generate from title">
                        <small>URL-friendly version (e.g., wildlife-photography-kaziranga)</small>
                    </div>
                    
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
                    </div>
                    
                    <div class="form-group">
                        <label>Short Description <span class="required">*</span></label>
                        <textarea name="short_description" required 
                                  placeholder="A brief description (2-3 sentences) that appears in tour listings"></textarea>
                        <small>Keep it concise and engaging</small>
                    </div>
                </div>
                
                <!-- Tour Details -->
                <div class="form-card">
                    <h2>Tour Details</h2>
                    
                    <div class="form-grid three-col">
                        <div class="form-group">
                            <label>Days <span class="required">*</span></label>
                            <input type="number" name="duration_days" required min="1" value="3">
                        </div>
                        
                        <div class="form-group">
                            <label>Nights <span class="required">*</span></label>
                            <input type="number" name="duration_nights" required min="0" value="2">
                        </div>
                        
                        <div class="form-group">
                            <label>Difficulty</label>
                            <select name="difficulty_level">
                                <option value="easy">Easy</option>
                                <option value="moderate" selected>Moderate</option>
                                <option value="challenging">Challenging</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date">
                        </div>
                        
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date">
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Price (₹)</label>
                            <input type="number" name="price" step="0.01" min="0" 
                                   placeholder="e.g., 25000">
                            <small>Leave blank if price on request</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Max Participants</label>
                            <input type="number" name="max_participants" value="10" min="1">
                        </div>
                    </div>
                </div>
                
                <!-- Full Description -->
                <div class="form-card">
                    <h2>Full Description</h2>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="full_description" class="large" 
                                  placeholder="Detailed description of the tour..."></textarea>
                        <small>Full details about the tour, what to expect, highlights, etc.</small>
                    </div>
                </div>
                
                <!-- Itinerary -->
                <div class="form-card">
                    <h2>Itinerary</h2>
                    
                    <div class="form-group">
                        <label>Day-by-Day Itinerary</label>
                        <textarea name="itinerary" class="large" 
                                  placeholder="Day 1: Arrival and orientation...&#10;Day 2: Morning safari..."></textarea>
                        <small>Describe each day's activities</small>
                    </div>
                </div>
                
                <!-- Inclusions & Exclusions -->
                <div class="form-card">
                    <h2>Services & Accommodation</h2>
                    
                    <div class="form-group">
                        <label>Included Services</label>
                        <textarea name="included_services" 
                                  placeholder="• All meals&#10;• Accommodation&#10;• Safari permits&#10;• Professional guide"></textarea>
                        <small>What's included in the tour package</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Excluded Services</label>
                        <textarea name="excluded_services" 
                                  placeholder="• Airfare&#10;• Travel insurance&#10;• Personal expenses"></textarea>
                        <small>What's NOT included in the package</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Accommodation Details</label>
                        <textarea name="accommodation_details" 
                                  placeholder="Details about lodges, hotels, or camps..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Photography Highlights</label>
                        <textarea name="photography_highlights" 
                                  placeholder="Special photography opportunities, best spots, wildlife to capture..."></textarea>
                    </div>
                </div>
                
                <!-- SEO -->
                <div class="form-card">
                    <h2>SEO Settings</h2>
                    
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" 
                               placeholder="Leave blank to use tour title">
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
                               placeholder="wildlife, photography, kaziranga, bird watching">
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
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="featured" id="featured">
                        <label for="featured">Featured Tour</label>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" value="0" min="0">
                        <small>Lower numbers appear first</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="add_tour" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Create Tour
                        </button>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Quick Tips</h3>
                    <div class="help-text">
                        <strong>Best Practices:</strong>
                        • Write engaging descriptions<br>
                        • Include clear itinerary<br>
                        • Set realistic pricing<br>
                        • Add photography highlights<br>
                        • Use proper SEO keywords
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
