<?php
/**
 * Contact Page Template
 * 
 * @package RestaurantPro
 * @version 2.0
 */

get_header(); ?>

<section class="page-header">
    <div class="container">
        <h1 class="page-title"><?php the_title(); ?></h1>
        <p class="page-subtitle"><?php _e('Get in touch with us for reservations, questions, or special requests', 'restaurant-pro'); ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-2">
            <!-- Contact Information -->
            <div class="contact-info-section">
                <h2><?php _e('Contact Information', 'restaurant-pro'); ?></h2>
                
                <div class="contact-details">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="dashicons dashicons-location"></i>
                        </div>
                        <div class="contact-content">
                            <h3><?php _e('Address', 'restaurant-pro'); ?></h3>
                            <p><?php echo get_theme_mod('restaurant_address', '123 Restaurant Street, Amsterdam, Netherlands'); ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="dashicons dashicons-phone"></i>
                        </div>
                        <div class="contact-content">
                            <h3><?php _e('Phone', 'restaurant-pro'); ?></h3>
                            <p><a href="tel:<?php echo get_theme_mod('restaurant_phone', '+31123456789'); ?>"><?php echo get_theme_mod('restaurant_phone', '+31 123 456 789'); ?></a></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="dashicons dashicons-email"></i>
                        </div>
                        <div class="contact-content">
                            <h3><?php _e('Email', 'restaurant-pro'); ?></h3>
                            <p><a href="mailto:<?php echo get_option('admin_email'); ?>"><?php echo get_option('admin_email'); ?></a></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="dashicons dashicons-clock"></i>
                        </div>
                        <div class="contact-content">
                            <h3><?php _e('Opening Hours', 'restaurant-pro'); ?></h3>
                            <div class="opening-hours">
                                <?php 
                                $hours = get_theme_mod('restaurant_opening_hours', 
                                    "Monday - Thursday: 11:00 - 22:00\nFriday - Saturday: 11:00 - 23:00\nSunday: 12:00 - 21:00"
                                );
                                echo nl2br(esc_html($hours));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="social-section">
                    <h3><?php _e('Follow Us', 'restaurant-pro'); ?></h3>
                    <div class="social-links">
                        <a href="#" class="social-link facebook">
                            <i class="dashicons dashicons-facebook"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="social-link instagram">
                            <i class="dashicons dashicons-instagram"></i>
                            <span>Instagram</span>
                        </a>
                        <a href="#" class="social-link twitter">
                            <i class="dashicons dashicons-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a href="#" class="social-link youtube">
                            <i class="dashicons dashicons-youtube"></i>
                            <span>YouTube</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-section">
                <h2><?php _e('Send us a Message', 'restaurant-pro'); ?></h2>
                
                <form id="contact-form" class="contact-form" method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-name"><?php _e('Full Name', 'restaurant-pro'); ?> <span class="required">*</span></label>
                            <input type="text" id="contact-name" name="contact_name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-email"><?php _e('Email Address', 'restaurant-pro'); ?> <span class="required">*</span></label>
                            <input type="email" id="contact-email" name="contact_email" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-phone"><?php _e('Phone Number', 'restaurant-pro'); ?></label>
                            <input type="tel" id="contact-phone" name="contact_phone">
                        </div>
                        <div class="form-group">
                            <label for="contact-subject"><?php _e('Subject', 'restaurant-pro'); ?> <span class="required">*</span></label>
                            <select id="contact-subject" name="contact_subject" required>
                                <option value=""><?php _e('Select a subject', 'restaurant-pro'); ?></option>
                                <option value="reservation"><?php _e('Reservation Inquiry', 'restaurant-pro'); ?></option>
                                <option value="menu"><?php _e('Menu Questions', 'restaurant-pro'); ?></option>
                                <option value="event"><?php _e('Private Events', 'restaurant-pro'); ?></option>
                                <option value="feedback"><?php _e('Feedback', 'restaurant-pro'); ?></option>
                                <option value="other"><?php _e('Other', 'restaurant-pro'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact-message"><?php _e('Message', 'restaurant-pro'); ?> <span class="required">*</span></label>
                        <textarea id="contact-message" name="contact_message" rows="6" required placeholder="<?php _e('Please tell us how we can help you...', 'restaurant-pro'); ?>"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="contact_newsletter" value="1">
                            <span class="checkmark"></span>
                            <?php _e('I would like to receive updates and special offers via email', 'restaurant-pro'); ?>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="dashicons dashicons-email"></i>
                            <?php _e('Send Message', 'restaurant-pro'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="container">
        <h2 class="section-title"><?php _e('Find Us', 'restaurant-pro'); ?></h2>
        <div class="map-container">
            <div class="map-placeholder">
                <i class="dashicons dashicons-location"></i>
                <h3><?php _e('Interactive Map', 'restaurant-pro'); ?></h3>
                <p><?php _e('Map integration can be added here using Google Maps or other mapping services.', 'restaurant-pro'); ?></p>
                <p><strong><?php _e('Address:', 'restaurant-pro'); ?></strong> <?php echo get_theme_mod('restaurant_address', '123 Restaurant Street, Amsterdam, Netherlands'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section" style="background: var(--color-light-gray);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php _e('Frequently Asked Questions', 'restaurant-pro'); ?></h2>
            <p class="section-subtitle"><?php _e('Find answers to common questions about our restaurant', 'restaurant-pro'); ?></p>
        </div>
        
        <div class="faq-grid">
            <div class="faq-item">
                <h3 class="faq-question"><?php _e('Do you accept reservations?', 'restaurant-pro'); ?></h3>
                <p class="faq-answer"><?php _e('Yes, we accept reservations for both lunch and dinner. You can make a reservation online through our website or by calling us directly.', 'restaurant-pro'); ?></p>
            </div>
            
            <div class="faq-item">
                <h3 class="faq-question"><?php _e('Do you accommodate dietary restrictions?', 'restaurant-pro'); ?></h3>
                <p class="faq-answer"><?php _e('Absolutely! We offer vegetarian, vegan, and gluten-free options. Please inform us of any dietary restrictions when making your reservation.', 'restaurant-pro'); ?></p>
            </div>
            
            <div class="faq-item">
                <h3 class="faq-question"><?php _e('Is there parking available?', 'restaurant-pro'); ?></h3>
                <p class="faq-answer"><?php _e('Yes, we have complimentary valet parking available for our guests. Street parking is also available in the surrounding area.', 'restaurant-pro'); ?></p>
            </div>
            
            <div class="faq-item">
                <h3 class="faq-question"><?php _e('Do you host private events?', 'restaurant-pro'); ?></h3>
                <p class="faq-answer"><?php _e('Yes, we offer private dining rooms and can accommodate special events. Please contact us for more information about our event packages.', 'restaurant-pro'); ?></p>
            </div>
            
            <div class="faq-item">
                <h3 class="faq-question"><?php _e('What is your dress code?', 'restaurant-pro'); ?></h3>
                <p class="faq-answer"><?php _e('We maintain a smart casual dress code. We ask that guests avoid wearing shorts, flip-flops, or overly casual attire.', 'restaurant-pro'); ?></p>
            </div>
            
            <div class="faq-item">
                <h3 class="faq-question"><?php _e('Do you offer takeout or delivery?', 'restaurant-pro'); ?></h3>
                <p class="faq-answer"><?php _e('Yes, we offer takeout service. Delivery is available through select third-party services in our area.', 'restaurant-pro'); ?></p>
            </div>
        </div>
    </div>
</section>

<style>
/* Contact Page Specific Styles */
.contact-info-section,
.contact-form-section {
    background: var(--color-white);
    padding: var(--spacing-2xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
}

.contact-details {
    margin-bottom: var(--spacing-2xl);
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--color-border);
}

.contact-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.contact-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    background: var(--gradient-primary);
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
}

.contact-icon .dashicons {
    font-size: 24px;
    color: var(--color-white);
}

.contact-content h3 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-primary);
}

