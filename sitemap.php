<?php
require_once 'config/config.php';

$page_title = 'Sitemap - ' . SITE_NAME;
require_once 'includes/header.php';

// Get all tours
$tourModel = new Tour();
$tours = $tourModel->where(['status' => 'published'], 'created_at DESC', 100);

// Get all destinations
$destModel = new Destination();
$destinations = $destModel->where(['status' => 'published'], 'created_at DESC', 100);

// Get all blog posts
$blogModel = new BlogPost();
$blogs = $blogModel->where(['status' => 'published'], 'created_at DESC', 100);
?>

<style>
.page-header {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    padding: 80px 0 60px;
    text-align: center;
    color: white;
}

.page-header h1 {
    font-size: 48px;
    font-weight: 700;
    margin: 0 0 15px 0;
}

.page-header p {
    font-size: 18px;
    opacity: 0.9;
}

.sitemap-content {
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
}

.sitemap-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.sitemap-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.sitemap-section h2 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #1a1a1a;
    padding-bottom: 15px;
    border-bottom: 3px solid #228B22;
}

.sitemap-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sitemap-section ul li {
    margin-bottom: 12px;
}

.sitemap-section ul li a {
    color: #4b5563;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    padding: 8px 0;
}

.sitemap-section ul li a:hover {
    color: #228B22;
    padding-left: 10px;
}

.sitemap-section ul li a i {
    font-size: 14px;
    opacity: 0.6;
}

.sitemap-section ul ul {
    margin-left: 20px;
    margin-top: 10px;
}

.sitemap-section ul ul li a {
    font-size: 15px;
    color: #6b7280;
}

.section-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .page-header h1 {
        font-size: 36px;
    }
    
    .sitemap-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}
</style>

<div class="page-header">
    <div class="container">
        <h1>Sitemap</h1>
        <p>Complete overview of all pages on our website</p>
    </div>
</div>

<div class="sitemap-content">
    <div class="sitemap-grid">
        <!-- Main Pages -->
        <div class="sitemap-section">
            <div class="section-icon">
                <i class="fas fa-home"></i>
            </div>
            <h2>Main Pages</h2>
            <ul>
                <li><a href="<?php echo SITE_URL; ?>"><i class="fas fa-angle-right"></i> Home</a></li>
                <li><a href="<?php echo SITE_URL; ?>/about.php"><i class="fas fa-angle-right"></i> About Us</a></li>
                <li><a href="<?php echo SITE_URL; ?>/contact.php"><i class="fas fa-angle-right"></i> Contact Us</a></li>
                <li><a href="<?php echo SITE_URL; ?>/gallery.php"><i class="fas fa-angle-right"></i> Gallery</a></li>
                <li><a href="<?php echo SITE_URL; ?>/reviews.php"><i class="fas fa-angle-right"></i> Customer Reviews</a></li>
            </ul>
        </div>

        <!-- Tours -->
        <div class="sitemap-section">
            <div class="section-icon">
                <i class="fas fa-camera"></i>
            </div>
            <h2>Photography Tours</h2>
            <ul>
                <li><a href="<?php echo SITE_URL; ?>/tours.php"><i class="fas fa-angle-right"></i> All Tours</a></li>
                <li><a href="<?php echo SITE_URL; ?>/custom-tours.php"><i class="fas fa-angle-right"></i> Customised Tours</a></li>
                <?php if (!empty($tours)): ?>
                    <ul>
                        <?php foreach ($tours as $tour): ?>
                            <li>
                                <a href="<?php echo SITE_URL; ?>/tour-details.php?id=<?php echo $tour['id']; ?>">
                                    <i class="fas fa-angle-right"></i> <?php echo htmlspecialchars($tour['title'] ?? ''); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Destinations -->
        <div class="sitemap-section">
            <div class="section-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h2>Destinations</h2>
            <ul>
                <li><a href="<?php echo SITE_URL; ?>/destinations.php"><i class="fas fa-angle-right"></i> All Destinations</a></li>
                <?php if (!empty($destinations)): ?>
                    <ul>
                        <?php foreach ($destinations as $dest): ?>
                            <li>
                                <a href="<?php echo SITE_URL; ?>/destination-details.php?id=<?php echo $dest['id']; ?>">
                                    <i class="fas fa-angle-right"></i> <?php echo htmlspecialchars($dest['title'] ?? ''); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Blog -->
        <div class="sitemap-section">
            <div class="section-icon">
                <i class="fas fa-blog"></i>
            </div>
            <h2>Blog Posts</h2>
            <ul>
                <li><a href="<?php echo SITE_URL; ?>/blogs.php"><i class="fas fa-angle-right"></i> All Blog Posts</a></li>
                <?php if (!empty($blogs)): ?>
                    <ul>
                        <?php foreach ($blogs as $blog): ?>
                            <li>
                                <a href="<?php echo SITE_URL; ?>/blog-details.php?id=<?php echo $blog['id']; ?>">
                                    <i class="fas fa-angle-right"></i> <?php echo htmlspecialchars($blog['title'] ?? ''); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Legal -->
        <div class="sitemap-section">
            <div class="section-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <h2>Legal & Info</h2>
            <ul>
                <li><a href="<?php echo SITE_URL; ?>/privacy-policy.php"><i class="fas fa-angle-right"></i> Privacy Policy</a></li>
                <li><a href="<?php echo SITE_URL; ?>/sitemap.php"><i class="fas fa-angle-right"></i> Sitemap</a></li>
            </ul>
        </div>

        <!-- Admin -->
        <div class="sitemap-section">
            <div class="section-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2>Admin Area</h2>
            <ul>
                <li><a href="<?php echo SITE_URL; ?>/admin/login.php"><i class="fas fa-angle-right"></i> Admin Login</a></li>
            </ul>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
