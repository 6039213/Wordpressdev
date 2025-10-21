<?php
/**
 * Restaurant Pro Theme Functions
 * 
 * @package RestaurantPro
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('RESTAURANT_THEME_VERSION', '2.0');
define('RESTAURANT_THEME_PATH', get_template_directory());
define('RESTAURANT_THEME_URL', get_template_directory_uri());

/**
 * Theme Setup and Support
 */
function restaurant_theme_setup() {
    // Load theme textdomain
    load_theme_textdomain('restaurant-pro', get_template_directory() . '/languages');
    
    // Add theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('custom-background');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('appearance-tools');
    add_theme_support('custom-spacing');
    add_theme_support('custom-units');
    add_theme_support('border');
    add_theme_support('link-color');
    add_theme_support('block-templates');
    
    // Add custom image sizes
    add_image_size('restaurant-hero', 1920, 1080, true);
    add_image_size('restaurant-dish', 600, 400, true);
    add_image_size('restaurant-thumb', 300, 200, true);
    add_image_size('restaurant-gallery', 800, 600, true);
    
    // Add editor styles
    add_editor_style('css/editor-style.css');
}
add_action('after_setup_theme', 'restaurant_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function restaurant_enqueue_assets() {
    // Main stylesheet
    wp_enqueue_style(
        'restaurant-main-style',
        get_stylesheet_uri(),
        array(),
        RESTAURANT_THEME_VERSION
    );
    
    // Custom CSS
    wp_enqueue_style(
        'restaurant-custom-style',
        RESTAURANT_THEME_URL . '/css/custom.css',
        array('restaurant-main-style'),
        RESTAURANT_THEME_VERSION
    );
    
    // Google Fonts
    wp_enqueue_style(
        'restaurant-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap',
        array(),
        null
    );
    
    // Main JavaScript
    wp_enqueue_script(
        'restaurant-main-script',
        RESTAURANT_THEME_URL . '/js/custom.js',
        array('jquery'),
        RESTAURANT_THEME_VERSION,
        true
    );
    
    // Localize script for AJAX
    wp_localize_script('restaurant-main-script', 'restaurant_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('restaurant_nonce'),
        'loading_text' => __('Loading...', 'restaurant-pro'),
        'error_text' => __('An error occurred. Please try again.', 'restaurant-pro')
    ));
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'restaurant_enqueue_assets');

/**
 * Register Navigation Menus
 */
function restaurant_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'restaurant-pro'),
        'footer' => __('Footer Menu', 'restaurant-pro'),
        'social' => __('Social Menu', 'restaurant-pro'),
        'mobile' => __('Mobile Menu', 'restaurant-pro')
    ));
}
add_action('after_setup_theme', 'restaurant_register_menus');

/**
 * Register Widget Areas
 */
