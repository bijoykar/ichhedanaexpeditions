<?php
require_once __DIR__ . '/includes/header.php';

if (!isset($_GET['slug'])) {
    redirect(SITE_URL . '/blogs.php');
}

$blogModel = new BlogPost();
$blog = $blogModel->getBySlug($_GET['slug']);

if (!$blog || $blog['status'] != 'published') {
    redirect(SITE_URL . '/blogs.php');
}

$page_title = $blog['title'];
$meta_description = $blog['meta_description'] ?: $blog['excerpt'];
$meta_keywords = $blog['meta_keywords'];

// Increment views
$blogModel->incrementViews($blog['id']);

// Get related posts
$relatedPosts = [];
if ($blog['category']) {
    $relatedPosts = $blogModel->getByCategory($blog['category'], 4);
    // Remove current post from related posts
    $relatedPosts = array_filter($relatedPosts, function($post) use ($blog) {
        return $post['id'] != $blog['id'];
    });
}
?>

<style>
.modern-blog-detail-header {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    padding: 140px 0 60px;
    position: relative;
    overflow: hidden;
}

.modern-blog-detail-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.modern-blog-detail-header .container {
    position: relative;
    z-index: 1;
}

.modern-breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
    animation: fadeInDown 0.6s ease;
}

.modern-breadcrumb a,
.modern-breadcrumb span {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.3s ease;
}

.modern-breadcrumb a:hover {
    color: white;
}

.modern-breadcrumb i {
    color: rgba(255,255,255,0.6);
    font-size: 0.8rem;
}

.modern-blog-detail-title {
    color: white;
    font-size: 3rem;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 25px;
    animation: fadeInUp 0.6s ease 0.2s both;
}

.modern-blog-detail-meta {
    display: flex;
    align-items: center;
    gap: 30px;
    flex-wrap: wrap;
    animation: fadeInUp 0.6s ease 0.3s both;
}

.modern-blog-detail-meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,0.95);
    font-size: 1rem;
}

.modern-blog-detail-meta-item i {
    color: rgba(255,255,255,0.8);
    font-size: 1.1rem;
}

.modern-blog-detail-category {
    background: rgba(255,255,255,0.2);
    padding: 8px 20px;
    border-radius: 25px;
    color: white;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.modern-blog-detail-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.modern-blog-detail-layout {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 50px;
    align-items: start;
}

.modern-blog-detail-main {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    animation: fadeInUp 0.6s ease;
}

.modern-blog-featured-image {
    width: 100%;
    height: 500px;
    position: relative;
    overflow: hidden;
}

.modern-blog-featured-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.modern-blog-article-content {
    padding: 50px 60px;
}

.modern-blog-content {
    font-size: 1.1rem;
    line-height: 1.9;
    color: #2c3e50;
}

.modern-blog-content h2 {
    color: #2c3e50;
    font-size: 2rem;
    font-weight: 700;
    margin-top: 40px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 3px solid #228B22;
}

.modern-blog-content h3 {
    color: #2c3e50;
    font-size: 1.6rem;
    font-weight: 600;
    margin-top: 35px;
    margin-bottom: 15px;
}

.modern-blog-content p {
    margin-bottom: 20px;
}

.modern-blog-content ul,
.modern-blog-content ol {
    margin-bottom: 25px;
    padding-left: 25px;
}

.modern-blog-content li {
    margin-bottom: 12px;
    line-height: 1.8;
}

.modern-blog-content li strong {
    color: #228B22;
    font-weight: 600;
}

.modern-blog-content blockquote {
    background: #f8f9fa;
    border-left: 4px solid #228B22;
    padding: 25px 30px;
    margin: 30px 0;
    border-radius: 0 12px 12px 0;
    font-style: italic;
    color: #6c757d;
}

.modern-blog-share-section {
    padding: 40px 60px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.modern-blog-share-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-blog-share-title i {
    color: #228B22;
}

.modern-social-share {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.modern-share-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    color: white;
}

.modern-share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.modern-share-btn.facebook {
    background: #1877f2;
}

.modern-share-btn.twitter {
    background: #1da1f2;
}

.modern-share-btn.whatsapp {
    background: #25d366;
}

.modern-share-btn.email {
    background: #228B22;
}

.modern-related-posts {
    padding: 50px 60px;
    border-top: 1px solid #e9ecef;
}

.modern-related-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.modern-related-title i {
    color: #228B22;
}

.modern-related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 25px;
}

.modern-related-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    text-decoration: none;
    display: block;
}

