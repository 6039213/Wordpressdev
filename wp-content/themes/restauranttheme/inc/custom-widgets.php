<?php
/**
 * Custom Widgets for Restaurant Pro Theme
 * 
 * @package RestaurantPro
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Restaurant Info Widget
 */
class Restaurant_Info_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'restaurant_info_widget',
            __('Restaurant Info Widget', 'restaurant-pro'),
            array('description' => __('Display restaurant information', 'restaurant-pro'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $phone = get_theme_mod('restaurant_phone', '');
        $address = get_theme_mod('restaurant_address', '');
        $city = get_theme_mod('restaurant_city', '');
        $postal_code = get_theme_mod('restaurant_postal_code', '');
        $opening_hours = get_theme_mod('restaurant_opening_hours', '');
        
        echo '<div class="restaurant-info-widget-content">';
        
        if ($phone) {
            echo '<div class="restaurant-info-item">';
            echo '<i class="fas fa-phone"></i>';
            echo '<span>' . esc_html($phone) . '</span>';
            echo '</div>';
        }
        
        if ($address || $city || $postal_code) {
            echo '<div class="restaurant-info-item">';
            echo '<i class="fas fa-map-marker-alt"></i>';
            echo '<span>';
            if ($address) echo esc_html($address) . '<br>';
            if ($postal_code) echo esc_html($postal_code) . ' ';
            if ($city) echo esc_html($city);
            echo '</span>';
            echo '</div>';
        }
        
        if ($opening_hours) {
            echo '<div class="restaurant-info-item">';
            echo '<i class="fas fa-clock"></i>';
            echo '<span>' . nl2br(esc_html($opening_hours)) . '</span>';
            echo '</div>';
        }
        
        echo '</div>';
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Restaurant Information', 'restaurant-pro');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'restaurant-pro'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}

/**
 * Featured Dishes Widget
 */
class Featured_Dishes_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'featured_dishes_widget',
            __('Featured Dishes Widget', 'restaurant-pro'),
            array('description' => __('Display featured dishes', 'restaurant-pro'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $number = !empty($instance['number']) ? absint($instance['number']) : 3;
        
        $dishes = new WP_Query(array(
            'post_type' => 'dish',
            'posts_per_page' => $number,
            'meta_query' => array(
                array(
                    'key' => 'featured_dish',
                    'value' => '1',
                    'compare' => '='
                )
            )
        ));
        
        if ($dishes->have_posts()) {
            echo '<div class="featured-dishes-widget">';
            while ($dishes->have_posts()) {
                $dishes->the_post();
                echo '<div class="featured-dish-item">';
                if (has_post_thumbnail()) {
                    echo '<div class="dish-thumbnail">';
                    the_post_thumbnail('restaurant-thumb');
                    echo '</div>';
                }
                echo '<div class="dish-info">';
                echo '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
                $price = get_post_meta(get_the_ID(), 'dish_price', true);
                if ($price) {
                    echo '<span class="dish-price">€' . esc_html($price) . '</span>';
                }
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            wp_reset_postdata();
        }
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Featured Dishes', 'restaurant-pro');
        $number = !empty($instance['number']) ? $instance['number'] : 3;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'restaurant-pro'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of dishes:', 'restaurant-pro'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 3;
        return $instance;
    }
}

/**
 * Recent Events Widget
 */
class Recent_Events_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'recent_events_widget',
            __('Recent Events Widget', 'restaurant-pro'),
            array('description' => __('Display recent events', 'restaurant-pro'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $number = !empty($instance['number']) ? absint($instance['number']) : 3;
        
        $events = new WP_Query(array(
            'post_type' => 'event',
            'posts_per_page' => $number,
            'meta_key' => 'event_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>='
                )
            )
        ));
        
        if ($events->have_posts()) {
            echo '<div class="recent-events-widget">';
            while ($events->have_posts()) {
                $events->the_post();
                echo '<div class="event-item">';
                if (has_post_thumbnail()) {
                    echo '<div class="event-thumbnail">';
                    the_post_thumbnail('restaurant-thumb');
                    echo '</div>';
                }
                echo '<div class="event-info">';
                echo '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
                $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                if ($event_date) {
                    echo '<span class="event-date">' . date('F j, Y', strtotime($event_date)) . '</span>';
                }
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            wp_reset_postdata();
        }
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Upcoming Events', 'restaurant-pro');
        $number = !empty($instance['number']) ? $instance['number'] : 3;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'restaurant-pro'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of events:', 'restaurant-pro'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 3;
        return $instance;
    }
}

/**
 * Testimonials Widget
 */
class Testimonials_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'testimonials_widget',
            __('Testimonials Widget', 'restaurant-pro'),
            array('description' => __('Display customer testimonials', 'restaurant-pro'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $number = !empty($instance['number']) ? absint($instance['number']) : 3;
        
        $testimonials = new WP_Query(array(
            'post_type' => 'testimonial',
            'posts_per_page' => $number,
            'orderby' => 'rand'
        ));
        
        if ($testimonials->have_posts()) {
            echo '<div class="testimonials-widget">';
            while ($testimonials->have_posts()) {
                $testimonials->the_post();
                echo '<div class="testimonial-item">';
                echo '<div class="testimonial-content">';
                echo '<p>"' . get_the_excerpt() . '"</p>';
                echo '</div>';
                echo '<div class="testimonial-author">';
                if (has_post_thumbnail()) {
                    echo '<div class="author-avatar">';
                    the_post_thumbnail('thumbnail');
                    echo '</div>';
                }
                echo '<div class="author-info">';
                echo '<h5>' . get_the_title() . '</h5>';
                $rating = get_post_meta(get_the_ID(), 'testimonial_rating', true);
                if ($rating) {
                    echo '<div class="rating">';
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo '<span class="star filled">★</span>';
                        } else {
                            echo '<span class="star">★</span>';
                        }
                    }
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            wp_reset_postdata();
        }
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Customer Testimonials', 'restaurant-pro');
        $number = !empty($instance['number']) ? $instance['number'] : 3;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'restaurant-pro'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of testimonials:', 'restaurant-pro'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 3;
        return $instance;
    }
}

// Register widgets
function restaurant_register_widgets() {
    register_widget('Restaurant_Info_Widget');
    register_widget('Featured_Dishes_Widget');
    register_widget('Recent_Events_Widget');
    register_widget('Testimonials_Widget');
}
add_action('widgets_init', 'restaurant_register_widgets');
