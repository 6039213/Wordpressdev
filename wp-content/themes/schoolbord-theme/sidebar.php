<?php
/**
 * De zijbalk met widgetgebied.
 *
 * Wordt opgenomen in pagina’s indien er een zijbalk is geregistreerd.
 */
?>

<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
    <aside id="secondary" class="sidebar widget-area" role="complementary">
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </aside><!-- #secondary -->
<?php endif; ?>