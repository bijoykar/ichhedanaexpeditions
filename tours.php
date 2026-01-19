<?php
$page_title = 'Photography Tours';
$meta_description = 'Explore our curated wildlife and nature photography expeditions across India and Bhutan.';
require_once __DIR__ . '/includes/header.php';

$tourModel = new Tour();
$destModel = new Destination();

// Get filter parameters
$destinationFilter = isset($_GET['destination']) ? (int)$_GET['destination'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12;

// Get all destinations for filter
$destinations = $destModel->where(['status' => 'published'], 'name ASC');

// Get tours based on filter with pagination
if ($destinationFilter) {
    $result = $tourModel->paginate($page, $perPage, ['status' => 'published', 'destination_id' => $destinationFilter], 'start_date ASC');
} else {
    $result = $tourModel->paginate($page, $perPage, ['status' => 'published'], 'start_date ASC');
}

$tours = $result['data'];
?>

<!-- Modern Tours Header -->
<section class="modern-tours-header">
    <div class="container">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h1 class="header-title">Photography Tours</h1>
            <p class="header-tagline">Embark on unforgettable wildlife and nature photography expeditions</p>
        </div>
    </div>
    <div class="header-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>

<!-- Tours Grid Section -->
<section class="modern-tours-section">
    <div class="container">
        <!-- Filter Section -->
        <div class="tours-filter-section">
            <div class="filter-header">
                <h3><i class="fas fa-filter"></i> Filter Tours</h3>
            </div>
            <form method="GET" action="" class="filter-form">
                <div class="filter-group">
                    <label for="destination-filter">
                        <i class="fas fa-map-marker-alt"></i> Destination
                    </label>
                    <select name="destination" id="destination-filter" onchange="this.form.submit()">
                        <option value="">All Destinations</option>
                        <?php foreach ($destinations as $dest): ?>
                        <option value="<?php echo $dest['id']; ?>" <?php echo $destinationFilter == $dest['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dest['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($destinationFilter): ?>
                <a href="tours.php" class="clear-filter-btn">
                    <i class="fas fa-times"></i> Clear Filter
                </a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (!empty($tours)): ?>
        <div class="tours-count">
            <p>Showing <?php echo count($tours); ?> of <?php echo $result['total_pages'] * $perPage; ?> tours</p>
        </div>
        <div class="modern-tours-grid">
            <?php foreach ($tours as $tour): ?>
            <div class="modern-tour-card" data-tour-id="<?php echo $tour['id']; ?>">
                <div class="tour-card-image">
                    <?php if ($tour['featured_image']): ?>
                    <img src="<?php echo UPLOAD_URL . '/tours/' . $tour['featured_image']; ?>" 
                         alt="<?php echo htmlspecialchars($tour['title']); ?>">
                    <?php else: ?>
                    <div class="tour-placeholder-image">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <?php endif; ?>
                    <?php if ($tour['featured']): ?>
                    <span class="tour-badge featured-badge">
                        <i class="fas fa-star"></i> Featured
                    </span>
                    <?php endif; ?>
                    <div class="tour-overlay">
                        <button class="view-details-btn" onclick="openTourModal(<?php echo $tour['id']; ?>)">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>
                <div class="tour-card-content">
                    <h3 class="tour-card-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                    
                    <div class="tour-card-meta">
                        <div class="meta-item">
                            <i class="far fa-calendar-alt"></i>
                            <span><?php echo date('M d, Y', strtotime($tour['start_date'])); ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="far fa-clock"></i>
                            <span><?php echo $tour['duration_nights']; ?>N / <?php echo $tour['duration_days']; ?>D</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-users"></i>
                            <span>Max <?php echo $tour['max_participants']; ?> pax</span>
                        </div>
                    </div>
                    
                    <p class="tour-card-description"><?php echo truncateText($tour['short_description'], 100); ?></p>
                    
                    <div class="tour-card-footer">
                        <?php if ($tour['price']): ?>
                        <div class="tour-card-price">
                            <span class="price-label">Starting from</span>
                            <span class="price-amount">₹<?php echo number_format($tour['price'], 0); ?></span>
                        </div>
                        <?php endif; ?>
                        <button class="tour-details-btn" onclick="openTourModal(<?php echo $tour['id']; ?>)">
                            Details <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($result) && $result['total_pages'] > 1): ?>
        <div class="pagination-wrapper">
            <?php 
            $paginationUrl = SITE_URL . '/tours.php';
            if ($destinationFilter) {
                $paginationUrl .= '?destination=' . $destinationFilter;
            }
            echo getPaginationHTML($result['current_page'], $result['total_pages'], $paginationUrl); 
            ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="no-tours-message">
            <div class="no-tours-icon">
                <i class="fas fa-compass"></i>
            </div>
            <h3>No Tours Available</h3>
            <p>We're currently planning exciting new photography expeditions. Check back soon!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Tour Details Modal -->
<div id="tourModal" class="tour-modal">
    <div class="tour-modal-overlay" onclick="closeTourModal()"></div>
    <div class="tour-modal-content">
        <button class="tour-modal-close" onclick="closeTourModal()">
            <i class="fas fa-times"></i>
        </button>
        <div id="tourModalBody" class="tour-modal-body">
            <div class="modal-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading tour details...</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Tours Header */
.modern-tours-header {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    padding: 100px 0 120px;
    position: relative;
    overflow: hidden;
    text-align: center;
}

.modern-tours-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat;
    opacity: 0.1;
}

