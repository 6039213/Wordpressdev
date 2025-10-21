<?php
/**
 * Template Name: Reserveren
 *
 * Deze template biedt een reserveringsformulier waarmee gasten eenvoudig een
 * tafel kunnen reserveren. De ingevulde gegevens worden per e‑mail verzonden
 * naar de sitebeheerder via de functies in functions.php. Na succesvolle
 * verzending wordt een bevestiging getoond.
 */

get_header();
?>

<main id="main" class="site-main reserveren-page" role="main">
    <h1 class="page-title"><?php the_title(); ?></h1>
    <?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>

    <?php
    // Melding weergeven wanneer reservering succesvol is verzonden.
    if ( get_transient( 'schoolbord_reserveren_success' ) ) :
        delete_transient( 'schoolbord_reserveren_success' );
        ?>
        <div class="notice notice-success">
            <p><?php esc_html_e( 'Bedankt voor je reservering! We sturen je spoedig een bevestiging.', 'restaurant-schoolbord' ); ?></p>
        </div>
    <?php endif; ?>

    <form class="reserveren-form" method="post" action="">
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
            <label for="date"><?php esc_html_e( 'Datum', 'restaurant-schoolbord' ); ?></label><br>
            <input type="date" name="date" id="date" required>
        </p>
        <p>
            <label for="time"><?php esc_html_e( 'Tijd', 'restaurant-schoolbord' ); ?></label><br>
            <input type="time" name="time" id="time" required>
        </p>
        <p>
            <label for="people"><?php esc_html_e( 'Aantal personen', 'restaurant-schoolbord' ); ?></label><br>
            <input type="number" name="people" id="people" min="1" max="20" required>
        </p>
        <p>
            <label for="comment"><?php esc_html_e( 'Opmerkingen', 'restaurant-schoolbord' ); ?></label><br>
            <textarea name="comment" id="comment" rows="4"></textarea>
        </p>
        <p>
            <input type="hidden" name="schoolbord_reserveren_submit" value="1">
            <button type="submit" class="button"><?php esc_html_e( 'Reserveer', 'restaurant-schoolbord' ); ?></button>
        </p>
    </form>
</main><!-- .site-main -->

<?php
get_footer();
?>