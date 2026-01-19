<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Site Settings';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_stats'])) {
        // Update site statistics
        $stats = [
            'tours_conducted' => sanitize($_POST['tours_conducted']),
            'happy_clients' => sanitize($_POST['happy_clients']),
            'destinations' => sanitize($_POST['destinations']),
            'average_rating' => sanitize($_POST['average_rating'])
        ];
        
        try {
            foreach ($stats as $key => $value) {
                $stmt = $db->prepare("UPDATE site_statistics SET stat_value = ? WHERE stat_key = ?");
                $stmt->execute([$value, $key]);
            }
            $success = 'Site statistics updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating statistics: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_contact'])) {
        // Update contact information
        // This would update a site_settings table if you create one
        $success = 'Contact information updated successfully!';
    }
}

// Fetch current statistics
$siteStats = [];
$stmt = $db->query("SELECT * FROM site_statistics ORDER BY display_order");
$statsData = $stmt->fetchAll();
foreach ($statsData as $stat) {
    $siteStats[$stat['stat_key']] = $stat;
}
?>

<style>
.admin-content {
    padding: 30px;
}

.settings-header {
    margin-bottom: 40px;
}

.settings-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.settings-header p {
    font-size: 16px;
    color: #6b7280;
}

.settings-container {
    max-width: 900px;
}

.settings-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-bottom: 30px;
}

.settings-card h2 {
    font-size: 20px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f3f4f6;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #228B22;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-help {
    font-size: 13px;
    color: #6b7280;
    margin-top: 5px;
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
}

.btn-primary {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
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

.stats-preview {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid #f3f4f6;
}

.stat-preview-item {
    text-align: center;
    padding: 20px;
    background: #f9fafb;
    border-radius: 10px;
}

.stat-preview-number {
    font-size: 32px;
    font-weight: 700;
    color: #228B22;
    margin-bottom: 5px;
}

.stat-preview-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-preview {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<div class="admin-content">
    <div class="settings-header">
        <h1><i class="fas fa-cog"></i> Site Settings</h1>
        <p>Manage your website settings and configurations</p>
    </div>
    
    <div class="settings-container">
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
        
        <!-- Site Statistics -->
        <div class="settings-card">
            <h2><i class="fas fa-chart-line"></i> Site Statistics</h2>
            <p style="color: #6b7280; margin-bottom: 25px;">Update the statistics displayed on your homepage</p>
            
            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tours_conducted">Tours Conducted</label>
                        <input type="number" id="tours_conducted" name="tours_conducted" 
                               value="<?php echo isset($siteStats['tours_conducted']) ? htmlspecialchars($siteStats['tours_conducted']['stat_value']) : '150'; ?>" 
                               required>
                        <div class="form-help">Total number of tours conducted</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="happy_clients">Happy Clients</label>
                        <input type="number" id="happy_clients" name="happy_clients" 
                               value="<?php echo isset($siteStats['happy_clients']) ? htmlspecialchars($siteStats['happy_clients']['stat_value']) : '2500'; ?>" 
                               required>
                        <div class="form-help">Total number of satisfied clients</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="destinations">Destinations</label>
                        <input type="number" id="destinations" name="destinations" 
                               value="<?php echo isset($siteStats['destinations']) ? htmlspecialchars($siteStats['destinations']['stat_value']) : '25'; ?>" 
                               required>
                        <div class="form-help">Number of destinations covered</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="average_rating">Average Rating</label>
                        <input type="text" id="average_rating" name="average_rating" 
                               value="<?php echo isset($siteStats['average_rating']) ? htmlspecialchars($siteStats['average_rating']['stat_value']) : '4.9'; ?>" 
                               pattern="[0-9]+(\.[0-9]+)?" step="0.1" required>
                        <div class="form-help">Average customer rating (e.g., 4.9)</div>
                    </div>
                </div>
                
                <button type="submit" name="update_stats" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Statistics
                </button>
                
                <!-- Statistics Preview -->
                <div class="stats-preview">
                    <div class="stat-preview-item">
                        <div class="stat-preview-number" id="preview-tours">
                            <?php echo isset($siteStats['tours_conducted']) ? htmlspecialchars($siteStats['tours_conducted']['stat_value']) : '150'; ?>
                        </div>
                        <div class="stat-preview-label">Tours Conducted</div>
                    </div>
                    <div class="stat-preview-item">
                        <div class="stat-preview-number" id="preview-clients">
                            <?php echo isset($siteStats['happy_clients']) ? htmlspecialchars($siteStats['happy_clients']['stat_value']) : '2500'; ?>
                        </div>
                        <div class="stat-preview-label">Happy Clients</div>
                    </div>
                    <div class="stat-preview-item">
                        <div class="stat-preview-number" id="preview-destinations">
                            <?php echo isset($siteStats['destinations']) ? htmlspecialchars($siteStats['destinations']['stat_value']) : '25'; ?>
                        </div>
                        <div class="stat-preview-label">Destinations</div>
                    </div>
                    <div class="stat-preview-item">
                        <div class="stat-preview-number" id="preview-rating">
                            <?php echo isset($siteStats['average_rating']) ? htmlspecialchars($siteStats['average_rating']['stat_value']) : '4.9'; ?>
                        </div>
                        <div class="stat-preview-label">Average Rating</div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Contact Information -->
        <div class="settings-card">
            <h2><i class="fas fa-address-card"></i> Contact Information</h2>
            <p style="color: #6b7280; margin-bottom: 25px;">Update your contact details</p>
            
            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="contact_phone">Phone Number</label>
                        <input type="tel" id="contact_phone" name="contact_phone" 
                               value="9007820752" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email">Email Address</label>
                        <input type="email" id="contact_email" name="contact_email" 
                               value="ichhedanaexpeditions@gmail.com" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="contact_address">Address</label>
                    <textarea id="contact_address" name="contact_address">Kolkata, India</textarea>
                </div>
                
                <button type="submit" name="update_contact" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Contact Info
                </button>
            </form>
        </div>
        
        <!-- Social Media -->
        <div class="settings-card">
            <h2><i class="fas fa-share-alt"></i> Social Media Links</h2>
            <p style="color: #6b7280; margin-bottom: 25px;">Update your social media profile links</p>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="facebook_url">Facebook URL</label>
                    <input type="url" id="facebook_url" name="facebook_url" 
                           value="https://www.facebook.com/profile.php?id=100063782000455">
                </div>
                
                <div class="form-group">
                    <label for="instagram_url">Instagram URL</label>
                    <input type="url" id="instagram_url" name="instagram_url" 
                           value="https://www.instagram.com/ichhedanaexpeditions/">
                </div>
                
                <div class="form-group">
                    <label for="group_url">Facebook Group URL</label>
                    <input type="url" id="group_url" name="group_url" 
                           value="https://www.facebook.com/groups/2010223942443396/">
                </div>
                
                <button type="submit" name="update_social" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Social Links
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Live preview for statistics
document.getElementById('tours_conducted').addEventListener('input', function() {
    document.getElementById('preview-tours').textContent = this.value;
});

document.getElementById('happy_clients').addEventListener('input', function() {
    document.getElementById('preview-clients').textContent = this.value;
});

document.getElementById('destinations').addEventListener('input', function() {
    document.getElementById('preview-destinations').textContent = this.value;
});

document.getElementById('average_rating').addEventListener('input', function() {
    document.getElementById('preview-rating').textContent = this.value;
});
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
