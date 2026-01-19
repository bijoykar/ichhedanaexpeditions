<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$page_title = 'Tour Bookings';
require_once __DIR__ . '/includes/admin-header.php';

$db = Database::getInstance()->getConnection();
$success = '';
$error = '';

// Handle booking actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_booking'])) {
        $id = (int)$_POST['booking_id'];
        
        try {
            $stmt = $db->prepare("DELETE FROM tour_bookings WHERE id = ?");
            $stmt->execute([$id]);
            $success = 'Booking deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting booking: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_booking_status'])) {
        $id = (int)$_POST['booking_id'];
        $status = sanitize($_POST['booking_status']);
        
        try {
            $stmt = $db->prepare("UPDATE tour_bookings SET booking_status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = 'Booking status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating status: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['update_payment_status'])) {
        $id = (int)$_POST['booking_id'];
        $status = sanitize($_POST['payment_status']);
        
        try {
            $stmt = $db->prepare("UPDATE tour_bookings SET payment_status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = 'Payment status updated successfully!';
        } catch (Exception $e) {
            $error = 'Error updating payment status: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['save_notes'])) {
        $id = (int)$_POST['booking_id'];
        $admin_notes = sanitize($_POST['admin_notes']);
        
        try {
            $stmt = $db->prepare("UPDATE tour_bookings SET admin_notes = ? WHERE id = ?");
            $stmt->execute([$admin_notes, $id]);
            $success = 'Notes saved successfully!';
        } catch (Exception $e) {
            $error = 'Error saving notes: ' . $e->getMessage();
        }
    }
}

// Fetch all bookings with tour info
$sql = "SELECT tb.*, t.title as tour_title, t.price 
        FROM tour_bookings tb 
        LEFT JOIN tours t ON tb.tour_id = t.id 
        ORDER BY tb.created_at DESC";
$bookings = $db->query($sql)->fetchAll();

// Get statistics
$statsQuery = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
    SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN booking_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
    SUM(total_amount) as total_revenue,
    SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as paid_revenue
    FROM tour_bookings";
$stats = $db->query($statsQuery)->fetch();
?>

<style>
.admin-content {
    padding: 30px;
}

.bookings-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.bookings-header h1 {
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
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
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
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
    font-size: 13px;
    color: #6b7280;
    margin: 0 0 10px 0;
    font-weight: 600;
    text-transform: uppercase;
}

.stat-card .stat-value {
    font-size: 32px;
    font-weight: 700;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
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

.bookings-table {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
}

thead th {
    padding: 16px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
}

tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.3s ease;
}

tbody tr:hover {
    background: #f9fafb;
}

tbody td {
    padding: 16px;
    font-size: 14px;
    color: #374151;
    vertical-align: top;
}

.booking-customer {
    font-weight: 600;
    color: #1a1a1a;
}

.booking-contact {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-confirmed {
    background: #dbeafe;
    color: #1e40af;
}

.status-completed {
    background: #d1fae5;
    color: #065f46;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.payment-pending {
    background: #fef3c7;
    color: #92400e;
}

.payment-paid {
    background: #d1fae5;
    color: #065f46;
}

.payment-refunded {
    background: #e5e7eb;
    color: #374151;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.expandable-row {
    display: none;
    background: #f9fafb;
}

.expandable-row.show {
    display: table-row;
}

.expandable-content {
    padding: 20px;
}

.booking-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.detail-group {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.detail-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.detail-group .value {
    font-size: 14px;
    color: #1a1a1a;
    font-weight: 600;
}

.notes-section {
    margin-top: 20px;
}

.notes-section label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.notes-section textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    min-height: 80px;
    resize: vertical;
}

.status-controls {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.status-controls select {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
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
    .bookings-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .bookings-table {
        overflow-x: auto;
    }
}
</style>

<div class="admin-content">
    <div class="bookings-header">
        <h1><i class="fas fa-calendar-check"></i> Tour Bookings</h1>
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
            <h3>Total Bookings</h3>
            <div class="stat-value"><?php echo $stats['total']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Pending</h3>
            <div class="stat-value"><?php echo $stats['pending']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Confirmed</h3>
            <div class="stat-value"><?php echo $stats['confirmed']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Completed</h3>
            <div class="stat-value"><?php echo $stats['completed']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Cancelled</h3>
            <div class="stat-value"><?php echo $stats['cancelled']; ?></div>
        </div>
        <div class="stat-card">
            <h3>Total Revenue</h3>
            <div class="stat-value">₹<?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></div>
        </div>
        <div class="stat-card">
            <h3>Paid Revenue</h3>
            <div class="stat-value">₹<?php echo number_format($stats['paid_revenue'] ?? 0, 2); ?></div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filters-section">
        <div class="filters-grid">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" id="searchInput" placeholder="Search bookings..." onkeyup="filterBookings()">
            </div>
            <div class="filter-group">
                <label>Booking Status</label>
                <select id="bookingStatusFilter" onchange="filterBookings()">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Payment Status</label>
                <select id="paymentStatusFilter" onchange="filterBookings()">
                    <option value="">All Payments</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Bookings Table -->
    <?php if (!empty($bookings)): ?>
    <div class="bookings-table">
        <table id="bookingsTable">
            <thead>
                <tr>
                    <th width="30"></th>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Tour</th>
                    <th>Participants</th>
                    <th>Amount</th>
                    <th>Booking Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                <tr data-booking-status="<?php echo $booking['booking_status']; ?>" 
                    data-payment-status="<?php echo $booking['payment_status']; ?>"
                    data-search="<?php echo htmlspecialchars(strtolower($booking['customer_name'] . ' ' . $booking['customer_email'] . ' ' . $booking['tour_title'])); ?>">
                    <td>
                        <button class="btn btn-icon btn-secondary" onclick="toggleDetails(<?php echo $booking['id']; ?>)" title="View Details">
                            <i class="fas fa-chevron-down" id="icon-<?php echo $booking['id']; ?>"></i>
                        </button>
                    </td>
                    <td><strong>#<?php echo str_pad($booking['id'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                    <td>
                        <div class="booking-customer"><?php echo htmlspecialchars($booking['customer_name'] ?? ''); ?></div>
                        <div class="booking-contact">
                            <?php echo htmlspecialchars($booking['customer_email'] ?? ''); ?><br>
                            <?php echo htmlspecialchars($booking['customer_phone'] ?? ''); ?>
                        </div>
                    </td>
                    <td><?php echo $booking['tour_title'] ? htmlspecialchars($booking['tour_title']) : '<em>Deleted tour</em>'; ?></td>
                    <td><?php echo $booking['number_of_participants']; ?> people</td>
                    <td><strong>₹<?php echo number_format($booking['total_amount'] ?? 0, 2); ?></strong></td>
                    <td><span class="status-badge status-<?php echo $booking['booking_status']; ?>"><?php echo ucfirst($booking['booking_status']); ?></span></td>
                    <td><span class="status-badge payment-<?php echo $booking['payment_status']; ?>"><?php echo ucfirst($booking['payment_status']); ?></span></td>
                    <td><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-danger btn-icon" 
                                    onclick="confirmDelete(<?php echo $booking['id']; ?>, '<?php echo htmlspecialchars($booking['customer_name'] ?? ''); ?>')"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="expandable-row" id="details-<?php echo $booking['id']; ?>">
                    <td colspan="10">
                        <div class="expandable-content">
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                
                                <div class="booking-details-grid">
                                    <div class="detail-group">
                                        <label>Booking Date</label>
                                        <div class="value"><?php echo date('F d, Y h:i A', strtotime($booking['booking_date'])); ?></div>
                                    </div>
                                    <div class="detail-group">
                                        <label>Tour Price</label>
                                        <div class="value">₹<?php echo number_format($booking['price'] ?? 0, 2); ?> per person</div>
                                    </div>
                                    <div class="detail-group">
                                        <label>Total Amount</label>
                                        <div class="value">₹<?php echo number_format($booking['total_amount'] ?? 0, 2); ?></div>
                                    </div>
                                    <div class="detail-group">
                                        <label>Participants</label>
                                        <div class="value"><?php echo $booking['number_of_participants']; ?> people</div>
                                    </div>
                                </div>
                                
                                <?php if ($booking['special_requests']): ?>
                                <div class="detail-group" style="margin-bottom: 20px;">
                                    <label>Special Requests</label>
                                    <div class="value"><?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?></div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="notes-section">
                                    <label><i class="fas fa-sticky-note"></i> Admin Notes</label>
                                    <textarea name="admin_notes" placeholder="Add internal notes about this booking..."><?php echo htmlspecialchars($booking['admin_notes'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="status-controls">
                                    <button type="submit" name="save_notes" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-save"></i> Save Notes
                                    </button>
                                    
                                    <select name="booking_status" onchange="this.form.querySelector('input[name=update_booking_status]').value='1'; this.form.submit();">
                                        <option value="">Change Booking Status</option>
                                        <option value="pending" <?php echo $booking['booking_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $booking['booking_status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="completed" <?php echo $booking['booking_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $booking['booking_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="update_booking_status" value="">
                                    
                                    <select name="payment_status" onchange="this.form.querySelector('input[name=update_payment_status]').value='1'; this.form.submit();">
                                        <option value="">Change Payment Status</option>
                                        <option value="pending" <?php echo $booking['payment_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="paid" <?php echo $booking['payment_status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="refunded" <?php echo $booking['payment_status'] == 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                                    </select>
                                    <input type="hidden" name="update_payment_status" value="">
                                    
                                    <a href="mailto:<?php echo htmlspecialchars($booking['customer_email']); ?>?subject=Booking Confirmation - <?php echo htmlspecialchars($booking['tour_title']); ?>" 
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-envelope"></i> Email Customer
                                    </a>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-calendar-check"></i>
        <h3>No bookings yet</h3>
        <p>Tour bookings will appear here</p>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleDetails(id) {
    const row = document.getElementById('details-' + id);
    const icon = document.getElementById('icon-' + id);
    
    if (row.classList.contains('show')) {
        row.classList.remove('show');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    } else {
        row.classList.add('show');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    }
}

function filterBookings() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const bookingStatus = document.getElementById('bookingStatusFilter').value;
    const paymentStatus = document.getElementById('paymentStatusFilter').value;
    const rows = document.querySelectorAll('#bookingsTable tbody tr:not(.expandable-row)');
    
    rows.forEach(row => {
        const searchData = row.dataset.search;
        const bStatus = row.dataset.bookingStatus;
        const pStatus = row.dataset.paymentStatus;
        
        const matchesSearch = searchData.includes(searchTerm);
        const matchesBookingStatus = !bookingStatus || bStatus === bookingStatus;
        const matchesPaymentStatus = !paymentStatus || pStatus === paymentStatus;
        
        if (matchesSearch && matchesBookingStatus && matchesPaymentStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
            // Also hide the expandable row
            const bookingId = row.querySelector('button[onclick^="toggleDetails"]')?.getAttribute('onclick').match(/\d+/)[0];
            if (bookingId) {
                const detailsRow = document.getElementById('details-' + bookingId);
                if (detailsRow) detailsRow.style.display = 'none';
            }
        }
    });
}

function confirmDelete(id, customerName) {
    if (confirm(`Are you sure you want to delete the booking from "${customerName}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="booking_id" value="${id}">
            <input type="hidden" name="delete_booking" value="1">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
