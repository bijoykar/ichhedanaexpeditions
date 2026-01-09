<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Manage Tours';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$tourModel = new Tour();
$destinationModel = new Destination();
$success = '';
$error = '';

// Handle tour actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_tour'])) {
        $id = (int)$_POST['tour_id'];
        
        try {
            $stmt = $db->prepare("DELETE FROM tours WHERE id = ?");
            $stmt->execute([$id]);
            $success = 'Tour deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting tour: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_status'])) {
        $id = (int)$_POST['tour_id'];
        $status = sanitize($_POST['status']);
        
        try {
            $stmt = $db->prepare("UPDATE tours SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = 'Tour status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating status: ' . $e->getMessage();
        }
    }
}

// Fetch all tours with destination names
$sql = "SELECT t.*, d.name as destination_name 
        FROM tours t 
        LEFT JOIN destinations d ON t.destination_id = d.id 
        ORDER BY t.created_at DESC";
$tours = $db->query($sql)->fetchAll();

// Get all destinations for filters
$destinations = $destinationModel->all();
?>

<style>
.admin-content {
    padding: 30px;
}

.tours-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.tours-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}

.alert {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.filters-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 20px 25px;
    margin-bottom: 25px;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.filter-group select,
.filter-group input {
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
}

.tours-table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.tours-table {
    width: 100%;
    border-collapse: collapse;
}

.tours-table thead {
    background: #f9fafb;
}

.tours-table th {
    padding: 16px 20px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tours-table td {
    padding: 16px 20px;
    border-top: 1px solid #f3f4f6;
    color: #374151;
}

.tours-table tbody tr:hover {
    background: #f9fafb;
}

.tour-info {
    display: flex;
    gap: 15px;
}

.tour-image {
    width: 80px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
}

.tour-details h4 {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 5px 0;
}

.tour-details p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-published {
    background: #d1fae5;
    color: #065f46;
}

.badge-draft {
    background: #fef3c7;
    color: #92400e;
}

.badge-archived {
    background: #e5e7eb;
    color: #374151;
}

.badge-featured {
    background: #dbeafe;
    color: #1e40af;
}

.tour-meta {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.tour-meta-item {
    font-size: 13px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 6px;
}

.tour-meta-item i {
    color: #667eea;
    width: 14px;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.status-dropdown {
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    background: white;
    cursor: pointer;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 64px;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 20px;
    color: #6b7280;
    margin-bottom: 10px;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.stat-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-card-icon.purple {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.stat-card-icon.green {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stat-card-icon.orange {
    background: rgba(251, 146, 60, 0.1);
    color: #fb923c;
}

.stat-card-icon.gray {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.stat-card-number {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 5px;
}

.stat-card-label {
    font-size: 14px;
    color: #6b7280;
}

@media (max-width: 768px) {
    .tours-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .tours-table {
        font-size: 13px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="admin-content">
    <div class="tours-header">
        <h1><i class="fas fa-mountain"></i> Manage Tours</h1>
        <div class="header-actions">
            <a href="<?php echo SITE_URL; ?>/tours.php" class="btn btn-secondary" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Tours Page
            </a>
            <a href="add-tour.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Tour
            </a>
        </div>
    </div>
    
    <?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span><?php echo $success; ?></span>
    </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo $error; ?></span>
    </div>
    <?php endif; ?>
    
    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon purple">
                    <i class="fas fa-mountain"></i>
                </div>
            </div>
            <div class="stat-card-number"><?php echo count($tours); ?></div>
            <div class="stat-card-label">Total Tours</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo count(array_filter($tours, function($t) { return $t['status'] == 'published'; })); ?>
            </div>
            <div class="stat-card-label">Published</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon orange">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo count(array_filter($tours, function($t) { return $t['status'] == 'draft'; })); ?>
            </div>
            <div class="stat-card-label">Drafts</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon gray">
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo count(array_filter($tours, function($t) { return $t['featured']; })); ?>
            </div>
            <div class="stat-card-label">Featured</div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filters-card">
        <div class="filters-grid">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" id="searchInput" placeholder="Search tours..." onkeyup="filterTable()">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="statusFilter" onchange="filterTable()">
                    <option value="">All Statuses</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Destination</label>
                <select id="destinationFilter" onchange="filterTable()">
                    <option value="">All Destinations</option>
                    <?php foreach ($destinations as $dest): ?>
                    <option value="<?php echo htmlspecialchars($dest['name']); ?>">
                        <?php echo htmlspecialchars($dest['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    
    <?php if (!empty($tours)): ?>
    <div class="tours-table-card">
        <table class="tours-table" id="toursTable">
            <thead>
                <tr>
                    <th>Tour</th>
                    <th>Destination</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tours as $tour): ?>
                <tr data-status="<?php echo $tour['status']; ?>" data-destination="<?php echo htmlspecialchars($tour['destination_name']); ?>">
                    <td>
                        <div class="tour-info">
                            <?php if ($tour['featured_image']): ?>
                            <img src="<?php echo UPLOAD_URL . '/tours/' . $tour['featured_image']; ?>" 
                                 alt="<?php echo htmlspecialchars($tour['title']); ?>" 
                                 class="tour-image">
                            <?php else: ?>
                            <div class="tour-image" style="background: #e5e7eb;"></div>
                            <?php endif; ?>
                            <div class="tour-details">
                                <h4><?php echo htmlspecialchars($tour['title']); ?></h4>
                                <p>
                                    <?php if ($tour['featured']): ?>
                                    <span class="badge badge-featured"><i class="fas fa-star"></i> Featured</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php echo $tour['destination_name'] ? htmlspecialchars($tour['destination_name']) : '<span style="color: #9ca3af;">—</span>'; ?>
                    </td>
                    <td>
                        <div class="tour-meta">
                            <div class="tour-meta-item">
                                <i class="fas fa-calendar-day"></i>
                                <?php echo $tour['duration_days']; ?>D / <?php echo $tour['duration_nights']; ?>N
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if ($tour['price']): ?>
                        <strong style="color: #667eea;">₹<?php echo number_format($tour['price'], 0); ?></strong>
                        <?php else: ?>
                        <span style="color: #9ca3af;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                            <select name="status" class="status-dropdown badge-<?php echo $tour['status']; ?>" 
                                    onchange="this.form.submit()">
                                <option value="published" <?php echo $tour['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo $tour['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="archived" <?php echo $tour['status'] == 'archived' ? 'selected' : ''; ?>>Archived</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td>
                        <div style="font-size: 13px; color: #6b7280;">
                            <?php echo date('M d, Y', strtotime($tour['created_at'])); ?>
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit-tour.php?id=<?php echo $tour['id']; ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?php echo SITE_URL; ?>/tour-details.php?slug=<?php echo $tour['slug']; ?>" 
                               class="btn btn-success btn-sm" target="_blank">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <button class="btn btn-danger btn-sm" 
                                    onclick="confirmDelete(<?php echo $tour['id']; ?>, '<?php echo htmlspecialchars($tour['title']); ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="tours-table-card">
        <div class="empty-state">
            <i class="fas fa-mountain"></i>
            <h3>No tours found</h3>
            <p>Start by adding your first tour</p>
            <a href="add-tour.php" class="btn btn-primary" style="margin-top: 20px;">
                <i class="fas fa-plus"></i> Add New Tour
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" action="" style="display: none;">
    <input type="hidden" id="delete_tour_id" name="tour_id">
    <input type="hidden" name="delete_tour" value="1">
</form>

<script>
function confirmDelete(tourId, tourTitle) {
    if (confirm('Are you sure you want to delete "' + tourTitle + '"? This action cannot be undone.')) {
        document.getElementById('delete_tour_id').value = tourId;
        document.getElementById('deleteForm').submit();
    }
}

function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const destinationFilter = document.getElementById('destinationFilter').value.toLowerCase();
    const table = document.getElementById('toursTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        const status = row.getAttribute('data-status').toLowerCase();
        const destination = row.getAttribute('data-destination').toLowerCase();
        
        let showRow = true;
        
        // Search filter
        if (searchInput && !text.includes(searchInput)) {
            showRow = false;
        }
        
        // Status filter
        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }
        
        // Destination filter
        if (destinationFilter && destination !== destinationFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    }
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
