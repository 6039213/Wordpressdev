<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-container">
        <div class="header-top">
            <div class="container">
                <div class="header-info">
                    <div class="restaurant-info">
                        <span class="phone">
                            <i class="dashicons dashicons-phone"></i>
                            <?php echo get_theme_mod('restaurant_phone', '+31 123 456 789'); ?>
                        </span>
                        <span class="address">
                            <i class="dashicons dashicons-location"></i>
                            <?php echo get_theme_mod('restaurant_address', '123 Restaurant Street, Amsterdam'); ?>
                        </span>
                    </div>
                    <div class="header-social">
                        <a href="#" class="social-link"><i class="dashicons dashicons-facebook"></i></a>
                        <a href="#" class="social-link"><i class="dashicons dashicons-instagram"></i></a>
                        <a href="#" class="social-link"><i class="dashicons dashicons-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="header-main">
            <div class="container">
                <div class="header-content">
                    <div class="site-branding">
                        <?php if (has_custom_logo()) : ?>
                            <div class="site-logo">
                                <?php the_custom_logo(); ?>
                            </div>
                        <?php else : ?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) : ?>
                                <p class="site-description"><?php echo $description; ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'restaurant-pro'); ?>">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="menu-toggle-text"><?php _e('Menu', 'restaurant-pro'); ?></span>
                            <span class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'menu_class'     => 'nav-menu',
                            'fallback_cb'    => 'restaurant_default_menu',
                        ));
                        ?>
                    </nav>
                    
                    <div class="header-actions">
                        <a href="#reservation-form" class="reservation-btn">
                            <i class="dashicons dashicons-calendar-alt"></i>
                            <?php _e('Make Reservation', 'restaurant-pro'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="site-main">
