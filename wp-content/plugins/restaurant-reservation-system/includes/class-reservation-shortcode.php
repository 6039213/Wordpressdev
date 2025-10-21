<?php
/**
 * Shortcode Handler for Restaurant Reservation System
 * 
 * @package RestaurantReservationSystem
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RRS_Reservation_Shortcode {
    
    public function __construct() {
        add_shortcode('restaurant_reservation', array($this, 'reservation_form_shortcode'));
        add_shortcode('restaurant_reservation_calendar', array($this, 'reservation_calendar_shortcode'));
        add_shortcode('restaurant_reservation_list', array($this, 'reservation_list_shortcode'));
    }
    
    public function reservation_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => get_option('rrs_form_title', __('Make a Reservation', 'restaurant-reservation')),
            'description' => get_option('rrs_form_description', __('Please fill out the form below to make a reservation.', 'restaurant-reservation')),
            'show_calendar' => get_option('rrs_show_calendar', true),
            'show_time_slots' => get_option('rrs_show_time_slots', true),
            'max_guests' => get_option('rrs_max_guests', 8),
            'require_phone' => get_option('rrs_require_phone', true),
            'require_message' => get_option('rrs_require_message', false),
            'theme_color' => get_option('rrs_theme_color', '#7bdcb5')
        ), $atts);
        
        ob_start();
        ?>
        <div class="rrs-reservation-form" style="--rrs-theme-color: <?php echo esc_attr($atts['theme_color']); ?>">
            <h2 class="rrs-form-title"><?php echo esc_html($atts['title']); ?></h2>
            <p class="rrs-form-description"><?php echo esc_html($atts['description']); ?></p>
            
            <form id="rrs-reservation-form" method="post">
                <div class="rrs-form-row">
                    <div class="rrs-form-group">
                        <label for="rrs-name"><?php _e('Full Name', 'restaurant-reservation'); ?> <span class="required">*</span></label>
                        <input type="text" id="rrs-name" name="rrs_name" required>
                        <div class="error-message"></div>
                    </div>
                    
                    <div class="rrs-form-group">
                        <label for="rrs-email"><?php _e('Email Address', 'restaurant-reservation'); ?> <span class="required">*</span></label>
                        <input type="email" id="rrs-email" name="rrs_email" required>
                        <div class="error-message"></div>
                    </div>
                </div>
                
                <div class="rrs-form-row">
                    <div class="rrs-form-group">
                        <label for="rrs-phone"><?php _e('Phone Number', 'restaurant-reservation'); ?><?php if ($atts['require_phone']): ?> <span class="required">*</span><?php endif; ?></label>
                        <input type="tel" id="rrs-phone" name="rrs_phone" <?php if ($atts['require_phone']): ?>required<?php endif; ?>>
                        <div class="error-message"></div>
                    </div>
                    
                    <div class="rrs-form-group">
                        <label for="rrs-guests"><?php _e('Number of Guests', 'restaurant-reservation'); ?> <span class="required">*</span></label>
                        <select id="rrs-guests" name="rrs_guests" required>
                            <?php for ($i = 1; $i <= $atts['max_guests']; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                        <div class="error-message"></div>
                    </div>
                </div>
                
                <div class="rrs-date-time-group">
                    <div class="rrs-date-group">
                        <div class="rrs-form-group">
                            <label for="rrs-date"><?php _e('Date', 'restaurant-reservation'); ?> <span class="required">*</span></label>
                            <input type="text" id="rrs-date" name="rrs_date" readonly required>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    
                    <div class="rrs-time-group">
                        <div class="rrs-form-group">
                            <label for="rrs-time"><?php _e('Time', 'restaurant-reservation'); ?> <span class="required">*</span></label>
                            <input type="hidden" id="rrs-time" name="rrs_time" required>
                            <div class="error-message"></div>
                        </div>
                    </div>
                </div>
                
                <?php if ($atts['show_time_slots']): ?>
                <div class="rrs-form-group">
                    <label><?php _e('Available Time Slots', 'restaurant-reservation'); ?></label>
                    <div class="rrs-time-slots">
                        <p class="rrs-message info"><?php _e('Please select a date to view available time slots.', 'restaurant-reservation'); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="rrs-form-group full-width">
                    <label for="rrs-message"><?php _e('Special Requests', 'restaurant-reservation'); ?><?php if ($atts['require_message']): ?> <span class="required">*</span><?php endif; ?></label>
                    <textarea id="rrs-message" name="rrs_message" rows="4" placeholder="<?php _e('Any special requests or dietary requirements...', 'restaurant-reservation'); ?>" <?php if ($atts['require_message']): ?>required<?php endif; ?>></textarea>
                    <div class="error-message"></div>
                </div>
                
                <div class="rrs-loading">
                    <div class="rrs-spinner"></div>
                    <span><?php _e('Processing your reservation...', 'restaurant-reservation'); ?></span>
                </div>
                
                <div class="rrs-message"></div>
                
                <button type="submit" class="rrs-submit-button">
                    <?php _e('Make Reservation', 'restaurant-reservation'); ?>
                </button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function reservation_calendar_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Reservation Calendar', 'restaurant-reservation'),
            'show_navigation' => true,
            'theme_color' => get_option('rrs_theme_color', '#7bdcb5')
        ), $atts);
        
        ob_start();
        ?>
        <div class="rrs-calendar" style="--rrs-theme-color: <?php echo esc_attr($atts['theme_color']); ?>">
            <div class="rrs-calendar-header">
                <h3 class="rrs-calendar-title"><?php echo esc_html($atts['title']); ?></h3>
                <?php if ($atts['show_navigation']): ?>
                <div class="rrs-calendar-nav">
                    <button type="button" data-direction="prev"><?php _e('Previous', 'restaurant-reservation'); ?></button>
                    <button type="button" data-direction="next"><?php _e('Next', 'restaurant-reservation'); ?></button>
                </div>
                <?php endif; ?>
            </div>
            <div class="rrs-calendar-grid">
                <!-- Calendar will be populated by JavaScript -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function reservation_list_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Upcoming Reservations', 'restaurant-reservation'),
            'limit' => 10,
            'status' => 'confirmed',
            'show_date' => true,
            'show_time' => true,
            'show_guests' => true,
            'show_contact' => false
        ), $atts);
        
        // Only show to logged-in users with appropriate permissions
        if (!is_user_logged_in() || !current_user_can('manage_options')) {
            return '<p>' . __('You must be logged in to view reservations.', 'restaurant-reservation') . '</p>';
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'rrs_reservations';
        
        $where_clause = '';
        if ($atts['status'] !== 'all') {
            $where_clause = $wpdb->prepare('WHERE status = %s', $atts['status']);
        }
        
        $reservations = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name $where_clause ORDER BY date ASC, time ASC LIMIT %d",
            $atts['limit']
        ));
        
        ob_start();
        ?>
        <div class="rrs-reservation-list">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            
            <?php if (empty($reservations)): ?>
                <p><?php _e('No reservations found.', 'restaurant-reservation'); ?></p>
            <?php else: ?>
                <div class="rrs-reservations">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="rrs-reservation-item">
                            <div class="rrs-reservation-header">
                                <h4><?php echo esc_html($reservation->name); ?></h4>
                                <span class="rrs-reservation-status status-<?php echo esc_attr($reservation->status); ?>">
                                    <?php echo esc_html(ucfirst($reservation->status)); ?>
                                </span>
                            </div>
                            
                            <div class="rrs-reservation-details">
                                <?php if ($atts['show_date']): ?>
                                    <div class="rrs-detail">
                                        <strong><?php _e('Date:', 'restaurant-reservation'); ?></strong>
                                        <?php echo date('F j, Y', strtotime($reservation->date)); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($atts['show_time']): ?>
                                    <div class="rrs-detail">
                                        <strong><?php _e('Time:', 'restaurant-reservation'); ?></strong>
                                        <?php echo date('g:i A', strtotime($reservation->time)); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($atts['show_guests']): ?>
                                    <div class="rrs-detail">
                                        <strong><?php _e('Guests:', 'restaurant-reservation'); ?></strong>
                                        <?php echo $reservation->guests; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($atts['show_contact']): ?>
                                    <div class="rrs-detail">
                                        <strong><?php _e('Email:', 'restaurant-reservation'); ?></strong>
                                        <?php echo esc_html($reservation->email); ?>
                                    </div>
                                    <div class="rrs-detail">
                                        <strong><?php _e('Phone:', 'restaurant-reservation'); ?></strong>
                                        <?php echo esc_html($reservation->phone); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($reservation->message)): ?>
                                    <div class="rrs-detail">
                                        <strong><?php _e('Message:', 'restaurant-reservation'); ?></strong>
                                        <?php echo esc_html($reservation->message); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <style>
        .rrs-reservation-list {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .rrs-reservation-item {
            background: #ffffff;
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .rrs-reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e1e8ed;
        }
        
        .rrs-reservation-header h4 {
            margin: 0;
            color: #2c3e50;
        }
        
        .rrs-reservation-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .rrs-reservation-status.status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .rrs-reservation-status.status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .rrs-reservation-status.status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .rrs-reservation-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }
        
        .rrs-detail {
            font-size: 0.95rem;
        }
        
        .rrs-detail strong {
            color: #495057;
        }
        
        @media (max-width: 768px) {
            .rrs-reservation-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .rrs-reservation-details {
                grid-template-columns: 1fr;
            }
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
