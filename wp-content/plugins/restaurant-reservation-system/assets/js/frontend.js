/**
 * Frontend JavaScript for Restaurant Reservation System
 * 
 * @package RestaurantReservationSystem
 * @version 2.0
 */

(function($) {
    'use strict';
    
    // Initialize when document is ready
    $(document).ready(function() {
        RRS_Frontend.init();
    });
    
    // Main Frontend Object
    window.RRS_Frontend = {
        
        // Initialize the frontend functionality
        init: function() {
            this.initDatePicker();
            this.initTimeSlots();
            this.initFormValidation();
            this.initFormSubmission();
            this.initCalendar();
            this.bindEvents();
        },
        
        // Initialize date picker
        initDatePicker: function() {
            if ($('#rrs-date').length) {
                $('#rrs-date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: 0,
                    maxDate: '+3M',
                    beforeShowDay: this.disableUnavailableDates,
                    onSelect: function(dateText) {
                        RRS_Frontend.loadTimeSlots(dateText);
                    }
                });
            }
        },
        
        // Initialize time slots
        initTimeSlots: function() {
            this.loadTimeSlots($('#rrs-date').val());
        },
        
        // Initialize form validation
        initFormValidation: function() {
            this.validateForm();
        },
        
        // Initialize form submission
        initFormSubmission: function() {
            $('#rrs-reservation-form').on('submit', this.handleFormSubmission);
        },
        
        // Initialize calendar
        initCalendar: function() {
            if ($('.rrs-calendar').length) {
                this.renderCalendar();
            }
        },
        
        // Bind events
        bindEvents: function() {
            // Real-time validation
            $('#rrs-reservation-form input, #rrs-reservation-form select, #rrs-reservation-form textarea').on('blur', this.validateField);
            
            // Time slot selection
            $(document).on('click', '.rrs-time-slot', this.selectTimeSlot);
            
            // Calendar navigation
            $(document).on('click', '.rrs-calendar-nav button', this.navigateCalendar);
            $(document).on('click', '.rrs-calendar-day', this.selectCalendarDate);
            
            // Guest count change
            $('#rrs-guests').on('change', function() {
                RRS_Frontend.loadTimeSlots($('#rrs-date').val());
            });
        },
        
        // Disable unavailable dates
        disableUnavailableDates: function(date) {
            var day = date.getDay();
            var dateString = $.datepicker.formatDate('yy-mm-dd', date);
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Disable past dates
            if (date < today) {
                return [false, 'past-date', 'Past dates are not available'];
            }
            
            // Check restaurant hours
            var dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            var dayName = dayNames[day];
            var openingHours = rrs_ajax.restaurant_hours;
            
            if (!openingHours[dayName] || !openingHours[dayName].open) {
                return [false, 'closed-day', 'Restaurant is closed on this day'];
            }
            
            return [true, '', ''];
        },
        
        // Load time slots for selected date
        loadTimeSlots: function(date) {
            if (!date) return;
            
            var guests = $('#rrs-guests').val() || 1;
            var $timeSlotsContainer = $('.rrs-time-slots');
            var $loading = $('.rrs-loading');
            
            $loading.addClass('show');
            $timeSlotsContainer.empty();
            
            $.ajax({
                url: rrs_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'rrs_get_time_slots',
                    date: date,
                    guests: guests,
                    nonce: rrs_ajax.nonce
                },
                success: function(response) {
                    $loading.removeClass('show');
                    
                    if (response.success) {
                        RRS_Frontend.renderTimeSlots(response.data.time_slots);
                    } else {
                        $timeSlotsContainer.html('<p class="rrs-message error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    $loading.removeClass('show');
                    $timeSlotsContainer.html('<p class="rrs-message error">' + rrs_ajax.error_text + '</p>');
                }
            });
        },
        
        // Render time slots
        renderTimeSlots: function(timeSlots) {
            var $container = $('.rrs-time-slots');
            $container.empty();
            
            if (timeSlots.length === 0) {
                $container.html('<p class="rrs-message info">No available time slots for this date.</p>');
                return;
            }
            
            timeSlots.forEach(function(time) {
                var $slot = $('<div class="rrs-time-slot" data-time="' + time + '">' + 
                    RRS_Frontend.formatTime(time) + '</div>');
                $container.append($slot);
            });
        },
        
        // Select time slot
        selectTimeSlot: function(e) {
            e.preventDefault();
            
            var $slot = $(this);
            
            if ($slot.hasClass('unavailable')) {
                return;
            }
            
            $('.rrs-time-slot').removeClass('selected');
            $slot.addClass('selected');
            
            $('#rrs-time').val($slot.data('time'));
        },
        
        // Validate field
        validateField: function() {
            var $field = $(this);
            var fieldName = $field.attr('name');
            var value = $field.val().trim();
            var isValid = true;
            var errorMessage = '';
            
            // Remove existing error state
            $field.removeClass('error');
            $field.siblings('.error-message').removeClass('show');
            
            // Validate based on field type
            switch (fieldName) {
                case 'rrs_name':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Name is required';
                    } else if (value.length < 2) {
                        isValid = false;
                        errorMessage = 'Name must be at least 2 characters';
                    }
                    break;
                    
                case 'rrs_email':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Email is required';
                    } else if (!RRS_Frontend.isValidEmail(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid email address';
                    }
                    break;
                    
                case 'rrs_phone':
                    if (rrs_ajax.require_phone && !value) {
                        isValid = false;
                        errorMessage = 'Phone number is required';
                    } else if (value && !RRS_Frontend.isValidPhone(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid phone number';
                    }
                    break;
                    
                case 'rrs_date':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Date is required';
                    } else if (!RRS_Frontend.isValidDate(value)) {
                        isValid = false;
                        errorMessage = 'Please select a valid date';
                    }
                    break;
                    
                case 'rrs_time':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Time is required';
                    }
                    break;
                    
                case 'rrs_guests':
                    var guests = parseInt(value);
                    if (!guests || guests < 1) {
                        isValid = false;
                        errorMessage = 'Number of guests must be at least 1';
                    } else if (guests > rrs_ajax.max_guests) {
                        isValid = false;
                        errorMessage = 'Maximum ' + rrs_ajax.max_guests + ' guests allowed';
                    }
                    break;
                    
                case 'rrs_message':
                    if (rrs_ajax.require_message && !value) {
                        isValid = false;
                        errorMessage = 'Message is required';
                    }
                    break;
            }
            
            if (!isValid) {
                $field.addClass('error');
                $field.siblings('.error-message').text(errorMessage).addClass('show');
            }
            
            return isValid;
        },
        
        // Validate entire form
        validateForm: function() {
            var isValid = true;
            
            $('#rrs-reservation-form input[required], #rrs-reservation-form select[required], #rrs-reservation-form textarea[required]').each(function() {
                if (!RRS_Frontend.validateField.call(this)) {
                    isValid = false;
                }
            });
            
            return isValid;
        },
        
        // Handle form submission
        handleFormSubmission: function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitButton = $form.find('.rrs-submit-button');
            var $loading = $('.rrs-loading');
            var $message = $('.rrs-message');
            
            // Validate form
            if (!RRS_Frontend.validateForm()) {
                $message.removeClass('show success').addClass('show error').text('Please correct the errors above.');
                return;
            }
            
            // Check if time slot is selected
            if (!$('.rrs-time-slot.selected').length) {
                $message.removeClass('show success').addClass('show error').text('Please select a time slot.');
                return;
            }
            
            // Disable submit button and show loading
            $submitButton.prop('disabled', true);
            $loading.addClass('show');
            $message.removeClass('show');
            
            // Prepare form data
            var formData = {
                action: 'rrs_submit_reservation',
                nonce: rrs_ajax.nonce,
                name: $('#rrs-name').val(),
                email: $('#rrs-email').val(),
                phone: $('#rrs-phone').val(),
                date: $('#rrs-date').val(),
                time: $('#rrs-time').val(),
                guests: $('#rrs-guests').val(),
                message: $('#rrs-message').val()
            };
            
            // Submit form
            $.ajax({
                url: rrs_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $loading.removeClass('show');
                    $submitButton.prop('disabled', false);
                    
                    if (response.success) {
                        $message.removeClass('error').addClass('show success').text(response.data.message);
                        $form[0].reset();
                        $('.rrs-time-slot').removeClass('selected');
                        $('.rrs-time-slots').empty();
                        
                        // Scroll to message
                        $('html, body').animate({
                            scrollTop: $message.offset().top - 100
                        }, 500);
                    } else {
                        $message.removeClass('success').addClass('show error').text(response.data.message);
                    }
                },
                error: function() {
                    $loading.removeClass('show');
                    $submitButton.prop('disabled', false);
                    $message.removeClass('success').addClass('show error').text(rrs_ajax.error_text);
                }
            });
        },
        
        // Render calendar
        renderCalendar: function() {
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth();
            var currentYear = currentDate.getFullYear();
            
            this.renderCalendarMonth(currentYear, currentMonth);
        },
        
        // Render calendar month
        renderCalendarMonth: function(year, month) {
            var $calendar = $('.rrs-calendar');
            var $grid = $calendar.find('.rrs-calendar-grid');
            var monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'];
            
            // Update calendar title
            $calendar.find('.rrs-calendar-title').text(monthNames[month] + ' ' + year);
            
            // Clear existing days
            $grid.find('.rrs-calendar-day').remove();
            
            // Get first day of month and number of days
            var firstDay = new Date(year, month, 1);
            var lastDay = new Date(year, month + 1, 0);
            var daysInMonth = lastDay.getDate();
            var startingDay = firstDay.getDay();
            
            // Add day headers
            var dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayHeaders.forEach(function(day) {
                $grid.append('<div class="rrs-calendar-day-header">' + day + '</div>');
            });
            
            // Add empty cells for days before month starts
            for (var i = 0; i < startingDay; i++) {
                $grid.append('<div class="rrs-calendar-day other-month"></div>');
            }
            
            // Add days of month
            for (var day = 1; day <= daysInMonth; day++) {
                var dateString = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                var $day = $('<div class="rrs-calendar-day" data-date="' + dateString + '">' + day + '</div>');
                
                // Check if date is in the past
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                var dayDate = new Date(year, month, day);
                
                if (dayDate < today) {
                    $day.addClass('disabled');
                }
                
                $grid.append($day);
            }
        },
        
        // Navigate calendar
        navigateCalendar: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var direction = $button.data('direction');
            var currentTitle = $('.rrs-calendar-title').text();
            var parts = currentTitle.split(' ');
            var month = new Date(parts[0] + ' 1, ' + parts[1]).getMonth();
            var year = parseInt(parts[1]);
            
            if (direction === 'prev') {
                month--;
                if (month < 0) {
                    month = 11;
                    year--;
                }
            } else {
                month++;
                if (month > 11) {
                    month = 0;
                    year++;
                }
            }
            
            RRS_Frontend.renderCalendarMonth(year, month);
        },
        
        // Select calendar date
        selectCalendarDate: function(e) {
            e.preventDefault();
            
            var $day = $(this);
            
            if ($day.hasClass('disabled') || $day.hasClass('other-month')) {
                return;
            }
            
            var date = $day.data('date');
            
            // Update date picker
            $('#rrs-date').val(date);
            
            // Load time slots
            RRS_Frontend.loadTimeSlots(date);
            
            // Update selected state
            $('.rrs-calendar-day').removeClass('selected');
            $day.addClass('selected');
        },
        
        // Utility functions
        isValidEmail: function(email) {
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        },
        
        isValidPhone: function(phone) {
            var regex = /^[\+]?[1-9][\d]{0,15}$/;
            return regex.test(phone.replace(/[\s\-\(\)]/g, ''));
        },
        
        isValidDate: function(dateString) {
            var date = new Date(dateString);
            return date instanceof Date && !isNaN(date);
        },
        
        formatTime: function(time) {
            var timeParts = time.split(':');
            var hours = parseInt(timeParts[0]);
            var minutes = timeParts[1];
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            return hours + ':' + minutes + ' ' + ampm;
        }
    };
    
})(jQuery);
