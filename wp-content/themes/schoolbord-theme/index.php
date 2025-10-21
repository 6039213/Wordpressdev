<?php
/**
 * Het standaard templatebestand dat posts toont wanneer er geen specifieker
 * template wordt gevonden. Voor de blogpagina wordt dit bestand gebruikt.
 */

get_header();
?>

<main id="main" class="site-main" role="main">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-meta">
                        <span class="posted-on"><?php echo get_the_date(); ?></span>
                        <span class="byline"> <?php echo esc_html__( 'door', 'restaurant-schoolbord' ); ?> <?php the_author(); ?></span>
                    </div>
                </header>
                <div class="entry-content">
                    <?php the_excerpt(); ?>
                </div>
            </article>
        <?php endwhile; ?>

        <div class="posts-navigation">
            <?php
            the_posts_pagination( array(
                'prev_text' => __( 'Vorige pagina', 'restaurant-schoolbord' ),
                'next_text' => __( 'Volgende pagina', 'restaurant-schoolbord' ),
            ) );
            ?>
        </div>
    <?php else : ?>
        <p><?php esc_html_e( 'Geen berichten gevonden.', 'restaurant-schoolbord' ); ?></p>
    <?php endif; ?>
</main><!-- #main -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>