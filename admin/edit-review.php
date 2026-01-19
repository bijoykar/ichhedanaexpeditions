<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Edit Review';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$tourModel = new Tour($db);
$success = '';
$error = '';

// Get review ID
$reviewId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$reviewId) {
    header('Location: reviews.php');
    exit;
}

// Fetch review data
$stmt = $db->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->execute([$reviewId]);
$review = $stmt->fetch();

if (!$review) {
    header('Location: reviews.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_review'])) {
    $customer_name = sanitize($_POST['customer_name']);
    $customer_email = sanitize($_POST['customer_email']);
    $tour_id = $_POST['tour_id'] ? (int)$_POST['tour_id'] : null;
    $rating = (int)$_POST['rating'];
    $review_text = sanitize($_POST['review_text']);
    $review_date = $_POST['review_date'] ? sanitize($_POST['review_date']) : null;
    $display_order = (int)$_POST['display_order'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    $status = sanitize($_POST['status']);
    
    // For now, we'll use existing photo or placeholder
    // TODO: Implement file upload
    $customer_photo = $review['customer_photo'];
    
    try {
        $sql = "UPDATE reviews SET
                    customer_name = ?, customer_email = ?, customer_photo = ?,
                    tour_id = ?, rating = ?, review_text = ?, review_date = ?,
                    display_order = ?, featured = ?, status = ?
                WHERE id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $customer_name, $customer_email, $customer_photo, $tour_id,
            $rating, $review_text, $review_date, $display_order, $featured, $status, $reviewId
        ]);
        
        $success = 'Review updated successfully!';
        
        // Refresh review data
        $stmt = $db->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$reviewId]);
        $review = $stmt->fetch();
        
    } catch (Exception $e) {
        $error = 'Error updating review: ' . $e->getMessage();
    }
}

// Get all tours for dropdown
$tours = $tourModel->all();

// Check for success message from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = 'Review added successfully!';
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
.form-group input[type="email"],
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
    border-color: #228B22;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
}

.form-group small {
    display: block;
    margin-top: 6px;
    font-size: 13px;
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
    margin-bottom: 15px;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-group label {
    margin: 0;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
}

.upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.upload-area:hover {
    border-color: #228B22;
    background: #f3f4f6;
}

.upload-area i {
    font-size: 48px;
    color: #9ca3af;
    margin-bottom: 15px;
}

.upload-area input[type="file"] {
    display: none;
}

.current-photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
    border: 3px solid #228B22;
}

.form-actions {
    display: flex;
    gap: 12px;
    padding-top: 20px;
    border-top: 2px solid #f3f4f6;
}

.form-actions .btn {
    width: 100%;
    justify-content: center;
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
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-box-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-box-item:last-child {
    border-bottom: none;
}

.info-box-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 600;
}

.rating-selector {
    display: flex;
    gap: 10px;
    font-size: 32px;
    color: #d1d5db;
}

.rating-selector i {
    cursor: pointer;
    transition: all 0.3s ease;
}

.rating-selector i:hover,
.rating-selector i.active {
    color: #fbbf24;
    transform: scale(1.1);
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
        <h1><i class="fas fa-edit"></i> Edit Review</h1>
        <div class="header-actions">
            <a href="reviews.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Reviews
            </a>
        </div>
    </div>
    
    <?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $success; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-layout">
            <div class="form-main">
                <!-- Customer Photo -->
                <div class="form-card">
                    <h2>Customer Photo</h2>
                    
                    <div style="text-align: center;">
                        <?php if ($review['customer_photo']): ?>
                        <img src="<?php echo UPLOAD_URL . '/reviews/' . $review['customer_photo']; ?>" 
                             alt="<?php echo htmlspecialchars($review['customer_name'] ?? ''); ?>" 
                             class="current-photo">
                        <?php else: ?>
                        <div class="current-photo" style="background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: 700;">
                            <?php echo strtoupper(substr($review['customer_name'] ?? 'U', 0, 1)); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="upload-area" onclick="document.getElementById('photoUpload').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p><strong>Click to upload new photo</strong></p>
                        <p style="font-size: 13px; color: #6b7280; margin: 5px 0 0 0;">
                            PNG, JPG up to 2MB
                        </p>
                        <input type="file" id="photoUpload" name="customer_photo" accept="image/*">
                    </div>
                    <small style="color: #ef4444; margin-top: 10px; display: block;">
                        <i class="fas fa-info-circle"></i> Note: File upload not yet implemented. Currently using placeholder.
                    </small>
                </div>
                
                <!-- Customer Information -->
                <div class="form-card">
                    <h2>Customer Information</h2>
                    
                    <div class="form-group">
                        <label>Customer Name <span class="required">*</span></label>
                        <input type="text" name="customer_name" required 
                               value="<?php echo htmlspecialchars($review['customer_name'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Customer Email</label>
                        <input type="email" name="customer_email" 
                               value="<?php echo htmlspecialchars($review['customer_email'] ?? ''); ?>">
                        <small>Optional - for contact purposes</small>
                    </div>
                </div>
                
                <!-- Review Details -->
                <div class="form-card">
                    <h2>Review Details</h2>
                    
                    <div class="form-group">
                        <label>Associated Tour</label>
                        <select name="tour_id">
                            <option value="">No specific tour</option>
                            <?php foreach ($tours as $tour): ?>
                            <option value="<?php echo $tour['id']; ?>" 
                                    <?php echo $review['tour_id'] == $tour['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tour['title']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <small>Link this review to a specific tour</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Rating <span class="required">*</span></label>
                        <div class="rating-selector" id="ratingStar">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="<?php echo $i <= $review['rating'] ? 'fas active' : 'far'; ?> fa-star" 
                               data-rating="<?php echo $i; ?>" 
                               onclick="setRating(<?php echo $i; ?>)"></i>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="<?php echo $review['rating']; ?>" required>
                        <small>Click on stars to set rating</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Review Text <span class="required">*</span></label>
                        <textarea name="review_text" required><?php echo htmlspecialchars($review['review_text'] ?? ''); ?></textarea>
                        <small>Customer's feedback and experience</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Review Date</label>
                        <input type="date" name="review_date" 
                               value="<?php echo $review['review_date'] ?? ''; ?>">
                        <small>When the review was given</small>
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
                            <option value="pending" <?php echo $review['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $review['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $review['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="featured" id="featured" 
                               <?php echo $review['featured'] ? 'checked' : ''; ?>>
                        <label for="featured">Featured Review</label>
                    </div>
                    
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" name="display_order" min="0"
                               value="<?php echo $review['display_order']; ?>">
                        <small>Lower numbers appear first</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update_review" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Review
                        </button>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Review Info</h3>
                    <div class="info-box">
                        <div class="info-box-item">
                            <span class="info-box-label">ID:</span>
                            <span><?php echo $review['id']; ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Created:</span>
                            <span><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">Updated:</span>
                            <span><?php echo date('M d, Y', strtotime($review['updated_at'])); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3>Quick Actions</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="reviews.php" class="btn btn-secondary btn-sm" style="justify-content: center;">
                            <i class="fas fa-list"></i> All Reviews
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function setRating(rating) {
    document.getElementById('ratingInput').value = rating;
    const stars = document.querySelectorAll('#ratingStar i');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('far');
            star.classList.add('fas', 'active');
        } else {
            star.classList.remove('fas', 'active');
            star.classList.add('far');
        }
    });
}

document.getElementById('photoUpload').addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        const fileName = e.target.files[0].name;
        const uploadArea = e.target.closest('.upload-area');
        uploadArea.querySelector('strong').textContent = 'Selected: ' + fileName;
    }
});
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
