<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Contact Messages';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$success = '';
$error = '';

// Handle message actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_message'])) {
        $id = (int)$_POST['message_id'];
        
        try {
            $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
            $stmt->execute([$id]);
            $success = 'Message deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting message: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_status'])) {
        $id = (int)$_POST['message_id'];
        $status = sanitize($_POST['status']);
        
        try {
            $stmt = $db->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = 'Message status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating status: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['save_notes'])) {
        $id = (int)$_POST['message_id'];
        $admin_notes = sanitize($_POST['admin_notes']);
        
        try {
            $stmt = $db->prepare("UPDATE contact_messages SET admin_notes = ? WHERE id = ?");
            $stmt->execute([$admin_notes, $id]);
            $success = 'Notes saved successfully!';
        } catch (Exception $e) {
            $error = 'Error saving notes: ' . $e->getMessage();
        }
    }
}

// Fetch all messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$messages = $db->query($sql)->fetchAll();

// Get statistics
$statsQuery = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new,
    SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as `read`,
    SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied,
    SUM(CASE WHEN status = 'archived' THEN 1 ELSE 0 END) as archived
    FROM contact_messages";
$stats = $db->query($statsQuery)->fetch();
?>

<style>
.admin-content {
    padding: 30px;
}

.messages-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.messages-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
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

.btn-icon {
    padding: 8px 12px;
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

.stats-grid {
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
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.stat-card h3 {
    font-size: 14px;
    color: #6b7280;
    margin: 0 0 10px 0;
    font-weight: 600;
    text-transform: uppercase;
}

.stat-card .stat-value {
    font-size: 36px;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.filters-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.filter-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.filter-group input,
.filter-group select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
}

.messages-grid {
    display: grid;
    gap: 20px;
}

.message-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.message-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.message-card.unread {
    border-left: 4px solid #667eea;
    background: #f9fafb;
}

.message-header {
    padding: 20px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: start;
}

.message-sender {
    flex: 1;
}

.message-sender h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    font-weight: 600;
}

.message-sender .contact-info {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    font-size: 13px;
    opacity: 0.9;
}

.contact-info span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.message-meta {
    text-align: right;
}

.message-date {
    font-size: 13px;
    opacity: 0.9;
}

.message-body {
    padding: 25px;
}

.message-subject {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 15px 0;
}

.message-text {
    color: #374151;
    line-height: 1.6;
    margin-bottom: 20px;
}

.message-footer {
    padding: 20px 25px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.admin-notes-section {
    margin-bottom: 20px;
}

.admin-notes-section label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.admin-notes-section textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    min-height: 80px;
    resize: vertical;
}

.message-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}

