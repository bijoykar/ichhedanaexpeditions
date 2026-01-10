<?php
$page_title = 'Customer Reviews';

// Initialize Review model - must be done before header to allow redirects
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/classes/Model.php';
require_once __DIR__ . '/includes/classes/Review.php';

$reviewModel = new Review();

// Handle form submission BEFORE including header
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    
    $errors = [];
    
    if (empty($name)) $errors[] = 'Name is required';
    if ($rating < 1 || $rating > 5) $errors[] = 'Please select a rating';
    if (empty($comment)) $errors[] = 'Comment is required';
    if (strlen($comment) > 1000) $errors[] = 'Comment must be less than 1000 characters';
    
    if (empty($errors)) {
        // Handle image upload
        $photoPath = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/reviews/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $fileExt = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($fileExt, $allowedTypes) && $_FILES['profile_image']['size'] <= 5000000) {
                $fileName = time() . '_' . uniqid() . '.' . $fileExt;
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
                    $photoPath = $fileName;
                }
            }
        }
        
        // Insert review
        $reviewData = [
            'customer_name' => $name,
            'customer_email' => $email ?: null,
            'customer_photo' => $photoPath,
            'rating' => $rating,
            'review_text' => $comment,
            'review_date' => date('Y-m-d'),
            'status' => 'approved' // Auto-approve for now (change to 'pending' for moderation)
        ];
        
        if ($reviewModel->create($reviewData)) {
            // Redirect to avoid form resubmission
            header('Location: ' . SITE_URL . '/reviews.php?success=1');
            exit;
        } else {
            $errors[] = 'Failed to submit review. Please try again.';
        }
    }
}

// Check for success message from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMsg = 'Thank you! Your review has been published successfully.';
}

// Get all approved reviews
$reviews = $reviewModel->getApproved();

// NOW include the header after all processing is done
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <div class="header-icon-reviews">
            <i class="fas fa-star"></i>
        </div>
        <h1>Customer Reviews</h1>
        <p>Read what our clients say about their photography tour experiences</p>
        <button class="btn btn-primary btn-lg" id="addReviewBtn">
            <i class="fas fa-plus"></i> Add Your Review
        </button>
    </div>
</div>

