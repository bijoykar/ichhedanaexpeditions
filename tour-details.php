<?php
require_once __DIR__ . '/includes/header.php';

if (!isset($_GET['slug'])) {
    redirect(SITE_URL . '/tours.php');
}

$tourModel = new Tour();
$tour = $tourModel->getBySlug($_GET['slug']);

if (!$tour || $tour['status'] != 'published') {
    redirect(SITE_URL . '/tours.php');
}

$page_title = $tour['title'];
$meta_description = $tour['meta_description'] ?: truncateText($tour['short_description'], 155);
$meta_keywords = $tour['meta_keywords'];
?>

<!-- Modern Tour Header -->
<section class="modern-tour-header">
    <div class="tour-header-bg" style="<?php echo $tour['featured_image'] ? 'background-image: url(\'' . UPLOAD_URL . '/tours/' . $tour['featured_image'] . '\');' : 'background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);'; ?>"></div>
    <div class="tour-header-overlay"></div>
    <div class="container">
        <div class="tour-header-content">
            <div class="breadcrumb-nav">
                <a href="<?php echo SITE_URL; ?>"><i class="fas fa-home"></i> Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="<?php echo SITE_URL; ?>/tours.php">Tours</a>
                <i class="fas fa-chevron-right"></i>
                <span><?php echo htmlspecialchars($tour['title']); ?></span>
            </div>
            <h1 class="tour-header-title"><?php echo htmlspecialchars($tour['title']); ?></h1>
            <p class="tour-header-subtitle"><?php echo htmlspecialchars($tour['short_description']); ?></p>
        </div>
    </div>
    <div class="header-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>

<!-- Modern Quick Info Bar -->
<section class="modern-quick-info">
    <div class="container">
        <div class="quick-info-grid">
            <div class="quick-info-item">
                <div class="info-icon">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <div class="info-content">
                    <span class="info-label">Start Date</span>
                    <span class="info-value"><?php echo date('M d, Y', strtotime($tour['start_date'])); ?></span>
                </div>
            </div>
            <div class="quick-info-item">
                <div class="info-icon">
                    <i class="far fa-clock"></i>
                </div>
                <div class="info-content">
                    <span class="info-label">Duration</span>
                    <span class="info-value"><?php echo $tour['duration_nights']; ?>N / <?php echo $tour['duration_days']; ?>D</span>
                </div>
            </div>
            <div class="quick-info-item">
                <div class="info-icon">
                    <i class="fas fa-signal"></i>
                </div>
                <div class="info-content">
                    <span class="info-label">Difficulty</span>
                    <span class="info-value"><?php echo ucfirst($tour['difficulty_level']); ?></span>
                </div>
            </div>
            <div class="quick-info-item">
                <div class="info-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info-content">
                    <span class="info-label">Max Group</span>
                    <span class="info-value"><?php echo $tour['max_participants']; ?> Pax</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modern Tour Content -->
