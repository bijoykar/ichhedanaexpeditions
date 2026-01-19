<?php
require_once __DIR__ . '/includes/header.php';

if (!isset($_GET['slug'])) {
    redirect(SITE_URL . '/destinations.php');
}

$destinationModel = new Destination();
$tourModel = new Tour();

$destination = $destinationModel->getBySlug($_GET['slug']);

if (!$destination || $destination['status'] != 'published') {
    redirect(SITE_URL . '/destinations.php');
}

$page_title = $destination['name'];
$meta_description = $destination['meta_description'] ?: substr(strip_tags($destination['description']), 0, 155);
$meta_keywords = $destination['meta_keywords'];

// Get tours for this destination
$destinationTours = $tourModel->getByDestination($destination['id'], 6);
?>

<style>
.modern-dest-detail-header {
    position: relative;
    height: 500px;
    overflow: hidden;
}

.modern-dest-detail-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(102,126,234,0.8) 100%);
    z-index: 1;
}

.modern-dest-detail-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    animation: slowZoom 20s ease-in-out infinite alternate;
}

@keyframes slowZoom {
    from { transform: scale(1); }
    to { transform: scale(1.1); }
}

.modern-dest-detail-header .container {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding-bottom: 60px;
}

.modern-dest-breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
    animation: fadeInDown 0.6s ease;
}

.modern-dest-breadcrumb a,
.modern-dest-breadcrumb span {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.3s ease;
}

.modern-dest-breadcrumb a:hover {
    color: white;
}

.modern-dest-breadcrumb i {
    color: rgba(255,255,255,0.6);
    font-size: 0.8rem;
}

.modern-dest-detail-title {
    color: white;
    font-size: 4rem;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
    animation: fadeInUp 0.6s ease 0.2s both;
}

.modern-dest-detail-subtitle {
    color: rgba(255,255,255,0.95);
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: fadeInUp 0.6s ease 0.3s both;
}

.modern-dest-detail-subtitle i {
    color: rgba(255,255,255,0.8);
}

.modern-dest-quick-info {
    background: white;
    margin-top: -50px;
    position: relative;
    z-index: 3;
    padding: 40px 50px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    animation: fadeInUp 0.6s ease 0.4s both;
}

.modern-dest-info-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.modern-dest-info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.modern-dest-info-icon i {
    color: white;
    font-size: 1.3rem;
}

.modern-dest-info-content strong {
    display: block;
    color: #2c3e50;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.modern-dest-info-content p {
    color: #6c757d;
    font-size: 1rem;
    margin: 0;
    line-height: 1.5;
}

.modern-dest-detail-section {
    padding: 80px 0;
}

.modern-dest-detail-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 50px;
    align-items: start;
}

.modern-dest-detail-main {
    animation: fadeInUp 0.6s ease;
}

