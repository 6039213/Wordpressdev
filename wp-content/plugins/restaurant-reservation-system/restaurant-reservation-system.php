<?php
/**
 * Plugin Name: Restaurant Reservation System
 * Plugin URI: https://restaurant-pro.com
 * Description: A comprehensive reservation system for restaurants with advanced features, real-time availability, email notifications, and admin management.
 * Version: 2.0
 * Author: Restaurant Pro Team
 * Author URI: https://restaurant-pro.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: restaurant-reservation
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.4
 * Requires PHP: 8.0
 * Network: false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('RRS_VERSION', '2.0');
define('RRS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RRS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('RRS_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Restaurant Reservation System Class
 */
class Restaurant_Reservation_System {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        $this->includes();
        $this->init_hooks();
    }
    
    public function includes() {
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-post-type.php';
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-admin.php';
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-frontend.php';
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-ajax.php';
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-email.php';
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-calendar.php';
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-settings.php';
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-widget.php';
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-shortcode.php';
        require_once RRS_PLUGIN_PATH . 'includes/class-reservation-api.php';
    }
    
    public function init_hooks() {
        // Initialize classes
        new RRS_Reservation_Post_Type();
        new RRS_Reservation_Admin();
        new RRS_Reservation_Frontend();
        new RRS_Reservation_Ajax();
        new RRS_Reservation_Email();
        new RRS_Reservation_Calendar();
        new RRS_Reservation_Settings();
        new RRS_Reservation_Widget();
        new RRS_Reservation_Shortcode();
        new RRS_Reservation_API();
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Add settings link
        add_filter('plugin_action_links_' . RRS_PLUGIN_BASENAME, array($this, 'add_settings_link'));
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('restaurant-reservation', false, dirname(RRS_PLUGIN_BASENAME) . '/languages');
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style(
            'rrs-frontend-style',
            RRS_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            RRS_VERSION
        );
        
        wp_enqueue_script(
            'rrs-frontend-script',
            RRS_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery', 'jquery-ui-datepicker'),
            RRS_VERSION,
            true
        );
        
        wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css');
        
        // Localize script
        wp_localize_script('rrs-frontend-script', 'rrs_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rrs_nonce'),
            'loading_text' => __('Loading...', 'restaurant-reservation'),
            'error_text' => __('An error occurred. Please try again.', 'restaurant-reservation'),
            'success_text' => __('Reservation submitted successfully!', 'restaurant-reservation'),
            'date_format' => get_option('date_format'),
            'time_format' => get_option('time_format'),
            'min_date' => date('Y-m-d'),
            'max_date' => date('Y-m-d', strtotime('+3 months')),
            'available_times' => $this->get_available_times(),
            'restaurant_hours' => $this->get_restaurant_hours()
        ));
    }
    
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'restaurant-reservation') !== false) {
            wp_enqueue_style(
                'rrs-admin-style',
                RRS_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                RRS_VERSION
            );
            
            wp_enqueue_script(
                'rrs-admin-script',
                RRS_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery'),
                RRS_VERSION,
                true
            );
        }
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __('Reservations', 'restaurant-reservation'),
            __('Reservations', 'restaurant-reservation'),
            'manage_options',
            'restaurant-reservation',
            array($this, 'admin_page'),
            'dashicons-calendar-alt',
            30
        );
        
        add_submenu_page(
            'restaurant-reservation',
            __('All Reservations', 'restaurant-reservation'),
            __('All Reservations', 'restaurant-reservation'),
            'manage_options',
            'restaurant-reservation',
            array($this, 'admin_page')
        );
        
        add_submenu_page(
            'restaurant-reservation',
            __('Calendar View', 'restaurant-reservation'),
            __('Calendar View', 'restaurant-reservation'),
            'manage_options',
            'restaurant-reservation-calendar',
            array($this, 'calendar_page')
        );
        
        add_submenu_page(
            'restaurant-reservation',
            __('Settings', 'restaurant-reservation'),
            __('Settings', 'restaurant-reservation'),
            'manage_options',
            'restaurant-reservation-settings',
            array($this, 'settings_page')
        );
    }
    
    public function admin_page() {
        include RRS_PLUGIN_PATH . 'templates/admin/reservations.php';
    }
    
    public function calendar_page() {
        include RRS_PLUGIN_PATH . 'templates/admin/calendar.php';
    }
    
    public function settings_page() {
        include RRS_PLUGIN_PATH . 'templates/admin/settings.php';
    }
    
    public function add_settings_link($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=restaurant-reservation-settings') . '">' . __('Settings', 'restaurant-reservation') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    
    public function activate() {
        // Create database tables
        $this->create_tables();
        
        // Create default settings
        $this->create_default_settings();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    private function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'rrs_reservations';
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20) NOT NULL,
            date date NOT NULL,
            time time NOT NULL,
            guests int(3) NOT NULL,
            message text,
            status varchar(20) DEFAULT 'pending',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY date_time (date, time),
            KEY status (status)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        $table_name = $wpdb->prefix . 'rrs_availability';
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            date date NOT NULL,
            time time NOT NULL,
            available_seats int(3) NOT NULL,
            total_seats int(3) NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY date_time (date, time)
        ) $charset_collate;";
        
        dbDelta($sql);
    }
    
    private function create_default_settings() {
        $default_settings = array(
            'rrs_restaurant_name' => get_bloginfo('name'),
            'rrs_restaurant_phone' => '',
            'rrs_restaurant_email' => get_option('admin_email'),
            'rrs_restaurant_address' => '',
            'rrs_opening_hours' => array(
                'monday' => array('open' => '09:00', 'close' => '22:00'),
                'tuesday' => array('open' => '09:00', 'close' => '22:00'),
                'wednesday' => array('open' => '09:00', 'close' => '22:00'),
                'thursday' => array('open' => '09:00', 'close' => '22:00'),
                'friday' => array('open' => '09:00', 'close' => '23:00'),
                'saturday' => array('open' => '09:00', 'close' => '23:00'),
                'sunday' => array('open' => '10:00', 'close' => '21:00')
            ),
            'rrs_max_guests' => 8,
            'rrs_advance_booking_days' => 90,
            'rrs_booking_time_slots' => array('18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30'),
            'rrs_auto_confirm' => false,
            'rrs_send_email_notifications' => true,
            'rrs_email_template' => 'default',
            'rrs_require_phone' => true,
            'rrs_require_message' => false,
            'rrs_show_calendar' => true,
            'rrs_show_time_slots' => true,
            'rrs_theme_color' => '#7bdcb5',
            'rrs_form_title' => __('Make a Reservation', 'restaurant-reservation'),
            'rrs_form_description' => __('Please fill out the form below to make a reservation.', 'restaurant-reservation')
        );
        
        foreach ($default_settings as $key => $value) {
            if (!get_option($key)) {
                add_option($key, $value);
            }
        }
    }
    
    private function get_available_times() {
        $times = get_option('rrs_booking_time_slots', array('18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30'));
        return $times;
    }
    
    private function get_restaurant_hours() {
        $hours = get_option('rrs_opening_hours', array());
        return $hours;
    }
}

// Initialize the plugin
Restaurant_Reservation_System::get_instance();
