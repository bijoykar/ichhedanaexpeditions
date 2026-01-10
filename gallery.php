<?php
$page_title = 'Photo Gallery';
$meta_description = 'View stunning wildlife and nature photography from our expeditions across India and Bhutan.';
require_once __DIR__ . '/includes/header.php';

$galleryModel = new Gallery();

// Get filter parameters
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12; // Items per page

// Get categories for filter
$categories = $galleryModel->getCategories();

// Get images based on filter with pagination
if ($category) {
    $result = $galleryModel->paginate($page, $perPage, ['status' => 'published', 'category' => $category], 'display_order ASC, created_at DESC');
    $images = $result['data'];
} else {
    $result = $galleryModel->paginate($page, $perPage, ['status' => 'published'], 'display_order ASC, created_at DESC');
    $images = $result['data'];
}
?>

<!-- Page Header -->
<section class="page-header-gallery">
    <div class="header-overlay"></div>
    <div class="container">
        <div class="header-content-gallery">
            <div class="header-icon-gallery">
                <i class="fas fa-images"></i>
            </div>
            <h1>Photo Gallery</h1>
            <p>Wildlife and nature photography from our expeditions</p>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="section gallery-page-modern">
    <div class="container">
        
        <!-- Category Filter -->
        <?php if (!empty($categories)): ?>
        <div class="gallery-filters-modern">
            <div class="filter-label">
                <i class="fas fa-filter"></i> Filter by:
            </div>
            <div class="filter-buttons">
                <button class="filter-btn-modern <?php echo !$category ? 'active' : ''; ?>" 
                        onclick="window.location.href='<?php echo SITE_URL; ?>/gallery.php'">
                    <i class="fas fa-th"></i> All
                </button>
                <?php foreach ($categories as $cat): ?>
                <button class="filter-btn-modern <?php echo $category == $cat ? 'active' : ''; ?>" 
                        onclick="window.location.href='<?php echo SITE_URL; ?>/gallery.php?category=<?php echo urlencode($cat); ?>'">
                    <?php echo htmlspecialchars(ucfirst($cat)); ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Gallery Grid -->
        <?php if (!empty($images)): ?>
        <div class="gallery-masonry-modern">
            <?php foreach ($images as $image): 
                // Fix image path - avoid duplication if path already contains 'uploads/gallery/'
                $imagePath = (strpos($image['image_path'], 'uploads/gallery/') === 0) 
                    ? $image['image_path'] 
                    : 'uploads/gallery/' . $image['image_path'];
                $imageUrl = SITE_URL . '/' . $imagePath;
            ?>
            <div class="gallery-item-modern" data-category="<?php echo htmlspecialchars($image['category']); ?>">
                <a href="<?php echo $imageUrl; ?>" 
                   class="gallery-link-modern" 
                   data-lightbox="gallery" 
                   data-title="<?php echo htmlspecialchars($image['title']); ?>">
                    <div class="gallery-image-wrapper">
                        <img src="<?php echo $imageUrl; ?>" 
                             alt="<?php echo htmlspecialchars($image['title']); ?>"
                             loading="lazy">
                    </div>
                    <div class="gallery-overlay-modern">
                        <div class="gallery-info-modern">
                            <h3><?php echo htmlspecialchars($image['title']); ?></h3>
                            <?php if ($image['location']): ?>
                            <p class="gallery-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($image['location']); ?></p>
                            <?php endif; ?>
                            <?php if ($image['photographer']): ?>
                            <p class="gallery-photographer"><i class="fas fa-camera"></i> <?php echo htmlspecialchars($image['photographer']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="gallery-icon-modern">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($result) && $result['total_pages'] > 1): ?>
        <div class="pagination-wrapper">
            <?php 
            $paginationUrl = SITE_URL . '/gallery.php';
            if ($category) {
                $paginationUrl .= '?category=' . urlencode($category);
            }
            echo getPaginationHTML($result['current_page'], $result['total_pages'], $paginationUrl); 
            ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="no-data-message">
            <i class="fas fa-images"></i>
            <p>No images available<?php echo $category ? ' in this category' : ''; ?>.</p>
            <?php if ($category): ?>
            <a href="<?php echo SITE_URL; ?>/gallery.php" class="btn btn-primary">View All</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<!-- Lightbox Styles (inline for quick implementation) -->
