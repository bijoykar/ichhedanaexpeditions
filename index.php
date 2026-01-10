<?php
$page_title = 'Home';
require_once __DIR__ . '/includes/header.php';

// Initialize models
$tourModel = new Tour();
$destinationModel = new Destination();
$blogModel = new BlogPost();
$galleryModel = new Gallery();
$reviewModel = new Review();
$statisticModel = new SiteStatistic();

// Get data
$featuredTours = $tourModel->getFeatured(6);
$upcomingTours = $tourModel->getUpcoming(6);
$featuredDestinations = $destinationModel->getFeatured(4);
$recentBlogs = $blogModel->getPublished(3);
$galleryImages = $galleryModel->getFeatured(8);
$featuredReviews = $reviewModel->getFeatured(3);

// Get statistics from database
$siteStats = $statisticModel->getAllStats();

// Get hero slider images from gallery
$heroImages = $galleryModel->getCarouselImages(5); // Get carousel images ordered by carousel_order
?>

<!-- Modern Hero Section -->
<section class="modern-hero-section">
    <div class="hero-slider" id="heroSlider">
        <?php if (!empty($heroImages)): ?>
            <?php foreach ($heroImages as $index => $image): 
                $imageUrl = UPLOAD_URL . '/gallery/' . $image['image_path'];
                $active = $index === 0 ? 'active' : '';
                
                // Use database values or fallback to defaults
                $heroTitle = !empty($image['hero_title']) ? $image['hero_title'] : $image['title'];
                $heroSubtitle = !empty($image['hero_subtitle']) ? $image['hero_subtitle'] : '';
                $buttonText = !empty($image['hero_button_text']) ? $image['hero_button_text'] : null;
                $buttonLink = !empty($image['hero_button_link']) ? SITE_URL . $image['hero_button_link'] : null;
            ?>
            <div class="hero-slide <?php echo $active; ?>" style="background-image: url('<?php echo $imageUrl; ?>');">
                <div class="hero-content">
                    <div class="container">
                        <h1 class="hero-title animate-fade-in"><?php echo htmlspecialchars($heroTitle); ?></h1>
                        <?php if ($heroSubtitle): ?>
                        <p class="hero-subtitle animate-fade-in-delay"><?php echo htmlspecialchars($heroSubtitle); ?></p>
                        <?php endif; ?>
                        <?php if ($buttonText && $buttonLink): ?>
                        <div class="hero-buttons animate-fade-in-delay-2">
                            <a href="<?php echo $buttonLink; ?>" class="btn-hero btn-hero-primary">
                                <i class="fas fa-compass"></i> <?php echo htmlspecialchars($buttonText); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Fallback if no gallery images -->
            <div class="hero-slide active" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="hero-content">
                    <div class="container">
                        <h1 class="hero-title animate-fade-in">Wildlife Photography Tours & Expeditions</h1>
                        <p class="hero-subtitle animate-fade-in-delay">Capture the Beauty of Nature with Expert Guidance</p>
                        <div class="hero-buttons animate-fade-in-delay-2">
                            <a href="<?php echo SITE_URL; ?>/tours.php" class="btn-hero btn-hero-primary">
                                <i class="fas fa-compass"></i> Explore Tours
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($heroImages) && count($heroImages) > 1): ?>
    <button class="modern-slider-nav prev" onclick="changeSlide(-1)">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="modern-slider-nav next" onclick="changeSlide(1)">
        <i class="fas fa-chevron-right"></i>
    </button>
    <div class="modern-slider-dots">
        <?php foreach ($heroImages as $index => $image): ?>
        <span class="modern-dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index; ?>)"></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <div class="scroll-icon">
            <div class="scroll-wheel"></div>
        </div>
        <span>Scroll to Explore</span>
    </div>
</section>

<style>
/* Modern Hero Section */
.modern-hero-section {
    position: relative;
    height: 100vh;
    min-height: 650px;
    max-height: 900px;
    overflow: hidden;
}

.hero-slider {
    position: relative;
    height: 100%;
}

.hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 1.5s ease-in-out, visibility 1.5s ease-in-out;
    z-index: 1;
}

.hero-slide.active {
    opacity: 1;
    visibility: visible;
    z-index: 2;
}

.hero-content {
    position: relative;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    z-index: 2;
    padding: 100px 20px;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 12px 24px;
    border-radius: 30px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 30px;
    border: 1px solid rgba(255,255,255,0.3);
    animation: fadeInDown 0.8s ease-out;
}