<section class="modern-tour-section">
    <div class="container">
        <div class="modern-tour-layout">
            <!-- Main Content -->
            <div class="tour-main-content">
                
                <!-- Overview Section -->
                <div class="modern-content-card">
                    <div class="content-card-header">
                        <div class="header-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h2>Tour Overview</h2>
                    </div>
                    <div class="content-card-body">
                        <p><?php echo nl2br(htmlspecialchars($tour['full_description'])); ?></p>
                    </div>
                </div>
                
                <?php if ($tour['itinerary']): ?>
                <!-- Itinerary Section -->
                <div class="modern-content-card">
                    <div class="content-card-header">
                        <div class="header-icon">
                            <i class="fas fa-route"></i>
                        </div>
                        <h2>Day-by-Day Itinerary</h2>
                    </div>
                    <div class="content-card-body">
                        <div class="itinerary-timeline">
                            <?php 
                            $days = explode("\n\n", $tour['itinerary']);
                            foreach ($days as $index => $day) {
                                if (trim($day)) {
                                    echo '<div class="timeline-item">';
                                    echo '<div class="timeline-marker">' . ($index + 1) . '</div>';
                                    echo '<div class="timeline-content">';
                                    echo '<p>' . nl2br(htmlspecialchars($day)) . '</p>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($tour['photography_highlights']): ?>
                <!-- Photography Highlights -->
                <div class="modern-content-card">
                    <div class="content-card-header">
                        <div class="header-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h2>Photography Highlights</h2>
                    </div>
                    <div class="content-card-body">
                        <div class="highlights-grid">
                            <?php 
                            $highlights = array_map('trim', explode(',', $tour['photography_highlights']));
                            foreach ($highlights as $highlight) {
                                if ($highlight) {
                                    echo '<div class="highlight-item">';
                                    echo '<i class="fas fa-camera-retro"></i>';
                                    echo '<span>' . htmlspecialchars($highlight) . '</span>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Inclusions & Exclusions -->
                <div class="inclusions-exclusions-wrapper">
                    <?php if ($tour['included_services']): ?>
                    <div class="modern-content-card inclusions-card">
                        <div class="content-card-header">
                            <div class="header-icon success-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h2>What's Included</h2>
                        </div>
                        <div class="content-card-body">
                            <ul class="services-list included-list">
                                <?php 
                                $included = array_map('trim', explode(',', $tour['included_services']));
                                foreach ($included as $item) {
                                    if ($item) {
                                        echo '<li><i class="fas fa-check"></i> ' . htmlspecialchars($item) . '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($tour['excluded_services']): ?>
                    <div class="modern-content-card exclusions-card">
                        <div class="content-card-header">
                            <div class="header-icon danger-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h2>What's Not Included</h2>
                        </div>
                        <div class="content-card-body">
                            <ul class="services-list excluded-list">
                                <?php 
                                $excluded = array_map('trim', explode(',', $tour['excluded_services']));
                                foreach ($excluded as $item) {
                                    if ($item) {
                                        echo '<li><i class="fas fa-times"></i> ' . htmlspecialchars($item) . '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($tour['accommodation_details']): ?>
                <!-- Accommodation Details -->
                <div class="modern-content-card">
                    <div class="content-card-header">
                        <div class="header-icon">
                            <i class="fas fa-hotel"></i>
                        </div>
                        <h2>Accommodation</h2>
                    </div>
                    <div class="content-card-body">
                        <p><?php echo nl2br(htmlspecialchars($tour['accommodation_details'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
            
            <!-- Sidebar -->
            <div class="tour-sidebar-modern">
                <!-- Booking Card -->
                <div class="modern-booking-card">
                    <?php if ($tour['price']): ?>
                    <div class="booking-price">
                        <div class="price-main">₹<?php echo number_format($tour['price'], 0); ?></div>
                        <div class="price-label">per person</div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="booking-actions">
                        <a href="<?php echo SITE_URL; ?>/contact.php?tour=<?php echo $tour['slug']; ?>" class="btn-modern btn-primary-modern">
                            <i class="fas fa-calendar-check"></i> Book This Tour
                        </a>
                        <a href="<?php echo SITE_URL; ?>/contact.php?tour=<?php echo $tour['slug']; ?>" class="btn-modern btn-outline-modern">
                            <i class="fas fa-question-circle"></i> Enquire Now
                        </a>
                    </div>
                    
                    <div class="booking-guarantee">
                        <i class="fas fa-shield-alt"></i>
                        <span>Best Price Guaranteed</span>
                    </div>
                </div>
                
                <!-- Contact Card -->
                <div class="modern-contact-card">
                    <div class="contact-header">
                        <i class="fas fa-headset"></i>
                        <h3>Need Assistance?</h3>
                    </div>
                    <p>Our travel experts are here to help you plan your perfect expedition.</p>
                    <div class="contact-details">
                        <a href="tel:<?php echo SITE_PHONE; ?>" class="contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <span><?php echo SITE_PHONE; ?></span>
                        </a>
                        <a href="mailto:<?php echo SITE_EMAIL; ?>" class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span><?php echo SITE_EMAIL; ?></span>
                        </a>
                    </div>
                </div>
                
                <!-- Share Card -->
                <div class="modern-share-card">
                    <h3>Share This Tour</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/tour-details.php?slug=' . $tour['slug']); ?>" target="_blank" class="share-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/tour-details.php?slug=' . $tour['slug']); ?>&text=<?php echo urlencode($tour['title']); ?>" target="_blank" class="share-btn twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode($tour['title'] . ' - ' . SITE_URL . '/tour-details.php?slug=' . $tour['slug']); ?>" target="_blank" class="share-btn whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode($tour['title']); ?>&body=<?php echo urlencode('Check out this tour: ' . SITE_URL . '/tour-details.php?slug=' . $tour['slug']); ?>" class="share-btn email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Modern Tour Header */
.modern-tour-header {
    position: relative;
    padding: 140px 0 120px;
    overflow: hidden;
}

.tour-header-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    z-index: 1;
}

.tour-header-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102,126,234,0.9) 0%, rgba(118,75,162,0.9) 100%);
    z-index: 2;
}

.tour-header-content {
    position: relative;
    z-index: 3;
    color: white;
    max-width: 900px;
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
    font-size: 14px;
}

.breadcrumb-nav a {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-nav a:hover {
    color: white;
}

.breadcrumb-nav i.fa-chevron-right {
    font-size: 10px;
    opacity: 0.7;
}

.breadcrumb-nav span {
    color: white;
    font-weight: 500;
}

.tour-header-title {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.2;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
}

.tour-header-subtitle {
    font-size: 18px;
    line-height: 1.7;
    opacity: 0.95;
}

.header-wave {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    z-index: 3;
}

.header-wave svg {
    display: block;
    width: 100%;
    height: 80px;
}

/* Quick Info Bar */
.modern-quick-info {
    margin-top: -50px;
    position: relative;
    z-index: 10;
    padding-bottom: 40px;
}

.quick-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}

.quick-info-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.quick-info-item:hover {
    background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
    transform: translateY(-3px);
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.info-content {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 13px;
    color: #94a3b8;
    margin-bottom: 4px;
}

.info-value {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
}

/* Modern Tour Section */
.modern-tour-section {
    padding: 40px 0 80px;
}

.modern-tour-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 40px;
}

/* Content Cards */
.modern-content-card {
    background: white;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.modern-content-card:hover {
    box-shadow: 0 12px 35px rgba(0,0,0,0.12);
}

.content-card-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f1f5f9;
}

.content-card-header .header-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
}

.content-card-header .header-icon.success-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.content-card-header .header-icon.danger-icon {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.content-card-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.content-card-body {
    color: #475569;
    line-height: 1.8;
    font-size: 15px;
}

/* Itinerary Timeline */
.itinerary-timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    gap: 25px;
    margin-bottom: 30px;
    position: relative;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 22px;
    top: 50px;
    bottom: -30px;
    width: 2px;
    background: linear-gradient(to bottom, #228B22 0%, #2F4F4F 100%);
    opacity: 0.3;
}

.timeline-marker {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102,126,234,0.4);
    z-index: 2;
    position: relative;
}

.timeline-content {
    flex: 1;
    background: #f8f9fa;
    padding: 20px 25px;
    border-radius: 12px;
    border-left: 3px solid #228B22;
}

.timeline-content p {
    margin: 0;
    color: #2d3748;
    line-height: 1.8;
}

/* Highlights Grid */
.highlights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
}

.highlight-item {
    background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
    padding: 18px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-left: 3px solid #228B22;
    transition: all 0.3s ease;
}

.highlight-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(102,126,234,0.15);
}

.highlight-item i {
    color: #228B22;
    font-size: 20px;
}

.highlight-item span {
    color: #2d3748;
    font-size: 14px;
    font-weight: 500;
}

/* Inclusions/Exclusions */
.inclusions-exclusions-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.inclusions-card {
    border-top: 4px solid #10b981;
}

.exclusions-card {
    border-top: 4px solid #ef4444;
}

.services-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.services-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
    color: #2d3748;
    font-size: 14px;
    line-height: 1.6;
}

.services-list li:last-child {
    border-bottom: none;
}

.included-list i {
    color: #10b981;
    margin-top: 3px;
}

.excluded-list i {
    color: #ef4444;
    margin-top: 3px;
}

/* Sidebar */
.tour-sidebar-modern {
    position: sticky;
    top: 100px;
}

.modern-booking-card {
    background: white;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    border-top: 5px solid #228B22;
}

.booking-price {
    text-align: center;
    padding: 25px;
    background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
    border-radius: 15px;
    margin-bottom: 25px;
}

.price-main {
    font-size: 42px;
    font-weight: 700;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 5px;
}

.price-label {
    font-size: 14px;
    color: #64748b;
}

.booking-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}

.btn-modern {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 16px 24px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102,126,234,0.3);
}

