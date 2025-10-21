<?php
/**
 * Index Template (Blog/Archive)
 * 
 * @package RestaurantPro
 * @version 2.0
 */

get_header(); ?>

<section class="page-header">
    <div class="container">
        <h1 class="page-title">
<?php
            if (is_home()) {
                echo get_the_title(get_option('page_for_posts', true));
            } elseif (is_archive()) {
                the_archive_title();
            } else {
                _e('Latest Posts', 'restaurant-pro');
            }
            ?>
        </h1>
        <?php if (is_archive()) : ?>
            <p class="page-subtitle"><?php the_archive_description(); ?></p>
        <?php else : ?>
            <p class="page-subtitle"><?php _e('Stay updated with our latest news, events, and culinary insights', 'restaurant-pro'); ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="blog-layout">
            <main class="blog-main">
                <?php if (have_posts()) : ?>
                    <div class="posts-grid">
                        <?php while (have_posts()) : the_post(); ?>
                            <article class="post-card">
                                <div class="post-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('restaurant-gallery'); ?>
                                        </a>
                                    <?php else : ?>
                                        <div class="placeholder-image">
                                            <i class="dashicons dashicons-format-aside"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="post-content">
                                    <div class="post-meta">
                                        <span class="post-date">
                                            <i class="dashicons dashicons-calendar-alt"></i>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        <span class="post-author">
                                            <i class="dashicons dashicons-admin-users"></i>
                                            <?php the_author(); ?>
                                        </span>
                                        <?php if (has_category()) : ?>
                                            <span class="post-category">
                                                <i class="dashicons dashicons-category"></i>
                                                <?php the_category(', '); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h2 class="post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    
                                    <div class="post-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <div class="post-footer">
                                        <a href="<?php the_permalink(); ?>" class="read-more">
                                            <?php _e('Read More', 'restaurant-pro'); ?>
                                            <i class="dashicons dashicons-arrow-right-alt2"></i>
                                        </a>
                                        
                                        <?php if (comments_open() || get_comments_number()) : ?>
                                            <span class="comments-count">
                                                <i class="dashicons dashicons-admin-comments"></i>
                                                <?php comments_number('0', '1', '%'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-wrapper">
                        <?php
                        the_posts_pagination(array(
                            'mid_size' => 2,
                            'prev_text' => '<i class="dashicons dashicons-arrow-left-alt2"></i> ' . __('Previous', 'restaurant-pro'),
                            'next_text' => __('Next', 'restaurant-pro') . ' <i class="dashicons dashicons-arrow-right-alt2"></i>',
                        ));
                        ?>
                    </div>
                    
                <?php else : ?>
                    <div class="no-posts">
                        <div class="no-posts-content">
                            <i class="dashicons dashicons-format-aside"></i>
                            <h2><?php _e('No Posts Found', 'restaurant-pro'); ?></h2>
                            <p><?php _e('Sorry, no posts were found. Please check back later for updates.', 'restaurant-pro'); ?></p>
                            <a href="<?php echo home_url(); ?>" class="btn btn-primary">
                                <?php _e('Back to Home', 'restaurant-pro'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </main>
            
            <aside class="blog-sidebar">
                <?php if (is_active_sidebar('sidebar-1')) : ?>
                    <?php dynamic_sidebar('sidebar-1'); ?>
                <?php else : ?>
                    <!-- Default sidebar content -->
                    <div class="widget">
                        <h3 class="widget-title"><?php _e('About Our Restaurant', 'restaurant-pro'); ?></h3>
                        <p><?php _e('We are passionate about serving delicious, fresh food in a warm and welcoming atmosphere. Our restaurant has been a local favorite for years.', 'restaurant-pro'); ?></p>
                    </div>
                    
                    <div class="widget">
                        <h3 class="widget-title"><?php _e('Recent Posts', 'restaurant-pro'); ?></h3>
                        <ul class="recent-posts">
                            <?php
                            $recent_posts = wp_get_recent_posts(array(
                                'numberposts' => 5,
                                'post_status' => 'publish'
                            ));
                            
                            foreach ($recent_posts as $post) :
                                ?>
                                <li>
                                    <a href="<?php echo get_permalink($post['ID']); ?>">
                                        <?php echo $post['post_title']; ?>
                                    </a>
                                    <span class="post-date"><?php echo get_the_date('', $post['ID']); ?></span>
                                </li>
                                <?php
                            endforeach;
                            wp_reset_query();
                            ?>
                        </ul>
                    </div>
                    
                    <div class="widget">
                        <h3 class="widget-title"><?php _e('Categories', 'restaurant-pro'); ?></h3>
                        <ul class="categories-list">
                            <?php wp_list_categories(array('title_li' => '')); ?>
                        </ul>
                    </div>
                    
                    <div class="widget">
                        <h3 class="widget-title"><?php _e('Make a Reservation', 'restaurant-pro'); ?></h3>
                        <p><?php _e('Ready to experience our delicious cuisine? Book your table today!', 'restaurant-pro'); ?></p>
                        <a href="#reservation-form" class="btn btn-primary">
                            <i class="dashicons dashicons-calendar-alt"></i>
                            <?php _e('Reserve Now', 'restaurant-pro'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>

<style>
/* Blog/Index Page Styles */
.blog-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--spacing-3xl);
}

.posts-grid {
    display: grid;
    gap: var(--spacing-2xl);
}

.post-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    transition: transform var(--transition-normal);
}