.hero-title {
    font-size: 64px;
    font-weight: 800;
    color: white;
    margin-bottom: 25px;
    line-height: 1.2;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5), 0 4px 8px rgba(0,0,0,0.3), 0 8px 16px rgba(0,0,0,0.2);
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
}

.hero-subtitle {
    font-size: 22px;
    color: rgba(255,255,255,0.98);
    margin-bottom: 40px;
    line-height: 1.6;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    font-weight: 300;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5), 0 4px 8px rgba(0,0,0,0.3);
}

.hero-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-hero {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 40px;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.4s ease;
    border: 2px solid transparent;
}

.btn-hero-primary {
    background: white;
    color: #667eea;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.btn-hero-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
    background: #f8f9fa;
}

.btn-hero-outline {
    background: transparent;
    color: white;
    border-color: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
}

.btn-hero-outline:hover {
    background: white;
    color: #667eea;
    border-color: white;
    transform: translateY(-3px);
}

/* Animations */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 1s ease-out 0.2s both;
}

.animate-fade-in-delay {
    animation: fadeInUp 1s ease-out 0.4s both;
}

.animate-fade-in-delay-2 {
    animation: fadeInUp 1s ease-out 0.6s both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modern Slider Navigation */
.modern-slider-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    font-size: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    transition: all 0.4s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modern-slider-nav:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-50%) scale(1.1);
}

.modern-slider-nav.prev {
    left: 40px;
}

.modern-slider-nav.next {
    right: 40px;
}

.modern-slider-dots {
    position: absolute;
    bottom: 50px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 12px;
    z-index: 10;
}

.modern-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    border: 2px solid rgba(255,255,255,0.6);
    cursor: pointer;
    transition: all 0.4s ease;
}

.modern-dot:hover {
    background: rgba(255,255,255,0.6);
    transform: scale(1.2);
}

.modern-dot.active {
    background: white;
    width: 40px;
    border-radius: 6px;
}

/* Scroll Indicator */
.scroll-indicator {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    color: white;
    font-size: 13px;
    z-index: 10;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateX(-50%) translateY(0);
    }
    40% {
        transform: translateX(-50%) translateY(-10px);
    }
    60% {
        transform: translateX(-50%) translateY(-5px);
    }
}

.scroll-icon {
    width: 30px;
    height: 50px;
    border: 2px solid rgba(255,255,255,0.5);
    border-radius: 25px;
    position: relative;
}

.scroll-wheel {
    width: 4px;
    height: 10px;
    background: white;
    border-radius: 2px;
    position: absolute;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    animation: scroll 1.5s infinite;
}

@keyframes scroll {
    0% {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    100% {
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
    }
}

@media (max-width: 992px) {
    .modern-hero-section {
        min-height: 550px;
        height: 80vh;
    }
    
    .hero-slide {
        background-size: cover;
        background-position: center;
    }
    
    .hero-title {
        font-size: 48px;
    }
    
    .hero-subtitle {
        font-size: 18px;
    }
    
    .modern-slider-nav {
        width: 50px;
        height: 50px;
        font-size: 18px;
    }
    
    .modern-slider-nav.prev {
        left: 20px;
    }
    
    .modern-slider-nav.next {
        right: 20px;
    }
}

@media (max-width: 768px) {
    .modern-hero-section {
        min-height: 500px;
        height: 70vh;
    }
    
    .hero-slide {
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
    }
    
    .hero-content {
        padding: 80px 20px;
    }
    
    .hero-title {
        font-size: 36px;
    }
    
    .hero-subtitle {
        font-size: 16px;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-hero {
        width: 100%;
        justify-content: center;
    }
    
    .scroll-indicator {
        display: none;
    }
    
    .modern-slider-nav {
        width: 45px;
        height: 45px;
        font-size: 16px;
    }
}
</style>

<script>
let currentSlideIndex = 0;
let slideInterval;
let slidesCount = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.modern-dot');
    
    if (!slides.length) return;
    
    slidesCount = slides.length;
    
    // Handle wrap around
    if (index >= slidesCount) {
        currentSlideIndex = 0;
    } else if (index < 0) {
        currentSlideIndex = slidesCount - 1;
    } else {
        currentSlideIndex = index;
    }
    
    // Remove active class from all slides and dots
    slides.forEach(slide => slide.classList.remove('active'));
    if (dots.length) dots.forEach(dot => dot.classList.remove('active'));
    
    // Add active class to current slide and dot
    slides[currentSlideIndex].classList.add('active');
    if (dots.length && dots[currentSlideIndex]) {
        dots[currentSlideIndex].classList.add('active');
    }
}

function changeSlide(direction) {
    showSlide(currentSlideIndex + direction);
    resetInterval();
}

function currentSlide(index) {
    showSlide(index);
    resetInterval();
}

function autoSlide() {
    showSlide(currentSlideIndex + 1);
}

function resetInterval() {
    if (slideInterval) {
        clearInterval(slideInterval);
    }
    slideInterval = setInterval(autoSlide, 6000);
}

// Initialize carousel on page load
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length > 1) {
        // Start auto-sliding only if there are multiple slides
        slideInterval = setInterval(autoSlide, 6000);
    }
});
</script>

