<?php
/**
 * De footer van het thema.
 *
 * Dit sjabloon sluit de content af en toont de footer met contactgegevens en
 * social media links.
 */
?>

        </div><!-- #content -->

        <footer class="site-footer" role="contentinfo">
            <div class="footer-logo">
                <?php
                // Kleine variant van het logo tonen indien beschikbaar.
                if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                    the_custom_logo();
                }
                ?>
            </div>
            <div class="footer-info">
                <p><?php echo esc_html__( 'Restaurant Schoolbord, Leiden', 'restaurant-schoolbord' ); ?></p>
                <p><?php echo esc_html__( 'Adres: Bethlehem 47, 2312 GP Leiden', 'restaurant-schoolbord' ); ?></p>
            </div>
            <div class="footer-social">
                <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">Instagram</a>
                <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer">TikTok</a>
            </div>
        </footer><!-- .site-footer -->

        <?php wp_footer(); ?>

    </body>
</html>