.header-content {
    position: relative;
    z-index: 2;
}

.header-icon {
    font-size: 64px;
    color: white;
    margin-bottom: 20px;
    animation: mapPulse 2s ease-in-out infinite;
}

@keyframes mapPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.header-title {
    font-size: 48px;
    font-weight: 700;
    color: white;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.header-tagline {
    font-size: 20px;
    color: rgba(255,255,255,0.95);
    font-weight: 300;
}

.header-wave {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
}

.header-wave svg {
    display: block;
    width: 100%;
    height: 80px;
}

/* Modern Tours Section */
.modern-tours-section {
    padding: 80px 0;
    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
}

/* Tours Filter Section */
.tours-filter-section {
    background: white;
    border-radius: 15px;
    padding: 25px 30px;
    margin-bottom: 40px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.filter-header {
    margin-bottom: 20px;
}

.filter-header h3 {
    font-size: 20px;
    color: #2d3748;
    font-weight: 600;
    margin: 0;
}

.filter-header h3 i {
    color: #228B22;
    margin-right: 10px;
}

.filter-form {
    display: flex;
    align-items: flex-end;
    gap: 20px;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 250px;
}

.filter-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
}

.filter-group label i {
    color: #228B22;
    margin-right: 6px;
}

.filter-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 15px;
    color: #2d3748;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-group select:hover {
    border-color: #228B22;
}

.filter-group select:focus {
    outline: none;
    border-color: #228B22;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.clear-filter-btn {
    padding: 12px 25px;
    background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.clear-filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 101, 101, 0.3);
}

.tours-count {
    margin-bottom: 20px;
    text-align: right;
}

.tours-count p {
    font-size: 14px;
    color: #718096;
    font-weight: 500;
}

.modern-tours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 35px;
}

.modern-tour-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: all 0.4s ease;
    cursor: pointer;
}

.modern-tour-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 45px rgba(102,126,234,0.25);
}

.tour-card-image {
    position: relative;
    height: 280px;
    overflow: hidden;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
}

.tour-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.modern-tour-card:hover .tour-card-image img {
    transform: scale(1.15);
}

.tour-placeholder-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 80px;
    opacity: 0.5;
}

.tour-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255,255,255,0.95);
    color: #228B22;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 2;
}

.tour-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 30px;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.modern-tour-card:hover .tour-overlay {
    opacity: 1;
}

.view-details-btn {
    background: white;
    color: #228B22;
    border: none;
    padding: 14px 32px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.view-details-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

.tour-card-content {
    padding: 30px;
}

.tour-card-title {
    font-size: 22px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 20px;
    line-height: 1.4;
}

.tour-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 18px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e2e8f0;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #64748b;
}

.meta-item i {
    color: #228B22;
    font-size: 16px;
}

.tour-card-description {
    color: #475569;
    line-height: 1.7;
    margin-bottom: 25px;
    font-size: 15px;
}

.tour-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.tour-card-price {
    display: flex;
    flex-direction: column;
}

.price-label {
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 4px;
}