.contact-content p {
    margin: 0;
    color: var(--color-text-light);
}

.contact-content a {
    color: var(--color-secondary);
    text-decoration: none;
}

.contact-content a:hover {
    color: var(--color-primary);
}

.opening-hours {
    line-height: 1.8;
}

.social-section {
    border-top: 1px solid var(--color-border);
    padding-top: var(--spacing-xl);
}

.social-section h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-primary);
}

.social-links {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
}

.social-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-md);
    background: var(--color-light-gray);
    border-radius: var(--radius-md);
    text-decoration: none;
    color: var(--color-text);
    transition: all var(--transition-normal);
}

.social-link:hover {
    background: var(--color-secondary);
    color: var(--color-white);
    transform: translateY(-2px);
}

.social-link .dashicons {
    font-size: 20px;
}

.contact-form {
    margin-top: var(--spacing-xl);
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-sm);
    cursor: pointer;
    font-size: var(--font-size-sm);
    color: var(--color-text-light);
}

.checkbox-label input[type="checkbox"] {
    margin: 0;
}

.map-section {
    padding: var(--spacing-3xl) 0;
    background: var(--color-white);
}

.map-container {
    max-width: 800px;
    margin: 0 auto;
}

.map-placeholder {
    background: var(--color-light-gray);
    border-radius: var(--radius-lg);
    padding: var(--spacing-3xl);
    text-align: center;
    border: 2px dashed var(--color-border);
}

