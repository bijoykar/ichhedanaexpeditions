<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Manage Destinations';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$destinationModel = new Destination();
$success = '';
$error = '';

// Handle destination actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_destination'])) {
        $id = (int)$_POST['destination_id'];
        
        try {
            // Check if destination has tours
            $stmt = $db->prepare("SELECT COUNT(*) FROM tours WHERE destination_id = ?");
            $stmt->execute([$id]);
            $tourCount = $stmt->fetchColumn();
            
            if ($tourCount > 0) {
                $error = "Cannot delete destination with $tourCount associated tours. Please reassign or delete the tours first.";
            } else {
                $stmt = $db->prepare("DELETE FROM destinations WHERE id = ?");
                $stmt->execute([$id]);
                $success = 'Destination deleted successfully!';
            }
        } catch (Exception $e) {
            $error = 'Error deleting destination: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_status'])) {
        $id = (int)$_POST['destination_id'];
        $status = sanitize($_POST['status']);
        
        try {
            $stmt = $db->prepare("UPDATE destinations SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = 'Destination status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating status: ' . $e->getMessage();
        }
    }
}

// Fetch all destinations
$destinations = $destinationModel->all();

// Get tour counts for each destination
$tourCounts = [];
$stmt = $db->query("SELECT destination_id, COUNT(*) as count FROM tours GROUP BY destination_id");
while ($row = $stmt->fetch()) {
    $tourCounts[$row['destination_id']] = $row['count'];
}
?>

<style>
.admin-content {
    padding: 30px;
}

.destinations-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.destinations-header h1 {
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

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

.destinations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
}

.destination-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.destination-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.destination-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.destination-content {
    padding: 20px;
}

.destination-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 12px;
}

.destination-title {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

.destination-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
}

.destination-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #6b7280;
}

.destination-meta-item i {
    color: #667eea;
    width: 16px;
}

.destination-description {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
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

.badge-featured {
    background: #dbeafe;
    color: #1e40af;
}

.destination-actions {
    display: flex;
    gap: 8px;
    padding-top: 15px;
    border-top: 1px solid #f3f4f6;
}

.status-form {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 0;
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
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

@media (max-width: 768px) {
    .destinations-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .destinations-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-content">
    <div class="destinations-header">
        <h1><i class="fas fa-map-marked-alt"></i> Manage Destinations</h1>
        <div class="header-actions">
            <a href="<?php echo SITE_URL; ?>/destinations.php" class="btn btn-secondary" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Destinations Page
            </a>
            <a href="add-destination.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Destination
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
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            </div>
            <div class="stat-card-number"><?php echo count($destinations); ?></div>
            <div class="stat-card-label">Total Destinations</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo count(array_filter($destinations, function($d) { return $d['status'] == 'published'; })); ?>
            </div>
            <div class="stat-card-label">Published</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon orange">
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <div class="stat-card-number">
                <?php echo count(array_filter($destinations, function($d) { return $d['featured']; })); ?>
            </div>
            <div class="stat-card-label">Featured</div>
        </div>
    </div>
    
    <?php if (!empty($destinations)): ?>
    <div class="destinations-grid">
        <?php foreach ($destinations as $destination): ?>
        <div class="destination-card">
            <?php if ($destination['featured_image']): ?>
            <img src="<?php echo UPLOAD_URL . '/destinations/' . $destination['featured_image']; ?>" 
                 alt="<?php echo htmlspecialchars($destination['name']); ?>" 
                 class="destination-image">
            <?php else: ?>
            <div class="destination-image"></div>
            <?php endif; ?>
            
            <div class="destination-content">
                <div class="destination-header">
                    <h3 class="destination-title"><?php echo htmlspecialchars($destination['name']); ?></h3>
                    <?php if ($destination['featured']): ?>
                    <span class="badge badge-featured"><i class="fas fa-star"></i></span>
                    <?php endif; ?>
                </div>
                
                <div class="destination-meta">
                    <div class="destination-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo htmlspecialchars($destination['region']); ?>, <?php echo htmlspecialchars($destination['country']); ?></span>
                    </div>
                    <div class="destination-meta-item">
                        <i class="fas fa-mountain"></i>
                        <span><?php echo isset($tourCounts[$destination['id']]) ? $tourCounts[$destination['id']] : 0; ?> Tours</span>
                    </div>
                    <?php if ($destination['best_time_to_visit']): ?>
                    <div class="destination-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Best: <?php echo htmlspecialchars($destination['best_time_to_visit']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($destination['description']): ?>
                <p class="destination-description">
                    <?php echo htmlspecialchars(strip_tags($destination['description'])); ?>
                </p>
                <?php endif; ?>
                
                <form method="POST" class="status-form">
                    <input type="hidden" name="destination_id" value="<?php echo $destination['id']; ?>">
                    <span style="font-size: 13px; font-weight: 600; color: #6b7280;">Status:</span>
                    <select name="status" class="status-dropdown" onchange="this.form.submit()">
                        <option value="published" <?php echo $destination['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo $destination['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                    </select>
                    <input type="hidden" name="update_status" value="1">
                </form>
                
                <div class="destination-actions">
                    <a href="edit-destination.php?id=<?php echo $destination['id']; ?>" 
                       class="btn btn-secondary btn-sm" style="flex: 1;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <?php if ($destination['status'] == 'published' && $destination['slug']): ?>
                    <a href="<?php echo SITE_URL; ?>/destination-details.php?slug=<?php echo $destination['slug']; ?>" 
                       class="btn btn-success btn-sm" target="_blank" style="flex: 1;">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <?php endif; ?>
                    <button class="btn btn-danger btn-sm" 
                            onclick="confirmDelete(<?php echo $destination['id']; ?>, '<?php echo htmlspecialchars($destination['name']); ?>')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-map-marked-alt"></i>
        <h3>No destinations found</h3>
        <p>Start by adding your first destination</p>
        <a href="add-destination.php" class="btn btn-primary" style="margin-top: 20px;">
            <i class="fas fa-plus"></i> Add New Destination
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" action="" style="display: none;">
    <input type="hidden" id="delete_destination_id" name="destination_id">
    <input type="hidden" name="delete_destination" value="1">
</form>

<script>
function confirmDelete(destinationId, destinationName) {
    if (confirm('Are you sure you want to delete "' + destinationName + '"?\n\nNote: You cannot delete destinations that have associated tours.')) {
        document.getElementById('delete_destination_id').value = destinationId;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