<!-- Modern Stats Section -->
<section class="modern-stats-section">
    <div class="container">
        <div class="stats-grid">
            <?php if (!empty($siteStats)): ?>
                <?php foreach ($siteStats as $stat): ?>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="<?php echo htmlspecialchars($stat['icon_class']); ?>"></i>
                    </div>
                    <div class="stat-content">
                        <?php if ($stat['stat_key'] === 'average_rating'): ?>
                            <div class="stat-number"><?php echo htmlspecialchars($stat['stat_value']); ?></div>
                        <?php else: ?>
                            <div class="stat-number" data-target="<?php echo htmlspecialchars($stat['stat_value']); ?>">0</div>
                        <?php endif; ?>
                        <div class="stat-label"><?php echo htmlspecialchars($stat['stat_label']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback if no stats in database -->
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" data-target="150">0</div>
                        <div class="stat-label">Tours Conducted</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" data-target="2500">0</div>
                        <div class="stat-label">Happy Clients</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number" data-target="25">0</div>
                        <div class="stat-label">Destinations</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">4.9</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Modern Stats Section */
.modern-stats-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 80px 0;
    margin-top: -80px;
    position: relative;
    z-index: 5;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 40px;
}

.stat-item {
    text-align: center;
    color: white;
    padding: 30px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.4s ease;
}