.modern-related-card:hover {
    border-color: #228B22;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(102,126,234,0.2);
}

.modern-related-card-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
}

.modern-related-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.modern-related-card:hover .modern-related-card-image img {
    transform: scale(1.1);
}

.modern-related-card-content {
    padding: 20px;
}

.modern-related-card-title {
    color: #2c3e50;
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.4;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.modern-blog-detail-sidebar {
    position: sticky;
    top: 120px;
}

.modern-sidebar-card {
    background: white;
    border-radius: 20px;
    padding: 35px;
    margin-bottom: 30px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    animation: fadeInRight 0.6s ease;
}

.modern-sidebar-card:nth-child(2) { animation-delay: 0.1s; }
.modern-sidebar-card:nth-child(3) { animation-delay: 0.2s; }

.modern-sidebar-card h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-sidebar-card h3 i {
    color: #228B22;
    font-size: 1.3rem;
}

.modern-post-info {
    list-style: none;
    padding: 0;
    margin: 0;
}

.modern-post-info li {
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #6c757d;
    font-size: 0.95rem;
}

.modern-post-info li:last-child {
    border-bottom: none;
}

.modern-post-info li i {
    color: #228B22;
    font-size: 1.1rem;
    width: 20px;
}

.modern-post-info li strong {
    color: #2c3e50;
    font-weight: 600;
    min-width: 90px;
}

.modern-sidebar-card p {
    color: #6c757d;
    font-size: 0.95rem;
    line-height: 1.7;
    margin-bottom: 20px;
}

.modern-sidebar-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 14px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.modern-sidebar-btn.primary {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
}

.modern-sidebar-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102,126,234,0.4);
}

.modern-sidebar-btn.outline {
    background: transparent;
    border: 2px solid #228B22;
    color: #228B22;
}

.modern-sidebar-btn.outline:hover {
    background: #228B22;
    color: white;
}

