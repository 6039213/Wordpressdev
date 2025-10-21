<?php
/**
 * Post Type Handler for Restaurant Reservation System
 * 
 * @package RestaurantReservationSystem
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RRS_Reservation_Post_Type {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_filter('manage_reservation_posts_columns', array($this, 'add_admin_columns'));
        add_action('manage_reservation_posts_custom_column', array($this, 'populate_admin_columns'), 10, 2);
        add_filter('manage_edit-reservation_sortable_columns', array($this, 'make_columns_sortable'));
    }
    
    public function register_post_type() {
        $labels = array(
            'name' => __('Reservations', 'restaurant-reservation'),
            'singular_name' => __('Reservation', 'restaurant-reservation'),
            'menu_name' => __('Reservations', 'restaurant-reservation'),
            'add_new' => __('Add New', 'restaurant-reservation'),
            'add_new_item' => __('Add New Reservation', 'restaurant-reservation'),
            'edit_item' => __('Edit Reservation', 'restaurant-reservation'),
            'new_item' => __('New Reservation', 'restaurant-reservation'),
            'view_item' => __('View Reservation', 'restaurant-reservation'),
            'search_items' => __('Search Reservations', 'restaurant-reservation'),
            'not_found' => __('No reservations found', 'restaurant-reservation'),
            'not_found_in_trash' => __('No reservations found in trash', 'restaurant-reservation'),
            'all_items' => __('All Reservations', 'restaurant-reservation'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => false, // We'll add it to our custom menu
            'query_var' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => array('title', 'custom-fields'),
            'show_in_rest' => true,
        );
        
        register_post_type('reservation', $args);
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'rrs_reservation_details',
            __('Reservation Details', 'restaurant-reservation'),
            array($this, 'reservation_details_meta_box'),
            'reservation',
            'normal',
            'high'
        );
        
        add_meta_box(
            'rrs_reservation_status',
            __('Reservation Status', 'restaurant-reservation'),
            array($this, 'reservation_status_meta_box'),
            'reservation',
            'side',
            'high'
        );
        
        add_meta_box(
            'rrs_reservation_actions',
            __('Actions', 'restaurant-reservation'),
            array($this, 'reservation_actions_meta_box'),
            'reservation',
            'side',
            'default'
        );
    }
    
    public function reservation_details_meta_box($post) {
        wp_nonce_field('rrs_reservation_meta_box', 'rrs_reservation_meta_box_nonce');
        
        $name = get_post_meta($post->ID, 'rrs_name', true);
        $email = get_post_meta($post->ID, 'rrs_email', true);
        $phone = get_post_meta($post->ID, 'rrs_phone', true);
        $date = get_post_meta($post->ID, 'rrs_date', true);
        $time = get_post_meta($post->ID, 'rrs_time', true);
        $guests = get_post_meta($post->ID, 'rrs_guests', true);
        $message = get_post_meta($post->ID, 'rrs_message', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="rrs_name"><?php _e('Customer Name', 'restaurant-reservation'); ?></label>
                </th>
                <td>
                    <input type="text" id="rrs_name" name="rrs_name" value="<?php echo esc_attr($name); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="rrs_email"><?php _e('Email Address', 'restaurant-reservation'); ?></label>
                </th>
                <td>
                    <input type="email" id="rrs_email" name="rrs_email" value="<?php echo esc_attr($email); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="rrs_phone"><?php _e('Phone Number', 'restaurant-reservation'); ?></label>
                </th>
                <td>
                    <input type="tel" id="rrs_phone" name="rrs_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="rrs_date"><?php _e('Reservation Date', 'restaurant-reservation'); ?></label>
                </th>
                <td>
                    <input type="date" id="rrs_date" name="rrs_date" value="<?php echo esc_attr($date); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="rrs_time"><?php _e('Reservation Time', 'restaurant-reservation'); ?></label>
                </th>
                <td>
                    <input type="time" id="rrs_time" name="rrs_time" value="<?php echo esc_attr($time); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="rrs_guests"><?php _e('Number of Guests', 'restaurant-reservation'); ?></label>
                </th>
                <td>
                    <input type="number" id="rrs_guests" name="rrs_guests" value="<?php echo esc_attr($guests); ?>" min="1" max="20" class="small-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="rrs_message"><?php _e('Special Requests', 'restaurant-reservation'); ?></label>
                </th>
                <td>
                    <textarea id="rrs_message" name="rrs_message" rows="4" cols="50" class="large-text"><?php echo esc_textarea($message); ?></textarea>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function reservation_status_meta_box($post) {
        $status = get_post_meta($post->ID, 'rrs_status', true);
        if (empty($status)) {
            $status = 'pending';
        }
        ?>
        <p>
            <label for="rrs_status"><?php _e('Status:', 'restaurant-reservation'); ?></label>
            <select id="rrs_status" name="rrs_status" class="widefat">
                <option value="pending" <?php selected($status, 'pending'); ?>><?php _e('Pending', 'restaurant-reservation'); ?></option>
                <option value="confirmed" <?php selected($status, 'confirmed'); ?>><?php _e('Confirmed', 'restaurant-reservation'); ?></option>
                <option value="cancelled" <?php selected($status, 'cancelled'); ?>><?php _e('Cancelled', 'restaurant-reservation'); ?></option>
                <option value="completed" <?php selected($status, 'completed'); ?>><?php _e('Completed', 'restaurant-reservation'); ?></option>
            </select>
        </p>
        
        <p>
            <strong><?php _e('Created:', 'restaurant-reservation'); ?></strong><br>
            <?php echo get_the_date('F j, Y g:i A', $post->ID); ?>
        </p>
        
        <?php if ($post->post_modified !== $post->post_date): ?>
        <p>
            <strong><?php _e('Last Modified:', 'restaurant-reservation'); ?></strong><br>
            <?php echo get_the_modified_date('F j, Y g:i A', $post->ID); ?>
        </p>
        <?php endif; ?>
        <?php
    }
    
    public function reservation_actions_meta_box($post) {
        $reservation_id = get_post_meta($post->ID, 'rrs_reservation_id', true);
        ?>
        <p>
            <a href="mailto:<?php echo esc_attr(get_post_meta($post->ID, 'rrs_email', true)); ?>" class="button button-secondary">
                <?php _e('Email Customer', 'restaurant-reservation'); ?>
            </a>
        </p>
        
        <p>
            <a href="tel:<?php echo esc_attr(get_post_meta($post->ID, 'rrs_phone', true)); ?>" class="button button-secondary">
                <?php _e('Call Customer', 'restaurant-reservation'); ?>
            </a>
        </p>
        
        <?php if ($reservation_id): ?>
        <p>
            <button type="button" class="button button-secondary" onclick="rrsSendConfirmation(<?php echo $reservation_id; ?>)">
                <?php _e('Send Confirmation', 'restaurant-reservation'); ?>
            </button>
        </p>
        <?php endif; ?>
        
        <script>
        function rrsSendConfirmation(reservationId) {
            if (confirm('<?php _e('Send confirmation email to customer?', 'restaurant-reservation'); ?>')) {
                // AJAX call to send confirmation email
                jQuery.post(ajaxurl, {
                    action: 'rrs_send_confirmation',
                    reservation_id: reservationId,
                    nonce: '<?php echo wp_create_nonce('rrs_send_confirmation'); ?>'
                }, function(response) {
                    if (response.success) {
                        alert('<?php _e('Confirmation email sent successfully!', 'restaurant-reservation'); ?>');
                    } else {
                        alert('<?php _e('Failed to send confirmation email.', 'restaurant-reservation'); ?>');
                    }
                });
            }
        }
        </script>
        <?php
    }
    
    public function save_meta_boxes($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['rrs_reservation_meta_box_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['rrs_reservation_meta_box_nonce'], 'rrs_reservation_meta_box')) {
            return;
        }
        
        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check the user's permissions
        if (isset($_POST['post_type']) && 'reservation' == $_POST['post_type']) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }
        
        // Save the meta box data
        $fields = array('rrs_name', 'rrs_email', 'rrs_phone', 'rrs_date', 'rrs_time', 'rrs_guests', 'rrs_message', 'rrs_status');
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                if ($field === 'rrs_message') {
                    $value = sanitize_textarea_field($_POST[$field]);
                } elseif ($field === 'rrs_guests') {
                    $value = intval($_POST[$field]);
                }
                update_post_meta($post_id, $field, $value);
            }
        }
        
        // Update the post title
        $name = get_post_meta($post_id, 'rrs_name', true);
        $date = get_post_meta($post_id, 'rrs_date', true);
        $time = get_post_meta($post_id, 'rrs_time', true);
        
        if ($name && $date && $time) {
            $new_title = sprintf(__('Reservation - %s - %s %s', 'restaurant-reservation'), $name, $date, $time);
            
            // Update the post title
            wp_update_post(array(
                'ID' => $post_id,
                'post_title' => $new_title
            ));
        }
    }
    
    public function add_admin_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = __('Customer', 'restaurant-reservation');
        $new_columns['rrs_date'] = __('Date', 'restaurant-reservation');
        $new_columns['rrs_time'] = __('Time', 'restaurant-reservation');
        $new_columns['rrs_guests'] = __('Guests', 'restaurant-reservation');
        $new_columns['rrs_status'] = __('Status', 'restaurant-reservation');
        $new_columns['rrs_contact'] = __('Contact', 'restaurant-reservation');
        $new_columns['date'] = __('Created', 'restaurant-reservation');
        
        return $new_columns;
    }
    
    public function populate_admin_columns($column, $post_id) {
        switch ($column) {
            case 'rrs_date':
                $date = get_post_meta($post_id, 'rrs_date', true);
                echo $date ? date('M j, Y', strtotime($date)) : '—';
                break;
                
            case 'rrs_time':
                $time = get_post_meta($post_id, 'rrs_time', true);
                echo $time ? date('g:i A', strtotime($time)) : '—';
                break;
                
            case 'rrs_guests':
                $guests = get_post_meta($post_id, 'rrs_guests', true);
                echo $guests ? $guests : '—';
                break;
                
            case 'rrs_status':
                $status = get_post_meta($post_id, 'rrs_status', true);
                if (empty($status)) {
                    $status = 'pending';
                }
                $status_colors = array(
                    'pending' => '#ffc107',
                    'confirmed' => '#28a745',
                    'cancelled' => '#dc3545',
                    'completed' => '#6c757d'
                );
                $color = isset($status_colors[$status]) ? $status_colors[$status] : '#6c757d';
                echo '<span style="color: ' . $color . '; font-weight: bold;">' . ucfirst($status) . '</span>';
                break;
                
            case 'rrs_contact':
                $email = get_post_meta($post_id, 'rrs_email', true);
                $phone = get_post_meta($post_id, 'rrs_phone', true);
                echo '<div>';
                if ($email) {
                    echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a><br>';
                }
                if ($phone) {
                    echo '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>';
                }
                echo '</div>';
                break;
        }
    }
    
    public function make_columns_sortable($columns) {
        $columns['rrs_date'] = 'rrs_date';
        $columns['rrs_time'] = 'rrs_time';
        $columns['rrs_guests'] = 'rrs_guests';
        $columns['rrs_status'] = 'rrs_status';
        
        return $columns;
    }
}
