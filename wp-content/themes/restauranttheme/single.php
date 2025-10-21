<?php get_header(); ?>
<?php
while ( have_posts() ){
    the_post(); ?>
    <h1>Dit is een blog post</h1>
    <section class="post">
        <h2 class="entry-title"><?php the_title(); ?></h2>
        <article class="entry-content">
        <?php the_content(); ?>
        </article>
    </section>
<?php
}
?>
<?php get_footer(); ?>