.status-select {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-new {
    background: #dbeafe;
    color: #1e40af;
}

.status-read {
    background: #fef3c7;
    color: #92400e;
}

.status-replied {
    background: #d1fae5;
    color: #065f46;
}

.status-archived {
    background: #e5e7eb;
    color: #374151;
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
    .message-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .message-meta {
        text-align: left;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .message-actions {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<div class="admin-content">
    <div class="messages-header">
        <h1><i class="fas fa-envelope"></i> Contact Messages</h1>
    </div>
    
    <?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $success; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Messages</h3>
            <div class="stat-value"><?php echo $stats['total']; ?></div>
        </div>
        <div class="stat-card">
            <h3>New</h3>
            <div class="stat-value"><?php echo $stats['new']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Read</h3>
            <div class="stat-value"><?php echo $stats['read']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Replied</h3>
            <div class="stat-value"><?php echo $stats['replied']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Archived</h3>
            <div class="stat-value"><?php echo $stats['archived']; ?></div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filters-section">
        <div class="filters-grid">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" id="searchInput" placeholder="Search messages..." onkeyup="filterMessages()">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="statusFilter" onchange="filterMessages()">
                    <option value="">All Status</option>
                    <option value="new">New</option>
                    <option value="read">Read</option>
                    <option value="replied">Replied</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Messages -->
    <?php if (!empty($messages)): ?>
    <div class="messages-grid" id="messagesGrid">
        <?php foreach ($messages as $msg): ?>
        <div class="message-card <?php echo $msg['status'] == 'new' ? 'unread' : ''; ?>" 
             data-status="<?php echo $msg['status']; ?>"
             data-search="<?php echo htmlspecialchars(strtolower($msg['name'] . ' ' . $msg['email'] . ' ' . $msg['subject'] . ' ' . $msg['message'])); ?>">
            <div class="message-header">
                <div class="message-sender">
                    <h3><?php echo htmlspecialchars($msg['name'] ?? ''); ?></h3>
                    <div class="contact-info">
                        <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($msg['email'] ?? ''); ?></span>
                        <?php if ($msg['phone']): ?>
                        <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($msg['phone']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="message-meta">
                    <div class="message-date">
                        <i class="fas fa-clock"></i> <?php echo date('M d, Y h:i A', strtotime($msg['created_at'])); ?>
                    </div>
                    <div style="margin-top: 8px;">
                        <span class="status-badge status-<?php echo $msg['status']; ?>">
                            <?php echo ucfirst($msg['status']); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="message-body">
                <?php if ($msg['subject']): ?>
                <div class="message-subject">
                    <i class="fas fa-comment-dots"></i> <?php echo htmlspecialchars($msg['subject']); ?>
                </div>
                <?php endif; ?>
                
                <div class="message-text">
                    <?php echo nl2br(htmlspecialchars($msg['message'] ?? '')); ?>
                </div>
                
                <?php if ($msg['ip_address']): ?>
                <div style="font-size: 12px; color: #6b7280;">
                    <i class="fas fa-globe"></i> IP: <?php echo htmlspecialchars($msg['ip_address']); ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="message-footer">
                <form method="POST" id="form-<?php echo $msg['id']; ?>">
                    <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                    
                    <div class="admin-notes-section">
                        <label><i class="fas fa-sticky-note"></i> Admin Notes</label>
                        <textarea name="admin_notes" placeholder="Add notes about this message..."><?php echo htmlspecialchars($msg['admin_notes'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="message-actions">
                        <button type="submit" name="save_notes" class="btn btn-secondary btn-sm">
                            <i class="fas fa-save"></i> Save Notes
                        </button>
                        
                        <select name="status" class="status-select" onchange="document.getElementById('status-form-<?php echo $msg['id']; ?>').submit()">
                            <option value="new" <?php echo $msg['status'] == 'new' ? 'selected' : ''; ?>>New</option>
                            <option value="read" <?php echo $msg['status'] == 'read' ? 'selected' : ''; ?>>Read</option>
                            <option value="replied" <?php echo $msg['status'] == 'replied' ? 'selected' : ''; ?>>Replied</option>
                            <option value="archived" <?php echo $msg['status'] == 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                        
                        <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>?subject=Re: <?php echo htmlspecialchars($msg['subject'] ?? 'Your inquiry'); ?>" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-reply"></i> Reply via Email
                        </a>
                        
                        <button type="button" 
                                class="btn btn-danger btn-sm" 
                                onclick="confirmDelete(<?php echo $msg['id']; ?>, '<?php echo htmlspecialchars($msg['name'] ?? ''); ?>')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </form>
                
                <form method="POST" id="status-form-<?php echo $msg['id']; ?>" style="display: none;">
                    <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                    <input type="hidden" name="status" value="">
                    <input type="hidden" name="update_status" value="1">
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-envelope-open"></i>
        <h3>No messages yet</h3>
        <p>Contact messages will appear here</p>
    </div>
    <?php endif; ?>
</div>

<script>
function filterMessages() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const cards = document.querySelectorAll('.message-card');
    
    cards.forEach(card => {
        const searchData = card.dataset.search;
        const status = card.dataset.status;
        
        const matchesSearch = searchData.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function confirmDelete(id, name) {
    if (confirm(`Are you sure you want to delete the message from "${name}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="message_id" value="${id}">
            <input type="hidden" name="delete_message" value="1">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Fix status dropdown submission
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const messageId = this.closest('form').querySelector('input[name="message_id"]').value;
        const statusForm = document.getElementById('status-form-' + messageId);
        statusForm.querySelector('input[name="status"]').value = this.value;
        statusForm.submit();
    });
});
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