.modern-back-to-blog {
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

.modern-back-to-blog:hover {
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
    .modern-blog-detail-layout {
        grid-template-columns: 1fr;
    }
    
    .modern-blog-detail-sidebar {
        position: static;
    }
    
    .modern-blog-detail-title {
        font-size: 2.2rem;
    }
    
    .modern-blog-article-content {
        padding: 40px 30px;
    }
    
    .modern-blog-share-section,
    .modern-related-posts {
        padding: 40px 30px;
    }
}

@media (max-width: 768px) {
    .modern-blog-detail-header {
        padding: 120px 0 50px;
    }
    
    .modern-blog-detail-title {
        font-size: 1.8rem;
    }
    
    .modern-blog-featured-image {
        height: 300px;
    }
    
    .modern-blog-article-content {
        padding: 30px 25px;
    }
    
    .modern-blog-content {
        font-size: 1rem;
    }
    
    .modern-blog-content h2 {
        font-size: 1.6rem;
    }
    
    .modern-related-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Modern Blog Detail Header -->
<section class="modern-blog-detail-header">
    <div class="container">
        <div class="modern-breadcrumb">
            <a href="<?php echo SITE_URL; ?>"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="<?php echo SITE_URL; ?>/blogs.php">Blog</a>
            <i class="fas fa-chevron-right"></i>
            <span><?php echo htmlspecialchars($blog['category'] ?: 'Article'); ?></span>
        </div>
        
        <h1 class="modern-blog-detail-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
        
        <div class="modern-blog-detail-meta">
            <div class="modern-blog-detail-meta-item">
                <i class="far fa-calendar-alt"></i>
                <span><?php echo date('F d, Y', strtotime($blog['published_at'])); ?></span>
            </div>
            <?php if (isset($blog['views']) && $blog['views'] > 0): ?>
            <div class="modern-blog-detail-meta-item">
                <i class="far fa-eye"></i>
                <span><?php echo number_format($blog['views']); ?> views</span>
            </div>
            <?php endif; ?>
            <?php if ($blog['category']): ?>
            <div class="modern-blog-detail-category">
                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($blog['category']); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modern Blog Detail Section -->
<section class="modern-blog-detail-section">
    <div class="container">
        <a href="<?php echo SITE_URL; ?>/blogs.php" class="modern-back-to-blog">
            <i class="fas fa-arrow-left"></i> Back to Blog
        </a>
        
        <div class="modern-blog-detail-layout">
            <!-- Main Content -->
            <article class="modern-blog-detail-main">
                <?php if ($blog['featured_image']): ?>
                <div class="modern-blog-featured-image">
                    <img src="<?php echo UPLOAD_URL . '/blog/' . $blog['featured_image']; ?>" 
                         alt="<?php echo htmlspecialchars($blog['title']); ?>">
                </div>
                <?php endif; ?>
                
                <div class="modern-blog-article-content">
                    <div class="modern-blog-content">
                        <?php echo $blog['content']; ?>
                    </div>
                </div>
                
                <!-- Share Section -->
                <div class="modern-blog-share-section">
                    <h3 class="modern-blog-share-title">
                        <i class="fas fa-share-alt"></i> Share This Article
                    </h3>
                    <div class="modern-social-share">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/blog-details.php?slug=' . $blog['slug']); ?>" 
                           target="_blank" class="modern-share-btn facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/blog-details.php?slug=' . $blog['slug']); ?>&text=<?php echo urlencode($blog['title']); ?>" 
                           target="_blank" class="modern-share-btn twitter">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode($blog['title'] . ' ' . SITE_URL . '/blog-details.php?slug=' . $blog['slug']); ?>" 
                           target="_blank" class="modern-share-btn whatsapp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode($blog['title']); ?>&body=<?php echo urlencode('Check out this article: ' . SITE_URL . '/blog-details.php?slug=' . $blog['slug']); ?>" 
                           class="modern-share-btn email">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>
                
                <!-- Related Posts -->
                <?php if (!empty($relatedPosts)): ?>
                <div class="modern-related-posts">
                    <h3 class="modern-related-title">
                        <i class="fas fa-newspaper"></i> Related Articles
                    </h3>
                    <div class="modern-related-grid">
                        <?php foreach (array_slice($relatedPosts, 0, 3) as $relatedPost): ?>
                        <a href="<?php echo SITE_URL; ?>/blog-details.php?slug=<?php echo $relatedPost['slug']; ?>" class="modern-related-card">
                            <div class="modern-related-card-image">
                                <img src="<?php echo $relatedPost['featured_image'] ? UPLOAD_URL . '/blog/' . $relatedPost['featured_image'] : ASSETS_URL . '/images/placeholder.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($relatedPost['title']); ?>">
                            </div>
                            <div class="modern-related-card-content">
                                <h4 class="modern-related-card-title"><?php echo htmlspecialchars($relatedPost['title']); ?></h4>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </article>
            
            <!-- Sidebar -->
            <aside class="modern-blog-detail-sidebar">
                <!-- Post Info Card -->
                <div class="modern-sidebar-card">
                    <h3><i class="fas fa-info-circle"></i> Post Details</h3>
                    <ul class="modern-post-info">
                        <li>
                            <i class="far fa-calendar"></i>
                            <strong>Published:</strong> 
                            <span><?php echo date('M d, Y', strtotime($blog['published_at'])); ?></span>
                        </li>
                        <?php if ($blog['category']): ?>
                        <li>
                            <i class="fas fa-folder"></i>
                            <strong>Category:</strong> 
                            <span><?php echo htmlspecialchars($blog['category']); ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if (isset($blog['views']) && $blog['views'] > 0): ?>
                        <li>
                            <i class="far fa-eye"></i>
                            <strong>Views:</strong> 
                            <span><?php echo number_format($blog['views']); ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Tours Card -->
                <div class="modern-sidebar-card">
                    <h3><i class="fas fa-mountain"></i> Explore Tours</h3>
                    <p>Interested in joining one of our wildlife photography expeditions? Discover our guided tours.</p>
                    <a href="<?php echo SITE_URL; ?>/tours.php" class="modern-sidebar-btn primary">
                        <i class="fas fa-map-marked-alt"></i> View All Tours
                    </a>
                </div>
                
                <!-- Contact Card -->
                <div class="modern-sidebar-card">
                    <h3><i class="fas fa-envelope"></i> Get in Touch</h3>
                    <p>Have questions or need more information? We're here to help you plan your perfect expedition!</p>
                    <a href="<?php echo SITE_URL; ?>/contact.php" class="modern-sidebar-btn outline">
                        <i class="fas fa-paper-plane"></i> Contact Us
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
