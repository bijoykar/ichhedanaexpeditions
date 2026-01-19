<?php
$page_title = 'Blog';
$meta_description = 'Read our latest articles on wildlife photography, travel tips, and expedition stories.';
require_once __DIR__ . '/includes/header.php';

$blogModel = new BlogPost();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';

if ($category) {
    $blogs = $blogModel->getByCategory($category);
    $totalPages = 1;
} else {
    $result = $blogModel->paginate($page, ITEMS_PER_PAGE, ['status' => 'published'], 'published_at DESC');
    $blogs = $result['data'];
    $totalPages = $result['total_pages'];
}

// Get categories
$categories = $blogModel->getCategories();
?>

<style>
.modern-blog-header {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    padding: 100px 0 80px;
    position: relative;
    overflow: hidden;
}

.modern-blog-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.modern-blog-header .container {
    position: relative;
    z-index: 1;
    text-align: center;
}

.modern-blog-header h1 {
    color: white;
    font-size: 3.5rem;
    margin-bottom: 15px;
    font-weight: 700;
    animation: fadeInUp 0.6s ease;
}

.modern-blog-header p {
    color: rgba(255,255,255,0.95);
    font-size: 1.3rem;
    margin-bottom: 0;
    animation: fadeInUp 0.6s ease 0.2s both;
}

.modern-blog-header .blog-icon {
    display: inline-block;
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    animation: float 3s ease-in-out infinite;
}

.modern-blog-header .blog-icon i {
    font-size: 2.5rem;
    color: white;
}

.modern-blog-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.modern-blog-layout {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 40px;
    align-items: start;
}

.modern-blog-grid {
    display: grid;
    gap: 35px;
}

.modern-blog-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.4s ease;
    animation: fadeInUp 0.6s ease both;
}

.modern-blog-card:nth-child(2) { animation-delay: 0.1s; }
.modern-blog-card:nth-child(3) { animation-delay: 0.2s; }
.modern-blog-card:nth-child(4) { animation-delay: 0.3s; }
.modern-blog-card:nth-child(5) { animation-delay: 0.4s; }
.modern-blog-card:nth-child(6) { animation-delay: 0.5s; }

.modern-blog-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(102,126,234,0.2);
}

.modern-blog-image {
    position: relative;
    height: 280px;
    overflow: hidden;
}

.modern-blog-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.modern-blog-card:hover .modern-blog-image img {
    transform: scale(1.1);
}

.modern-blog-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    padding: 8px 18px;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(102,126,234,0.4);
    z-index: 2;
}

.modern-blog-content {
    padding: 30px;
}

.modern-blog-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.modern-blog-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 0.9rem;
}

.modern-blog-meta-item i {
    color: #228B22;
    font-size: 1rem;
}

.modern-blog-title {
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 15px;
    line-height: 1.4;
}

.modern-blog-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.modern-blog-title a:hover {
    color: #228B22;
}

.modern-blog-excerpt {
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.7;
    margin-bottom: 20px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.modern-blog-read-more {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #228B22;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.modern-blog-read-more:hover {
    gap: 15px;
    color: #2F4F4F;
}

.modern-blog-read-more i {
    transition: transform 0.3s ease;
}

.modern-blog-read-more:hover i {
    transform: translateX(5px);
}

.modern-sidebar {
    position: sticky;
    top: 120px;
}

.modern-sidebar-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    animation: fadeInRight 0.6s ease;
}

.modern-sidebar-card h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-sidebar-card h3 i {
    color: #228B22;
}

.modern-category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.modern-category-list li {
    margin-bottom: 10px;
}

.modern-category-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    border-radius: 12px;
    background: #f8f9fa;
    color: #2c3e50;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.modern-category-item:hover,
.modern-category-item.active {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    transform: translateX(5px);
}

.modern-category-count {
    background: rgba(102,126,234,0.2);
    color: #228B22;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.modern-category-item.active .modern-category-count,
.modern-category-item:hover .modern-category-count {
    background: rgba(255,255,255,0.3);
    color: white;
}

.modern-newsletter-form {
    margin-top: 20px;
}

.modern-newsletter-form p {
    color: #6c757d;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
}

.modern-newsletter-input {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 1rem;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.modern-newsletter-input:focus {
    outline: none;
    border-color: #228B22;
    box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
}

.modern-newsletter-btn {
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modern-newsletter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102,126,234,0.4);
}

.modern-no-posts {
    background: white;
    border-radius: 20px;
    padding: 80px 40px;
    text-align: center;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.modern-no-posts i {
    font-size: 5rem;
    color: #228B22;
    margin-bottom: 25px;
    opacity: 0.6;
}

.modern-no-posts h3 {
    font-size: 1.8rem;
    color: #2c3e50;
    margin-bottom: 15px;
}

.modern-no-posts p {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.modern-no-posts .btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 30px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.modern-no-posts .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102,126,234,0.4);
}

.modern-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 50px;
}

.modern-pagination a,
.modern-pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 45px;
    height: 45px;
    padding: 0 15px;
    border-radius: 12px;
    background: white;
    color: #2c3e50;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.modern-pagination a:hover {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    transform: translateY(-2px);
}

