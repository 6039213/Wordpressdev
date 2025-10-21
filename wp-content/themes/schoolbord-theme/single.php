<?php
/**
 * Template voor een enkel bericht (post).
 */

get_header();
?>

<main id="main" class="site-main" role="main">
    <?php
    while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class="entry-meta">
                    <span class="posted-on"><?php echo get_the_date(); ?></span>
                    <span class="byline"> <?php echo esc_html__( 'door', 'restaurant-schoolbord' ); ?> <?php the_author(); ?></span>
                </div>
            </header>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="entry-thumbnail">
                    <?php the_post_thumbnail( 'large' ); ?>
                </div>
            <?php endif; ?>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; // end of the loop. ?>
</main><!-- #main -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>