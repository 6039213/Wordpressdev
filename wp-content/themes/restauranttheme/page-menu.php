<?php
/**
 * Menu Page Template
 * 
 * @package RestaurantPro
 * @version 2.0
 */

get_header(); ?>

<section class="page-header">
    <div class="container">
        <h1 class="page-title"><?php the_title(); ?></h1>
        <p class="page-subtitle"><?php _e('Discover our delicious selection of dishes made with fresh, local ingredients', 'restaurant-pro'); ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Menu Categories Filter -->
        <div class="menu-filter">
            <button class="filter-btn active" data-category="all"><?php _e('All Dishes', 'restaurant-pro'); ?></button>
            <?php
            $categories = get_terms(array(
                'taxonomy' => 'dish_category',
                'hide_empty' => true,
            ));
            
            if ($categories && !is_wp_error($categories)) :
                foreach ($categories as $category) :
                    ?>
                    <button class="filter-btn" data-category="<?php echo esc_attr($category->slug); ?>">
                        <?php echo esc_html($category->name); ?>
                    </button>
                    <?php
                endforeach;
            else :
                // Fallback categories
                $fallback_categories = array(
                    __('Appetizers', 'restaurant-pro'),
                    __('Main Courses', 'restaurant-pro'),
                    __('Desserts', 'restaurant-pro'),
                    __('Beverages', 'restaurant-pro')
                );
                
                foreach ($fallback_categories as $index => $category_name) :
                    ?>
                    <button class="filter-btn" data-category="category-<?php echo $index; ?>">
                        <?php echo esc_html($category_name); ?>
                    </button>
                    <?php
                endforeach;
            endif;
            ?>
        </div>
        
        <!-- Menu Items -->
        <div class="menu-grid">
            <?php
            $dishes = new WP_Query(array(
                'post_type' => 'dish',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC'
            ));
            
            if ($dishes->have_posts()) :
                while ($dishes->have_posts()) : $dishes->the_post();
                    $price = get_post_meta(get_the_ID(), 'dish_price', true);
                    $category = get_the_terms(get_the_ID(), 'dish_category');
                    $category_class = $category ? $category[0]->slug : 'uncategorized';
                    $ingredients = get_post_meta(get_the_ID(), 'dish_ingredients', true);
                    $allergens = get_post_meta(get_the_ID(), 'dish_allergens', true);
                    ?>
                    <div class="menu-item" data-category="<?php echo esc_attr($category_class); ?>">
                        <div class="menu-item-content">
                            <div class="menu-item-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('restaurant-dish'); ?>
                                <?php else : ?>
                                    <div class="placeholder-image">
                                        <i class="dashicons dashicons-food"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3 class="menu-item-title"><?php the_title(); ?></h3>
                                    <?php if ($price) : ?>
                                        <span class="menu-item-price">€<?php echo esc_html($price); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="menu-item-description">
                                    <?php the_content(); ?>
                                </div>
                                
                                <?php if ($ingredients) : ?>
                                    <div class="menu-item-ingredients">
                                        <strong><?php _e('Ingredients:', 'restaurant-pro'); ?></strong>
                                        <span><?php echo esc_html($ingredients); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($allergens) : ?>
                                    <div class="menu-item-allergens">
                                        <strong><?php _e('Allergens:', 'restaurant-pro'); ?></strong>
                                        <span><?php echo esc_html($allergens); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($category) : ?>
                                    <div class="menu-item-category">
                                        <span class="category-badge"><?php echo esc_html($category[0]->name); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Fallback menu items
                $fallback_dishes = array(
                    array(
                        'title' => __('Grilled Salmon', 'restaurant-pro'),
                        'price' => '24',
                        'description' => __('Fresh Atlantic salmon grilled to perfection, served with seasonal vegetables and herb butter.', 'restaurant-pro'),
                        'ingredients' => __('Salmon, seasonal vegetables, herbs, butter', 'restaurant-pro'),
                        'category' => __('Main Courses', 'restaurant-pro'),
                        'category_slug' => 'main-courses'
                    ),
                    array(
                        'title' => __('Caesar Salad', 'restaurant-pro'),
                        'price' => '12',
                        'description' => __('Crisp romaine lettuce with parmesan cheese, croutons, and our house-made Caesar dressing.', 'restaurant-pro'),
                        'ingredients' => __('Romaine lettuce, parmesan, croutons, Caesar dressing', 'restaurant-pro'),
                        'category' => __('Appetizers', 'restaurant-pro'),
                        'category_slug' => 'appetizers'
                    ),
                    array(
                        'title' => __('Chocolate Lava Cake', 'restaurant-pro'),
                        'price' => '8',
                        'description' => __('Warm chocolate cake with a molten center, served with vanilla ice cream and fresh berries.', 'restaurant-pro'),
                        'ingredients' => __('Chocolate, flour, eggs, vanilla ice cream, berries', 'restaurant-pro'),
                        'category' => __('Desserts', 'restaurant-pro'),
                        'category_slug' => 'desserts'
                    ),
                    array(
                        'title' => __('House Wine Selection', 'restaurant-pro'),
                        'price' => '6',
                        'description' => __('Carefully selected wines from local vineyards, available by glass or bottle.', 'restaurant-pro'),
                        'ingredients' => __('Wine selection varies', 'restaurant-pro'),
                        'category' => __('Beverages', 'restaurant-pro'),
                        'category_slug' => 'beverages'
                    )
                );
                
                foreach ($fallback_dishes as $dish) :
                    ?>
                    <div class="menu-item" data-category="<?php echo esc_attr($dish['category_slug']); ?>">
                        <div class="menu-item-content">
                            <div class="menu-item-image">
                                <div class="placeholder-image">
                                    <i class="dashicons dashicons-food"></i>
                                </div>
                            </div>
                            
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3 class="menu-item-title"><?php echo esc_html($dish['title']); ?></h3>
                                    <span class="menu-item-price">€<?php echo esc_html($dish['price']); ?></span>
                                </div>
                                
                                <div class="menu-item-description">
                                    <p><?php echo esc_html($dish['description']); ?></p>
                                </div>
                                
                                <div class="menu-item-ingredients">
                                    <strong><?php _e('Ingredients:', 'restaurant-pro'); ?></strong>
                                    <span><?php echo esc_html($dish['ingredients']); ?></span>
                                </div>
                                
                                <div class="menu-item-category">
                                    <span class="category-badge"><?php echo esc_html($dish['category']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
        </div>
        
        <!-- Menu Call to Action -->
        <div class="menu-cta">
            <div class="menu-cta-content">
                <h3><?php _e('Ready to Experience Our Menu?', 'restaurant-pro'); ?></h3>
                <p><?php _e('Book a table and let us serve you an unforgettable dining experience.', 'restaurant-pro'); ?></p>
                <a href="#reservation-form" class="btn btn-primary">
                    <i class="dashicons dashicons-calendar-alt"></i>
                    <?php _e('Make a Reservation', 'restaurant-pro'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Menu Page Specific Styles */
.page-header {
    background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), url('../img/restaurant.jpg');
    background-size: cover;
    background-position: center;
    padding: var(--spacing-4xl) 0;
    text-align: center;
    color: var(--color-white);
}

.page-title {
    font-size: var(--font-size-5xl);
    margin-bottom: var(--spacing-lg);
    color: var(--color-white);
}

.page-subtitle {
    font-size: var(--font-size-xl);
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.menu-filter {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-3xl);
}

.filter-btn {
    background: var(--color-white);
    color: var(--color-text);
    border: 2px solid var(--color-border);
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition-normal);
    font-weight: 500;
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--color-secondary);
    color: var(--color-white);
    border-color: var(--color-secondary);
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-3xl);
}