.stat-item:hover {
    transform: translateY(-10px);
    background: rgba(255,255,255,0.15);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.stat-icon {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.9;
}

.stat-number {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 10px;
    line-height: 1;
}

.stat-label {
    font-size: 16px;
    opacity: 0.95;
    font-weight: 300;
}

@media (max-width: 992px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .modern-stats-section {
        padding: 60px 0;
        margin-top: -60px;
    }
}
</style>

<!-- Photography Tours 2025 -->
<section class="modern-section tours-section">
    <div class="container">
        <div class="modern-section-header">
            <div class="section-badge">
                <i class="fas fa-calendar-alt"></i> Upcoming Expeditions
            </div>
            <h2 class="modern-section-title">Photography Tours 2026</h2>
            <p class="modern-section-subtitle">Join our expertly guided photography expeditions to stunning destinations across India and Bhutan</p>
        </div>
        
        <div class="tours-grid">
            <?php if (!empty($upcomingTours)): ?>
                <?php foreach ($upcomingTours as $tour): ?>
                <div class="tour-card">
                    <div class="tour-image">
                        <?php if ($tour['featured_image']): ?>
                        <img src="<?php echo UPLOAD_URL . '/tours/' . $tour['featured_image']; ?>" 
                             alt="<?php echo htmlspecialchars($tour['title']); ?>">
                        <?php else: ?>
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                            <i class="fas fa-camera"></i>
                        </div>
                        <?php endif; ?>
                        <?php if ($tour['featured']): ?>
                        <span class="badge badge-featured">Featured</span>
                        <?php endif; ?>
                    </div>
                    <div class="tour-content">
                        <h3 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                        <div class="tour-meta">
                            <span><i class="far fa-calendar"></i> <?php echo formatDate($tour['start_date']); ?> - <?php echo formatDate($tour['end_date']); ?></span>
                            <span><i class="far fa-clock"></i> <?php echo $tour['duration_nights']; ?>N / <?php echo $tour['duration_days']; ?>D</span>
                        </div>
                        <p class="tour-description"><?php echo truncateText($tour['short_description'], 120); ?></p>
                        <a href="<?php echo SITE_URL; ?>/tour-details.php?slug=<?php echo $tour['slug']; ?>" class="btn btn-outline">View Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-data">No upcoming tours available at the moment.</p>
            <?php endif; ?>
        </div>
        
        <div class="text-center" style="margin-top: 50px;">
            <a href="<?php echo SITE_URL; ?>/tours.php" class="btn-modern-primary">
                <span>View All Tours</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Top Destinations -->
<section class="modern-section destinations-section">
    <div class="container">
        <div class="modern-section-header">
            <div class="section-badge">
                <i class="fas fa-map-marked-alt"></i> Popular Locations
            </div>
            <h2 class="modern-section-title">Top Destinations</h2>
            <p class="modern-section-subtitle">Explore the most popular wildlife and nature photography destinations</p>
        </div>
        
        <div class="destinations-grid">
            <?php if (!empty($featuredDestinations)): ?>
                <?php foreach ($featuredDestinations as $destination): ?>
                <div class="destination-card">
                    <div class="destination-image">
                        <img src="<?php echo $destination['featured_image'] ? UPLOAD_URL . '/destinations/' . $destination['featured_image'] : ASSETS_URL . '/images/placeholder.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($destination['name']); ?>">
                        <div class="destination-overlay">
                            <h3><?php echo htmlspecialchars($destination['name']); ?></h3>
                            <p><?php echo htmlspecialchars($destination['region']) . ', ' . htmlspecialchars($destination['country']); ?></p>
                            <a href="<?php echo SITE_URL; ?>/destination-details.php?slug=<?php echo $destination['slug']; ?>" class="btn btn-outline-light">Explore</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center" style="margin-top: 50px;">
            <a href="<?php echo SITE_URL; ?>/destinations.php" class="btn-modern-primary">
                <span>View All Destinations</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Gallery -->
<section class="modern-section gallery-section">
    <div class="container">
        <div class="modern-section-header">
            <div class="section-badge">
                <i class="fas fa-images"></i> Our Captures
            </div>
            <h2 class="modern-section-title">Gallery</h2>
            <p class="modern-section-subtitle">A glimpse of our photography expeditions and the beauty we've captured</p>
        </div>
        
        <div class="gallery-grid">
            <?php if (!empty($galleryImages)): ?>
                <?php foreach ($galleryImages as $image): ?>
                <div class="gallery-item">
                    <img src="<?php echo UPLOAD_URL . '/gallery/' . $image['image_path']; ?>" 
                         alt="<?php echo htmlspecialchars($image['title']); ?>">
                    <div class="gallery-overlay">
                        <h4><?php echo htmlspecialchars($image['title']); ?></h4>
                        <?php if ($image['location']): ?>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($image['location']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center" style="margin-top: 50px;">
            <a href="<?php echo SITE_URL; ?>/gallery.php" class="btn-modern-primary">
                <span>View Full Gallery</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Recent Blog Posts -->
<section class="modern-section blog-section">
    <div class="container">
        <div class="modern-section-header">
            <div class="section-badge">
                <i class="fas fa-blog"></i> Latest Stories
            </div>
            <h2 class="modern-section-title">Recent Blog Posts</h2>
            <p class="modern-section-subtitle">Photography tips, travel stories, and expedition insights</p>
        </div>
        
        <div class="blog-grid">
            <?php if (!empty($recentBlogs)): ?>
                <?php foreach ($recentBlogs as $blog): ?>
                <div class="blog-card">
                    <div class="blog-image">
                        <img src="<?php echo $blog['featured_image'] ? UPLOAD_URL . '/blog/' . $blog['featured_image'] : ASSETS_URL . '/images/placeholder.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($blog['title']); ?>">
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span><i class="far fa-calendar"></i> <?php echo formatDate($blog['published_at']); ?></span>
                            <?php if ($blog['category']): ?>
                            <span class="blog-category"><?php echo htmlspecialchars($blog['category']); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h3>
                        <p class="blog-excerpt"><?php echo truncateText($blog['excerpt'], 150); ?></p>
                        <a href="<?php echo SITE_URL; ?>/blog-details.php?slug=<?php echo $blog['slug']; ?>" class="btn btn-outline">Read More</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center" style="margin-top: 50px;">
            <a href="<?php echo SITE_URL; ?>/blogs.php" class="btn-modern-primary">
                <span>Read All Posts</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Reviews -->
<section class="modern-section reviews-section">
    <div class="container">
        <div class="modern-section-header">
            <div class="section-badge">
                <i class="fas fa-star"></i> Testimonials
            </div>
            <h2 class="modern-section-title">What Our Customers Say</h2>
            <p class="modern-section-subtitle">Real experiences from our photography tour participants</p>
        </div>
        
        <div class="reviews-grid">
            <?php if (!empty($featuredReviews)): ?>
                <?php foreach ($featuredReviews as $review): ?>
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-photo">
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
                                <div class="reviewer-avatar" style="background: <?php echo $bgColor; ?>;">
                                    <?php echo $initials; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="reviewer-info">
                            <h4><?php echo htmlspecialchars($review['customer_name']); ?></h4>
                            <?php echo getStarRatingHTML($review['rating']); ?>
                        </div>
                    </div>
                    <p class="review-text">"<?php echo htmlspecialchars($review['review_text']); ?>"</p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center" style="margin-top: 50px;">
            <a href="<?php echo SITE_URL; ?>/reviews.php" class="btn-modern-primary">
                <span>Read All Reviews</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Modern CTA Section -->
<section class="modern-cta-section">
    <div class="cta-gradient-bg"></div>
    <div class="cta-pattern-overlay"></div>
    <div class="container">
        <div class="modern-cta-content">
            <div class="cta-icon">
                <i class="fas fa-mountain"></i>
            </div>
            <h2 class="cta-title">Ready for Your Next Adventure?</h2>
            <p class="cta-subtitle">Join us for an unforgettable wildlife photography experience. Let's capture nature's wonders together.</p>
            <div class="cta-buttons-modern">
                <a href="<?php echo SITE_URL; ?>/tours.php" class="btn-cta btn-cta-white">
                    <i class="fas fa-binoculars"></i> Browse Tours
                </a>
                <a href="<?php echo SITE_URL; ?>/contact.php" class="btn-cta btn-cta-outline">
                    <i class="fas fa-phone-alt"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Modern Section Styling */
.modern-section {
    padding: 100px 0;
    position: relative;
}

.modern-section:nth-child(even) {
    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
}

.modern-section-header {
    text-align: center;
    margin-bottom: 60px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.section-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
    color: #667eea;
    padding: 10px 24px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    border: 2px solid rgba(102,126,234,0.2);
}

.modern-section-title {
    font-size: 42px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 20px;
    line-height: 1.2;
}

.modern-section-subtitle {
    font-size: 18px;
    color: #64748b;
    line-height: 1.6;
}

/* Modern Primary Button */
.btn-modern-primary {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 16px 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.4s ease;
    box-shadow: 0 8px 25px rgba(102,126,234,0.3);
    position: relative;
    overflow: hidden;
}

.btn-modern-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.btn-modern-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(102,126,234,0.4);
}

.btn-modern-primary:hover::before {
    left: 100%;
}

/* Modern CTA Section */
.modern-cta-section {
    position: relative;
    padding: 120px 0;
    overflow: hidden;
}

.cta-gradient-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    z-index: 1;
}