.btn-primary-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(102,126,234,0.4);
}

.btn-outline-modern {
    background: white;
    color: #228B22;
    border-color: #228B22;
}

.btn-outline-modern:hover {
    background: #228B22;
    color: white;
    transform: translateY(-2px);
}

.booking-guarantee {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px;
    background: #f0fdf4;
    border-radius: 10px;
    color: #15803d;
    font-size: 14px;
    font-weight: 600;
}

.modern-contact-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.contact-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.contact-header i {
    font-size: 28px;
    color: #228B22;
}

.contact-header h3 {
    font-size: 20px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.modern-contact-card p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
}

.contact-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 10px;
    color: #2d3748;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

.contact-item:hover {
    background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
    transform: translateX(5px);
}

.contact-item i {
    color: #228B22;
    font-size: 16px;
}

.modern-share-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.modern-share-card h3 {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 20px;
}

.share-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.share-btn {
    width: 100%;
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    color: white;
    text-decoration: none;
    font-size: 18px;
    transition: all 0.3s ease;
}

.share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

.share-btn.facebook {
    background: #1877f2;
}

.share-btn.twitter {
    background: #1da1f2;
}

.share-btn.whatsapp {
    background: #25d366;
}

.share-btn.email {
    background: #228B22;
}

/* Responsive */
@media (max-width: 992px) {
    .modern-tour-layout {
        grid-template-columns: 1fr;
    }
    
    .tour-sidebar-modern {
        position: static;
    }
    
    .quick-info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .inclusions-exclusions-wrapper {
        grid-template-columns: 1fr;
    }
    
    .tour-header-title {
        font-size: 36px;
    }
}

@media (max-width: 768px) {
    .modern-tour-header {
        padding: 100px 0 80px;
    }
    
    .tour-header-title {
        font-size: 28px;
    }
    
    .tour-header-subtitle {
        font-size: 16px;
    }
    
    .quick-info-grid {
        grid-template-columns: 1fr;
        padding: 20px;
    }
    
    .modern-quick-info {
        margin-top: -30px;
    }
    
    .highlights-grid {
        grid-template-columns: 1fr;
    }
    
    .modern-content-card {
        padding: 25px 20px;
    }
    
    .share-buttons {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
