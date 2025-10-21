<?php
/**
 * Admin Reservations Template
 * 
 * @package RestaurantReservationSystem
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'rrs_reservations';

// Get reservations
$reservations = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date DESC, time DESC LIMIT 50");

// Get statistics
$total_reservations = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
$pending_reservations = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'pending'");
$confirmed_reservations = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'confirmed'");
$today_reservations = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE date = CURDATE()");
?>

<div class="wrap">
    <h1><?php _e('Restaurant Reservations', 'restaurant-reservation'); ?></h1>
    
    <!-- Statistics Cards -->
    <div class="rrs-stats-grid">
        <div class="rrs-stat-card">
            <h3><?php echo $total_reservations; ?></h3>
            <p><?php _e('Total Reservations', 'restaurant-reservation'); ?></p>
        </div>
        <div class="rrs-stat-card">
            <h3><?php echo $pending_reservations; ?></h3>
            <p><?php _e('Pending', 'restaurant-reservation'); ?></p>
        </div>
        <div class="rrs-stat-card">
            <h3><?php echo $confirmed_reservations; ?></h3>
            <p><?php _e('Confirmed', 'restaurant-reservation'); ?></p>
        </div>
        <div class="rrs-stat-card">
            <h3><?php echo $today_reservations; ?></h3>
            <p><?php _e('Today', 'restaurant-reservation'); ?></p>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="rrs-actions">
        <a href="<?php echo admin_url('admin.php?page=restaurant-reservation-calendar'); ?>" class="button button-primary">
            <?php _e('Calendar View', 'restaurant-reservation'); ?>
        </a>
        <button type="button" class="button" onclick="rrsExportReservations()">
            <?php _e('Export CSV', 'restaurant-reservation'); ?>
        </button>
    </div>
    
    <!-- Reservations Table -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('Customer', 'restaurant-reservation'); ?></th>
                <th><?php _e('Date & Time', 'restaurant-reservation'); ?></th>
                <th><?php _e('Guests', 'restaurant-reservation'); ?></th>
                <th><?php _e('Status', 'restaurant-reservation'); ?></th>
                <th><?php _e('Contact', 'restaurant-reservation'); ?></th>
                <th><?php _e('Actions', 'restaurant-reservation'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reservations)): ?>
                <tr>
                    <td colspan="6"><?php _e('No reservations found.', 'restaurant-reservation'); ?></td>
                </tr>
            <?php else: ?>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html($reservation->name); ?></strong>
                        </td>
                        <td>
                            <?php echo date('M j, Y', strtotime($reservation->date)); ?><br>
                            <small><?php echo date('g:i A', strtotime($reservation->time)); ?></small>
                        </td>
                        <td><?php echo $reservation->guests; ?></td>
                        <td>
                            <span class="rrs-status status-<?php echo esc_attr($reservation->status); ?>">
                                <?php echo ucfirst($reservation->status); ?>
                            </span>
                        </td>
                        <td>
                            <a href="mailto:<?php echo esc_attr($reservation->email); ?>"><?php echo esc_html($reservation->email); ?></a><br>
                            <a href="tel:<?php echo esc_attr($reservation->phone); ?>"><?php echo esc_html($reservation->phone); ?></a>
                        </td>
                        <td>
                            <select onchange="rrsUpdateStatus(<?php echo $reservation->id; ?>, this.value)">
                                <option value="pending" <?php selected($reservation->status, 'pending'); ?>><?php _e('Pending', 'restaurant-reservation'); ?></option>
                                <option value="confirmed" <?php selected($reservation->status, 'confirmed'); ?>><?php _e('Confirmed', 'restaurant-reservation'); ?></option>
                                <option value="cancelled" <?php selected($reservation->status, 'cancelled'); ?>><?php _e('Cancelled', 'restaurant-reservation'); ?></option>
                                <option value="completed" <?php selected($reservation->status, 'completed'); ?>><?php _e('Completed', 'restaurant-reservation'); ?></option>
                            </select>
                            <button type="button" class="button button-small" onclick="rrsDeleteReservation(<?php echo $reservation->id; ?>)">
                                <?php _e('Delete', 'restaurant-reservation'); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.rrs-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.rrs-stat-card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.rrs-stat-card h3 {
    font-size: 2rem;
    margin: 0 0 10px 0;
    color: #7bdcb5;
}

.rrs-stat-card p {
    margin: 0;
    color: #666;
    font-weight: 500;
}

.rrs-actions {
    margin: 20px 0;
}

.rrs-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: uppercase;
}

.rrs-status.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.rrs-status.status-confirmed {
    background-color: #d4edda;
    color: #155724;
}

.rrs-status.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}

.rrs-status.status-completed {
    background-color: #d1ecf1;
    color: #0c5460;
}
</style>

<script>
function rrsUpdateStatus(reservationId, status) {
    jQuery.post(ajaxurl, {
        action: 'rrs_update_reservation_status',
        reservation_id: reservationId,
        status: status,
        nonce: '<?php echo wp_create_nonce('rrs_nonce'); ?>'
    }, function(response) {
        if (response.success) {
            location.reload();
        } else {
            alert('Failed to update reservation status');
        }
    });
}

function rrsDeleteReservation(reservationId) {
    if (confirm('Are you sure you want to delete this reservation?')) {
        jQuery.post(ajaxurl, {
            action: 'rrs_delete_reservation',
            reservation_id: reservationId,
            nonce: '<?php echo wp_create_nonce('rrs_nonce'); ?>'
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Failed to delete reservation');
            }
        });
    }
}

function rrsExportReservations() {
    window.location.href = ajaxurl + '?action=rrs_export_reservations&nonce=<?php echo wp_create_nonce('rrs_export'); ?>';
}
</script>
