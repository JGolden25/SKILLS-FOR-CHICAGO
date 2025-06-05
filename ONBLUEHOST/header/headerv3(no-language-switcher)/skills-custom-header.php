<?php
/*
Plugin Name: Skills Custom Header 2
Description: Adds a shortcode to display the Skills for Chicago custom header
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin URL constant for assets
define('SKILLS_HEADER_PLUGIN_URL', plugin_dir_url(__FILE__));

// Add custom styles and scripts for header
function skills_header_custom_assets() {
    // Only load on pages with our shortcode
    global $post;
    if (is_a($post, 'WP_Post') && (has_shortcode($post->post_content, 'skills_custom_header_2') || is_active_widget('', '', 'skills_custom_header_2_widget'))) {
        // Enqueue Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
        
        // Enqueue jQuery
        wp_enqueue_script('jquery');
        
        // Enqueue custom CSS
        wp_enqueue_style(
            'skills-header-style',
            SKILLS_HEADER_PLUGIN_URL . 'skills-header.css',
            array('font-awesome'),
            '1.0.0',
            'all'
        );
        
        // Enqueue custom JS
        wp_enqueue_script(
            'skills-header-script',
            SKILLS_HEADER_PLUGIN_URL . 'skills-header.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'skills_header_custom_assets');

// Create shortcode for skills header
function skills_custom_header_2_shortcode() {
    ob_start();
    ?>
    <div id="skills-header-main-content">
        <div class="skills-social-strip">
            <div class="skills-social-strip-container">
                <!-- Language Switcher with flag icons on the left -->
                <div class="skills-language-switcher">
                    <?php 
                    // More direct method to display language switcher - this should work better
                    if (function_exists('icl_get_languages')) {
                        $languages = icl_get_languages('skip_missing=0&orderby=code');
                        if (!empty($languages)) {
                            echo '<div class="wpml-ls-statics-shortcode_actions wpml-ls wpml-ls-legacy-list-horizontal">';
                            echo '<ul class="wpml-ls-list">';
                            foreach ($languages as $lang) {
                                $active_class = $lang['active'] ? ' wpml-ls-current-language' : '';
                                echo '<li class="wpml-ls-slot-shortcode_actions wpml-ls-item' . $active_class . '">';
                                echo '<a href="' . $lang['url'] . '" class="wpml-ls-link">';
                                
                                // Use US flag for English - size now controlled in CSS
                                if ($lang['language_code'] == 'en') {
                                    // URL for US flag - provide a direct URL to a small US flag image
                                    echo '<img class="wpml-ls-flag" src="' . SKILLS_HEADER_PLUGIN_URL . 'usa-flag.png" alt="en" title="English">';
                                } else {
                                    echo '<img class="wpml-ls-flag" src="' . $lang['country_flag_url'] . '" alt="' . $lang['language_code'] . '" title="' . $lang['native_name'] . '">';
                                }
                                
                                echo '</a>';
                                echo '</li>';
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                    } else {
                        // Fallback if WPML functions aren't available
                        echo do_action('wpml_add_language_selector');
                    }
                    ?>
                </div>
                
                <!-- Social icons on the right -->
                <div class="skills-social-icons-top">
                    <a href="https://www.facebook.com/SkillsForChicago/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.linkedin.com/company/skills-for-chicago/?viewAsMember=true" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="https://www.youtube.com/@skillsforchicagoland" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.instagram.com/skillsforchicago/" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Header -->
        <header class="skills-header">
            <div class="skills-header-container">
                <a href="https://skillsforchicago.org/" class="skills-logo-link">
                    <img src="https://ibc.gzr.mybluehost.me/wp-content/uploads/2025/03/SFC_Logo_Assets_2025_v1-2_optimized.png" alt="Skills for Chicago Logo" class="skills-logo">
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="skills-dropdown-nav">
                    <ul class="skills-nav-items">
                        <li class="skills-nav-item">
                            <a href="https://skillsforchicago.org/" class="skills-nav-link">Home</a>
                        </li>
                        <li class="skills-nav-item skills-dropdown">
                            <a href="#" class="skills-nav-link skills-dropdown-toggle">About Us <i class="fas fa-chevron-down"></i></a>
                            <div class="skills-dropdown-menu">
                                <a href="https://skillsforchicago.org/who-we-are/" class="skills-dropdown-item">Who We Are</a>
                                <a href="https://skillsforchicago.org/our-team/" class="skills-dropdown-item">Our Team</a>
                                <a href="https://skillsforchicago.org/annual-reports/" class="skills-dropdown-item">Annual Reports & Financials</a>
                                <a href="https://skillsforchicago.org/our-work/" class="skills-dropdown-item">Our Work in Action</a>
                                <a href="https://skillsforchicago.org/careers/" class="skills-dropdown-item">Careers at Skills</a>
                            </div>
                        </li>
                        <li class="skills-nav-item skills-dropdown">
                            <a href="#" class="skills-nav-link skills-dropdown-toggle">Job Seekers <i class="fas fa-chevron-down"></i></a>
                            <div class="skills-dropdown-menu">
                                <a href="https://skillsforchicago.org/find-a-job/" class="skills-dropdown-item">Find A Job</a>
                                <a href="https://skillsforchicago.org/career-resources/" class="skills-dropdown-item">Career Resources</a>
                                <a href="https://skillsforchicago.org/faqs/" class="skills-dropdown-item">FAQs for Job Seekers</a>
                            </div>
                        </li>
                        <li class="skills-nav-item skills-dropdown">
                            <a href="#" class="skills-nav-link skills-dropdown-toggle">Employers <i class="fas fa-chevron-down"></i></a>
                            <div class="skills-dropdown-menu">
                                <a href="https://skillsforchicago.org/partner/" class="skills-dropdown-item">Partner With Us</a>
                                <a href="https://skillsforchicago.org/employer-partners/" class="skills-dropdown-item">Our Employer Partners</a>
                            </div>
                        </li>
                        <li class="skills-nav-item skills-dropdown">
                            <a href="#" class="skills-nav-link skills-dropdown-toggle">Community Partners <i class="fas fa-chevron-down"></i></a>
                            <div class="skills-dropdown-menu">
                                <a href="https://skillsforchicago.org/collaborate/" class="skills-dropdown-item">Collaborate With Us</a>
                                <a href="https://skillsforchicago.org/community-impact/" class="skills-dropdown-item">Community Impact</a>
                                <a href="https://skillsforchicago.org/community-partners/" class="skills-dropdown-item">Our Community Partners</a>
                            </div>
                        </li>
                        <li class="skills-nav-item">
                            <a href="https://skillsforchicago.org/donors/" class="skills-nav-link">Donors</a>
                        </li>
                        <li class="skills-nav-item skills-dropdown">
                            <a href="#" class="skills-nav-link skills-dropdown-toggle">Events <i class="fas fa-chevron-down"></i></a>
                            <div class="skills-dropdown-menu">
                                <a href="https://skillsforchicago.org/events/" class="skills-dropdown-item">Events & Highlights</a>
                                <a href="https://skillsforchicago.org/ecb/" class="skills-dropdown-item">Employment Champions Breakfast</a>
                            </div>
                        </li>
                        <li class="skills-nav-item">
                            <a href="https://skillsforchicago.org/national-expansion/" class="skills-nav-link">National Expansion</a>
                        </li>
                        <li class="skills-nav-item">
                            <a href="https://skillsforchicago.org/contact-us/" class="skills-nav-link">Contact Us</a>
                        </li>
                    </ul>
                </nav>
                
                <a href="https://fundraise.givesmart.com/form/ezpk5w?utm_source=embed&utm_medium=page&utm_campaign=donation" class="skills-donate-button">
                    <span>Donate</span>
                    <div class="skills-circle-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
                
                <!-- Mobile Menu Toggle Button -->
                <button class="skills-mobile-menu-toggle" aria-label="Toggle Mobile Menu">
                    <div class="skills-hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                
                <!-- Mobile Navigation Panel -->
                <div class="skills-mobile-nav">
                    <div class="skills-mobile-nav-links">
                        <a href="https://skillsforchicago.org/">Home</a>
                        
                        <!-- About Us Dropdown -->
                        <div class="skills-mobile-dropdown">
                            <div class="skills-mobile-dropdown-toggle">
                                <span>About Us</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="skills-mobile-dropdown-menu">
                                <a href="https://skillsforchicago.org/who-we-are/">Who We Are</a>
                                <a href="https://skillsforchicago.org/our-team/">Our Team</a>
                                <a href="https://skillsforchicago.org/annual-reports/">Annual Reports & Financials</a>
                                <a href="https://skillsforchicago.org/our-work/">Our Work in Action</a>
                                <a href="https://skillsforchicago.org/careers/">Careers at Skills</a>
                            </div>
                        </div>
                        
                        <!-- Job Seekers Dropdown -->
                        <div class="skills-mobile-dropdown">
                            <div class="skills-mobile-dropdown-toggle">
                                <span>Job Seekers</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="skills-mobile-dropdown-menu">
                                <a href="https://skillsforchicago.org/find-a-job/">Find A Job</a>
                                <a href="https://skillsforchicago.org/career-resources/">Career Resources</a>
                                <a href="https://skillsforchicago.org/faqs/">FAQs for Job Seekers</a>
                            </div>
                        </div>
                        
                        <!-- Employers Dropdown -->
                        <div class="skills-mobile-dropdown">
                            <div class="skills-mobile-dropdown-toggle">
                                <span>Employers</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="skills-mobile-dropdown-menu">
                                <a href="https://skillsforchicago.org/partner/">Partner With Us</a>
                                <a href="https://skillsforchicago.org/employer-partners/">Our Employer Partners</a>
                            </div>
                        </div>
                        
                        <!-- Community Partners Dropdown -->
                        <div class="skills-mobile-dropdown">
                            <div class="skills-mobile-dropdown-toggle">
                                <span>Community Partners</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="skills-mobile-dropdown-menu">
                                <a href="https://skillsforchicago.org/collaborate/">Collaborate With Us</a>
                                <a href="https://skillsforchicago.org/community-impact/">Community Impact</a>
                                <a href="https://skillsforchicago.org/community-partners/">Our Community Partners</a>
                            </div>
                        </div>
                        
                        <a href="https://skillsforchicago.org/donors/">Donors</a>
                        
                        <!-- Events Dropdown -->
                        <div class="skills-mobile-dropdown">
                            <div class="skills-mobile-dropdown-toggle">
                                <span>Events</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="skills-mobile-dropdown-menu">
                                <a href="https://skillsforchicago.org/events/">Events & Highlights</a>
                                <a href="https://skillsforchicago.org/ecb/">Employment Champions Breakfast</a>
                            </div>
                        </div>
                        
                        <a href="https://skillsforchicago.org/national-expansion/">National Expansion</a>
                        <a href="https://skillsforchicago.org/contact-us/">Contact Us</a>
                    </div>
                    
                    <!-- Mobile Language Switcher -->
                    <div class="skills-mobile-language-switcher">
                        <?php 
                        // Use the same approach for mobile language switcher
                        if (function_exists('icl_get_languages')) {
                            $languages = icl_get_languages('skip_missing=0&orderby=code');
                            if (!empty($languages)) {
                                echo '<div class="wpml-ls-statics-shortcode_actions wpml-ls wpml-ls-legacy-list-horizontal">';
                                echo '<ul class="wpml-ls-list">';
                                foreach ($languages as $lang) {
                                    $active_class = $lang['active'] ? ' wpml-ls-current-language' : '';
                                    echo '<li class="wpml-ls-slot-shortcode_actions wpml-ls-item' . $active_class . '">';
                                    echo '<a href="' . $lang['url'] . '" class="wpml-ls-link">';
                                    
                                    // Use same US flag approach for mobile
                                    if ($lang['language_code'] == 'en') {
                                        echo '<img class="wpml-ls-flag" src="' . SKILLS_HEADER_PLUGIN_URL . 'usa-flag.png" alt="en" title="English">';
                                    } else {
                                        echo '<img class="wpml-ls-flag" src="' . $lang['country_flag_url'] . '" alt="' . $lang['language_code'] . '" title="' . $lang['native_name'] . '">';
                                    }
                                    
                                    echo '</a>';
                                    echo '</li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                        } else {
                            echo do_action('wpml_add_language_selector');
                        }
                        ?>
                    </div>
                    
                    <a href="https://fundraise.givesmart.com/form/ezpk5w?utm_source=embed&utm_medium=page&utm_campaign=donation" class="skills-mobile-donate-button">
                        <span>Donate</span>
                        <div class="skills-circle-icon">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
                
                <!-- Overlay for mobile menu -->
                <div class="skills-overlay"></div>
            </div>
        </header>
    </div>
    <?php
    $output = ob_get_clean();
    return $output;
}
add_shortcode('skills_custom_header_2', 'skills_custom_header_2_shortcode');

// Register widget if needed
class skills_custom_header_2_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'skills_custom_header_2_widget',
            'Skills Custom Header',
            array('description' => 'Displays the Skills for Chicago custom header')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo do_shortcode('[skills_custom_header_2]');
        echo $args['after_widget'];
    }

    public function form($instance) {
        echo '<p>This widget displays the Skills for Chicago custom header.</p>';
    }
}
function register_skills_header_widget() {
    register_widget('skills_custom_header_2_Widget');
}
add_action('widgets_init', 'register_skills_header_widget');

// Activation hook to create necessary files and directories
function skills_header_activate() {
    // Create necessary directories if they don't exist
    $plugin_dir = plugin_dir_path(__FILE__);
    
    // CSS file path
    $css_file = $plugin_dir . 'skills-header.css';
    
    // JS file path
    $js_file = $plugin_dir . 'skills-header.js';
    
    // Create CSS file if it doesn't exist
    if (!file_exists($css_file)) {
        $css_content = file_get_contents(plugin_dir_path(__FILE__) . 'skills-header.css');
        if (!$css_content) {
            // If file doesn't exist or can't be read, create it with default content
            $css_content = '/* Default CSS will be created here */';
        }
        file_put_contents($css_file, $css_content);
    }
    
    // Create JS file if it doesn't exist
    if (!file_exists($js_file)) {
        $js_content = file_get_contents(plugin_dir_path(__FILE__) . 'skills-header.js');
        if (!$js_content) {
            // If file doesn't exist or can't be read, create it with default content
            $js_content = '/* Default JS will be created here */';
        }
        file_put_contents($js_file, $js_content);
    }
    
    // Add USA flag image if it doesn't exist - using a small optimized image
    $usa_flag_file = $plugin_dir . 'usa-flag.png';
    if (!file_exists($usa_flag_file)) {
        // Download a small USA flag (24x16 pixels) if possible
        $usa_flag_url = 'https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/flags/4x3/us.svg';
        if (function_exists('file_get_contents') && function_exists('file_put_contents')) {
            $flag_content = @file_get_contents($usa_flag_url);
            if ($flag_content) {
                file_put_contents($usa_flag_file, $flag_content);
            }
        }
    }
}
register_activation_hook(__FILE__, 'skills_header_activate');