function restaurant_widgets_init() {
    register_sidebar(array(
        'name'          => __('Main Sidebar', 'restaurant-pro'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'restaurant-pro'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'restaurant-pro'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'restaurant-pro'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('Restaurant Info Widget Area', 'restaurant-pro'),
        'id'            => 'restaurant-info',
        'description'   => __('Add restaurant information widgets here.', 'restaurant-pro'),
        'before_widget' => '<div id="%1$s" class="restaurant-info-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="restaurant-info-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'restaurant_widgets_init');

/**
 * Custom Post Types
 */
function restaurant_register_post_types() {
    // Dishes Post Type
    register_post_type('dish', array(
        'labels' => array(
            'name' => __('Dishes', 'restaurant-pro'),
            'singular_name' => __('Dish', 'restaurant-pro'),
            'add_new' => __('Add New Dish', 'restaurant-pro'),
            'add_new_item' => __('Add New Dish', 'restaurant-pro'),
            'edit_item' => __('Edit Dish', 'restaurant-pro'),
            'new_item' => __('New Dish', 'restaurant-pro'),
            'view_item' => __('View Dish', 'restaurant-pro'),
            'search_items' => __('Search Dishes', 'restaurant-pro'),
            'not_found' => __('No dishes found', 'restaurant-pro'),
            'not_found_in_trash' => __('No dishes found in trash', 'restaurant-pro'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-food',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'dishes'),
        'show_in_rest' => true,
        'menu_position' => 5,
    ));
    
    // Events Post Type
    register_post_type('event', array(
        'labels' => array(
            'name' => __('Events', 'restaurant-pro'),
            'singular_name' => __('Event', 'restaurant-pro'),
            'add_new' => __('Add New Event', 'restaurant-pro'),
            'add_new_item' => __('Add New Event', 'restaurant-pro'),
            'edit_item' => __('Edit Event', 'restaurant-pro'),
            'new_item' => __('New Event', 'restaurant-pro'),
            'view_item' => __('View Event', 'restaurant-pro'),
            'search_items' => __('Search Events', 'restaurant-pro'),
            'not_found' => __('No events found', 'restaurant-pro'),
            'not_found_in_trash' => __('No events found in trash', 'restaurant-pro'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'events'),
        'show_in_rest' => true,
        'menu_position' => 6,
    ));
    
    // Testimonials Post Type
    register_post_type('testimonial', array(
        'labels' => array(
            'name' => __('Testimonials', 'restaurant-pro'),
            'singular_name' => __('Testimonial', 'restaurant-pro'),
            'add_new' => __('Add New Testimonial', 'restaurant-pro'),
            'add_new_item' => __('Add New Testimonial', 'restaurant-pro'),
            'edit_item' => __('Edit Testimonial', 'restaurant-pro'),
            'new_item' => __('New Testimonial', 'restaurant-pro'),
            'view_item' => __('View Testimonial', 'restaurant-pro'),
            'search_items' => __('Search Testimonials', 'restaurant-pro'),
            'not_found' => __('No testimonials found', 'restaurant-pro'),
            'not_found_in_trash' => __('No testimonials found in trash', 'restaurant-pro'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite' => array('slug' => 'testimonials'),
        'show_in_rest' => true,
        'menu_position' => 7,
    ));
    
    // Reservations Post Type
    register_post_type('reservation', array(
        'labels' => array(
            'name' => __('Reservations', 'restaurant-pro'),
            'singular_name' => __('Reservation', 'restaurant-pro'),
            'add_new' => __('Add New Reservation', 'restaurant-pro'),
            'add_new_item' => __('Add New Reservation', 'restaurant-pro'),
            'edit_item' => __('Edit Reservation', 'restaurant-pro'),
            'new_item' => __('New Reservation', 'restaurant-pro'),
            'view_item' => __('View Reservation', 'restaurant-pro'),
            'search_items' => __('Search Reservations', 'restaurant-pro'),
            'not_found' => __('No reservations found', 'restaurant-pro'),
            'not_found_in_trash' => __('No reservations found in trash', 'restaurant-pro'),
        ),
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-calendar',
        'supports' => array('title', 'custom-fields'),
        'show_in_rest' => true,
        'menu_position' => 8,
    ));
}
add_action('init', 'restaurant_register_post_types');

/**
 * Custom Taxonomies
 */
function restaurant_register_taxonomies() {
    // Dish Categories
    register_taxonomy('dish_category', 'dish', array(
        'labels' => array(
            'name' => __('Dish Categories', 'restaurant-pro'),
            'singular_name' => __('Dish Category', 'restaurant-pro'),
            'search_items' => __('Search Categories', 'restaurant-pro'),
            'all_items' => __('All Categories', 'restaurant-pro'),
            'parent_item' => __('Parent Category', 'restaurant-pro'),
            'parent_item_colon' => __('Parent Category:', 'restaurant-pro'),
            'edit_item' => __('Edit Category', 'restaurant-pro'),
            'update_item' => __('Update Category', 'restaurant-pro'),
            'add_new_item' => __('Add New Category', 'restaurant-pro'),
            'new_item_name' => __('New Category Name', 'restaurant-pro'),
            'menu_name' => __('Categories', 'restaurant-pro'),
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'dish-category'),
        'show_in_rest' => true,
    ));
    
    // Event Categories
    register_taxonomy('event_category', 'event', array(
        'labels' => array(
            'name' => __('Event Categories', 'restaurant-pro'),
            'singular_name' => __('Event Category', 'restaurant-pro'),
            'search_items' => __('Search Categories', 'restaurant-pro'),
            'all_items' => __('All Categories', 'restaurant-pro'),
            'parent_item' => __('Parent Category', 'restaurant-pro'),
            'parent_item_colon' => __('Parent Category:', 'restaurant-pro'),
            'edit_item' => __('Edit Category', 'restaurant-pro'),
            'update_item' => __('Update Category', 'restaurant-pro'),
            'add_new_item' => __('Add New Category', 'restaurant-pro'),
            'new_item_name' => __('New Category Name', 'restaurant-pro'),
            'menu_name' => __('Categories', 'restaurant-pro'),
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'event-category'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'restaurant_register_taxonomies');

/**
 * Custom Meta Boxes
 */
function restaurant_add_meta_boxes() {
    add_meta_box(
        'restaurant_dish_details',
        __('Dish Details', 'restaurant-pro'),
        'restaurant_render_dish_meta_box',
        'dish',
        'normal',
        'high'
    );

    add_meta_box(
        'restaurant_event_details',
        __('Event Details', 'restaurant-pro'),
        'restaurant_render_event_meta_box',
        'event',
        'normal',
        'high'
    );

    add_meta_box(
        'restaurant_testimonial_details',
        __('Testimonial Details', 'restaurant-pro'),
        'restaurant_render_testimonial_meta_box',
        'testimonial',
        'normal',
        'high'
    );

    add_meta_box(
        'restaurant_reservation_status',
        __('Reservation Status', 'restaurant-pro'),
        'restaurant_render_reservation_meta_box',
        'reservation',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'restaurant_add_meta_boxes');

function restaurant_render_dish_meta_box($post) {
    wp_nonce_field('restaurant_save_dish_meta', 'restaurant_dish_nonce');

    $price        = get_post_meta($post->ID, 'dish_price', true);
    $ingredients  = get_post_meta($post->ID, 'dish_ingredients', true);
    $allergens    = get_post_meta($post->ID, 'dish_allergens', true);
    $featured     = get_post_meta($post->ID, 'featured_dish', true);
    ?>
    <p>
        <label for="dish_price"><strong><?php _e('Price (€)', 'restaurant-pro'); ?></strong></label>
        <input type="text" id="dish_price" name="dish_price" value="<?php echo esc_attr($price); ?>" class="widefat" placeholder="17.50">
    </p>
    <p>
        <label for="dish_ingredients"><strong><?php _e('Ingredients', 'restaurant-pro'); ?></strong></label>
        <textarea id="dish_ingredients" name="dish_ingredients" class="widefat" rows="3" placeholder="<?php esc_attr_e('List the primary ingredients', 'restaurant-pro'); ?>"><?php echo esc_textarea($ingredients); ?></textarea>
    </p>
    <p>
        <label for="dish_allergens"><strong><?php _e('Allergens', 'restaurant-pro'); ?></strong></label>
        <textarea id="dish_allergens" name="dish_allergens" class="widefat" rows="2" placeholder="<?php esc_attr_e('Specify allergens such as gluten, lactose, nuts…', 'restaurant-pro'); ?>"><?php echo esc_textarea($allergens); ?></textarea>
    </p>
    <p>
        <label for="featured_dish">
            <input type="checkbox" id="featured_dish" name="featured_dish" value="1" <?php checked($featured, '1'); ?>>
            <?php _e('Mark as featured dish', 'restaurant-pro'); ?>
        </label>
    </p>
    <?php
}

function restaurant_render_event_meta_box($post) {
    wp_nonce_field('restaurant_save_event_meta', 'restaurant_event_nonce');

    $date      = get_post_meta($post->ID, 'event_date', true);
    $time      = get_post_meta($post->ID, 'event_time', true);
    $location  = get_post_meta($post->ID, 'event_location', true);
    ?>
    <p>
        <label for="event_date"><strong><?php _e('Event Date', 'restaurant-pro'); ?></strong></label>
        <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($date); ?>" class="widefat">
    </p>
    <p>
        <label for="event_time"><strong><?php _e('Event Time', 'restaurant-pro'); ?></strong></label>
        <input type="time" id="event_time" name="event_time" value="<?php echo esc_attr($time); ?>" class="widefat">
    </p>
    <p>
        <label for="event_location"><strong><?php _e('Location', 'restaurant-pro'); ?></strong></label>
        <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($location); ?>" class="widefat" placeholder="<?php esc_attr_e('Restaurant Schoolbord, Leiden', 'restaurant-pro'); ?>">
    </p>
    <?php
}

function restaurant_render_testimonial_meta_box($post) {
    wp_nonce_field('restaurant_save_testimonial_meta', 'restaurant_testimonial_nonce');

    $role    = get_post_meta($post->ID, 'testimonial_role', true);
    $rating  = get_post_meta($post->ID, 'testimonial_rating', true);
    ?>
    <p>
        <label for="testimonial_role"><strong><?php _e('Role / Subtitle', 'restaurant-pro'); ?></strong></label>
        <input type="text" id="testimonial_role" name="testimonial_role" value="<?php echo esc_attr($role); ?>" class="widefat" placeholder="<?php esc_attr_e('Food critic, Returning guest…', 'restaurant-pro'); ?>">
    </p>
    <p>
        <label for="testimonial_rating"><strong><?php _e('Rating (1-5)', 'restaurant-pro'); ?></strong></label>
        <select id="testimonial_rating" name="testimonial_rating" class="widefat">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <option value="<?php echo $i; ?>" <?php selected((int) $rating, $i); ?>>
                    <?php echo sprintf(_n('%d star', '%d stars', $i, 'restaurant-pro'), $i); ?>
                </option>
            <?php endfor; ?>
        </select>
    </p>
    <?php
}

function restaurant_render_reservation_meta_box($post) {
    wp_nonce_field('restaurant_save_reservation_meta', 'restaurant_reservation_nonce');

    $status  = get_post_meta($post->ID, 'reservation_status', true) ?: 'pending';
    $notes   = get_post_meta($post->ID, 'reservation_internal_notes', true);
    ?>
    <p>
        <label for="reservation_status"><strong><?php _e('Reservation Status', 'restaurant-pro'); ?></strong></label>
        <select id="reservation_status" name="reservation_status" class="widefat">
            <option value="pending" <?php selected($status, 'pending'); ?>><?php _e('Pending', 'restaurant-pro'); ?></option>
            <option value="confirmed" <?php selected($status, 'confirmed'); ?>><?php _e('Confirmed', 'restaurant-pro'); ?></option>
            <option value="cancelled" <?php selected($status, 'cancelled'); ?>><?php _e('Cancelled', 'restaurant-pro'); ?></option>
        </select>
    </p>
    <p>
        <label for="reservation_internal_notes"><strong><?php _e('Internal Notes', 'restaurant-pro'); ?></strong></label>
        <textarea id="reservation_internal_notes" name="reservation_internal_notes" class="widefat" rows="3" placeholder="<?php esc_attr_e('Add preparation notes or follow-up actions', 'restaurant-pro'); ?>"><?php echo esc_textarea($notes); ?></textarea>
    </p>
    <?php
}

function restaurant_save_dish_meta($post_id) {
    if (!isset($_POST['restaurant_dish_nonce']) || !wp_verify_nonce($_POST['restaurant_dish_nonce'], 'restaurant_save_dish_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $price       = isset($_POST['dish_price']) ? sanitize_text_field($_POST['dish_price']) : '';
    $ingredients = isset($_POST['dish_ingredients']) ? sanitize_textarea_field($_POST['dish_ingredients']) : '';
    $allergens   = isset($_POST['dish_allergens']) ? sanitize_textarea_field($_POST['dish_allergens']) : '';
    $featured    = isset($_POST['featured_dish']) ? '1' : '';

    update_post_meta($post_id, 'dish_price', $price);
    update_post_meta($post_id, 'dish_ingredients', $ingredients);
    update_post_meta($post_id, 'dish_allergens', $allergens);
    update_post_meta($post_id, 'featured_dish', $featured);
}
add_action('save_post_dish', 'restaurant_save_dish_meta');

function restaurant_save_event_meta($post_id) {
    if (!isset($_POST['restaurant_event_nonce']) || !wp_verify_nonce($_POST['restaurant_event_nonce'], 'restaurant_save_event_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $date     = isset($_POST['event_date']) ? sanitize_text_field($_POST['event_date']) : '';
    $time     = isset($_POST['event_time']) ? sanitize_text_field($_POST['event_time']) : '';
    $location = isset($_POST['event_location']) ? sanitize_text_field($_POST['event_location']) : '';

    update_post_meta($post_id, 'event_date', $date);
    update_post_meta($post_id, 'event_time', $time);
    update_post_meta($post_id, 'event_location', $location);
}
add_action('save_post_event', 'restaurant_save_event_meta');

function restaurant_save_testimonial_meta($post_id) {
    if (!isset($_POST['restaurant_testimonial_nonce']) || !wp_verify_nonce($_POST['restaurant_testimonial_nonce'], 'restaurant_save_testimonial_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $role   = isset($_POST['testimonial_role']) ? sanitize_text_field($_POST['testimonial_role']) : '';
    $rating = isset($_POST['testimonial_rating']) ? max(1, min(5, intval($_POST['testimonial_rating']))) : 5;

    update_post_meta($post_id, 'testimonial_role', $role);
    update_post_meta($post_id, 'testimonial_rating', $rating);
}
add_action('save_post_testimonial', 'restaurant_save_testimonial_meta');

function restaurant_save_reservation_meta($post_id) {
    if (!isset($_POST['restaurant_reservation_nonce']) || !wp_verify_nonce($_POST['restaurant_reservation_nonce'], 'restaurant_save_reservation_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $status = isset($_POST['reservation_status']) ? sanitize_key($_POST['reservation_status']) : 'pending';
    $notes  = isset($_POST['reservation_internal_notes']) ? sanitize_textarea_field($_POST['reservation_internal_notes']) : '';

    update_post_meta($post_id, 'reservation_status', $status);
    update_post_meta($post_id, 'reservation_internal_notes', $notes);
}
add_action('save_post_reservation', 'restaurant_save_reservation_meta');

/**
 * Admin Columns
 */
function restaurant_dish_columns($columns) {
    $columns['dish_price']    = __('Price', 'restaurant-pro');
    $columns['dish_category'] = __('Category', 'restaurant-pro');
    $columns['dish_featured'] = __('Featured', 'restaurant-pro');
    return $columns;
}
add_filter('manage_dish_posts_columns', 'restaurant_dish_columns');

function restaurant_render_dish_columns($column, $post_id) {
    switch ($column) {
        case 'dish_price':
            $price = get_post_meta($post_id, 'dish_price', true);
            echo $price ? '€' . esc_html(number_format((float) $price, 2, ',', '.')) : '—';
            break;
        case 'dish_category':
            $terms = get_the_term_list($post_id, 'dish_category', '', ', ');
            echo $terms ? wp_kses_post($terms) : __('Uncategorised', 'restaurant-pro');
            break;
        case 'dish_featured':
            $featured = get_post_meta($post_id, 'featured_dish', true);
            echo $featured ? '<span class="dashicons dashicons-star-filled" aria-hidden="true"></span>' : '—';
            break;
    }
}
add_action('manage_dish_posts_custom_column', 'restaurant_render_dish_columns', 10, 2);

function restaurant_sortable_dish_columns($columns) {
    $columns['dish_price'] = 'dish_price';
    return $columns;
}
add_filter('manage_edit-dish_sortable_columns', 'restaurant_sortable_dish_columns');

function restaurant_dish_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ('dish_price' === $query->get('orderby')) {
        $query->set('meta_key', 'dish_price');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'restaurant_dish_orderby');

function restaurant_reservation_columns($columns) {
    $columns['reservation_date']   = __('Date', 'restaurant-pro');
    $columns['reservation_time']   = __('Time', 'restaurant-pro');
    $columns['reservation_guests'] = __('Guests', 'restaurant-pro');
    $columns['reservation_status'] = __('Status', 'restaurant-pro');
    return $columns;
}
add_filter('manage_reservation_posts_columns', 'restaurant_reservation_columns');

function restaurant_render_reservation_columns($column, $post_id) {
    switch ($column) {
        case 'reservation_date':
            echo esc_html(get_post_meta($post_id, 'reservation_date', true));
            break;
        case 'reservation_time':
            echo esc_html(get_post_meta($post_id, 'reservation_time', true));
            break;
        case 'reservation_guests':
            $guests = get_post_meta($post_id, 'reservation_guests', true);
            echo $guests ? intval($guests) : '—';
            break;
        case 'reservation_status':
            $status = get_post_meta($post_id, 'reservation_status', true);
            $labels = array(
                'pending'   => __('Pending', 'restaurant-pro'),
                'confirmed' => __('Confirmed', 'restaurant-pro'),
                'cancelled' => __('Cancelled', 'restaurant-pro'),
            );
            echo esc_html($labels[$status] ?? __('Pending', 'restaurant-pro'));
            break;
    }
}
add_action('manage_reservation_posts_custom_column', 'restaurant_render_reservation_columns', 10, 2);

/**
 * Register Custom Blocks
 */
function restaurant_register_blocks() {
    // Register existing block
    if (file_exists(__DIR__ . '/build/blockone/block.json')) {
        register_block_type(__DIR__ . '/build/blockone');
    }
    
    // Register additional custom blocks
    $blocks = array(
        'hero-section',
        'dish-grid',
        'testimonial-slider',
        'reservation-form',
        'event-calendar',
        'contact-info',
        'gallery-grid'
    );
    
    foreach ($blocks as $block) {
        $block_path = __DIR__ . '/build/' . $block;
        if (file_exists($block_path . '/block.json')) {
            register_block_type($block_path);
        }
    }
}
add_action('init', 'restaurant_register_blocks');

/**
 * Custom Admin Enhancements
 */
function restaurant_admin_enhancements() {
    // Add custom admin styles
    wp_enqueue_style(
        'restaurant-admin-style',
        RESTAURANT_THEME_URL . '/css/admin.css',
        array(),
        RESTAURANT_THEME_VERSION
    );
}
add_action('admin_enqueue_scripts', 'restaurant_admin_enhancements');

/**
 * Performance Optimizations
 */
function restaurant_performance_optimizations() {
    // Remove unnecessary WordPress features
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    
    // Disable emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');
    
    // Remove REST API links
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
}
add_action('init', 'restaurant_performance_optimizations');

/**
 * Security Enhancements
 */
function restaurant_security_enhancements() {
    // Hide WordPress version
    add_filter('the_generator', '__return_empty_string');
    
    // Remove version from scripts and styles
    add_filter('style_loader_src', 'restaurant_remove_version_strings');
    add_filter('script_loader_src', 'restaurant_remove_version_strings');
    
    // Disable file editing
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}
add_action('init', 'restaurant_security_enhancements');

function restaurant_remove_version_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * SEO Enhancements
 */
function restaurant_seo_enhancements() {
    // Add meta tags
    add_action('wp_head', 'restaurant_add_meta_tags');
    
    // Add structured data
    add_action('wp_head', 'restaurant_add_structured_data');
}
add_action('init', 'restaurant_seo_enhancements');

function restaurant_add_meta_tags() {
    if (is_front_page()) {
        echo '<meta name="description" content="' . get_bloginfo('description') . '">' . "\n";
        echo '<meta name="keywords" content="restaurant, food, dining, reservations, menu">' . "\n";
    }
}

function restaurant_add_structured_data() {
    if (is_front_page()) {
        $structured_data = array(
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url(),
            'telephone' => get_theme_mod('restaurant_phone', ''),
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => get_theme_mod('restaurant_address', ''),
                'addressLocality' => get_theme_mod('restaurant_city', ''),
                'postalCode' => get_theme_mod('restaurant_postal_code', ''),
                'addressCountry' => get_theme_mod('restaurant_country', 'NL')
            )
        );
        
        echo '<script type="application/ld+json">' . json_encode($structured_data) . '</script>' . "\n";
    }
}

/**
 * Customizer Settings
 */
function restaurant_customize_register($wp_customize) {
    // Restaurant Information Section
    $wp_customize->add_section('restaurant_info', array(
        'title' => __('Restaurant Information', 'restaurant-pro'),
        'priority' => 30,
    ));
    
    // Phone Number
    $wp_customize->add_setting('restaurant_phone', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('restaurant_phone', array(
        'label' => __('Phone Number', 'restaurant-pro'),
        'section' => 'restaurant_info',
        'type' => 'text',
    ));
    
    // Address
    $wp_customize->add_setting('restaurant_address', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('restaurant_address', array(
        'label' => __('Address', 'restaurant-pro'),
        'section' => 'restaurant_info',
        'type' => 'text',
    ));
    
    // City
    $wp_customize->add_setting('restaurant_city', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('restaurant_city', array(
        'label' => __('City', 'restaurant-pro'),
        'section' => 'restaurant_info',
        'type' => 'text',
    ));
    
    // Postal Code
    $wp_customize->add_setting('restaurant_postal_code', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('restaurant_postal_code', array(
        'label' => __('Postal Code', 'restaurant-pro'),
        'section' => 'restaurant_info',
        'type' => 'text',
    ));
    
    // Country
    $wp_customize->add_setting('restaurant_country', array(
        'default' => 'NL',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('restaurant_country', array(
        'label' => __('Country', 'restaurant-pro'),
        'section' => 'restaurant_info',
        'type' => 'text',
    ));
    
    // Opening Hours
    $wp_customize->add_setting('restaurant_opening_hours', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('restaurant_opening_hours', array(
        'label' => __('Opening Hours', 'restaurant-pro'),
        'section' => 'restaurant_info',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'restaurant_customize_register');

/**
 * AJAX Handlers
 */
function restaurant_ajax_reservation() {
    check_ajax_referer('restaurant_nonce', 'nonce');
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $date = sanitize_text_field($_POST['date']);
    $time = sanitize_text_field($_POST['time']);
    $guests = intval($_POST['guests']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Create reservation post
    $reservation_id = wp_insert_post(array(
        'post_title' => 'Reservation - ' . $name . ' - ' . $date . ' ' . $time,
        'post_type' => 'reservation',
        'post_status' => 'publish',
        'meta_input' => array(
            'reservation_name' => $name,
            'reservation_email' => $email,
            'reservation_phone' => $phone,
            'reservation_date' => $date,
            'reservation_time' => $time,
            'reservation_guests' => $guests,
            'reservation_message' => $message,
            'reservation_status' => 'pending'
        )
    ));
    
    if ($reservation_id) {
        // Send email notification
        $to = get_option('admin_email');
        $subject = 'New Reservation - ' . get_bloginfo('name');
        $body = "New reservation received:\n\n";
        $body .= "Name: $name\n";
        $body .= "Email: $email\n";
        $body .= "Phone: $phone\n";
        $body .= "Date: $date\n";
        $body .= "Time: $time\n";
        $body .= "Guests: $guests\n";
        $body .= "Message: $message\n";
        
        wp_mail($to, $subject, $body);
        
        wp_send_json_success(array('message' => __('Reservation submitted successfully!', 'restaurant-pro')));
    } else {
        wp_send_json_error(array('message' => __('Failed to submit reservation. Please try again.', 'restaurant-pro')));
    }
}
add_action('wp_ajax_restaurant_reservation', 'restaurant_ajax_reservation');
add_action('wp_ajax_nopriv_restaurant_reservation', 'restaurant_ajax_reservation');

/**
 * Theme Activation Hook
 */
function restaurant_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Create default pages
    $pages = array(
        'Home' => 'home',
        'Menu' => 'menu',
        'Reservations' => 'reservations',
        'About' => 'about',
        'Contact' => 'contact'
    );
    
    foreach ($pages as $title => $slug) {
        if (!get_page_by_path($slug)) {
            wp_insert_post(array(
                'post_title' => $title,
                'post_name' => $slug,
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page'
            ));
        }
    }
    
    // Set front page
    $home_page = get_page_by_path('home');
    if ($home_page) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $home_page->ID);
    }
}
add_action('after_switch_theme', 'restaurant_theme_activation');

/**
 * Default Menu Fallback
 */
function restaurant_default_menu() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . home_url('/') . '">' . __('Home', 'restaurant-pro') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('menu'))) . '">' . __('Menu', 'restaurant-pro') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('about'))) . '">' . __('About', 'restaurant-pro') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('contact'))) . '">' . __('Contact', 'restaurant-pro') . '</a></li>';
    echo '<li><a href="#reservation-form">' . __('Reservations', 'restaurant-pro') . '</a></li>';
    echo '</ul>';
}

/**
 * Include additional files
 */
require_once RESTAURANT_THEME_PATH . '/inc/custom-widgets.php';
require_once RESTAURANT_THEME_PATH . '/inc/customizer.php';
require_once RESTAURANT_THEME_PATH . '/inc/security.php';
require_once RESTAURANT_THEME_PATH . '/inc/performance.php';

/**
 * Seed demo content for new installations (can be disabled with RESTAURANT_DISABLE_DEMO_CONTENT)
 */
function restaurant_seed_default_content() {
    if (defined('RESTAURANT_DISABLE_DEMO_CONTENT') && RESTAURANT_DISABLE_DEMO_CONTENT) {
        return;
    }

    if (get_option('restaurant_demo_content_seeded')) {
        return;
    }

    if (!post_type_exists('dish') || !post_type_exists('event') || !post_type_exists('testimonial')) {
        return;
    }

    // Create dish categories if missing.
    $dish_terms = array(
        'appetizers'   => __('Appetizers', 'restaurant-pro'),
        'main-courses' => __('Main Courses', 'restaurant-pro'),
        'desserts'     => __('Desserts', 'restaurant-pro'),
    );

    foreach ($dish_terms as $slug => $name) {
        if (!term_exists($slug, 'dish_category')) {
            wp_insert_term($name, 'dish_category', array('slug' => $slug));
        }
    }

    if (!restaurant_post_type_has_content('dish')) {
        $dishes = array(
            array(
                'title'       => __('Citrus Cured Salmon', 'restaurant-pro'),
                'content'     => __('Huisgerookte zalm met dille, krokante kappertjes en citroenmayonaise.', 'restaurant-pro'),
                'price'       => '8.50',
                'ingredients' => __('Zalm, dille, citroen, kappertjes, huisgebakken brioche.', 'restaurant-pro'),
                'allergens'   => __('Vis, gluten, ei, lactose', 'restaurant-pro'),
                'category'    => 'appetizers',
                'featured'    => true,
            ),
            array(
                'title'       => __('Slow-cooked Short Rib', 'restaurant-pro'),
                'content'     => __('Botermals rundvlees met geroosterde groenten, aardappelmousseline en rode wijnsaus.', 'restaurant-pro'),
                'price'       => '17.50',
                'ingredients' => __('Rundvlees, wortel, pastinaak, aardappel, rode wijn.', 'restaurant-pro'),
                'allergens'   => __('Selderij, lactose, sulfiet', 'restaurant-pro'),
                'category'    => 'main-courses',
                'featured'    => true,
            ),
            array(
                'title'       => __('Dutch Apple Crumble', 'restaurant-pro'),
                'content'     => __('Warme appeltaart met kaneel, vanille-ijs en karamelsaus.', 'restaurant-pro'),
                'price'       => '6.00',
                'ingredients' => __('Appel, kaneel, roomboter, bloem, vanille.', 'restaurant-pro'),
                'allergens'   => __('Gluten, lactose', 'restaurant-pro'),
                'category'    => 'desserts',
                'featured'    => false,
            ),
        );

        foreach ($dishes as $dish) {
            $dish_id = wp_insert_post(array(
                'post_title'   => $dish['title'],
                'post_content' => $dish['content'],
                'post_status'  => 'publish',
                'post_type'    => 'dish',
            ));

            if ($dish_id && !is_wp_error($dish_id)) {
                wp_set_object_terms($dish_id, $dish['category'], 'dish_category', false);
                update_post_meta($dish_id, 'dish_price', $dish['price']);
                update_post_meta($dish_id, 'dish_ingredients', $dish['ingredients']);
                update_post_meta($dish_id, 'dish_allergens', $dish['allergens']);
                update_post_meta($dish_id, 'featured_dish', $dish['featured'] ? '1' : '');
            }
        }
    }

    if (!restaurant_post_type_has_content('event')) {
        $today = current_time('Y-m-d');

        $events = array(
            array(
                'title'       => __('Verrassingsmenu van de Chef', 'restaurant-pro'),
                'content'     => __('Een avond vol gastronomische verrassingen met ingrediënten uit het seizoen.', 'restaurant-pro'),
                'date'        => date('Y-m-d', strtotime($today . ' +7 days')),
                'time'        => '17:30',
                'location'    => __('Restaurant Schoolbord, Leiden', 'restaurant-pro'),
            ),
            array(
                'title'       => __('Masterclass Desserts', 'restaurant-pro'),
                'content'     => __('Leer van onze studenten hoe je een perfect dessert opbouwt in drie technieken.', 'restaurant-pro'),
                'date'        => date('Y-m-d', strtotime($today . ' +21 days')),
                'time'        => '16:00',
                'location'    => __('Praktijkkeuken Lammenschans', 'restaurant-pro'),
            ),
        );

        foreach ($events as $event) {
            $event_id = wp_insert_post(array(
                'post_title'   => $event['title'],
                'post_content' => $event['content'],
                'post_status'  => 'publish',
                'post_type'    => 'event',
            ));

            if ($event_id && !is_wp_error($event_id)) {
                update_post_meta($event_id, 'event_date', $event['date']);
                update_post_meta($event_id, 'event_time', $event['time']);
                update_post_meta($event_id, 'event_location', $event['location']);
            }
        }
    }

    if (!restaurant_post_type_has_content('testimonial')) {
        $testimonials = array(
            array(
                'title'       => __('Annemieke Jansen', 'restaurant-pro'),
                'content'     => __('“Wat een geweldige avond! De studenten zijn ontzettend gastvrij en het eten was van topniveau.”', 'restaurant-pro'),
                'role'        => __('Gast uit Leiden', 'restaurant-pro'),
                'rating'      => 5,
            ),
            array(
                'title'       => __('Michel de Bruijn', 'restaurant-pro'),
                'content'     => __('“De sfeer is uniek en de kwaliteit van de gerechten overtreft iedere keer weer mijn verwachtingen.”', 'restaurant-pro'),
                'role'        => __('Stamgast', 'restaurant-pro'),
                'rating'      => 4,
            ),
            array(
                'title'       => __('Saskia van der Velde', 'restaurant-pro'),
                'content'     => __('“Prachtig om te zien hoeveel passie de studenten hebben. Een echte aanrader voor een culinaire avond.”', 'restaurant-pro'),
                'role'        => __('Food blogger', 'restaurant-pro'),
                'rating'      => 5,
            ),
        );

        foreach ($testimonials as $testimonial) {
            $testimonial_id = wp_insert_post(array(
                'post_title'   => $testimonial['title'],
                'post_content' => $testimonial['content'],
                'post_status'  => 'publish',
                'post_type'    => 'testimonial',
                'post_excerpt' => $testimonial['content'],
            ));

            if ($testimonial_id && !is_wp_error($testimonial_id)) {
                update_post_meta($testimonial_id, 'testimonial_role', $testimonial['role']);
                update_post_meta($testimonial_id, 'testimonial_rating', $testimonial['rating']);
            }
        }
    }

    update_option('restaurant_demo_content_seeded', 1);
}
add_action('init', 'restaurant_seed_default_content', 20);

function restaurant_post_type_has_content($post_type) {
    $counts = wp_count_posts($post_type);
    if (!$counts) {
        return false;
    }

    return (int) $counts->publish > 0;
}
