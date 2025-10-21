</main>

<footer class="site-footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-content">
                <div class="footer-widgets">
                    <div class="footer-widget">
                        <h3 class="footer-widget-title"><?php _e('About Our Restaurant', 'restaurant-pro'); ?></h3>
                        <p><?php echo get_theme_mod('restaurant_description', __('We are passionate about serving delicious, fresh food in a warm and welcoming atmosphere. Our restaurant has been a local favorite for years, offering an exceptional dining experience.', 'restaurant-pro')); ?></p>
                        <div class="footer-social">
                            <a href="#" class="social-link"><i class="dashicons dashicons-facebook"></i></a>
                            <a href="#" class="social-link"><i class="dashicons dashicons-instagram"></i></a>
                            <a href="#" class="social-link"><i class="dashicons dashicons-twitter"></i></a>
                            <a href="#" class="social-link"><i class="dashicons dashicons-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div class="footer-widget">
                        <h3 class="footer-widget-title"><?php _e('Quick Links', 'restaurant-pro'); ?></h3>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'footer-menu',
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </div>
                    
                    <div class="footer-widget">
                        <h3 class="footer-widget-title"><?php _e('Contact Info', 'restaurant-pro'); ?></h3>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="dashicons dashicons-location"></i>
                                <span><?php echo get_theme_mod('restaurant_address', '123 Restaurant Street, Amsterdam'); ?></span>
                            </div>
                            <div class="contact-item">
                                <i class="dashicons dashicons-phone"></i>
                                <span><?php echo get_theme_mod('restaurant_phone', '+31 123 456 789'); ?></span>
                            </div>
                            <div class="contact-item">
                                <i class="dashicons dashicons-email"></i>
                                <span><?php echo get_option('admin_email'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="footer-widget">
                        <h3 class="footer-widget-title"><?php _e('Opening Hours', 'restaurant-pro'); ?></h3>
                        <div class="opening-hours">
                            <?php 
                            $hours = get_theme_mod('restaurant_opening_hours', 
                                "Monday - Thursday: 11:00 - 22:00\nFriday - Saturday: 11:00 - 23:00\nSunday: 12:00 - 21:00"
                            );
                            echo nl2br(esc_html($hours));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'restaurant-pro'); ?></p>
                </div>
                <div class="footer-links">
                    <a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php _e('Privacy Policy', 'restaurant-pro'); ?></a>
                    <a href="#"><?php _e('Terms of Service', 'restaurant-pro'); ?></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>