.modern-dest-content-section {
    background: white;
    padding: 40px;
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.modern-dest-content-section h2 {
    color: #2c3e50;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 3px solid #228B22;
    display: flex;
    align-items: center;
    gap: 12px;
}

.modern-dest-content-section h2 i {
    color: #228B22;
}

.modern-dest-content-section p {
    color: #6c757d;
    font-size: 1.05rem;
    line-height: 1.8;
    margin-bottom: 20px;
}

.modern-dest-content-section ul {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.modern-dest-content-section ul li {
    padding: 12px 0 12px 35px;
    position: relative;
    color: #6c757d;
    font-size: 1.05rem;
    line-height: 1.7;
}

.modern-dest-content-section ul li::before {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    left: 0;
    color: #228B22;
    font-size: 0.9rem;
}

.modern-dest-sidebar {
    position: sticky;
    top: 120px;
}

.modern-dest-sidebar-card {
    background: white;
    border-radius: 20px;
    padding: 35px;
    margin-bottom: 30px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    animation: fadeInRight 0.6s ease;
}

.modern-dest-sidebar-card:nth-child(2) { animation-delay: 0.1s; }
.modern-dest-sidebar-card:nth-child(3) { animation-delay: 0.2s; }

.modern-dest-sidebar-card h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-dest-sidebar-card h3 i {
    color: #228B22;
}

.modern-dest-facts-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.modern-dest-facts-list li {
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: start;
    gap: 15px;
}

.modern-dest-facts-list li:last-child {
    border-bottom: none;
}

.modern-dest-facts-list li strong {
    color: #2c3e50;
    font-weight: 600;
    min-width: 100px;
}

.modern-dest-facts-list li span {
    color: #6c757d;
    text-align: right;
}

.modern-dest-tour-item {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
    margin-bottom: 20px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.modern-dest-tour-item:hover {
    border-color: #228B22;
    background: white;
    transform: translateX(5px);
}

.modern-dest-tour-item h4 {
    color: #2c3e50;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
    line-height: 1.4;
}

.modern-dest-tour-item p {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modern-dest-tour-item p i {
    color: #228B22;
}

.modern-dest-tour-item .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.modern-dest-tour-item .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102,126,234,0.4);
}

.modern-dest-sidebar-card p {
    color: #6c757d;
    font-size: 0.95rem;
    line-height: 1.7;
    margin-bottom: 20px;
}

.modern-dest-sidebar-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.modern-dest-sidebar-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102,126,234,0.4);
}

