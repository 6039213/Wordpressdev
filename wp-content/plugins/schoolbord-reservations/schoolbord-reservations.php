<?php
/**
 * Plugin Name: Schoolbord Reserveringen
 * Plugin URI: https://schoolbord.nl
 * Description: Reserveringssysteem specifiek voor Restaurant Schoolbord Leiden
 * Version: 1.0.0
 * Author: MBO Rijnland
 * Author URI: https://mborijnland.nl
 * License: GPL v2 or later
 * Text Domain: schoolbord-reservations
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Schoolbord_Reservations {
    
    public function __construct() {
        // Register custom post type
        add_action('init', array($this, 'register_reservation_post_type'));
        
        // AJAX handlers
        add_action('wp_ajax_schoolbord_reservation', array($this, 'handle_reservation'));
        add_action('wp_ajax_nopriv_schoolbord_reservation', array($this, 'handle_reservation'));
        
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    /**
     * Register Reservation Custom Post Type
     */
    public function register_reservation_post_type() {
        register_post_type('schoolbord_res', array(
            'labels' => array(
                'name' => 'Reserveringen',
                'singular_name' => 'Reservering',
                'add_new' => 'Nieuwe Reservering',
                'add_new_item' => 'Nieuwe Reservering Toevoegen',
                'edit_item' => 'Reservering Bewerken',
                'view_item' => 'Reservering Bekijken',
                'search_items' => 'Reserveringen Zoeken',
            ),
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => array('title', 'custom-fields'),
            'menu_position' => 5,
        ));
    }
    
    /**
     * Handle Reservation Submission
     */
    public function handle_reservation() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'restaurant_nonce')) {
            wp_send_json_error(array('message' => 'Beveiligingscontrole mislukt.'));
            return;
        }
        
        // Get form data
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $date = sanitize_text_field($_POST['date']);
        $time = sanitize_text_field($_POST['time']);
        $guests = intval($_POST['guests']);
        $message = sanitize_textarea_field($_POST['message']);
        
        // Validate required fields
        if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($guests)) {
            wp_send_json_error(array('message' => 'Vul alle verplichte velden in.'));
            return;
        }
        
        // Validate day (only Monday, Wednesday, Thursday)
        $day_of_week = date('N', strtotime($date));
        if (!in_array($day_of_week, [1, 3, 4])) { // 1=Monday, 3=Wednesday, 4=Thursday
            wp_send_json_error(array('message' => 'Reserveringen zijn alleen mogelijk op maandag, woensdag en donderdag.'));
            return;
        }
        
        // Check if date is not in the past
        if (strtotime($date) < strtotime('today')) {
            wp_send_json_error(array('message' => 'U kunt geen reservering maken voor een datum in het verleden.'));
            return;
        }
        
        // Create reservation post
        $reservation_id = wp_insert_post(array(
            'post_title' => sprintf('Reservering - %s - %s %s', $name, $date, $time),
            'post_type' => 'schoolbord_res',
            'post_status' => 'publish',
            'meta_input' => array(
                'res_name' => $name,
                'res_email' => $email,
                'res_phone' => $phone,
                'res_date' => $date,
                'res_time' => $time,
                'res_guests' => $guests,
                'res_message' => $message,
                'res_status' => 'pending',
                'res_created' => current_time('mysql')
            )
        ));
        
        if ($reservation_id) {
            // Send confirmation email to customer
            $this->send_confirmation_email($reservation_id);
            
            // Send notification to restaurant
            $this->send_admin_notification($reservation_id);
            
            wp_send_json_success(array(
                'message' => 'Uw reservering is succesvol geplaatst! U ontvangt een bevestiging per e-mail.'
            ));
        } else {
            wp_send_json_error(array('message' => 'Er is een fout opgetreden. Probeer het opnieuw.'));
        }
    }
    
    /**
     * Send confirmation email to customer
     */
    private function send_confirmation_email($reservation_id) {
        $name = get_post_meta($reservation_id, 'res_name', true);
        $email = get_post_meta($reservation_id, 'res_email', true);
        $date = get_post_meta($reservation_id, 'res_date', true);
        $time = get_post_meta($reservation_id, 'res_time', true);
        $guests = get_post_meta($reservation_id, 'res_guests', true);
        
        $subject = 'Bevestiging reservering Restaurant Schoolbord';
        
        $message = "Beste $name,\n\n";
        $message .= "Hartelijk dank voor uw reservering bij Restaurant Schoolbord.\n\n";
        $message .= "Reserveringsdetails:\n";
        $message .= "Datum: " . date('d-m-Y', strtotime($date)) . "\n";
        $message .= "Tijd: $time uur\n";
        $message .= "Aantal personen: $guests\n\n";
        $message .= "Belangrijke informatie:\n";
        $message .= "- Inloop is tussen 17:15 en 17:30 uur\n";
        $message .= "- Wij serveren om 17:30 uur de eerste gang\n";
        $message .= "- Het einde van de service is om 19:30 uur\n";
        $message .= "- U kunt alleen pinnen (geen contant geld)\n";
        $message .= "- Tips kunnen via pin, deze gaan naar excursies voor studenten\n\n";
        $message .= "Locatie:\n";
        $message .= "Schoolgebouw Lammenschans\n";
        $message .= "Leiden\n\n";
        $message .= "Wij kijken ernaar uit u te mogen verwelkomen!\n\n";
        $message .= "Met vriendelijke groet,\n";
        $message .= "Restaurant Schoolbord\n";
        $message .= "MBO Rijnland";
        
        wp_mail($email, $subject, $message);
    }
    
    /**
     * Send notification to restaurant admin
     */
    private function send_admin_notification($reservation_id) {
        $name = get_post_meta($reservation_id, 'res_name', true);
        $email = get_post_meta($reservation_id, 'res_email', true);
        $phone = get_post_meta($reservation_id, 'res_phone', true);
        $date = get_post_meta($reservation_id, 'res_date', true);
        $time = get_post_meta($reservation_id, 'res_time', true);
        $guests = get_post_meta($reservation_id, 'res_guests', true);
        $dietary = get_post_meta($reservation_id, 'res_message', true);
        
        $admin_email = get_option('admin_email');
        $subject = 'Nieuwe reservering - Restaurant Schoolbord';
        
        $message = "Nieuwe reservering ontvangen:\n\n";
        $message .= "Naam: $name\n";
        $message .= "E-mail: $email\n";
        $message .= "Telefoon: $phone\n";
        $message .= "Datum: " . date('d-m-Y', strtotime($date)) . "\n";
        $message .= "Tijd: $time uur\n";
        $message .= "Aantal personen: $guests\n";
        if ($dietary) {
            $message .= "Dieetwensen: $dietary\n";
        }
        $message .= "\nReservering ID: #$reservation_id\n";
        $message .= "Bekijk in admin: " . admin_url('post.php?post=' . $reservation_id . '&action=edit');
        
        wp_mail($admin_email, $subject, $message);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=schoolbord_res',
            'Reserveringen Overzicht',
            'Overzicht',
            'manage_options',
            'schoolbord-overview',
            array($this, 'render_overview_page')
        );
    }
    
    /**
     * Render overview page
     */
    public function render_overview_page() {
        ?>
        <div class="wrap">
            <h1>Reserveringen Overzicht</h1>
            <p>Bekijk en beheer alle reserveringen voor Restaurant Schoolbord.</p>
            
            <?php
            $reservations = get_posts(array(
                'post_type' => 'schoolbord_res',
                'posts_per_page' => 50,
                'orderby' => 'meta_value',
                'meta_key' => 'res_date',
                'order' => 'ASC'
            ));
            
            if ($reservations) :
                ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Naam</th>
                            <th>Email</th>
                            <th>Telefoon</th>
                            <th>Personen</th>
                            <th>Dieetwensen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $res) : ?>
                            <tr>
                                <td><?php echo date('d-m-Y', strtotime(get_post_meta($res->ID, 'res_date', true))); ?></td>
                                <td><?php echo get_post_meta($res->ID, 'res_time', true); ?></td>
                                <td><?php echo get_post_meta($res->ID, 'res_name', true); ?></td>
                                <td><?php echo get_post_meta($res->ID, 'res_email', true); ?></td>
                                <td><?php echo get_post_meta($res->ID, 'res_phone', true); ?></td>
                                <td><?php echo get_post_meta($res->ID, 'res_guests', true); ?></td>
                                <td><?php echo get_post_meta($res->ID, 'res_message', true); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
            else :
                echo '<p>Nog geen reserveringen.</p>';
            endif;
            ?>
        </div>
        <?php
    }
}

// Initialize plugin
new Schoolbord_Reservations();