<style>
/* Modern Page Header */
.page-header-gallery {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 100px 0 80px;
    position: relative;
    overflow: hidden;
}

.header-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
    opacity: 0.3;
}

.header-content-gallery {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
}

.header-icon-gallery {
    font-size: 64px;
    margin-bottom: 20px;
    animation: zoomPulse 2s ease-in-out infinite;
}

@keyframes zoomPulse {
    0%, 100% { 
        transform: scale(1);
    }
    50% { 
        transform: scale(1.15);
    }
}

.header-content-gallery h1 {
    font-size: 56px;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.header-content-gallery p {
    font-size: 20px;
    opacity: 0.95;
}

/* Gallery Page Modern */
.gallery-page-modern {
    padding: 80px 0;
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
}

/* Modern Filters */
.gallery-filters-modern {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    margin-bottom: 50px;
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.filter-label {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-label i {
    color: #667eea;
}

.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    flex: 1;
}

.filter-btn-modern {
    padding: 12px 28px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid transparent;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-btn-modern:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102,126,234,0.2);
}

.filter-btn-modern.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 8px 20px rgba(102,126,234,0.3);
}

/* Modern Gallery Grid */
.gallery-masonry-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.gallery-item-modern {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: white;
}

.gallery-item-modern:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.gallery-link-modern {
    display: block;
    position: relative;
}

.gallery-image-wrapper {
    overflow: hidden;
    position: relative;
}

.gallery-item-modern img {
    width: 100%;
    height: 350px;
    object-fit: cover;
    display: block;
    transition: transform 0.6s ease;
}

.gallery-item-modern:hover img {
    transform: scale(1.1);
}

.gallery-overlay-modern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(102,126,234,0.95) 0%, rgba(118,75,162,0.85) 50%, rgba(0,0,0,0.6) 100%);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: all 0.4s ease;
    padding: 30px;
    text-align: center;
}

.gallery-item-modern:hover .gallery-overlay-modern {
    opacity: 1;
}

.gallery-info-modern {
    color: white;
    margin-bottom: 20px;
    transform: translateY(20px);
    transition: transform 0.4s ease 0.1s;
}

.gallery-item-modern:hover .gallery-info-modern {
    transform: translateY(0);
}