.cta-pattern-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
    z-index: 2;
}

.modern-cta-content {
    position: relative;
    z-index: 3;
    text-align: center;
    color: white;
}

.cta-icon {
    font-size: 72px;
    margin-bottom: 30px;
    opacity: 0.9;
    animation: float 3s ease-in-out infinite;
}

.cta-title {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.cta-subtitle {
    font-size: 20px;
    margin-bottom: 40px;
    opacity: 0.95;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

.cta-buttons-modern {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 40px;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.4s ease;
    border: 2px solid transparent;
}

.btn-cta-white {
    background: white;
    color: #667eea;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.btn-cta-white:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
}

.btn-cta-outline {
    background: transparent;
    color: white;
    border-color: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
}

.btn-cta-outline:hover {
    background: white;
    color: #667eea;
    border-color: white;
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 992px) {
    .modern-section {
        padding: 80px 0;
    }
    
    .modern-section-title {
        font-size: 36px;
    }
    
    .modern-section-subtitle {
        font-size: 16px;
    }
    
    .cta-title {
        font-size: 36px;
    }
    
    .cta-subtitle {
        font-size: 18px;
    }
}

@media (max-width: 768px) {
    .modern-section {
        padding: 60px 0;
    }
    
    .modern-section-header {
        margin-bottom: 40px;
    }
    
    .modern-section-title {
        font-size: 32px;
    }
    
    .modern-section-subtitle {
        font-size: 15px;
    }
    
    .modern-cta-section {
        padding: 80px 0;
    }
    
    .cta-icon {
        font-size: 56px;
    }
    
    .cta-title {
        font-size: 28px;
    }
    
    .cta-subtitle {
        font-size: 16px;
    }
    
    .cta-buttons-modern {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-cta {
        justify-content: center;
    }
}
</style>

<script>
// Stats Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('.stat-number[data-target]');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    });
}

// Intersection Observer for Stats
const statsSection = document.querySelector('.modern-stats-section');
if (statsSection) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    observer.observe(statsSection);
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
