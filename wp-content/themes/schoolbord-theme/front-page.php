<?php
/**
 * Template voor de homepage.
 *
 * Toont een slideshow met drie sfeerbeelden, een introductietekst en knoppen
 * naar het menu en de reserveringspagina. De inhoud van de pagina kan via
 * de WordPress editor worden beheerd.
 */

get_header();
?>

<div class="slider">
    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/slider1.png' ); ?>" alt="Sfeerbeeld 1">
    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/slider2.png' ); ?>" alt="Sfeerbeeld 2">
    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/slider3.png' ); ?>" alt="Sfeerbeeld 3">
</div>

<section class="home-intro">
    <?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>
    <div class="home-buttons">
        <?php
        // Zoek de pagina's voor Menu en Reserveren op basis van slug.
        $menu_page       = get_page_by_path( 'menu' );
        $reserveren_page = get_page_by_path( 'reserveren' );
        if ( $menu_page ) :
            ?>
            <a class="button" href="<?php echo esc_url( get_permalink( $menu_page->ID ) ); ?>"><?php esc_html_e( 'Bekijk het menu', 'restaurant-schoolbord' ); ?></a>
        <?php endif; ?>
        <?php if ( $reserveren_page ) : ?>
            <a class="button" href="<?php echo esc_url( get_permalink( $reserveren_page->ID ) ); ?>"><?php esc_html_e( 'Reserveer nu', 'restaurant-schoolbord' ); ?></a>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
?>