.price-amount {
    font-size: 26px;
    font-weight: 700;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.tour-details-btn {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(102,126,234,0.3);
}

.tour-details-btn:hover {
    transform: translateX(5px);
    box-shadow: 0 6px 18px rgba(102,126,234,0.4);
}

/* No Tours Message */
.no-tours-message {
    text-align: center;
    padding: 80px 20px;
}

.no-tours-icon {
    font-size: 80px;
    color: #cbd5e0;
    margin-bottom: 30px;
}

.no-tours-message h3 {
    font-size: 28px;
    color: #2d3748;
    margin-bottom: 15px;
}

.no-tours-message p {
    font-size: 16px;
    color: #64748b;
}

/* Tour Modal */
.tour-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;
    align-items: center;
    justify-content: center;
}

.tour-modal.active {
    display: flex;
}

.tour-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(8px);
}

.tour-modal-content {
    position: relative;
    background: white;
    border-radius: 25px;
    max-width: 900px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    animation: modalSlideUp 0.4s ease-out;
}

@keyframes modalSlideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tour-modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.tour-modal-close:hover {
    background: #ff4757;
    color: white;
    transform: rotate(90deg);
}

.tour-modal-body {
    overflow-y: auto;
    max-height: 90vh;
    padding: 40px;
}

.modal-loading {
    text-align: center;
    padding: 60px 20px;
    color: #228B22;
}

.modal-loading i {
    font-size: 48px;
    margin-bottom: 20px;
}

.modal-loading p {
    font-size: 16px;
    color: #64748b;
}

/* Responsive */
@media (max-width: 992px) {
    .modern-tours-grid {
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
    }
    
    .header-title {
        font-size: 36px;
    }
}

@media (max-width: 768px) {
    .modern-tours-header {
        padding: 80px 0 100px;
    }
    
    .header-icon {
        font-size: 48px;
    }
    
    .header-title {
        font-size: 32px;
    }
    
    .header-tagline {
        font-size: 16px;
    }
    
    .modern-tours-grid {
        grid-template-columns: 1fr;
    }
    
    .tour-card-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .tour-modal-content {
        width: 95%;
        max-height: 95vh;
    }
    
    .tour-modal-body {
        padding: 30px 20px;
    }
}