.modern-pagination .active {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
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

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@media (max-width: 992px) {
    .modern-blog-layout {
        grid-template-columns: 1fr;
    }
    
    .modern-sidebar {
        position: static;
    }
    
    .modern-blog-header h1 {
        font-size: 2.5rem;
    }
}

@media (max-width: 768px) {
    .modern-blog-header {
        padding: 80px 0 60px;
    }
    
    .modern-blog-header h1 {
        font-size: 2rem;
    }
    
    .modern-blog-header p {
        font-size: 1.1rem;
    }
    
    .modern-blog-section {
        padding: 50px 0;
    }
    
    .modern-blog-image {
        height: 220px;
    }
    
    .modern-blog-title {
        font-size: 1.4rem;
    }
}
</style>

<!-- Modern Blog Header -->
<section class="modern-blog-header">
    <div class="container">
        <div class="blog-icon">
            <i class="fas fa-blog"></i>
        </div>
        <h1>Our Blog</h1>
        <p>Photography tips, travel stories, and expedition insights</p>
    </div>
</section>

<!-- Modern Blog Section -->
<section class="modern-blog-section">
    <div class="container">
        <div class="modern-blog-layout">
            <!-- Blog Main Content -->
            <div class="modern-blog-main">
                <?php if (!empty($blogs)): ?>
                    <div class="modern-blog-grid">
                        <?php foreach ($blogs as $blog): ?>
                            <article class="modern-blog-card">
                                <div class="modern-blog-image">
                                    <a href="<?php echo SITE_URL; ?>/blog-details.php?slug=<?php echo $blog['slug']; ?>">
                                        <img src="<?php echo $blog['featured_image'] ? UPLOAD_URL . '/blog/' . $blog['featured_image'] : ASSETS_URL . '/images/placeholder.jpg'; ?>" 
                                             alt="<?php echo htmlspecialchars($blog['title']); ?>">
                                    </a>
                                    <?php if ($blog['category']): ?>
                                        <div class="modern-blog-badge">
                                            <?php echo htmlspecialchars($blog['category']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="modern-blog-content">
                                    <div class="modern-blog-meta">
                                        <div class="modern-blog-meta-item">
                                            <i class="far fa-calendar"></i>
                                            <span><?php echo date('M d, Y', strtotime($blog['published_at'])); ?></span>
                                        </div>
                                        <?php if (isset($blog['views']) && $blog['views'] > 0): ?>
                                        <div class="modern-blog-meta-item">
                                            <i class="far fa-eye"></i>
                                            <span><?php echo number_format($blog['views']); ?> views</span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <h2 class="modern-blog-title">
                                        <a href="<?php echo SITE_URL; ?>/blog-details.php?slug=<?php echo $blog['slug']; ?>">
                                            <?php echo htmlspecialchars($blog['title']); ?>
                                        </a>
                                    </h2>
                                    <p class="modern-blog-excerpt"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                                    <a href="<?php echo SITE_URL; ?>/blog-details.php?slug=<?php echo $blog['slug']; ?>" class="modern-blog-read-more">
                                        Read Full Article <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (!$category && $totalPages > 1): ?>
                        <div class="modern-pagination">
                            <?php if ($page > 1): ?>
                                <a href="<?php echo SITE_URL; ?>/blogs.php?page=<?php echo ($page - 1); ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="active"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="<?php echo SITE_URL; ?>/blogs.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <a href="<?php echo SITE_URL; ?>/blogs.php?page=<?php echo ($page + 1); ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="modern-no-posts">
                        <i class="fas fa-newspaper"></i>
                        <h3>No Posts Found</h3>
                        <p>There are no blog posts available<?php echo $category ? ' in this category' : ''; ?> at the moment.</p>
                        <?php if ($category): ?>
                            <a href="<?php echo SITE_URL; ?>/blogs.php" class="btn">
                                <i class="fas fa-arrow-left"></i> View All Posts
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <aside class="modern-sidebar">
                <!-- Categories Widget -->
                <?php if (!empty($categories)): ?>
                    <div class="modern-sidebar-card">
                        <h3><i class="fas fa-folder"></i> Categories</h3>
                        <ul class="modern-category-list">
                            <li>
                                <a href="<?php echo SITE_URL; ?>/blogs.php" class="modern-category-item <?php echo !$category ? 'active' : ''; ?>">
                                    <span>All Posts</span>
                                    <span class="modern-category-count"><?php echo count($blogs); ?></span>
                                </a>
                            </li>
                            <?php foreach ($categories as $cat): ?>
                                <li>
                                    <a href="<?php echo SITE_URL; ?>/blogs.php?category=<?php echo urlencode($cat['category']); ?>" 
                                       class="modern-category-item <?php echo $category == $cat['category'] ? 'active' : ''; ?>">
                                        <span><?php echo htmlspecialchars($cat['category']); ?></span>
                                        <span class="modern-category-count"><?php echo $cat['count']; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Newsletter Widget -->
                <div class="modern-sidebar-card">
                    <h3><i class="fas fa-envelope"></i> Newsletter</h3>
                    <div class="modern-newsletter-form">
                        <p>Subscribe to get latest updates on tours and photography tips delivered to your inbox.</p>
                        <form method="POST" action="<?php echo SITE_URL; ?>/api/newsletter.php">
                            <input type="email" name="email" class="modern-newsletter-input" placeholder="Your email address" required>
                            <button type="submit" class="modern-newsletter-btn">
                                <i class="fas fa-paper-plane"></i> Subscribe Now
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
