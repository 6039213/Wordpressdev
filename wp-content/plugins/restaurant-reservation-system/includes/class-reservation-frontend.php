<?php
/**
 * Frontend Handler for Restaurant Reservation System
 * 
 * @package RestaurantReservationSystem
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RRS_Reservation_Frontend {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_custom_styles'));
    }
    
    public function enqueue_scripts() {
        // Scripts are enqueued by the main plugin class
    }
    
    public function add_custom_styles() {
        $theme_color = get_option('rrs_theme_color', '#7bdcb5');
        ?>
        <style>
        :root {
            --rrs-primary-color: <?php echo esc_attr($theme_color); ?>;
            --rrs-primary-dark: <?php echo esc_attr($this->darken_color($theme_color, 20)); ?>;
            --rrs-primary-light: <?php echo esc_attr($this->lighten_color($theme_color, 20)); ?>;
        }
        
        .rrs-reservation-form {
            --rrs-theme-color: var(--rrs-primary-color);
        }
        
        .rrs-calendar {
            --rrs-theme-color: var(--rrs-primary-color);
        }
        
        .rrs-submit-button {
            background: linear-gradient(135deg, var(--rrs-primary-color) 0%, var(--rrs-primary-dark) 100%);
        }
        
        .rrs-submit-button:hover {
            box-shadow: 0 8px 25px rgba(123, 220, 181, 0.3);
        }
        
        .rrs-time-slot.selected {
            background-color: var(--rrs-primary-color);
            border-color: var(--rrs-primary-color);
        }
        
        .rrs-time-slot:hover {
            border-color: var(--rrs-primary-color);
            background-color: var(--rrs-primary-light);
        }
        
        .rrs-calendar-header {
            background: linear-gradient(135deg, var(--rrs-primary-color) 0%, var(--rrs-primary-dark) 100%);
        }
        
        .rrs-calendar-day.selected {
            background-color: var(--rrs-primary-color);
            color: #ffffff;
        }
        
        .rrs-form-group input:focus,
        .rrs-form-group select:focus,
        .rrs-form-group textarea:focus {
            border-color: var(--rrs-primary-color);
            box-shadow: 0 0 0 3px var(--rrs-primary-light);
        }
        </style>
        <?php
    }
    
    private function darken_color($color, $percent) {
        $color = ltrim($color, '#');
        $rgb = array_map('hexdec', str_split($color, 2));
        
        foreach ($rgb as &$value) {
            $value = max(0, $value - ($value * $percent / 100));
        }
        
        return '#' . implode('', array_map(function($val) {
            return str_pad(dechex(round($val)), 2, '0', STR_PAD_LEFT);
        }, $rgb));
    }
    
    private function lighten_color($color, $percent) {
        $color = ltrim($color, '#');
        $rgb = array_map('hexdec', str_split($color, 2));
        
        foreach ($rgb as &$value) {
            $value = min(255, $value + ((255 - $value) * $percent / 100));
        }
        
        return '#' . implode('', array_map(function($val) {
            return str_pad(dechex(round($val)), 2, '0', STR_PAD_LEFT);
        }, $rgb));
    }
}