@media (max-width: 480px) {
    .tour-card-footer {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .tour-details-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
// Store tour data for modal
const toursData = <?php echo json_encode($tours); ?>;

function openTourModal(tourId) {
    const modal = document.getElementById('tourModal');
    const modalBody = document.getElementById('tourModalBody');
    
    // Find tour data
    const tour = toursData.find(t => t.id == tourId);
    
    if (!tour) {
        alert('Tour details not found');
        return;
    }
    
    // Build modal content
    const modalHTML = `
        <div class="modal-tour-header" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%); padding: 40px; border-radius: 15px; margin-bottom: 30px;">
            <h2 style="font-size: 32px; color: #2d3748; margin-bottom: 15px; font-weight: 700;">${tour.title}</h2>
            <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #64748b;">
                    <i class="far fa-calendar-alt" style="color: #228B22;"></i>
                    <span>${formatDate(tour.start_date)} - ${formatDate(tour.end_date)}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; color: #64748b;">
                    <i class="far fa-clock" style="color: #228B22;"></i>
                    <span>${tour.duration_nights} Nights / ${tour.duration_days} Days</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; color: #64748b;">
                    <i class="fas fa-users" style="color: #228B22;"></i>
                    <span>Max ${tour.max_participants} participants</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; color: #64748b;">
                    <i class="fas fa-signal" style="color: #228B22;"></i>
                    <span style="text-transform: capitalize;">${tour.difficulty_level}</span>
                </div>
            </div>
            ${tour.price ? `<div style="font-size: 32px; font-weight: 700; background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">₹${formatPrice(tour.price)}</div>` : ''}
        </div>
        
        <div class="modal-section" style="margin-bottom: 35px;">
            <h3 style="font-size: 24px; color: #2d3748; margin-bottom: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-info-circle" style="color: #228B22;"></i> Overview
            </h3>
            <p style="color: #475569; line-height: 1.8; font-size: 15px;">${tour.full_description}</p>
        </div>
        
        ${tour.itinerary ? `
        <div class="modal-section" style="margin-bottom: 35px;">
            <h3 style="font-size: 24px; color: #2d3748; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-route" style="color: #228B22;"></i> Itinerary
            </h3>
            <div style="background: #f8f9fa; padding: 25px; border-radius: 12px; border-left: 4px solid #228B22;">
                ${formatItinerary(tour.itinerary)}
            </div>
        </div>
        ` : ''}
        
        ${tour.photography_highlights ? `
        <div class="modal-section" style="margin-bottom: 35px;">
            <h3 style="font-size: 24px; color: #2d3748; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-camera" style="color: #228B22;"></i> Photography Highlights
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                ${formatHighlights(tour.photography_highlights)}
            </div>
        </div>
        ` : ''}
        
        <div class="modal-section" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 35px;">
            ${tour.included_services ? `
            <div style="background: #f0fdf4; padding: 25px; border-radius: 12px; border: 2px solid #86efac;">
                <h4 style="font-size: 18px; color: #15803d; margin-bottom: 15px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-check-circle"></i> Included
                </h4>
                ${formatServices(tour.included_services, true)}
            </div>
            ` : ''}
            
            ${tour.excluded_services ? `
            <div style="background: #fef2f2; padding: 25px; border-radius: 12px; border: 2px solid #fca5a5;">
                <h4 style="font-size: 18px; color: #dc2626; margin-bottom: 15px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-times-circle"></i> Excluded
                </h4>
                ${formatServices(tour.excluded_services, false)}
            </div>
            ` : ''}
        </div>
        
        ${tour.accommodation_details ? `
        <div class="modal-section" style="margin-bottom: 35px;">
            <h3 style="font-size: 24px; color: #2d3748; margin-bottom: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-hotel" style="color: #228B22;"></i> Accommodation
            </h3>
            <p style="color: #475569; line-height: 1.8; font-size: 15px; background: #f8f9fa; padding: 20px; border-radius: 12px;">${tour.accommodation_details}</p>
        </div>
        ` : ''}
        
        <div style="background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%); padding: 30px; border-radius: 15px; text-align: center; color: white;">
            <h3 style="font-size: 22px; margin-bottom: 15px; font-weight: 600;">Ready to Join This Expedition?</h3>
            <p style="margin-bottom: 20px; opacity: 0.95;">Contact us to book your spot or get more information</p>
            <a href="<?php echo SITE_URL; ?>/contact.php" style="display: inline-block; background: white; color: #228B22; padding: 14px 35px; border-radius: 30px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.2);" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-envelope"></i> Contact Us
            </a>
        </div>
    `;
    
    modalBody.innerHTML = modalHTML;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeTourModal() {
    const modal = document.getElementById('tourModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

function formatPrice(price) {
    return new Intl.NumberFormat('en-IN').format(price);
}

function formatItinerary(itinerary) {
    const days = itinerary.split('\n\n');
    return days.map((day, index) => {
        if (!day.trim()) return '';
        return `
            <div style="margin-bottom: 20px; padding-bottom: 20px; ${index < days.length - 1 ? 'border-bottom: 1px solid #e2e8f0;' : ''}">
                <p style="color: #2d3748; line-height: 1.8; white-space: pre-line;">${day.trim()}</p>
            </div>
        `;
    }).join('');
}

function formatHighlights(highlights) {
    const items = highlights.split(',').map(h => h.trim()).filter(h => h);
    return items.map(item => `
        <div style="background: white; padding: 15px; border-radius: 10px; display: flex; align-items: center; gap: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 3px solid #228B22;">
            <i class="fas fa-camera-retro" style="color: #228B22; font-size: 18px;"></i>
            <span style="color: #2d3748; font-size: 14px;">${item}</span>
        </div>
    `).join('');
}

function formatServices(services, isIncluded) {
    const items = services.split(',').map(s => s.trim()).filter(s => s);
    const iconClass = isIncluded ? 'fa-check' : 'fa-times';
    const iconColor = isIncluded ? '#15803d' : '#dc2626';
    
    return `
        <ul style="list-style: none; padding: 0; margin: 0;">
            ${items.map(item => `
                <li style="display: flex; gap: 10px; margin-bottom: 10px; color: #475569; font-size: 14px; line-height: 1.6;">
                    <i class="fas ${iconClass}" style="color: ${iconColor}; margin-top: 4px; flex-shrink: 0;"></i>
                    <span>${item}</span>
                </li>
            `).join('')}
        </ul>
    `;
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTourModal();
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
