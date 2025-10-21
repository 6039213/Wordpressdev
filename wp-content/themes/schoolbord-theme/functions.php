<?php
/**
 * Basale functies voor het Restaurant Schoolbord thema.
 *
 * In dit bestand worden thema‑ondersteuning, menu’s, widgets en scripts geregistreerd.
 */

// Voer acties uit nadat het thema is ingeladen.
function schoolbord_theme_setup() {
    // Vertaaltekst ondersteunen (indien nodig).
    load_theme_textdomain( 'restaurant-schoolbord', get_template_directory() . '/languages' );

    // RSS feed links toevoegen in de head.
    add_theme_support( 'automatic-feed-links' );

    // Laat WordPress de paginatitel beheren.
    add_theme_support( 'title-tag' );

    // Ondersteun een custom logo.
    add_theme_support( 'custom-logo' );

    // Ondersteun uitgelichte afbeeldingen (featured images).
    add_theme_support( 'post-thumbnails' );

    // HTML5 markup ondersteuning voor bepaalde elementen.
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

    // Registreer navigatiemenu’s.
    register_nav_menus( array(
        'primary' => __( 'Hoofdmenu', 'restaurant-schoolbord' ),
        'footer'  => __( 'Footermenu', 'restaurant-schoolbord' ),
        'sidebar' => __( 'Sidebar menu', 'restaurant-schoolbord' ),
    ) );
}
add_action( 'after_setup_theme', 'schoolbord_theme_setup' );

/**
 * Enqueue stijlbladen en scripts.
 */
function schoolbord_enqueue_scripts() {
    // Hoofd stylesheet.
    wp_enqueue_style( 'restaurant-schoolbord-style', get_stylesheet_uri(), array(), filemtime( get_template_directory() . '/style.css' ) );

    // Hoofd script (plaats in footer).
    wp_enqueue_script( 'restaurant-schoolbord-script', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/main.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'schoolbord_enqueue_scripts' );

/**
 * Registreer widgetgebieden.
 */
function schoolbord_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'restaurant-schoolbord' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Voeg hier widgets toe voor de zijbalk.', 'restaurant-schoolbord' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'schoolbord_widgets_init' );

/**
 * Verwerk verzonden formulieren voor contact en reserveren.
 *
 * Dit is een eenvoudige aanpak die mails verstuurt naar het sitebeheerder e‑mailadres.
 */
function schoolbord_handle_form_submission() {
    // Controleer of het een POST verzoek is vanaf een van onze formulieren.
    if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
        return;
    }

    // Contactformulier verwerken.
    if ( isset( $_POST['schoolbord_contact_submit'] ) ) {
        $first_name = sanitize_text_field( $_POST['first_name'] ?? '' );
        $last_name  = sanitize_text_field( $_POST['last_name'] ?? '' );
        $email      = sanitize_email( $_POST['email'] ?? '' );
        $message    = sanitize_textarea_field( $_POST['message'] ?? '' );
        $to         = get_option( 'admin_email' );
        $subject    = sprintf( __( 'Contactbericht van %s %s', 'restaurant-schoolbord' ), $first_name, $last_name );
        $body       = "Naam: $first_name $last_name\nE‑mail: $email\n\nBericht:\n$message";
        $headers    = array( 'Content-Type: text/plain; charset=UTF-8', "Reply-To: $first_name $last_name <$email>" );
        wp_mail( $to, $subject, $body, $headers );
        // Zet een tijdelijke optie om succesmelding weer te geven.
        set_transient( 'schoolbord_contact_success', true, 30 );
        wp_safe_redirect( esc_url_raw( $_SERVER['REQUEST_URI'] ) );
        exit;
    }

    // Reserveringsformulier verwerken.
    if ( isset( $_POST['schoolbord_reserveren_submit'] ) ) {
        $first_name = sanitize_text_field( $_POST['first_name'] ?? '' );
        $last_name  = sanitize_text_field( $_POST['last_name'] ?? '' );
        $email      = sanitize_email( $_POST['email'] ?? '' );
        $date       = sanitize_text_field( $_POST['date'] ?? '' );
        $time       = sanitize_text_field( $_POST['time'] ?? '' );
        $people     = intval( $_POST['people'] ?? 0 );
        $comment    = sanitize_textarea_field( $_POST['comment'] ?? '' );
        $to         = get_option( 'admin_email' );
        $subject    = sprintf( __( 'Nieuwe reservering van %s %s', 'restaurant-schoolbord' ), $first_name, $last_name );
        $body       = "Naam: $first_name $last_name\nE‑mail: $email\nDatum: $date\nTijd: $time\nAantal personen: $people\n\nOpmerkingen:\n$comment";
        $headers    = array( 'Content-Type: text/plain; charset=UTF-8', "Reply-To: $first_name $last_name <$email>" );
        wp_mail( $to, $subject, $body, $headers );
        set_transient( 'schoolbord_reserveren_success', true, 30 );
        wp_safe_redirect( esc_url_raw( $_SERVER['REQUEST_URI'] ) );
        exit;
    }
}
add_action( 'template_redirect', 'schoolbord_handle_form_submission' );

function schoolbord_block_one_block_init() {
    register_block_type( __DIR__ . '/build/blockone' );
}
add_action( 'init', 'schoolbord_block_one_block_init' );



?>