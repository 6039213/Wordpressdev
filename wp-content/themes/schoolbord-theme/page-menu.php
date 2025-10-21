<?php
/**
 * Template Name: Menu
 *
 * Toont de menukaart met gerechten en prijzen. De inhoud van de pagina kan
 * worden beheerd via de WordPress editor. Gebruik bijvoorbeeld headings en
 * lijsten om voor-, hoofd- en nagerechten overzichtelijk weer te geven.
 */

get_header();
?>

<main id="main" class="site-main menu-page" role="main">
    <h1 class="page-title"><?php the_title(); ?></h1>
    <?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>
</main><!-- .site-main -->

<?php
get_footer();
?>