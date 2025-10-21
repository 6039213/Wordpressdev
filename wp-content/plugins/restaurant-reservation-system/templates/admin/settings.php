<?php
/**
 * Admin Settings Template
 * 
 * @package RestaurantReservationSystem
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Save settings
    update_option('rrs_restaurant_name', sanitize_text_field($_POST['rrs_restaurant_name']));
    update_option('rrs_restaurant_phone', sanitize_text_field($_POST['rrs_restaurant_phone']));
    update_option('rrs_restaurant_email', sanitize_email($_POST['rrs_restaurant_email']));
    update_option('rrs_restaurant_address', sanitize_text_field($_POST['rrs_restaurant_address']));
    update_option('rrs_max_guests', intval($_POST['rrs_max_guests']));
    update_option('rrs_advance_booking_days', intval($_POST['rrs_advance_booking_days']));
    update_option('rrs_auto_confirm', isset($_POST['rrs_auto_confirm']));
    update_option('rrs_send_email_notifications', isset($_POST['rrs_send_email_notifications']));
    update_option('rrs_require_phone', isset($_POST['rrs_require_phone']));
    update_option('rrs_require_message', isset($_POST['rrs_require_message']));
    update_option('rrs_show_calendar', isset($_POST['rrs_show_calendar']));
    update_option('rrs_show_time_slots', isset($_POST['rrs_show_time_slots']));
    update_option('rrs_theme_color', sanitize_hex_color($_POST['rrs_theme_color']));
    update_option('rrs_form_title', sanitize_text_field($_POST['rrs_form_title']));
    update_option('rrs_form_description', sanitize_textarea_field($_POST['rrs_form_description']));
    
    // Save opening hours
    $opening_hours = array();
    $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    foreach ($days as $day) {
        $opening_hours[$day] = array(
            'open' => sanitize_text_field($_POST['rrs_opening_hours'][$day]['open']),
            'close' => sanitize_text_field($_POST['rrs_opening_hours'][$day]['close'])
        );
    }
    update_option('rrs_opening_hours', $opening_hours);
    
    // Save time slots
    $time_slots = array();
    if (isset($_POST['rrs_booking_time_slots'])) {
        foreach ($_POST['rrs_booking_time_slots'] as $time) {
            if (!empty($time)) {
                $time_slots[] = sanitize_text_field($time);
            }
        }
    }
    update_option('rrs_booking_time_slots', $time_slots);
    
    echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'restaurant-reservation') . '</p></div>';
}

// Get current settings
$restaurant_name = get_option('rrs_restaurant_name', get_bloginfo('name'));
$restaurant_phone = get_option('rrs_restaurant_phone', '');
$restaurant_email = get_option('rrs_restaurant_email', get_option('admin_email'));
$restaurant_address = get_option('rrs_restaurant_address', '');
$max_guests = get_option('rrs_max_guests', 8);
$advance_booking_days = get_option('rrs_advance_booking_days', 90);
$auto_confirm = get_option('rrs_auto_confirm', false);
$send_email_notifications = get_option('rrs_send_email_notifications', true);
$require_phone = get_option('rrs_require_phone', true);
$require_message = get_option('rrs_require_message', false);
$show_calendar = get_option('rrs_show_calendar', true);
$show_time_slots = get_option('rrs_show_time_slots', true);
$theme_color = get_option('rrs_theme_color', '#7bdcb5');
$form_title = get_option('rrs_form_title', __('Make a Reservation', 'restaurant-reservation'));
$form_description = get_option('rrs_form_description', __('Please fill out the form below to make a reservation.', 'restaurant-reservation'));
$opening_hours = get_option('rrs_opening_hours', array());
$booking_time_slots = get_option('rrs_booking_time_slots', array('18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30'));
?>

<div class="wrap">
    <h1><?php _e('Reservation Settings', 'restaurant-reservation'); ?></h1>
    
    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Restaurant Name', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="text" name="rrs_restaurant_name" value="<?php echo esc_attr($restaurant_name); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Phone Number', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="tel" name="rrs_restaurant_phone" value="<?php echo esc_attr($restaurant_phone); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Email Address', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="email" name="rrs_restaurant_email" value="<?php echo esc_attr($restaurant_email); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Address', 'restaurant-reservation'); ?></th>
                <td>
                    <textarea name="rrs_restaurant_address" rows="3" cols="50" class="large-text"><?php echo esc_textarea($restaurant_address); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Maximum Guests', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="number" name="rrs_max_guests" value="<?php echo esc_attr($max_guests); ?>" min="1" max="50" class="small-text" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Advance Booking Days', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="number" name="rrs_advance_booking_days" value="<?php echo esc_attr($advance_booking_days); ?>" min="1" max="365" class="small-text" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Auto Confirm Reservations', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="checkbox" name="rrs_auto_confirm" value="1" <?php checked($auto_confirm); ?> />
                    <label><?php _e('Automatically confirm reservations without manual approval', 'restaurant-reservation'); ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Send Email Notifications', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="checkbox" name="rrs_send_email_notifications" value="1" <?php checked($send_email_notifications); ?> />
                    <label><?php _e('Send email notifications for new reservations', 'restaurant-reservation'); ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Require Phone Number', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="checkbox" name="rrs_require_phone" value="1" <?php checked($require_phone); ?> />
                    <label><?php _e('Make phone number required on reservation form', 'restaurant-reservation'); ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Require Message', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="checkbox" name="rrs_require_message" value="1" <?php checked($require_message); ?> />
                    <label><?php _e('Make special requests required on reservation form', 'restaurant-reservation'); ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Show Calendar', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="checkbox" name="rrs_show_calendar" value="1" <?php checked($show_calendar); ?> />
                    <label><?php _e('Show calendar widget on reservation form', 'restaurant-reservation'); ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Show Time Slots', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="checkbox" name="rrs_show_time_slots" value="1" <?php checked($show_time_slots); ?> />
                    <label><?php _e('Show available time slots on reservation form', 'restaurant-reservation'); ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Theme Color', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="color" name="rrs_theme_color" value="<?php echo esc_attr($theme_color); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Form Title', 'restaurant-reservation'); ?></th>
                <td>
                    <input type="text" name="rrs_form_title" value="<?php echo esc_attr($form_title); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Form Description', 'restaurant-reservation'); ?></th>
                <td>
                    <textarea name="rrs_form_description" rows="3" cols="50" class="large-text"><?php echo esc_textarea($form_description); ?></textarea>
                </td>
            </tr>
        </table>
        
        <h2><?php _e('Opening Hours', 'restaurant-reservation'); ?></h2>
        <table class="form-table">
            <?php
            $days = array(
                'monday' => __('Monday', 'restaurant-reservation'),
                'tuesday' => __('Tuesday', 'restaurant-reservation'),
                'wednesday' => __('Wednesday', 'restaurant-reservation'),
                'thursday' => __('Thursday', 'restaurant-reservation'),
                'friday' => __('Friday', 'restaurant-reservation'),
                'saturday' => __('Saturday', 'restaurant-reservation'),
                'sunday' => __('Sunday', 'restaurant-reservation')
            );
            
            foreach ($days as $day => $day_name):
                $open = isset($opening_hours[$day]['open']) ? $opening_hours[$day]['open'] : '09:00';
                $close = isset($opening_hours[$day]['close']) ? $opening_hours[$day]['close'] : '22:00';
            ?>
            <tr>
                <th scope="row"><?php echo $day_name; ?></th>
                <td>
                    <input type="time" name="rrs_opening_hours[<?php echo $day; ?>][open]" value="<?php echo esc_attr($open); ?>" />
                    <span>to</span>
                    <input type="time" name="rrs_opening_hours[<?php echo $day; ?>][close]" value="<?php echo esc_attr($close); ?>" />
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <h2><?php _e('Booking Time Slots', 'restaurant-reservation'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Available Time Slots', 'restaurant-reservation'); ?></th>
                <td>
                    <div id="time-slots-container">
                        <?php foreach ($booking_time_slots as $index => $time): ?>
                            <div class="time-slot-row">
                                <input type="time" name="rrs_booking_time_slots[]" value="<?php echo esc_attr($time); ?>" />
                                <button type="button" class="button remove-time-slot"><?php _e('Remove', 'restaurant-reservation'); ?></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="add-time-slot" class="button"><?php _e('Add Time Slot', 'restaurant-reservation'); ?></button>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#add-time-slot').click(function() {
        var timeSlotRow = '<div class="time-slot-row"><input type="time" name="rrs_booking_time_slots[]" value="18:00" /><button type="button" class="button remove-time-slot"><?php _e('Remove', 'restaurant-reservation'); ?></button></div>';
        $('#time-slots-container').append(timeSlotRow);
    });
    
    $(document).on('click', '.remove-time-slot', function() {
        $(this).parent().remove();
    });
});
</script>

<style>
.time-slot-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.time-slot-row input[type="time"] {
    width: 120px;
}

.remove-time-slot {
    color: #dc3545;
}
</style>
