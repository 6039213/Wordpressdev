<?php
/**
 * AJAX Handler for Restaurant Reservation System
 * 
 * @package RestaurantReservationSystem
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RRS_Reservation_Ajax {
    
    public function __construct() {
        add_action('wp_ajax_rrs_submit_reservation', array($this, 'submit_reservation'));
        add_action('wp_ajax_nopriv_rrs_submit_reservation', array($this, 'submit_reservation'));
        add_action('wp_ajax_rrs_check_availability', array($this, 'check_availability'));
        add_action('wp_ajax_nopriv_rrs_check_availability', array($this, 'check_availability'));
        add_action('wp_ajax_rrs_get_time_slots', array($this, 'get_time_slots'));
        add_action('wp_ajax_nopriv_rrs_get_time_slots', array($this, 'get_time_slots'));
        add_action('wp_ajax_rrs_update_reservation_status', array($this, 'update_reservation_status'));
        add_action('wp_ajax_rrs_delete_reservation', array($this, 'delete_reservation'));
    }
    
    public function submit_reservation() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'rrs_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'restaurant-reservation')));
        }
        
        // Sanitize and validate input
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $date = sanitize_text_field($_POST['date']);
        $time = sanitize_text_field($_POST['time']);
        $guests = intval($_POST['guests']);
        $message = sanitize_textarea_field($_POST['message']);
        
        // Validation
        $errors = array();
        
        if (empty($name)) {
            $errors[] = __('Name is required', 'restaurant-reservation');
        }
        
        if (empty($email) || !is_email($email)) {
            $errors[] = __('Valid email is required', 'restaurant-reservation');
        }
        
        if (get_option('rrs_require_phone', true) && empty($phone)) {
            $errors[] = __('Phone number is required', 'restaurant-reservation');
        }
        
        if (empty($date) || !$this->is_valid_date($date)) {
            $errors[] = __('Valid date is required', 'restaurant-reservation');
        }
        
        if (empty($time)) {
            $errors[] = __('Time is required', 'restaurant-reservation');
        }
        
        if ($guests < 1 || $guests > get_option('rrs_max_guests', 8)) {
            $errors[] = sprintf(__('Number of guests must be between 1 and %d', 'restaurant-reservation'), get_option('rrs_max_guests', 8));
        }
        
        if (get_option('rrs_require_message', false) && empty($message)) {
            $errors[] = __('Message is required', 'restaurant-reservation');
        }
        
        if (!empty($errors)) {
            wp_send_json_error(array('message' => implode(', ', $errors)));
        }
        
        // Check availability
        if (!$this->is_available($date, $time, $guests)) {
            wp_send_json_error(array('message' => __('Selected time slot is not available', 'restaurant-reservation')));
        }
        
        // Create reservation
        global $wpdb;
        $table_name = $wpdb->prefix . 'rrs_reservations';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'date' => $date,
                'time' => $time,
                'guests' => $guests,
                'message' => $message,
                'status' => get_option('rrs_auto_confirm', false) ? 'confirmed' : 'pending'
            ),
            array('%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to create reservation', 'restaurant-reservation')));
        }
        
        $reservation_id = $wpdb->insert_id;
        
        // Update availability
        $this->update_availability($date, $time, $guests, 'subtract');
        
        // Send email notifications
        $this->send_notifications($reservation_id, $name, $email, $phone, $date, $time, $guests, $message);
        
        // Create WordPress post for admin management
        $post_id = wp_insert_post(array(
            'post_title' => sprintf(__('Reservation - %s - %s %s', 'restaurant-reservation'), $name, $date, $time),
            'post_type' => 'reservation',
            'post_status' => 'publish',
            'meta_input' => array(
                'rrs_reservation_id' => $reservation_id,
                'rrs_name' => $name,
                'rrs_email' => $email,
                'rrs_phone' => $phone,
                'rrs_date' => $date,
                'rrs_time' => $time,
                'rrs_guests' => $guests,
                'rrs_message' => $message,
                'rrs_status' => get_option('rrs_auto_confirm', false) ? 'confirmed' : 'pending'
            )
        ));
        
        wp_send_json_success(array(
            'message' => __('Reservation submitted successfully!', 'restaurant-reservation'),
            'reservation_id' => $reservation_id,
            'post_id' => $post_id
        ));
    }
    
    public function check_availability() {
        $date = sanitize_text_field($_POST['date']);
        $time = sanitize_text_field($_POST['time']);
        $guests = intval($_POST['guests']);
        
        $available = $this->is_available($date, $time, $guests);
        
        wp_send_json_success(array('available' => $available));
    }
    
    public function get_time_slots() {
        $date = sanitize_text_field($_POST['date']);
        $guests = intval($_POST['guests']);
        
        $time_slots = get_option('rrs_booking_time_slots', array('18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30'));
        $available_slots = array();
        
        foreach ($time_slots as $time) {
            if ($this->is_available($date, $time, $guests)) {
                $available_slots[] = $time;
            }
        }
        
        wp_send_json_success(array('time_slots' => $available_slots));
    }
    
    public function update_reservation_status() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'restaurant-reservation')));
        }
        
        $reservation_id = intval($_POST['reservation_id']);
        $status = sanitize_text_field($_POST['status']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'rrs_reservations';
        
        $result = $wpdb->update(
            $table_name,
            array('status' => $status),
            array('id' => $reservation_id),
            array('%s'),
            array('%d')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to update reservation', 'restaurant-reservation')));
        }
        
        // Update WordPress post meta
        $posts = get_posts(array(
            'post_type' => 'reservation',
            'meta_key' => 'rrs_reservation_id',
            'meta_value' => $reservation_id,
            'posts_per_page' => 1
        ));
        
        if (!empty($posts)) {
            update_post_meta($posts[0]->ID, 'rrs_status', $status);
        }
        
        wp_send_json_success(array('message' => __('Reservation status updated', 'restaurant-reservation')));
    }
    
    public function delete_reservation() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'restaurant-reservation')));
        }
        
        $reservation_id = intval($_POST['reservation_id']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'rrs_reservations';
        
        // Get reservation details for availability update
        $reservation = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $reservation_id));
        
        if (!$reservation) {
            wp_send_json_error(array('message' => __('Reservation not found', 'restaurant-reservation')));
        }
        
        // Update availability
        $this->update_availability($reservation->date, $reservation->time, $reservation->guests, 'add');
        
        // Delete from database
        $result = $wpdb->delete($table_name, array('id' => $reservation_id), array('%d'));
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to delete reservation', 'restaurant-reservation')));
        }
        
        // Delete WordPress post
        $posts = get_posts(array(
            'post_type' => 'reservation',
            'meta_key' => 'rrs_reservation_id',
            'meta_value' => $reservation_id,
            'posts_per_page' => 1
        ));
        
        if (!empty($posts)) {
            wp_delete_post($posts[0]->ID, true);
        }
        
        wp_send_json_success(array('message' => __('Reservation deleted', 'restaurant-reservation')));
    }
    
    private function is_valid_date($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
    
    private function is_available($date, $time, $guests) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rrs_availability';
        
        // Check if restaurant is open on this day
        $day_of_week = strtolower(date('l', strtotime($date)));
        $opening_hours = get_option('rrs_opening_hours', array());
        
        if (!isset($opening_hours[$day_of_week]) || !$opening_hours[$day_of_week]['open']) {
            return false;
        }
        
        // Check if time is within opening hours
        $open_time = $opening_hours[$day_of_week]['open'];
        $close_time = $opening_hours[$day_of_week]['close'];
        
        if ($time < $open_time || $time > $close_time) {
            return false;
        }
        
        // Check availability
        $availability = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE date = %s AND time = %s",
            $date,
            $time
        ));
        
        if (!$availability) {
            // Create default availability if not exists
            $total_seats = get_option('rrs_total_seats', 50);
            $wpdb->insert(
                $table_name,
                array(
                    'date' => $date,
                    'time' => $time,
                    'available_seats' => $total_seats,
                    'total_seats' => $total_seats
                ),
                array('%s', '%s', '%d', '%d')
            );
            return $total_seats >= $guests;
        }
        
        return $availability->available_seats >= $guests;
    }
    
    private function update_availability($date, $time, $guests, $operation) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rrs_availability';
        
        $availability = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE date = %s AND time = %s",
            $date,
            $time
        ));
        
        if ($availability) {
            $new_available = $operation === 'subtract' 
                ? $availability->available_seats - $guests 
                : $availability->available_seats + $guests;
            
            $wpdb->update(
                $table_name,
                array('available_seats' => $new_available),
                array('id' => $availability->id),
                array('%d'),
                array('%d')
            );
        }
    }
    
    private function send_notifications($reservation_id, $name, $email, $phone, $date, $time, $guests, $message) {
        if (!get_option('rrs_send_email_notifications', true)) {
            return;
        }
        
        $restaurant_name = get_option('rrs_restaurant_name', get_bloginfo('name'));
        $restaurant_email = get_option('rrs_restaurant_email', get_option('admin_email'));
        
        // Send confirmation email to customer
        $customer_subject = sprintf(__('Reservation Confirmation - %s', 'restaurant-reservation'), $restaurant_name);
        $customer_message = $this->get_customer_email_template($name, $date, $time, $guests, $message);
        
        wp_mail($email, $customer_subject, $customer_message, array('Content-Type: text/html; charset=UTF-8'));
        
        // Send notification email to restaurant
        $admin_subject = sprintf(__('New Reservation - %s', 'restaurant-reservation'), $restaurant_name);
        $admin_message = $this->get_admin_email_template($name, $email, $phone, $date, $time, $guests, $message);
        
        wp_mail($restaurant_email, $admin_subject, $admin_message, array('Content-Type: text/html; charset=UTF-8'));
    }
    
    private function get_customer_email_template($name, $date, $time, $guests, $message) {
        $restaurant_name = get_option('rrs_restaurant_name', get_bloginfo('name'));
        $restaurant_phone = get_option('rrs_restaurant_phone', '');
        $restaurant_address = get_option('rrs_restaurant_address', '');
        
        $template = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #7bdcb5; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .reservation-details { background-color: white; padding: 20px; margin: 20px 0; border-left: 4px solid #7bdcb5; }
                .footer { text-align: center; padding: 20px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>' . esc_html($restaurant_name) . '</h1>
                    <p>' . __('Reservation Confirmation', 'restaurant-reservation') . '</p>
                </div>
                <div class="content">
                    <p>' . sprintf(__('Dear %s,', 'restaurant-reservation'), esc_html($name)) . '</p>
                    <p>' . __('Thank you for your reservation. Here are your reservation details:', 'restaurant-reservation') . '</p>
                    <div class="reservation-details">
                        <h3>' . __('Reservation Details', 'restaurant-reservation') . '</h3>
                        <p><strong>' . __('Date:', 'restaurant-reservation') . '</strong> ' . date('F j, Y', strtotime($date)) . '</p>
                        <p><strong>' . __('Time:', 'restaurant-reservation') . '</strong> ' . date('g:i A', strtotime($time)) . '</p>
                        <p><strong>' . __('Number of Guests:', 'restaurant-reservation') . '</strong> ' . $guests . '</p>';
        
        if (!empty($message)) {
            $template .= '<p><strong>' . __('Special Requests:', 'restaurant-reservation') . '</strong> ' . esc_html($message) . '</p>';
        }
        
        $template .= '
                    </div>
                    <p>' . __('We look forward to serving you!', 'restaurant-reservation') . '</p>';
        
        if ($restaurant_phone) {
            $template .= '<p>' . sprintf(__('If you need to make any changes, please call us at %s.', 'restaurant-reservation'), esc_html($restaurant_phone)) . '</p>';
        }
        
        $template .= '
                </div>
                <div class="footer">
                    <p>' . esc_html($restaurant_name) . '</p>';
        
        if ($restaurant_address) {
            $template .= '<p>' . esc_html($restaurant_address) . '</p>';
        }
        
        $template .= '
                </div>
            </div>
        </body>
        </html>';
        
        return $template;
    }
    
    private function get_admin_email_template($name, $email, $phone, $date, $time, $guests, $message) {
        $template = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #7bdcb5; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .reservation-details { background-color: white; padding: 20px; margin: 20px 0; border-left: 4px solid #7bdcb5; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>' . __('New Reservation', 'restaurant-reservation') . '</h1>
                </div>
                <div class="content">
                    <div class="reservation-details">
                        <h3>' . __('Customer Information', 'restaurant-reservation') . '</h3>
                        <p><strong>' . __('Name:', 'restaurant-reservation') . '</strong> ' . esc_html($name) . '</p>
                        <p><strong>' . __('Email:', 'restaurant-reservation') . '</strong> ' . esc_html($email) . '</p>
                        <p><strong>' . __('Phone:', 'restaurant-reservation') . '</strong> ' . esc_html($phone) . '</p>
                        <h3>' . __('Reservation Details', 'restaurant-reservation') . '</h3>
                        <p><strong>' . __('Date:', 'restaurant-reservation') . '</strong> ' . date('F j, Y', strtotime($date)) . '</p>
                        <p><strong>' . __('Time:', 'restaurant-reservation') . '</strong> ' . date('g:i A', strtotime($time)) . '</p>
                        <p><strong>' . __('Number of Guests:', 'restaurant-reservation') . '</strong> ' . $guests . '</p>';
        
        if (!empty($message)) {
            $template .= '<p><strong>' . __('Special Requests:', 'restaurant-reservation') . '</strong> ' . esc_html($message) . '</p>';
        }
        
        $template .= '
                    </div>
                </div>
            </div>
        </body>
        </html>';
        
        return $template;
    }
}