.gallery-info-modern h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 12px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.gallery-info-modern p {
    font-size: 14px;
    margin: 6px 0;
    opacity: 0.95;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.gallery-icon-modern {
    color: white;
    font-size: 40px;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    transform: scale(0);
    transition: transform 0.4s ease 0.2s;
}

.gallery-item-modern:hover .gallery-icon-modern {
    transform: scale(1);
}

.no-data-message {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.no-data-message i {
    font-size: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 25px;
}

.no-data-message p {
    font-size: 20px;
    color: #666;
    margin-bottom: 25px;
}

/* Lightbox styles */
.lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.lightbox.active {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.lightbox-content {
    max-width: 90%;
    max-height: 90%;
    position: relative;
    animation: zoomIn 0.3s ease;
}

@keyframes zoomIn {
    from { 
        transform: scale(0.8);
        opacity: 0;
    }
    to { 
        transform: scale(1);
        opacity: 1;
    }
}

.lightbox-content img {
    max-width: 100%;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    pointer-events: none;
}

.lightbox-close {
    position: fixed;
    top: 30px;
    right: 40px;
    width: 60px;
    height: 60px;
    font-size: 24px;
    color: white;
    cursor: pointer;
    z-index: 10001;
    background: rgba(102, 126, 234, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

.lightbox-close:hover {
    background: rgba(118, 75, 162, 1);
    transform: rotate(90deg) scale(1.1);
}

.lightbox-title {
    position: fixed;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
    padding: 15px 30px;
    border-radius: 30px;
    font-size: 18px;
    font-weight: 600;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    max-width: 80%;
    text-align: center;
}

.lightbox-nav {
    position: fixed;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    font-size: 24px;
    color: white;
    cursor: pointer;
    background: rgba(102, 126, 234, 0.8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

.lightbox-nav:hover {
    background: rgba(118, 75, 162, 1);
    transform: translateY(-50%) scale(1.15);
}

.lightbox-prev {
    left: 30px;
}

.lightbox-next {
    right: 30px;
}

@media (max-width: 992px) {
    .header-content-gallery h1 {
        font-size: 42px;
    }
    
    .gallery-filters-modern {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .filter-buttons {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .page-header-gallery {
        padding: 80px 0 60px;
    }
    
    .header-content-gallery h1 {
        font-size: 36px;
    }
    
    .header-content-gallery p {
        font-size: 16px;
    }
    
    .header-icon-gallery {
        font-size: 48px;
    }
    
    .gallery-page-modern {
        padding: 60px 0;
    }
    
    .gallery-masonry-modern {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
    
    .gallery-item-modern img {
        height: 280px;
    }
    
    .gallery-filters-modern {
        padding: 20px;
    }
    
    .filter-btn-modern {
        padding: 10px 20px;
        font-size: 13px;
    }
    
    .gallery-info-modern h3 {
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .gallery-masonry-modern {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .gallery-item-modern img {
        height: 300px;
    }
    
    .filter-buttons {
        justify-content: center;
    }
}
</style>

<!-- Lightbox JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Disable right-click on all gallery images
    const allImages = document.querySelectorAll('.gallery-item-modern img, .lightbox img');
    allImages.forEach(img => {
        img.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Prevent drag
        img.addEventListener('dragstart', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Add user-select none
        img.style.userSelect = 'none';
        img.style.webkitUserSelect = 'none';
        img.style.mozUserSelect = 'none';
    });
    
    // Create lightbox element
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <span class="lightbox-close"><i class="fas fa-times"></i></span>
        <span class="lightbox-nav lightbox-prev"><i class="fas fa-chevron-left"></i></span>
        <span class="lightbox-nav lightbox-next"><i class="fas fa-chevron-right"></i></span>
        <div class="lightbox-content">
            <img src="" alt="">
            <div class="lightbox-title"></div>
        </div>
    `;
    document.body.appendChild(lightbox);
    
    const galleryLinks = document.querySelectorAll('.gallery-link-modern');
    const lightboxImg = lightbox.querySelector('img');
    const lightboxTitle = lightbox.querySelector('.lightbox-title');
    const closeBtn = lightbox.querySelector('.lightbox-close');
    const prevBtn = lightbox.querySelector('.lightbox-prev');
    const nextBtn = lightbox.querySelector('.lightbox-next');
    
    // Disable right-click on lightbox image
    lightboxImg.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        return false;
    });
    lightboxImg.addEventListener('dragstart', function(e) {
        e.preventDefault();
        return false;
    });
    
    let currentIndex = 0;
    const images = Array.from(galleryLinks);
    
    function openLightbox(index) {
        currentIndex = index;
        const link = images[currentIndex];
        lightboxImg.src = link.href;
        lightboxTitle.textContent = link.getAttribute('data-title') || '';
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function showNext() {
        currentIndex = (currentIndex + 1) % images.length;
        openLightbox(currentIndex);
    }
    
    function showPrev() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        openLightbox(currentIndex);
    }
    
    // Event listeners
    galleryLinks.forEach((link, index) => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            openLightbox(index);
        });
    });
    
    closeBtn.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) closeLightbox();
    });
    
    nextBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        showNext();
    });
    prevBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        showPrev();
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('active')) return;
        
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') showNext();
        if (e.key === 'ArrowLeft') showPrev();
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
