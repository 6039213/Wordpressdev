<?php
/**
 * Template Name: Contact
 *
 * Toont een contactformulier zodat bezoekers contact kunnen opnemen. Bij
 * succesvolle verzending wordt een melding weergegeven. De verzonden gegevens
 * worden per e‑mail naar de sitebeheerder gestuurd via functions.php.
 */

get_header();
?>

<main id="main" class="site-main contact-page" role="main">
    <h1 class="page-title"><?php the_title(); ?></h1>
    <?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>

    <?php
    // Toon succesmelding als het contactformulier succesvol is verzonden.
    if ( get_transient( 'schoolbord_contact_success' ) ) :
        delete_transient( 'schoolbord_contact_success' );
        ?>
        <div class="notice notice-success">
            <p><?php esc_html_e( 'Dank voor je bericht! We nemen zo snel mogelijk contact met je op.', 'restaurant-schoolbord' ); ?></p>
        </div>
    <?php endif; ?>

    <form class="contact-form" method="post" action="">
        <p>
            <label for="first_name"><?php esc_html_e( 'Voornaam', 'restaurant-schoolbord' ); ?></label><br>
            <input type="text" name="first_name" id="first_name" required>
        </p>
        <p>
            <label for="last_name"><?php esc_html_e( 'Achternaam', 'restaurant-schoolbord' ); ?></label><br>
            <input type="text" name="last_name" id="last_name" required>
        </p>
        <p>
            <label for="email"><?php esc_html_e( 'E‑mail', 'restaurant-schoolbord' ); ?></label><br>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <label for="message"><?php esc_html_e( 'Bericht', 'restaurant-schoolbord' ); ?></label><br>
            <textarea name="message" id="message" rows="6" required></textarea>
        </p>
        <p>
            <input type="hidden" name="schoolbord_contact_submit" value="1">
            <button type="submit" class="button"><?php esc_html_e( 'Verzend', 'restaurant-schoolbord' ); ?></button>
        </p>
    </form>
</main><!-- .site-main -->

<?php
get_footer();
?>