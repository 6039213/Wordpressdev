<?php
/**
 * Dynamic sections for the front page.
 *
 * @package RestaurantPro
 */

if (!defined('ABSPATH')) {
    exit;
}

// Featured dishes (fallback to latest dishes if no featured ones).
$dish_query = new WP_Query([
    'post_type'      => 'dish',
    'posts_per_page' => 3,
    'meta_query'     => [
        [
            'key'     => 'featured_dish',
            'value'   => '1',
            'compare' => '=',
        ],
    ],
]);

if (!$dish_query->have_posts()) {
    wp_reset_postdata();
    $dish_query = new WP_Query([
        'post_type'      => 'dish',
        'posts_per_page' => 3,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);
}

$today       = current_time('Y-m-d');
$event_query = new WP_Query([
    'post_type'      => 'event',
    'posts_per_page' => 3,
    'meta_key'       => 'event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_type'      => 'DATE',
    'meta_query'     => [
        [
            'key'     => 'event_date',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATE',
        ],
    ],
]);

if (!$event_query->have_posts()) {
    wp_reset_postdata();
    $event_query = new WP_Query([
        'post_type'      => 'event',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
}

$testimonial_query = new WP_Query([
    'post_type'      => 'testimonial',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

$news_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'ignore_sticky_posts' => true,
]);
?>

<section class="section menu-preview">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e('Chef\'s specials', 'restaurant-pro'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Onze studenten presenteren iedere week nieuwe gerechten uit het seizoen.', 'restaurant-pro'); ?></p>
        </div>

        <?php if ($dish_query->have_posts()) : ?>
            <div class="dish-grid">
                <?php while ($dish_query->have_posts()) : $dish_query->the_post();
                    $price       = get_post_meta(get_the_ID(), 'dish_price', true);
                    $ingredients = get_post_meta(get_the_ID(), 'dish_ingredients', true);
                    $allergens   = get_post_meta(get_the_ID(), 'dish_allergens', true);
                    ?>
                    <article class="dish-card">
                        <div class="dish-card-header">
                            <h3 class="dish-card-title"><?php the_title(); ?></h3>
                            <?php if ($price) : ?>
                                <span class="dish-card-price">&euro;<?php echo esc_html(number_format((float) $price, 2, ',', '.')); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="dish-card-content">
                            <?php the_excerpt(); ?>
                        </div>

                        <?php if ($ingredients) : ?>
                            <p class="dish-card-meta"><strong><?php esc_html_e('Ingrediënten:', 'restaurant-pro'); ?></strong> <?php echo esc_html($ingredients); ?></p>
                        <?php endif; ?>
                        <?php if ($allergens) : ?>
                            <p class="dish-card-meta"><strong><?php esc_html_e('Allergenen:', 'restaurant-pro'); ?></strong> <?php echo esc_html($allergens); ?></p>
                        <?php endif; ?>

                        <footer class="dish-card-footer">
                            <span class="dish-card-taxonomy"><?php echo wp_kses_post(get_the_term_list(get_the_ID(), 'dish_category', '', ', ')); ?></span>
                        </footer>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="no-content-message"><?php esc_html_e('Voeg gerechten toe via het menu “Dishes” om ze hier te tonen.', 'restaurant-pro'); ?></p>
        <?php endif; ?>

        <div class="menu-preview-cta">
            <a class="btn btn-secondary" href="<?php echo esc_url(get_permalink(get_page_by_path('menu'))); ?>">
                <span class="dashicons dashicons-book" aria-hidden="true"></span>
                <?php esc_html_e('Bekijk het volledige menu', 'restaurant-pro'); ?>
            </a>
        </div>
    </div>
</section>

<?php wp_reset_postdata(); ?>

<section class="section events-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e('Aankomende evenementen', 'restaurant-pro'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Workshops en thema-avonden begeleid door onze studenten.', 'restaurant-pro'); ?></p>
        </div>

        <?php if ($event_query->have_posts()) : ?>
            <div class="events-grid">
                <?php while ($event_query->have_posts()) : $event_query->the_post();
                    $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                    $event_time = get_post_meta(get_the_ID(), 'event_time', true);
                    $location   = get_post_meta(get_the_ID(), 'event_location', true);
                    $date_display = $event_date ? date_i18n(get_option('date_format'), strtotime($event_date)) : '';
                    ?>
                    <article class="event-card">
                        <div class="event-card-date">
                            <span class="event-card-day"><?php echo esc_html(date_i18n('j', strtotime($event_date ?: current_time('mysql')))); ?></span>
                            <span class="event-card-month"><?php echo esc_html(date_i18n('M', strtotime($event_date ?: current_time('mysql')))); ?></span>
                        </div>

                        <div class="event-card-body">
                            <h3 class="event-card-title"><?php the_title(); ?></h3>
                            <p class="event-card-meta">
                                <span><span class="dashicons dashicons-calendar-alt" aria-hidden="true"></span> <?php echo esc_html($date_display); ?></span>
                                <?php if ($event_time) : ?>
                                    <span><span class="dashicons dashicons-clock" aria-hidden="true"></span> <?php echo esc_html($event_time); ?></span>
                                <?php endif; ?>
                                <?php if ($location) : ?>
                                    <span><span class="dashicons dashicons-location" aria-hidden="true"></span> <?php echo esc_html($location); ?></span>
                                <?php endif; ?>
                            </p>
                            <div class="event-card-content"><?php the_excerpt(); ?></div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="no-content-message"><?php esc_html_e('Plan nieuwe events via het “Events” menu om bezoekers op de hoogte te houden.', 'restaurant-pro'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>

<section class="section testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e('Ervaringen van gasten', 'restaurant-pro'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Lees wat bezoekers zeggen over Restaurant Schoolbord.', 'restaurant-pro'); ?></p>
        </div>

        <?php if ($testimonial_query->have_posts()) : ?>
            <div class="testimonial-slider" aria-live="polite">
                <?php $index = 0; while ($testimonial_query->have_posts()) : $testimonial_query->the_post();
                    $rating = (int) get_post_meta(get_the_ID(), 'testimonial_rating', true);
                    $role   = get_post_meta(get_the_ID(), 'testimonial_role', true);
                    ?>
                    <article class="testimonial-item<?php echo $index === 0 ? ' active' : ''; ?>">
                        <blockquote class="testimonial-quote">
                            <p><?php echo wp_kses_post(wpautop(get_the_content())); ?></p>
                        </blockquote>
                        <footer class="testimonial-author">
                            <div class="author-details">
                                <span class="author-name"><?php the_title(); ?></span>
                                <?php if ($role) : ?>
                                    <span class="author-role"><?php echo esc_html($role); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($rating) : ?>
                                <div class="author-rating" aria-label="<?php printf(esc_attr__('Beoordeling: %d van 5 sterren', 'restaurant-pro'), $rating); ?>">
                                    <?php for ($star = 1; $star <= 5; $star++) : ?>
                                        <span class="dashicons <?php echo $star <= $rating ? 'dashicons-star-filled' : 'dashicons-star-empty'; ?>" aria-hidden="true"></span>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                        </footer>
                    </article>
                <?php $index++; endwhile; ?>
            </div>
        <?php else : ?>
            <p class="no-content-message"><?php esc_html_e('Publiceer testimonials via het “Testimonials” menu om vertrouwen op te bouwen.', 'restaurant-pro'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>

<section class="section news-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e('Laatste nieuws', 'restaurant-pro'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Updates van de opleiding, evenementen en sfeerimpressies.', 'restaurant-pro'); ?></p>
        </div>

        <?php if ($news_query->have_posts()) : ?>
            <div class="news-grid">
                <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                    <article class="news-card">
                        <div class="news-card-meta">
                            <span class="news-date"><span class="dashicons dashicons-calendar-alt" aria-hidden="true"></span> <?php echo esc_html(get_the_date()); ?></span>
                            <span class="news-author"><span class="dashicons dashicons-admin-users" aria-hidden="true"></span> <?php echo esc_html(get_the_author()); ?></span>
                        </div>
                        <h3 class="news-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="news-card-excerpt"><?php the_excerpt(); ?></div>
                        <a class="news-card-link" href="<?php the_permalink(); ?>">
                            <?php esc_html_e('Lees verder', 'restaurant-pro'); ?>
                            <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="no-content-message"><?php esc_html_e('Schrijf een nieuwsbericht om dit blok te vullen.', 'restaurant-pro'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>
