<?php
/**
 * Customizer Settings for Restaurant Pro Theme
 * 
 * @package RestaurantPro
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add customizer settings
 */
function restaurant_customizer_settings($wp_customize) {
    
    // Colors Section
    $wp_customize->add_section('restaurant_colors', array(
        'title' => __('Theme Colors', 'restaurant-pro'),
        'priority' => 30,
    ));
    
    // Primary Color
    $wp_customize->add_setting('restaurant_primary_color', array(
        'default' => '#7bdcb5',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'restaurant_primary_color', array(
        'label' => __('Primary Color', 'restaurant-pro'),
        'section' => 'restaurant_colors',
    )));
    
    // Secondary Color
    $wp_customize->add_setting('restaurant_secondary_color', array(
        'default' => '#f6a4a4',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'restaurant_secondary_color', array(
        'label' => __('Secondary Color', 'restaurant-pro'),
        'section' => 'restaurant_colors',
    )));
    
    // Text Color
    $wp_customize->add_setting('restaurant_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'restaurant_text_color', array(
        'label' => __('Text Color', 'restaurant-pro'),
        'section' => 'restaurant_colors',
    )));
    
    // Header Section
    $wp_customize->add_section('restaurant_header', array(
        'title' => __('Header Settings', 'restaurant-pro'),
        'priority' => 35,
    ));
    
    // Header Background Color
    $wp_customize->add_setting('restaurant_header_bg', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'restaurant_header_bg', array(
        'label' => __('Header Background Color', 'restaurant-pro'),
        'section' => 'restaurant_header',
    )));
    
    // Header Text Color
    $wp_customize->add_setting('restaurant_header_text', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'restaurant_header_text', array(
        'label' => __('Header Text Color', 'restaurant-pro'),
        'section' => 'restaurant_header',
    )));
    
    // Footer Section
    $wp_customize->add_section('restaurant_footer', array(
        'title' => __('Footer Settings', 'restaurant-pro'),
        'priority' => 40,
    ));
    
    // Footer Background Color
    $wp_customize->add_setting('restaurant_footer_bg', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'restaurant_footer_bg', array(
        'label' => __('Footer Background Color', 'restaurant-pro'),
        'section' => 'restaurant_footer',
    )));
    
    // Footer Text Color
    $wp_customize->add_setting('restaurant_footer_text', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'restaurant_footer_text', array(
        'label' => __('Footer Text Color', 'restaurant-pro'),
        'section' => 'restaurant_footer',
    )));
    
    // Social Media Section
    $wp_customize->add_section('restaurant_social', array(
        'title' => __('Social Media', 'restaurant-pro'),
        'priority' => 45,
    ));
    
    // Facebook URL
    $wp_customize->add_setting('restaurant_facebook', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('restaurant_facebook', array(
        'label' => __('Facebook URL', 'restaurant-pro'),
        'section' => 'restaurant_social',
        'type' => 'url',
    ));
    
    // Instagram URL
    $wp_customize->add_setting('restaurant_instagram', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('restaurant_instagram', array(
        'label' => __('Instagram URL', 'restaurant-pro'),
        'section' => 'restaurant_social',
        'type' => 'url',
    ));
    
    // Twitter URL
    $wp_customize->add_setting('restaurant_twitter', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('restaurant_twitter', array(
        'label' => __('Twitter URL', 'restaurant-pro'),
        'section' => 'restaurant_social',
        'type' => 'url',
    ));
    
    // LinkedIn URL
    $wp_customize->add_setting('restaurant_linkedin', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('restaurant_linkedin', array(
        'label' => __('LinkedIn URL', 'restaurant-pro'),
        'section' => 'restaurant_social',
        'type' => 'url',
    ));
    
    // Typography Section
    $wp_customize->add_section('restaurant_typography', array(
        'title' => __('Typography', 'restaurant-pro'),
        'priority' => 50,
    ));
    
    // Heading Font
    $wp_customize->add_setting('restaurant_heading_font', array(
        'default' => 'Playfair Display',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('restaurant_heading_font', array(
        'label' => __('Heading Font', 'restaurant-pro'),
        'section' => 'restaurant_typography',
        'type' => 'select',
        'choices' => array(
            'Playfair Display' => 'Playfair Display',
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
        ),
    ));
    
    // Body Font
    $wp_customize->add_setting('restaurant_body_font', array(
        'default' => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('restaurant_body_font', array(
        'label' => __('Body Font', 'restaurant-pro'),
        'section' => 'restaurant_typography',
        'type' => 'select',
        'choices' => array(
            'Inter' => 'Inter',
            'Playfair Display' => 'Playfair Display',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
        ),
    ));
    
    // Layout Section
    $wp_customize->add_section('restaurant_layout', array(
        'title' => __('Layout Settings', 'restaurant-pro'),
        'priority' => 55,
    ));
    
    // Container Width
    $wp_customize->add_setting('restaurant_container_width', array(
        'default' => '1200',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('restaurant_container_width', array(
        'label' => __('Container Width (px)', 'restaurant-pro'),
        'section' => 'restaurant_layout',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 800,
            'max' => 1600,
            'step' => 50,
        ),
    ));
    
    // Sidebar Position
    $wp_customize->add_setting('restaurant_sidebar_position', array(
        'default' => 'right',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('restaurant_sidebar_position', array(
        'label' => __('Sidebar Position', 'restaurant-pro'),
        'section' => 'restaurant_layout',
        'type' => 'select',
        'choices' => array(
            'left' => __('Left', 'restaurant-pro'),
            'right' => __('Right', 'restaurant-pro'),
            'none' => __('No Sidebar', 'restaurant-pro'),
        ),
    ));
    
    // Performance Section
    $wp_customize->add_section('restaurant_performance', array(
        'title' => __('Performance Settings', 'restaurant-pro'),
        'priority' => 60,
    ));
    
    // Enable Lazy Loading
    $wp_customize->add_setting('restaurant_lazy_loading', array(
        'default' => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    
    $wp_customize->add_control('restaurant_lazy_loading', array(
        'label' => __('Enable Lazy Loading', 'restaurant-pro'),
        'section' => 'restaurant_performance',
        'type' => 'checkbox',
    ));
    
    // Enable Image Optimization
    $wp_customize->add_setting('restaurant_image_optimization', array(
        'default' => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    
    $wp_customize->add_control('restaurant_image_optimization', array(
        'label' => __('Enable Image Optimization', 'restaurant-pro'),
        'section' => 'restaurant_performance',
        'type' => 'checkbox',
    ));
    
    // Enable Caching
    $wp_customize->add_setting('restaurant_enable_caching', array(
        'default' => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    
    $wp_customize->add_control('restaurant_enable_caching', array(
        'label' => __('Enable Caching', 'restaurant-pro'),
        'section' => 'restaurant_performance',
        'type' => 'checkbox',
    ));
}

add_action('customize_register', 'restaurant_customizer_settings');

/**
 * Generate custom CSS from customizer settings
 */
function restaurant_customizer_css() {
    $primary_color = get_theme_mod('restaurant_primary_color', '#7bdcb5');
    $secondary_color = get_theme_mod('restaurant_secondary_color', '#f6a4a4');
    $text_color = get_theme_mod('restaurant_text_color', '#333333');
    $header_bg = get_theme_mod('restaurant_header_bg', '#ffffff');
    $header_text = get_theme_mod('restaurant_header_text', '#333333');
    $footer_bg = get_theme_mod('restaurant_footer_bg', '#333333');
    $footer_text = get_theme_mod('restaurant_footer_text', '#ffffff');
    $container_width = get_theme_mod('restaurant_container_width', 1200);
    $heading_font = get_theme_mod('restaurant_heading_font', 'Playfair Display');
    $body_font = get_theme_mod('restaurant_body_font', 'Inter');
    
    $css = "
    :root {
        --color-primary: {$primary_color};
        --color-secondary: {$secondary_color};
        --color-text: {$text_color};
        --color-header-bg: {$header_bg};
        --color-header-text: {$header_text};
        --color-footer-bg: {$footer_bg};
        --color-footer-text: {$footer_text};
        --font-heading: '{$heading_font}', serif;
        --font-body: '{$body_font}', sans-serif;
    }
    
    .container {
        max-width: {$container_width}px;
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-heading);
    }
    
    body {
        font-family: var(--font-body);
        color: var(--color-text);
    }
    
    .site-header {
        background-color: var(--color-header-bg);
        color: var(--color-header-text);
    }
    
    .site-footer {
        background-color: var(--color-footer-bg);
        color: var(--color-footer-text);
    }
    
    .button, .wp-block-button__link {
        background-color: var(--color-primary);
    }
    
    .button:hover, .wp-block-button__link:hover {
        background-color: var(--color-secondary);
    }
    ";
    
    wp_add_inline_style('restaurant-custom-style', $css);
}

add_action('wp_enqueue_scripts', 'restaurant_customizer_css');
