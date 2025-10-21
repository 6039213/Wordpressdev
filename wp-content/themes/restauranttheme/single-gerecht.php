<?php get_header(); ?>
<?php
while ( have_posts() ){
    the_post(); ?>
    <article class="post">
        <h2 class="entry-title"><?php the_title(); ?></h2>
        <article class="entry-content">
        <?php the_content(); ?>
        </article>
    </article>
<?php
}
?>
<?php get_footer(); ?>