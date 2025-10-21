<?php
/**
 * Admin Calendar Template
 * 
 * @package RestaurantReservationSystem
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Reservation Calendar', 'restaurant-reservation'); ?></h1>
    
    <div class="rrs-calendar-admin">
        <div class="rrs-calendar-header">
            <h3 class="rrs-calendar-title"><?php echo date('F Y'); ?></h3>
            <div class="rrs-calendar-nav">
                <button type="button" data-direction="prev"><?php _e('Previous', 'restaurant-reservation'); ?></button>
                <button type="button" data-direction="next"><?php _e('Next', 'restaurant-reservation'); ?></button>
            </div>
        </div>
        <div class="rrs-calendar-grid">
            <!-- Calendar will be populated by JavaScript -->
        </div>
    </div>
    
    <div class="rrs-calendar-legend">
        <h4><?php _e('Legend', 'restaurant-reservation'); ?></h4>
        <div class="rrs-legend-items">
            <div class="rrs-legend-item">
                <span class="rrs-legend-color" style="background-color: #fff3cd;"></span>
                <span><?php _e('Pending', 'restaurant-reservation'); ?></span>
            </div>
            <div class="rrs-legend-item">
                <span class="rrs-legend-color" style="background-color: #d4edda;"></span>
                <span><?php _e('Confirmed', 'restaurant-reservation'); ?></span>
            </div>
            <div class="rrs-legend-item">
                <span class="rrs-legend-color" style="background-color: #f8d7da;"></span>
                <span><?php _e('Cancelled', 'restaurant-reservation'); ?></span>
            </div>
            <div class="rrs-legend-item">
                <span class="rrs-legend-color" style="background-color: #d1ecf1;"></span>
                <span><?php _e('Completed', 'restaurant-reservation'); ?></span>
            </div>
        </div>
    </div>
</div>

<style>
.rrs-calendar-admin {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

.rrs-calendar-header {
    background: linear-gradient(135deg, #7bdcb5 0%, #57cfa1 100%);
    color: #ffffff;
    padding: 20px;
    text-align: center;
}

.rrs-calendar-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.rrs-calendar-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
}

.rrs-calendar-nav button {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: #ffffff;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.rrs-calendar-nav button:hover {
    background: rgba(255, 255, 255, 0.3);
}

.rrs-calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background-color: #e1e8ed;
}

.rrs-calendar-day-header {
    background-color: #f8f9fa;
    padding: 15px 10px;
    text-align: center;
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.rrs-calendar-day {
    background-color: #ffffff;
    padding: 15px 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
}

.rrs-calendar-day:hover {
    background-color: #f8f9fa;
}

.rrs-calendar-day.disabled {
    background-color: #f8f9fa;
    color: #adb5bd;
    cursor: not-allowed;
}

.rrs-calendar-day.other-month {
    color: #adb5bd;
}

.rrs-calendar-day.has-reservations::after {
    content: '';
    position: absolute;
    bottom: 5px;
    left: 50%;
    transform: translateX(-50%);
    width: 6px;
    height: 6px;
    background-color: #7bdcb5;
    border-radius: 50%;
}

.rrs-calendar-legend {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.rrs-calendar-legend h4 {
    margin: 0 0 15px 0;
    color: #2c3e50;
}

.rrs-legend-items {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.rrs-legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rrs-legend-color {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    display: inline-block;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Calendar functionality will be implemented here
    // This is a simplified version for demonstration
});
</script>
