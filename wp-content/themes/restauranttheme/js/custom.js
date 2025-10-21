/**
 * Restaurant Pro Theme - Custom JavaScript
 * Interactive features and enhancements
 * 
 * @package RestaurantPro
 * @version 2.0
 */

(function($) {
    'use strict';
    
    // Initialize when document is ready
    $(document).ready(function() {
        RestaurantTheme.init();
    });
    
    // Main Theme Object
    window.RestaurantTheme = {
        
        // Initialize all functionality
        init: function() {
            this.initMobileMenu();
            this.initSmoothScrolling();
            this.initReservationForm();
            this.initAnimations();
            this.initTestimonialSlider();
            this.initBackToTop();
            this.initLazyLoading();
        },
        
        // Mobile Menu Toggle
        initMobileMenu: function() {
            $('.menu-toggle').on('click', function(e) {
                e.preventDefault();
                
                var $menu = $('.nav-menu');
                var $toggle = $(this);
                var $hamburger = $toggle.find('.hamburger');
                
                $menu.toggleClass('active');
                $toggle.attr('aria-expanded', $menu.hasClass('active'));
                
                // Animate hamburger
                $hamburger.toggleClass('active');
                
                // Close menu when clicking outside
                if ($menu.hasClass('active')) {
                    $(document).on('click.mobileMenu', function(e) {
                        if (!$(e.target).closest('.main-navigation').length) {
                            $menu.removeClass('active');
                            $toggle.attr('aria-expanded', false);
                            $hamburger.removeClass('active');
                            $(document).off('click.mobileMenu');
                        }
                    });
                } else {
                    $(document).off('click.mobileMenu');
                }
            });
            
            // Close menu when clicking on menu links
            $('.nav-menu a').on('click', function() {
                $('.nav-menu').removeClass('active');
                $('.menu-toggle').attr('aria-expanded', false);
                $('.menu-toggle .hamburger').removeClass('active');
            });
        },
        
        // Smooth Scrolling for Anchor Links
        initSmoothScrolling: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function(e) {
                var target = $(this.hash);
                if (target.length) {
                    e.preventDefault();
                    
                    var offset = 80; // Account for fixed header
                    var scrollTop = target.offset().top - offset;
                    
                    $('html, body').animate({
                        scrollTop: scrollTop
                    }, 800, 'swing');
                }
            });
        },
        
        // Reservation Form Handling
        initReservationForm: function() {
            var $form = $('#restaurant-reservation-form');
            if (!$form.length) return;
            
            // Form validation
            $form.on('submit', function(e) {
                e.preventDefault();
                
                if (RestaurantTheme.validateReservationForm()) {
                    RestaurantTheme.submitReservation();
                }
            });
            
            // Real-time validation
            $form.find('input, select, textarea').on('blur', function() {
                RestaurantTheme.validateField($(this));
            });
            
            // Set minimum date to today
            var today = new Date().toISOString().split('T')[0];
            $('#reservation-date').attr('min', today);
        },
        
        // Validate reservation form
        validateReservationForm: function() {
            var isValid = true;
            var $form = $('#restaurant-reservation-form');
            
            // Required fields
            var requiredFields = ['reservation_name', 'reservation_email', 'reservation_guests', 'reservation_date', 'reservation_time'];
            
            requiredFields.forEach(function(fieldName) {
                var $field = $form.find('[name="' + fieldName + '"]');
                if (!RestaurantTheme.validateField($field)) {
                    isValid = false;
                }
            });
            
            return isValid;
        },
        
        // Validate individual field
        validateField: function($field) {
            var value = $field.val().trim();
            var fieldName = $field.attr('name');
            var isValid = true;
            var errorMessage = '';
            
            // Remove existing error state
            $field.removeClass('error');
            $field.siblings('.error-message').remove();
            
            // Validation rules
            switch (fieldName) {
                case 'reservation_name':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Name is required';
                    } else if (value.length < 2) {
                        isValid = false;
                        errorMessage = 'Name must be at least 2 characters';
                    }
                    break;
                    
                case 'reservation_email':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Email is required';
                    } else if (!RestaurantTheme.isValidEmail(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid email address';
                    }
                    break;
                    
                case 'reservation_phone':
                    if (value && !RestaurantTheme.isValidPhone(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid phone number';
                    }
                    break;
                    
                case 'reservation_guests':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Please select number of guests';
                    }
                    break;
                    
                case 'reservation_date':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Please select a date';
                    } else if (new Date(value) < new Date().setHours(0, 0, 0, 0)) {
                        isValid = false;
                        errorMessage = 'Date cannot be in the past';
                    }
                    break;
                    
                case 'reservation_time':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Please select a time';
                    }
                    break;
            }
            
            if (!isValid) {
                $field.addClass('error');
                $field.after('<div class="error-message">' + errorMessage + '</div>');
            }
            
            return isValid;
        },
        
        // Submit reservation form
        submitReservation: function() {
            var $form = $('#restaurant-reservation-form');
            var $submitBtn = $form.find('button[type="submit"]');
            var originalText = $submitBtn.html();
            
            // Show loading state
            $submitBtn.prop('disabled', true).html('<i class="dashicons dashicons-update"></i> Submitting...');
            
            // Prepare form data
            var formData = {
                action: 'restaurant_reservation',
                nonce: restaurant_ajax.nonce,
                name: $form.find('[name="reservation_name"]').val(),
                email: $form.find('[name="reservation_email"]').val(),
                phone: $form.find('[name="reservation_phone"]').val(),
                guests: $form.find('[name="reservation_guests"]').val(),
                date: $form.find('[name="reservation_date"]').val(),
                time: $form.find('[name="reservation_time"]').val(),
                message: $form.find('[name="reservation_message"]').val()
            };
            
            // Submit via AJAX
            $.ajax({
                url: restaurant_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        RestaurantTheme.showMessage('success', response.data.message);
                        $form[0].reset();
                    } else {
                        RestaurantTheme.showMessage('error', response.data.message);
                    }
                },
                error: function() {
                    RestaurantTheme.showMessage('error', restaurant_ajax.error_text);
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            });
        },
        
        // Show message to user
        showMessage: function(type, message) {
            var $message = $('<div class="reservation-message ' + type + '">' + message + '</div>');
            var $form = $('#restaurant-reservation-form');
            
            // Remove existing messages
            $form.find('.reservation-message').remove();
            
            // Add new message
            $form.prepend($message);
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $message.offset().top - 100
            }, 500);
            
            // Auto-hide success messages
            if (type === 'success') {
                setTimeout(function() {
                    $message.fadeOut();
                }, 5000);
            }
        },
        
        // Initialize animations on scroll
        initAnimations: function() {
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-fade-in-up');
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });
                
                // Observe elements
                $('.card, .dish-card, .testimonial-item, .contact-card').each(function() {
                    observer.observe(this);
                });
            }
        },
        
        // Initialize testimonial slider
        initTestimonialSlider: function() {
            var $slider = $('.testimonial-slider');
            if (!$slider.length) return;
            
            var $items = $slider.find('.testimonial-item');
            if ($items.length <= 1) return;
            
            var currentIndex = 0;
            var autoSlide = true;
            var slideInterval;
            
            // Create slider controls
            var $controls = $('<div class="testimonial-controls"></div>');
            var $prevBtn = $('<button class="testimonial-prev" aria-label="Previous testimonial"><i class="dashicons dashicons-arrow-left-alt2"></i></button>');
            var $nextBtn = $('<button class="testimonial-next" aria-label="Next testimonial"><i class="dashicons dashicons-arrow-right-alt2"></i></button>');
            var $dots = $('<div class="testimonial-dots"></div>');
            
            $controls.append($prevBtn, $nextBtn, $dots);
            $slider.append($controls);
            
            // Create dots
            $items.each(function(index) {
                var $dot = $('<button class="testimonial-dot" data-index="' + index + '"></button>');
                if (index === 0) $dot.addClass('active');
                $dots.append($dot);
            });
            
            // Show specific slide
            function showSlide(index) {
                $items.removeClass('active').eq(index).addClass('active');
                $dots.find('.testimonial-dot').removeClass('active').eq(index).addClass('active');
                currentIndex = index;
            }
            
            // Next slide
            function nextSlide() {
                var nextIndex = (currentIndex + 1) % $items.length;
                showSlide(nextIndex);
            }
            
            // Previous slide
            function prevSlide() {
                var prevIndex = (currentIndex - 1 + $items.length) % $items.length;
                showSlide(prevIndex);
            }
            
            // Start auto-slide
            function startAutoSlide() {
                if (autoSlide) {
                    slideInterval = setInterval(nextSlide, 5000);
                }
            }
            
            // Stop auto-slide
            function stopAutoSlide() {
                clearInterval(slideInterval);
            }
            
            // Event handlers
            $nextBtn.on('click', function() {
                nextSlide();
                stopAutoSlide();
                startAutoSlide();
            });
            
            $prevBtn.on('click', function() {
                prevSlide();
                stopAutoSlide();
                startAutoSlide();
            });
            
            $dots.on('click', '.testimonial-dot', function() {
                var index = parseInt($(this).data('index'));
                showSlide(index);
                stopAutoSlide();
                startAutoSlide();
            });
            
            // Pause on hover
            $slider.on('mouseenter', stopAutoSlide);
            $slider.on('mouseleave', startAutoSlide);
            
            // Initialize
            $items.first().addClass('active');
            startAutoSlide();
        },
        
        // Back to top button
        initBackToTop: function() {
            var $backToTop = $('<button class="back-to-top" aria-label="Back to top"><i class="dashicons dashicons-arrow-up-alt2"></i></button>');
            $('body').append($backToTop);
            
            // Show/hide button based on scroll position
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    $backToTop.addClass('visible');
                } else {
                    $backToTop.removeClass('visible');
                }
            });
            
            // Scroll to top when clicked
            $backToTop.on('click', function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
            });
        },
        
        // Lazy loading for images
        initLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                var imageObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });
                
                $('img[data-src]').each(function() {
                    imageObserver.observe(this);
                });
            }
        },
        
        // Utility functions
        isValidEmail: function(email) {
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        },
        
        isValidPhone: function(phone) {
            var regex = /^[\+]?[1-9][\d]{0,15}$/;
            return regex.test(phone.replace(/[\s\-\(\)]/g, ''));
        }
    };
    
})(jQuery);

