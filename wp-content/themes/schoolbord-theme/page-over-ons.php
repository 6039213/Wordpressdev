<?php
/**
 * Template Name: Over ons
 *
 * Deze template wordt gebruikt voor de "Over ons" pagina van Restaurant
 * Schoolbord. Het toont de pagina-inhoud en een overzicht van eventuele
 * subpagina’s (bijvoorbeeld Team en Vacatures). Voor elke subpagina wordt de
 * titel, een uitgelichte afbeelding, een verkorte tekst en een link
 * weergegeven.
 */

get_header();
?>

<main id="main" class="site-main overons-page" role="main">
    <h1 class="page-title"><?php the_title(); ?></h1>
    <?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>

    <?php
    // Haal subpagina’s op van deze pagina
    $child_pages = new WP_Query( array(
        'post_type'      => 'page',
        'post_parent'    => get_the_ID(),
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'posts_per_page' => -1,
    ) );
    if ( $child_pages->have_posts() ) :
        echo '<div class="overons-subpages">';
        while ( $child_pages->have_posts() ) : $child_pages->the_post(); ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class( 'overons-item' ); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="overons-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'medium' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <h2 class="overons-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="overons-excerpt">
                    <?php the_excerpt(); ?>
                </div>
                <a class="button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Lees meer', 'restaurant-schoolbord' ); ?></a>
            </article>
        <?php endwhile;
        echo '</div>';
        wp_reset_postdata();
    endif;
    ?>
</main><!-- .site-main -->

<?php get_footer(); ?>