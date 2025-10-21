<?php get_header(); ?>
<?php
while ( have_posts() ){
    the_post(); ?>
    <section class="content-container">
    <div><?php the_content(); ?></div>
    </section>
<?php
}
?>
<?php get_footer(); ?>