.modern-back-to-destinations {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: white;
    color: #228B22;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    margin-bottom: 30px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.modern-back-to-destinations:hover {
    transform: translateX(-5px);
    box-shadow: 0 5px 20px rgba(102,126,234,0.2);
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

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@media (max-width: 992px) {
    .modern-dest-detail-layout {
        grid-template-columns: 1fr;
    }
    
    .modern-dest-sidebar {
        position: static;
    }
    
    .modern-dest-detail-title {
        font-size: 2.5rem;
    }
    
    .modern-dest-quick-info {
        grid-template-columns: 1fr;
        padding: 30px;
    }
}

@media (max-width: 768px) {
    .modern-dest-detail-header {
        height: 400px;
    }
    
    .modern-dest-detail-title {
        font-size: 2rem;
    }
    
    .modern-dest-detail-subtitle {
        font-size: 1.1rem;
    }
    
    .modern-dest-content-section {
        padding: 30px 25px;
    }
    
    .modern-dest-content-section h2 {
        font-size: 1.6rem;
    }
}
</style>

<!-- Modern Destination Header -->
<section class="modern-dest-detail-header">
    <div class="modern-dest-detail-bg" style="background-image: url('<?php echo $destination['featured_image'] ? UPLOAD_URL . '/destinations/' . $destination['featured_image'] : ASSETS_URL . '/images/placeholder.jpg'; ?>');"></div>
    
    <div class="container">
        <div class="modern-dest-breadcrumb">
            <a href="<?php echo SITE_URL; ?>"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="<?php echo SITE_URL; ?>/destinations.php">Destinations</a>
            <i class="fas fa-chevron-right"></i>
            <span><?php echo htmlspecialchars($destination['name']); ?></span>
        </div>
        
        <h1 class="modern-dest-detail-title"><?php echo htmlspecialchars($destination['name']); ?></h1>
        <div class="modern-dest-detail-subtitle">
            <i class="fas fa-map-marker-alt"></i>
            <?php echo htmlspecialchars($destination['region']) . ', ' . htmlspecialchars($destination['country']); ?>
        </div>
    </div>
</section>

<!-- Quick Info Bar -->
<section class="modern-dest-detail-section" style="padding-top: 0;">
    <div class="container">
        <div class="modern-dest-quick-info">
            <div class="modern-dest-info-item">
                <div class="modern-dest-info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="modern-dest-info-content">
                    <strong>Location</strong>
                    <p><?php echo htmlspecialchars($destination['region']); ?>, <?php echo htmlspecialchars($destination['country']); ?></p>
                </div>
            </div>
            
            <?php if ($destination['best_time_to_visit']): ?>
            <div class="modern-dest-info-item">
                <div class="modern-dest-info-icon">
                    <i class="far fa-calendar-check"></i>
                </div>
                <div class="modern-dest-info-content">
                    <strong>Best Time</strong>
                    <p><?php echo htmlspecialchars($destination['best_time_to_visit']); ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($destination['climate_info']): ?>
            <div class="modern-dest-info-item">
                <div class="modern-dest-info-icon">
                    <i class="fas fa-cloud-sun"></i>
                </div>
                <div class="modern-dest-info-content">
                    <strong>Climate</strong>
                    <p><?php echo htmlspecialchars(substr($destination['climate_info'], 0, 80)); ?>...</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="modern-dest-detail-section">
    <div class="container">
        <a href="<?php echo SITE_URL; ?>/destinations.php" class="modern-back-to-destinations">
            <i class="fas fa-arrow-left"></i> Back to Destinations
        </a>
        
        <div class="modern-dest-detail-layout">
            <!-- Main Content -->
            <div class="modern-dest-detail-main">
                <!-- About Section -->
                <div class="modern-dest-content-section">
                    <h2><i class="fas fa-info-circle"></i> About <?php echo htmlspecialchars($destination['name']); ?></h2>
                    <div><?php echo nl2br(htmlspecialchars($destination['description'])); ?></div>
                </div>
                
                <!-- Wildlife Section -->
                <?php if ($destination['wildlife_info']): ?>
                <div class="modern-dest-content-section">
                    <h2><i class="fas fa-paw"></i> Wildlife & Photography Opportunities</h2>
                    <div><?php echo nl2br(htmlspecialchars($destination['wildlife_info'])); ?></div>
                </div>
                <?php endif; ?>
                
                <!-- Climate Section -->
                <?php if ($destination['climate_info']): ?>
                <div class="modern-dest-content-section">
                    <h2><i class="fas fa-cloud-sun-rain"></i> Climate Information</h2>
                    <div><?php echo nl2br(htmlspecialchars($destination['climate_info'])); ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <aside class="modern-dest-sidebar">
                <!-- Quick Facts -->
                <div class="modern-dest-sidebar-card">
                    <h3><i class="fas fa-list-check"></i> Quick Facts</h3>
                    <ul class="modern-dest-facts-list">
                        <li>
                            <strong>Region:</strong>
                            <span><?php echo htmlspecialchars($destination['region']); ?></span>
                        </li>
                        <li>
                            <strong>Country:</strong>
                            <span><?php echo htmlspecialchars($destination['country']); ?></span>
                        </li>
                        <?php if ($destination['best_time_to_visit']): ?>
                        <li>
                            <strong>Best Season:</strong>
                            <span><?php echo htmlspecialchars($destination['best_time_to_visit']); ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Available Tours -->
                <?php if (!empty($destinationTours)): ?>
                <div class="modern-dest-sidebar-card">
                    <h3><i class="fas fa-route"></i> Available Tours</h3>
                    <?php foreach ($destinationTours as $tour): ?>
                    <div class="modern-dest-tour-item">
                        <h4><?php echo htmlspecialchars($tour['title']); ?></h4>
                        <p>
                            <i class="far fa-calendar"></i>
                            <?php echo $tour['duration_days']; ?> Days / <?php echo $tour['duration_nights']; ?> Nights
                        </p>
                        <a href="<?php echo SITE_URL; ?>/tour-details.php?slug=<?php echo $tour['slug']; ?>" class="btn">
                            View Tour <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Contact Card -->
                <div class="modern-dest-sidebar-card">
                    <h3><i class="fas fa-paper-plane"></i> Plan Your Trip</h3>
                    <p>Interested in visiting <?php echo htmlspecialchars($destination['name']); ?>? Contact us to plan your perfect photography expedition!</p>
                    <a href="<?php echo SITE_URL; ?>/contact.php" class="modern-dest-sidebar-btn">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
