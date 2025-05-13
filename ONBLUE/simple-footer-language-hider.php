<?php
/**
 * Plugin Name: Simple Footer Language Selector Hider
 * Description: Hides the WPML footer language selector using custom CSS
 * Version: 1.0
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add CSS to hide the WPML footer language selector
 */
function hide_wpml_footer_selector() {
    echo '<style>
    .wpml-ls-legacy-list-horizontal.wpml-ls-statics-footer {
        margin-bottom: 30px;    
        display: none;
    }
    </style>';
}

// Add the CSS to the head of the page
add_action('wp_head', 'hide_wpml_footer_selector');