.map-placeholder .dashicons {
    font-size: 4rem;
    color: var(--color-secondary);
    margin-bottom: var(--spacing-lg);
}

.map-placeholder h3 {
    margin-bottom: var(--spacing-md);
    color: var(--color-primary);
}

.map-placeholder p {
    color: var(--color-text-light);
    margin-bottom: var(--spacing-sm);
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
}

.faq-item {
    background: var(--color-white);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
}

.faq-question {
    color: var(--color-primary);
    margin-bottom: var(--spacing-md);
    font-size: var(--font-size-lg);
}

.faq-answer {
    color: var(--color-text-light);
    margin: 0;
    line-height: 1.6;
}

.required {
    color: #e74c3c;
}

/* Responsive Design */
@media (max-width: 768px) {
    .grid-2 {
        grid-template-columns: 1fr;
    }
    
    .contact-info-section,
    .contact-form-section {
        padding: var(--spacing-lg);
    }
    
    .contact-item {
        flex-direction: column;
        text-align: center;
    }
    
    .social-links {
        grid-template-columns: 1fr;
    }
    
    .faq-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .contact-item {
        gap: var(--spacing-md);
    }
    
    .contact-icon {
        width: 40px;
        height: 40px;
    }
    
    .contact-icon .dashicons {
        font-size: 20px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Contact form handling
    $('#contact-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var originalText = $submitBtn.html();
        
        // Basic validation
        var isValid = true;
        var requiredFields = ['contact_name', 'contact_email', 'contact_subject', 'contact_message'];
        
        requiredFields.forEach(function(fieldName) {
            var $field = $form.find('[name="' + fieldName + '"]');
            if (!$field.val().trim()) {
                $field.addClass('error');
                isValid = false;
            } else {
                $field.removeClass('error');
            }
        });
        
        if (!isValid) {
            alert('<?php _e('Please fill in all required fields.', 'restaurant-pro'); ?>');
            return;
        }
        
        // Show loading state
        $submitBtn.prop('disabled', true).html('<i class="dashicons dashicons-update"></i> <?php _e('Sending...', 'restaurant-pro'); ?>');
        
        // Simulate form submission (replace with actual AJAX call)
        setTimeout(function() {
            $submitBtn.prop('disabled', false).html(originalText);
            alert('<?php _e('Thank you for your message! We will get back to you soon.', 'restaurant-pro'); ?>');
            $form[0].reset();
        }, 2000);
    });
    
    // Remove error class on input
    $('#contact-form input, #contact-form select, #contact-form textarea').on('input change', function() {
        $(this).removeClass('error');
    });
});
</script>

<?php get_footer(); ?>