// Additional CSS for JavaScript features
jQuery(document).ready(function($) {
    // Add styles for JavaScript-enhanced elements
    var styles = `
        <style>
        /* Mobile Menu Styles */
        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        /* Form Validation Styles */
        .form-group input.error,
        .form-group select.error,
        .form-group textarea.error {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .reservation-message {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .reservation-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .reservation-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Testimonial Slider Styles */
        .testimonial-item {
            display: none;
        }
        
        .testimonial-item.active {
            display: block;
        }
        
        .testimonial-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .testimonial-prev,
        .testimonial-next {
            background: var(--color-secondary);
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .testimonial-prev:hover,
        .testimonial-next:hover {
            background: var(--color-primary);
            transform: scale(1.1);
        }
        
        .testimonial-dots {
            display: flex;
            gap: 0.5rem;
        }
        
        .testimonial-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: none;
            background: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .testimonial-dot.active {
            background: var(--color-secondary);
        }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--color-secondary);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: var(--color-primary);
            transform: translateY(-2px);
        }
        
        /* Lazy Loading */
        img.lazy {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        img.lazy.loaded {
            opacity: 1;
        }
        
        /* Loading Animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .dashicons-update {
            animation: spin 1s linear infinite;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .testimonial-controls {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .back-to-top {
                bottom: 1rem;
                right: 1rem;
                width: 45px;
                height: 45px;
            }
        }
        </style>
    `;
    
    $('head').append(styles);
});