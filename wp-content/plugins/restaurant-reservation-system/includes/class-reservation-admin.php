<?php
/**
 * Admin Handler for Restaurant Reservation System
 * 
 * @package RestaurantReservationSystem
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RRS_Reservation_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_rrs_export_reservations', array($this, 'export_reservations'));
        add_action('wp_ajax_rrs_get_reservation_stats', array($this, 'get_reservation_stats'));
    }
    
    public function add_admin_menu() {
        // This is handled by the main plugin class
    }
    
    public function register_settings() {
        // Register settings for the plugin
        register_setting('rrs_settings', 'rrs_restaurant_name');
        register_setting('rrs_settings', 'rrs_restaurant_phone');
        register_setting('rrs_settings', 'rrs_restaurant_email');
        register_setting('rrs_settings', 'rrs_restaurant_address');
        register_setting('rrs_settings', 'rrs_opening_hours');
        register_setting('rrs_settings', 'rrs_max_guests');
        register_setting('rrs_settings', 'rrs_advance_booking_days');
        register_setting('rrs_settings', 'rrs_booking_time_slots');
        register_setting('rrs_settings', 'rrs_auto_confirm');
        register_setting('rrs_settings', 'rrs_send_email_notifications');
        register_setting('rrs_settings', 'rrs_require_phone');
        register_setting('rrs_settings', 'rrs_require_message');
        register_setting('rrs_settings', 'rrs_show_calendar');
        register_setting('rrs_settings', 'rrs_show_time_slots');
        register_setting('rrs_settings', 'rrs_theme_color');
        register_setting('rrs_settings', 'rrs_form_title');
        register_setting('rrs_settings', 'rrs_form_description');
    }
    
    public function export_reservations() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'restaurant-reservation'));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'rrs_reservations';
        
        $reservations = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date DESC, time DESC");
        
        $filename = 'reservations-' . date('Y-m-d-H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, array(
            'ID',
            'Name',
            'Email',
            'Phone',
            'Date',
            'Time',
            'Guests',
            'Message',
            'Status',
            'Created At'
        ));
        
        // CSV data
        foreach ($reservations as $reservation) {
            fputcsv($output, array(
                $reservation->id,
                $reservation->name,
                $reservation->email,
                $reservation->phone,
                $reservation->date,
                $reservation->time,
                $reservation->guests,
                $reservation->message,
                $reservation->status,
                $reservation->created_at
            ));
        }
        
        fclose($output);
        exit;
    }
    
    public function get_reservation_stats() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'restaurant-reservation')));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'rrs_reservations';
        
        $stats = array();
        
        // Total reservations
        $stats['total'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        
        // Reservations by status
        $status_counts = $wpdb->get_results("SELECT status, COUNT(*) as count FROM $table_name GROUP BY status");
        $stats['by_status'] = array();
        foreach ($status_counts as $status) {
            $stats['by_status'][$status->status] = $status->count;
        }
        
        // Reservations this month
        $stats['this_month'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())");
        
        // Reservations today
        $stats['today'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE date = CURDATE()");
        
        // Average guests per reservation
        $stats['avg_guests'] = $wpdb->get_var("SELECT AVG(guests) FROM $table_name");
        
        // Most popular time slots
        $popular_times = $wpdb->get_results("SELECT time, COUNT(*) as count FROM $table_name GROUP BY time ORDER BY count DESC LIMIT 5");
        $stats['popular_times'] = $popular_times;
        
        wp_send_json_success($stats);
    }
}
