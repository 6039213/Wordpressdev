<?php
/**
 * Server-side render for the featured dish block.
 *
 * @package RestaurantPro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fallback_args = array(
	'post_type'      => 'dish',
	'posts_per_page' => 1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'post_status'    => 'publish',
);

$featured_query = new WP_Query(
	array(
		'post_type'      => 'dish',
		'posts_per_page' => 1,
		'meta_key'       => 'featured_dish',
		'meta_value'     => '1',
	)
);

if ( $featured_query->have_posts() ) {
	$query = $featured_query;
} else {
	wp_reset_postdata();
	$query = new WP_Query( $fallback_args );
}

echo '<section ' . get_block_wrapper_attributes(
	array(
		'class' => 'blockone featured-dish-highlight',
	)
) . '>';

if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();

		$price       = get_post_meta( get_the_ID(), 'dish_price', true );
		$ingredients = get_post_meta( get_the_ID(), 'dish_ingredients', true );

		echo '<div class="featured-dish-card">';
		echo '<h3 class="featured-dish-title">' . esc_html( get_the_title() ) . '</h3>';
		echo '<div class="featured-dish-content">' . wp_kses_post( wpautop( get_the_excerpt() ) ) . '</div>';

		if ( $ingredients ) {
			echo '<p class="featured-dish-meta"><strong>' . esc_html__( 'Ingrediënten:', 'restaurant-pro' ) . '</strong> ' . esc_html( $ingredients ) . '</p>';
		}

		if ( $price ) {
			echo '<span class="featured-dish-price">' . esc_html__( 'Vanaf', 'restaurant-pro' ) . ' €' . esc_html( number_format( (float) $price, 2, ',', '.' ) ) . '</span>';
		}

		echo '<a class="featured-dish-link" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Bekijk gerecht', 'restaurant-pro' ) . '</a>';
		echo '</div>';
	}

	wp_reset_postdata();
} else {
	echo '<p class="featured-dish-empty">' . esc_html__( 'Voeg gerechten toe om deze spotlight te vullen.', 'restaurant-pro' ) . '</p>';
}

echo '</section>';