.post-card:hover {
    transform: translateY(-5px);
}

.post-image {
    position: relative;
    overflow: hidden;
}

.post-image img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.post-card:hover .post-image img {
    transform: scale(1.05);
}

.post-content {
    padding: var(--spacing-xl);
}

.post-meta {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    font-size: var(--font-size-sm);
    color: var(--color-text-light);
}

.post-meta span {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.post-meta .dashicons {
    font-size: 16px;
}

.post-title {
    margin-bottom: var(--spacing-md);
}

.post-title a {
    color: var(--color-primary);
    text-decoration: none;
    transition: color var(--transition-fast);
}

.post-title a:hover {
    color: var(--color-secondary);
}

.post-excerpt {
    color: var(--color-text-light);
    margin-bottom: var(--spacing-lg);
    line-height: 1.6;
}

.post-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--color-border);
}

.read-more {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--color-secondary);
    font-weight: 500;
    text-decoration: none;
    transition: color var(--transition-fast);
}

.read-more:hover {
    color: var(--color-primary);
}

.comments-count {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--color-text-light);
    font-size: var(--font-size-sm);
}

.pagination-wrapper {
    margin-top: var(--spacing-3xl);
    text-align: center;
}

.page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-sm) var(--spacing-md);
    margin: 0 var(--spacing-xs);
    background: var(--color-white);
    color: var(--color-text);
    text-decoration: none;
    border-radius: var(--radius-md);
    border: 1px solid var(--color-border);
    transition: all var(--transition-fast);
}

.page-numbers:hover,
.page-numbers.current {
    background: var(--color-secondary);
    color: var(--color-white);
    border-color: var(--color-secondary);
}

.no-posts {
    text-align: center;
    padding: var(--spacing-4xl) 0;
}

.no-posts-content {
    max-width: 400px;
    margin: 0 auto;
}

.no-posts-content .dashicons {
    font-size: 4rem;
    color: var(--color-secondary);
    margin-bottom: var(--spacing-lg);
}

.no-posts-content h2 {
    margin-bottom: var(--spacing-md);
    color: var(--color-primary);
}

.no-posts-content p {
    margin-bottom: var(--spacing-xl);
    color: var(--color-text-light);
}

/* Sidebar Styles */
.blog-sidebar {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xl);
}

.widget {
    background: var(--color-white);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
}

.widget-title {
    margin-bottom: var(--spacing-lg);
    color: var(--color-primary);
    font-size: var(--font-size-xl);
}

.widget p {
    color: var(--color-text-light);
    line-height: 1.6;
}

.recent-posts {
    list-style: none;
    padding: 0;
    margin: 0;
}

.recent-posts li {
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid var(--color-border);
}

.recent-posts li:last-child {
    border-bottom: none;
}

.recent-posts a {
    color: var(--color-text);
    text-decoration: none;
    font-weight: 500;
    transition: color var(--transition-fast);
}

.recent-posts a:hover {
    color: var(--color-secondary);
}

.recent-posts .post-date {
    display: block;
    font-size: var(--font-size-sm);
    color: var(--color-text-light);
    margin-top: var(--spacing-xs);
}

.categories-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.categories-list li {
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid var(--color-border);
}

.categories-list li:last-child {
    border-bottom: none;
}

.categories-list a {
    color: var(--color-text);
    text-decoration: none;
    transition: color var(--transition-fast);
}

.categories-list a:hover {
    color: var(--color-secondary);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .blog-layout {
        grid-template-columns: 1fr;
        gap: var(--spacing-2xl);
    }
    
    .blog-sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .post-meta {
        flex-direction: column;
        gap: var(--spacing-sm);
    }
    
    .post-footer {
        flex-direction: column;
        gap: var(--spacing-md);
        align-items: flex-start;
    }
    
    .post-content {
        padding: var(--spacing-lg);
    }
    
    .widget {
        padding: var(--spacing-lg);
    }
}
</style>

<?php get_footer(); ?>