<section class="section reviews-page">
    <div class="container">
        <?php if (isset($successMsg)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $successMsg; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($reviews)): ?>
            <div class="reviews-masonry">
                <?php foreach ($reviews as $review): 
                    $truncateLength = 200;
                    $reviewText = htmlspecialchars($review['review_text']);
                    $isTruncated = strlen($reviewText) > $truncateLength;
                    $displayText = $isTruncated ? substr($reviewText, 0, $truncateLength) . '...' : $reviewText;
                ?>
                <div class="review-card-modern">
                    <div class="review-header-modern">
                        <div class="reviewer-avatar">
                            <?php if ($review['customer_photo']): ?>
                                <img src="<?php echo UPLOAD_URL . '/reviews/' . $review['customer_photo']; ?>" 
                                     alt="<?php echo htmlspecialchars($review['customer_name']); ?>">
                            <?php else: 
                                // Generate avatar from initials
                                $nameParts = explode(' ', $review['customer_name']);
                                $initials = '';
                                foreach ($nameParts as $part) {
                                    if (!empty($part)) {
                                        $initials .= strtoupper($part[0]);
                                        if (strlen($initials) >= 2) break;
                                    }
                                }
                                // Generate color based on name
                                $colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#30cfd0', '#a8edea'];
                                $colorIndex = ord($initials[0]) % count($colors);
                                $bgColor = $colors[$colorIndex];
                            ?>
                                <div class="avatar-initials" style="background: <?php echo $bgColor; ?>;">
                                    <?php echo $initials; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="reviewer-details">
                            <h4><?php echo htmlspecialchars($review['customer_name']); ?></h4>
                            <div class="rating-stars">
                                <?php echo getStarRatingHTML($review['rating']); ?>
                            </div>
                            <?php if ($review['review_date']): ?>
                            <span class="review-date-modern">
                                <i class="far fa-calendar"></i> <?php echo formatDate($review['review_date']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="review-content-modern">
                        <p class="review-text-preview"><?php echo nl2br($displayText); ?></p>
                        <?php if ($isTruncated): ?>
                        <button class="read-more-btn" data-review-id="<?php echo $review['id']; ?>" 
                                data-name="<?php echo htmlspecialchars($review['customer_name']); ?>"
                                data-rating="<?php echo $review['rating']; ?>"
                                data-date="<?php echo $review['review_date'] ? formatDate($review['review_date']) : ''; ?>"
                                data-photo="<?php echo $review['customer_photo'] ? UPLOAD_URL . '/reviews/' . $review['customer_photo'] : ''; ?>"
                                data-initials="<?php echo $initials; ?>"
                                data-avatar-color="<?php echo $bgColor; ?>"
                                data-text="<?php echo htmlspecialchars($review['review_text']); ?>">
                            Read More <i class="fas fa-chevron-right"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-reviews">
                <i class="fas fa-star"></i>
                <h3>No reviews yet</h3>
                <p>Be the first to share your experience!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Add Review Modal -->
<div class="modal" id="reviewModal">
    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-star"></i> Share Your Experience</h3>
            <button class="modal-close" id="closeModal">&times;</button>
        </div>
        <div class="modal-body">
            <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" id="reviewForm">
                <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email <span class="optional">(Optional)</span></label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="profile_image">Profile Image <span class="optional">(Optional)</span></label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" class="file-input">
                        <label for="profile_image" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Choose Image or Drag & Drop</span>
                        </label>
                        <div class="image-preview" id="imagePreview"></div>
                    </div>
                    <small>Max size: 5MB. Allowed formats: JPG, PNG, GIF</small>
                </div>
                
                <div class="form-group">
                    <label>Rating <span class="required">*</span></label>
                    <div class="star-rating-input">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="comment">Your Review <span class="required">*</span></label>
                    <textarea id="comment" name="comment" class="form-control" rows="6" required 
                              maxlength="1000" placeholder="Share your experience with us..."><?php echo isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : ''; ?></textarea>
                    <div class="char-count">
                        <span id="charCount">0</span> / 1000 characters
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="submit" name="submit_review" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Read More Modal -->
<div class="modal" id="readMoreModal">
    <div class="modal-overlay" id="readMoreOverlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-comment-alt"></i> Review Details</h3>
            <button class="modal-close" id="closeReadMore">&times;</button>
        </div>
        <div class="modal-body">
            <div class="review-full">
                <div class="review-header-full">
                    <div class="full-review-avatar" id="fullReviewAvatar"></div>
                    <div>
                        <h4 id="fullReviewName"></h4>
                        <div class="rating-stars" id="fullReviewRating"></div>
                        <span class="review-date-modern" id="fullReviewDate"></span>
                    </div>
                </div>
                <div class="review-text-full" id="fullReviewText"></div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
    opacity: 0.3;
}

.page-header .container {
    position: relative;
    z-index: 2;
}

.header-icon-reviews {
    font-size: 64px;
    margin-bottom: 20px;
    animation: starPulse 2s ease-in-out infinite;
}

@keyframes starPulse {
    0%, 100% { 
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }
    50% { 
        transform: scale(1.1) rotate(10deg);
        opacity: 0.9;
    }
}

