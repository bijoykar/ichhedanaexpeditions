<?php
$page_title = 'Destinations';
$meta_description = 'Explore our top wildlife and nature photography destinations across India and Bhutan.';
require_once __DIR__ . '/includes/header.php';

$destinationModel = new Destination();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$result = $destinationModel->paginate($page, 12, ['status' => 'published'], 'display_order ASC, name ASC');
?>

<style>
.modern-destinations-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 140px 0 80px;
    position: relative;
    overflow: hidden;
}

.modern-destinations-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.modern-destinations-header .container {
    position: relative;
    z-index: 1;
    text-align: center;
}

.modern-destinations-header .dest-icon {
    display: inline-block;
    width: 90px;
    height: 90px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    animation: float 3s ease-in-out infinite;
}

.modern-destinations-header .dest-icon i {
    font-size: 3rem;
    color: white;
}

.modern-destinations-header h1 {
    color: white;
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    animation: fadeInUp 0.6s ease;
}

.modern-destinations-header p {
    color: rgba(255,255,255,0.95);
    font-size: 1.3rem;
    margin-bottom: 0;
    animation: fadeInUp 0.6s ease 0.2s both;
}

.modern-destinations-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.modern-destinations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 35px;
    margin-bottom: 50px;
}

.modern-destination-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.4s ease;
    animation: fadeInUp 0.6s ease both;
}

.modern-destination-card:nth-child(2) { animation-delay: 0.1s; }
.modern-destination-card:nth-child(3) { animation-delay: 0.2s; }
.modern-destination-card:nth-child(4) { animation-delay: 0.3s; }
.modern-destination-card:nth-child(5) { animation-delay: 0.4s; }
.modern-destination-card:nth-child(6) { animation-delay: 0.5s; }

.modern-destination-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(102,126,234,0.25);
}

.modern-destination-image {
    position: relative;
    height: 280px;
    overflow: hidden;
}

.modern-destination-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.modern-destination-card:hover .modern-destination-image img {
    transform: scale(1.15);
}

.modern-destination-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 25px;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.modern-destination-card:hover .modern-destination-overlay {
    opacity: 1;
}

.modern-destination-overlay h3 {
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.modern-destination-overlay p {
    color: rgba(255,255,255,0.9);
    font-size: 0.95rem;
    margin-bottom: 15px;
}

.modern-destination-overlay .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    color: #667eea;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.modern-destination-overlay .btn:hover {
    background: #667eea;
    color: white;
    transform: translateX(5px);
}

.modern-destination-featured-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #ffd700 0%, #ffa500 100%);
    color: white;
    padding: 8px 18px;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(255,165,0,0.4);
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 6px;
}

.modern-destination-content {
    padding: 30px;
}

.modern-destination-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modern-destination-location {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 15px;
}

.modern-destination-location i {
    color: #667eea;
    font-size: 1.1rem;
}

.modern-destination-description {
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.7;
    margin-bottom: 20px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.modern-destination-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
    margin-bottom: 20px;
}

.modern-destination-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 0.9rem;
}

.modern-destination-meta-item i {
    color: #667eea;
}

.modern-destination-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.modern-destination-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102,126,234,0.4);
}

.modern-no-destinations {
    background: white;
    border-radius: 20px;
    padding: 100px 40px;
    text-align: center;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.modern-no-destinations i {
    font-size: 5rem;
    color: #667eea;
    margin-bottom: 30px;
    opacity: 0.6;
}

.modern-no-destinations h3 {
    font-size: 1.8rem;
    color: #2c3e50;
    margin-bottom: 15px;
}

.modern-no-destinations p {
    color: #6c757d;
    font-size: 1.1rem;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
}

.modern-pagination .active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@media (max-width: 768px) {
    .modern-destinations-header {
        padding: 120px 0 60px;
    }
    
    .modern-destinations-header h1 {
        font-size: 2.5rem;
    }
    
    .modern-destinations-grid {
        grid-template-columns: 1fr;
    }
    
    .modern-destination-image {
        height: 240px;
    }
}
</style>

<!-- Modern Destinations Header -->
<section class="modern-destinations-header">
    <div class="container">
        <div class="dest-icon">
            <i class="fas fa-globe-asia"></i>
        </div>
        <h1>All Destinations</h1>
        <p>Discover the best wildlife and nature photography locations</p>
    </div>
</section>

<!-- Modern Destinations Section -->
<section class="modern-destinations-section">
    <div class="container">
        <?php if (!empty($result['data'])): ?>
            <div class="modern-destinations-grid">
                <?php foreach ($result['data'] as $destination): ?>
                <div class="modern-destination-card">
                    <div class="modern-destination-image">
                        <img src="<?php echo $destination['featured_image'] ? UPLOAD_URL . '/destinations/' . $destination['featured_image'] : ASSETS_URL . '/images/placeholder.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($destination['name']); ?>">
                        
                        <?php if ($destination['featured']): ?>
                        <div class="modern-destination-featured-badge">
                            <i class="fas fa-star"></i> Featured
                        </div>
                        <?php endif; ?>
                        
                        <div class="modern-destination-overlay">
                            <h3><?php echo htmlspecialchars($destination['name']); ?></h3>
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($destination['region']) . ', ' . htmlspecialchars($destination['country']); ?></p>
                            <a href="<?php echo SITE_URL; ?>/destination-details.php?slug=<?php echo $destination['slug']; ?>" class="btn">
                                Explore <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="modern-destination-content">
                        <h3 class="modern-destination-title">
                            <?php echo htmlspecialchars($destination['name']); ?>
                        </h3>
                        
                        <div class="modern-destination-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo htmlspecialchars($destination['region']) . ', ' . htmlspecialchars($destination['country']); ?></span>
                        </div>
                        
                        <p class="modern-destination-description">
                            <?php echo htmlspecialchars(substr($destination['description'], 0, 150)) . '...'; ?>
                        </p>
                        
                        <?php if ($destination['best_time_to_visit']): ?>
                        <div class="modern-destination-meta">
                            <div class="modern-destination-meta-item">
                                <i class="far fa-calendar-check"></i>
                                <span><?php echo htmlspecialchars($destination['best_time_to_visit']); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <a href="<?php echo SITE_URL; ?>/destination-details.php?slug=<?php echo $destination['slug']; ?>" class="modern-destination-btn">
                            <i class="fas fa-info-circle"></i> View Details
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($result['total_pages'] > 1): ?>
                <div class="modern-pagination">
                    <?php if ($page > 1): ?>
                        <a href="<?php echo SITE_URL; ?>/destinations.php?page=<?php echo ($page - 1); ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="<?php echo SITE_URL; ?>/destinations.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $result['total_pages']): ?>
                        <a href="<?php echo SITE_URL; ?>/destinations.php?page=<?php echo ($page + 1); ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="modern-no-destinations">
                <i class="fas fa-map-marked-alt"></i>
                <h3>No Destinations Available</h3>
                <p>We're currently updating our destination listings. Please check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