.menu-item {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    transition: transform var(--transition-normal);
}

.menu-item:hover {
    transform: translateY(-5px);
}

.menu-item-content {
    display: flex;
    height: 100%;
}

.menu-item-image {
    flex: 0 0 150px;
    overflow: hidden;
}

.menu-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.menu-item-details {
    flex: 1;
    padding: var(--spacing-lg);
    display: flex;
    flex-direction: column;
}

.menu-item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--spacing-md);
}

.menu-item-title {
    font-size: var(--font-size-xl);
    margin: 0;
    color: var(--color-primary);
    flex: 1;
}

.menu-item-price {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: var(--color-secondary);
    margin-left: var(--spacing-md);
}

.menu-item-description {
    color: var(--color-text-light);
    margin-bottom: var(--spacing-md);
    flex: 1;
}

.menu-item-ingredients,
.menu-item-allergens {
    font-size: var(--font-size-sm);
    margin-bottom: var(--spacing-sm);
}

.menu-item-ingredients strong,
.menu-item-allergens strong {
    color: var(--color-primary);
}

.menu-item-category {
    margin-top: auto;
}

.category-badge {
    background: var(--color-secondary);
    color: var(--color-white);
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: 500;
}

.menu-cta {
    background: var(--gradient-primary);
    color: var(--color-white);
    padding: var(--spacing-3xl);
    border-radius: var(--radius-lg);
    text-align: center;
}

.menu-cta h3 {
    color: var(--color-white);
    margin-bottom: var(--spacing-md);
}

.menu-cta p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: var(--spacing-xl);
}

.menu-cta .btn {
    background: var(--color-white);
    color: var(--color-secondary);
}

.menu-cta .btn:hover {
    background: var(--color-light-gray);
    color: var(--color-primary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: var(--font-size-3xl);
    }
    
    .menu-grid {
        grid-template-columns: 1fr;
    }
    
    .menu-item-content {
        flex-direction: column;
    }
    
    .menu-item-image {
        flex: none;
        height: 200px;
    }
    
    .menu-filter {
        flex-direction: column;
        align-items: center;
    }
    
    .filter-btn {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 480px) {
    .menu-item-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .menu-item-price {
        margin-left: 0;
        margin-top: var(--spacing-xs);
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Menu filtering functionality
    $('.filter-btn').on('click', function() {
        var category = $(this).data('category');
        
        // Update active button
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        // Filter menu items
        if (category === 'all') {
            $('.menu-item').fadeIn();
        } else {
            $('.menu-item').hide();
            $('.menu-item[data-category="' + category + '"]').fadeIn();
        }
    });
});
</script>

<?php get_footer(); ?>