.page-header h1 {
    font-size: 48px;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.page-header p {
    font-size: 20px;
    margin-bottom: 30px;
    opacity: 0.95;
}

.reviews-masonry {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.review-card-modern {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.review-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.review-header-modern {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.reviewer-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.reviewer-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.avatar-initials {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
    font-weight: 700;
    text-transform: uppercase;
}

.reviewer-details h4 {
    margin: 0 0 8px 0;
    font-size: 18px;
    color: #333;
}

.rating-stars {
    margin-bottom: 5px;
}

.review-date-modern {
    font-size: 13px;
    color: #888;
}

.review-content-modern {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.review-text-preview {
    color: #555;
    line-height: 1.6;
    margin-bottom: 15px;
    flex: 1;
}

.read-more-btn {
    background: none;
    border: none;
    color: #667eea;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: gap 0.3s;
}

.read-more-btn:hover {
    gap: 10px;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(5px);
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 20px;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    z-index: 10000;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 25px 30px;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #333;
    font-size: 24px;
}

.modal-close {
    background: none;
    border: none;
    font-size: 32px;
    color: #999;
    cursor: pointer;
    line-height: 1;
    transition: color 0.3s;
}

.modal-close:hover {
    color: #333;
}

.modal-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.required {
    color: #e74c3c;
}

.optional {
    color: #999;
    font-weight: normal;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 15px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
}

.file-upload-wrapper {
    position: relative;
}

.file-input {
    display: none;
}

.file-upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px;
    border: 2px dashed #e0e0e0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
    background: #f9f9f9;
}

.file-upload-label:hover {
    border-color: #667eea;
    background: #f0f0ff;
}

.file-upload-label i {
    font-size: 36px;
    color: #667eea;
    margin-bottom: 10px;
}

.image-preview {
    margin-top: 15px;
    text-align: center;
}

.image-preview img {
    max-width: 150px;
    max-height: 150px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.star-rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.star-rating-input input {
    display: none;
}

.star-rating-input label {
    font-size: 32px;
    color: #ddd;
    cursor: pointer;
    transition: color 0.3s;
}

.star-rating-input input:checked ~ label,
.star-rating-input label:hover,
.star-rating-input label:hover ~ label {
    color: #ffc107;
}

.char-count {
    text-align: right;
    font-size: 13px;
    color: #888;
    margin-top: 5px;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.review-header-full {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.full-review-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.full-review-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.review-text-full {
    color: #555;
    line-height: 1.8;
    font-size: 16px;
    white-space: pre-wrap;
}

.no-reviews {
    text-align: center;
    padding: 80px 20px;
}

.no-reviews i {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .reviews-masonry {
        grid-template-columns: 1fr;
    }
    
    .page-header h1 {
        font-size: 32px;
    }
    
    .modal-content {
        width: 95%;
    }
}
</style>

<script>
// Modal handlers
const reviewModal = document.getElementById('reviewModal');
const readMoreModal = document.getElementById('readMoreModal');
const addReviewBtn = document.getElementById('addReviewBtn');
const closeModal = document.getElementById('closeModal');
const cancelBtn = document.getElementById('cancelBtn');
const modalOverlay = document.getElementById('modalOverlay');
const closeReadMore = document.getElementById('closeReadMore');
const readMoreOverlay = document.getElementById('readMoreOverlay');

// Open review form modal
addReviewBtn?.addEventListener('click', () => {
    reviewModal.classList.add('active');
    document.body.style.overflow = 'hidden';
});

// Close review modal
[closeModal, cancelBtn, modalOverlay].forEach(el => {
    el?.addEventListener('click', () => {
        reviewModal.classList.remove('active');
        document.body.style.overflow = '';
    });
});

// Close read more modal
[closeReadMore, readMoreOverlay].forEach(el => {
    el?.addEventListener('click', () => {
        readMoreModal.classList.remove('active');
        document.body.style.overflow = '';
    });
});

// Read more buttons
document.querySelectorAll('.read-more-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const name = this.dataset.name;
        const rating = parseInt(this.dataset.rating);
        const date = this.dataset.date;
        const photo = this.dataset.photo;
        const initials = this.dataset.initials;
        const avatarColor = this.dataset.avatarColor;
        const text = this.dataset.text;
        
        document.getElementById('fullReviewName').textContent = name;
        document.getElementById('fullReviewDate').innerHTML = '<i class="far fa-calendar"></i> ' + date;
        document.getElementById('fullReviewText').textContent = text;
        
        // Set avatar or photo
        const avatarContainer = document.getElementById('fullReviewAvatar');
        if (photo) {
            avatarContainer.innerHTML = `<img src="${photo}" alt="${name}">`;
        } else {
            avatarContainer.innerHTML = `<div class="avatar-initials" style="background: ${avatarColor};">${initials}</div>`;
        }
        
        // Generate stars
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            starsHtml += `<i class="fas fa-star" style="color: ${i <= rating ? '#ffc107' : '#ddd'}"></i>`;
        }
        document.getElementById('fullReviewRating').innerHTML = starsHtml;
        
        readMoreModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
});

// Image preview
const fileInput = document.getElementById('profile_image');
const imagePreview = document.getElementById('imagePreview');

fileInput?.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
});

// Character count
const commentField = document.getElementById('comment');
const charCount = document.getElementById('charCount');

commentField?.addEventListener('input', function() {
    charCount.textContent = this.value.length;
});

// Initialize character count
if (commentField) {
    charCount.textContent = commentField.value.length;
}

// Auto-open modal if there are errors
<?php if (!empty($errors)): ?>
reviewModal.classList.add('active');
document.body.style.overflow = 'hidden';
<?php endif; ?>
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
