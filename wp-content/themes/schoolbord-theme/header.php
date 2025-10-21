<?php
/**
 * De header van het thema.
 *
 * Dit sjabloon toont het <head> gedeelte en het begin van de body, inclusief
 * sitebranding en het hoofdmenu.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header" role="banner">
    <div class="site-branding">
        <?php
        // Toon een logo indien beschikbaar, anders site‑titel.
        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
            the_custom_logo();
        } else {
            ?>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            <p class="site-description"><?php bloginfo( 'description' ); ?></p>
            <?php
        }
        ?>
    </div><!-- .site-branding -->
    <nav id="site-navigation" class="main-navigation" role="navigation">
        <?php
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
        ) );
        ?>
    </nav><!-- .main-navigation -->
</header><!-- .site-header -->

<div id="content" class="site-content">