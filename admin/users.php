<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Manage Users';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$success = '';
$error = '';

// Get current admin ID
$currentAdminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $username = sanitize($_POST['username']);
        $email = sanitize($_POST['email']);
        $full_name = sanitize($_POST['full_name']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = sanitize($_POST['role']);
        $status = sanitize($_POST['status']);
        
        try {
            $stmt = $db->prepare("INSERT INTO admin_users (username, email, full_name, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $full_name, $password, $role, $status]);
            $success = 'User added successfully!';
        } catch (Exception $e) {
            $error = 'Error adding user: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_user'])) {
        $id = (int)$_POST['user_id'];
        $username = sanitize($_POST['username']);
        $email = sanitize($_POST['email']);
        $full_name = sanitize($_POST['full_name']);
        $role = sanitize($_POST['role']);
        $status = sanitize($_POST['status']);
        
        try {
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE admin_users SET username = ?, email = ?, full_name = ?, password = ?, role = ?, status = ? WHERE id = ?");
                $stmt->execute([$username, $email, $full_name, $password, $role, $status, $id]);
            } else {
                $stmt = $db->prepare("UPDATE admin_users SET username = ?, email = ?, full_name = ?, role = ?, status = ? WHERE id = ?");
                $stmt->execute([$username, $email, $full_name, $role, $status, $id]);
            }
            $success = 'User updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating user: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['delete_user'])) {
        $id = (int)$_POST['user_id'];
        
        // Prevent deleting current user
        if ($id != $currentAdminId) {
            try {
                $stmt = $db->prepare("DELETE FROM admin_users WHERE id = ?");
                $stmt->execute([$id]);
                $success = 'User deleted successfully!';
            } catch (Exception $e) {
                $error = 'Error deleting user: ' . $e->getMessage();
            }
        } else {
            $error = 'You cannot delete your own account!';
        }
    }
}

// Fetch all users
$users = $db->query("SELECT * FROM admin_users ORDER BY created_at DESC")->fetchAll();
?>

<style>
.admin-content {
    padding: 30px;
}

.users-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.users-header h1 {
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

.users-table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table thead {
    background: #f9fafb;
}

.users-table th {
    padding: 16px 20px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.users-table td {
    padding: 16px 20px;
    border-top: 1px solid #f3f4f6;
    color: #374151;
}

.users-table tbody tr:hover {
    background: #f9fafb;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 16px;
}

.user-details h4 {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 3px 0;
}

.user-details p {
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

.badge-admin {
    background: #dbeafe;
    color: #1e40af;
}

.badge-editor {
    background: #fef3c7;
    color: #92400e;
}

.badge-active {
    background: #d1fae5;
    color: #065f46;
}

.badge-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 25px 30px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

.modal-body {
    padding: 30px;
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
.form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-help {
    font-size: 13px;
    color: #6b7280;
    margin-top: 5px;
}

.modal-footer {
    padding: 20px 30px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
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

@media (max-width: 768px) {
    .users-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .users-table {
        font-size: 13px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="admin-content">
    <div class="users-header">
        <h1><i class="fas fa-users"></i> Manage Users</h1>
        <button class="btn btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add New User
        </button>
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
    
    <?php if (!empty($users)): ?>
    <div class="users-table-card">
        <table class="users-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                            </div>
                            <div class="user-details">
                                <h4><?php echo htmlspecialchars($user['full_name']); ?></h4>
                                <p><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $user['role']; ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo $user['status']; ?>">
                            <?php echo ucfirst($user['status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php echo $user['last_login'] ? date('M d, Y h:i A', strtotime($user['last_login'])) : 'Never'; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-secondary btn-sm" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <?php if ($user['id'] != $currentAdminId): ?>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="users-table-card">
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3>No users found</h3>
            <p>Start by adding your first user</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Add User Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-user-plus"></i> Add New User</h2>
        </div>
        <form method="POST" action="">
            <div class="modal-body">
                <div class="form-group">
                    <label for="add_full_name">Full Name *</label>
                    <input type="text" id="add_full_name" name="full_name" required>
                </div>
                
                <div class="form-group">
                    <label for="add_username">Username *</label>
                    <input type="text" id="add_username" name="username" required>
                    <div class="form-help">Used for login</div>
                </div>
                
                <div class="form-group">
                    <label for="add_email">Email Address *</label>
                    <input type="email" id="add_email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="add_password">Password *</label>
                    <input type="password" id="add_password" name="password" required>
                    <div class="form-help">Minimum 6 characters</div>
                </div>
                
                <div class="form-group">
                    <label for="add_role">Role *</label>
                    <select id="add_role" name="role" required>
                        <option value="editor">Editor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="add_status">Status *</label>
                    <select id="add_status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
                <button type="submit" name="add_user" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-user-edit"></i> Edit User</h2>
        </div>
        <form method="POST" action="">
            <input type="hidden" id="edit_user_id" name="user_id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_full_name">Full Name *</label>
                    <input type="text" id="edit_full_name" name="full_name" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_username">Username *</label>
                    <input type="text" id="edit_username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_email">Email Address *</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_password">New Password</label>
                    <input type="password" id="edit_password" name="password">
                    <div class="form-help">Leave blank to keep current password</div>
                </div>
                
                <div class="form-group">
                    <label for="edit_role">Role *</label>
                    <select id="edit_role" name="role" required>
                        <option value="editor">Editor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_status">Status *</label>
                    <select id="edit_status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" name="update_user" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" action="" style="display: none;">
    <input type="hidden" id="delete_user_id" name="user_id">
    <input type="hidden" name="delete_user" value="1">
</form>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddModal() {
    document.getElementById('addModal').classList.remove('active');
}

function openEditModal(user) {
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_full_name').value = user.full_name;
    document.getElementById('edit_username').value = user.username;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_status').value = user.status;
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

function confirmDelete(userId, userName) {
    if (confirm('Are you sure you want to delete user "' + userName + '"? This action cannot be undone.')) {
        document.getElementById('delete_user_id').value = userId;
        document.getElementById('deleteForm').submit();
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
