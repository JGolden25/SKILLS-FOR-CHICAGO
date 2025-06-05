<?php
/*
Plugin Name: Skills Custom Header
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
    if (is_a($post, 'WP_Post') && (has_shortcode($post->post_content, 'skills_custom_header') || is_active_widget('', '', 'skills_custom_header_widget'))) {
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
function skills_custom_header_shortcode() {
    ob_start();
    
    // Check if the URL has lang=es parameter
    $is_spanish = isset($_GET['lang']) && $_GET['lang'] === 'es';
    
    // Set the "Manage My Account" text based on language
    $manage_account_text = $is_spanish ? 'Administrar Mi Cuenta' : 'Manage My Account';
    ?>
    <div id="skills-header-main-content">
        <div class="skills-social-strip">
            <div class="skills-social-strip-container">
                <!-- Language Switcher dropdown (replacing flag icons) -->
                <div class="skills-language-switcher">
                    <?php 
                    if (function_exists('icl_get_languages')) {
                        $languages = icl_get_languages('skip_missing=0&orderby=code');
                        if (!empty($languages)) {
                            $current_language = '';
                            foreach ($languages as $lang) {
                                if ($lang['active']) {
                                    $current_language = $lang['language_code'];
                                    break;
                                }
                            }
                            
                            // Default to English if no active language found
                            if (empty($current_language)) {
                                $current_language = 'en';
                            }
                            
                            // Create dropdown instead of flags
                            echo '<div class="skills-lang-dropdown">';
                            echo '<div class="skills-lang-current">';
                            echo ($current_language == 'en') ? 'Eng' : 'Esp';
                            echo ' <i class="fas fa-chevron-down"></i></div>';
                            echo '<div class="skills-lang-options">';
                            
                            foreach ($languages as $lang) {
                                if (!$lang['active']) {
                                    $display_name = ($lang['language_code'] == 'en') ? 'Eng' : 'Esp';
                                    echo '<a href="' . $lang['url'] . '">' . $display_name . '</a>';
                                }
                            }
                            
                            echo '</div>'; // End skills-lang-options
                            echo '</div>'; // End skills-lang-dropdown
                        }
                    } else {
                        // Fallback if WPML functions aren't available
                        echo '<div class="skills-lang-dropdown">';
                        echo '<div class="skills-lang-current">Eng <i class="fas fa-chevron-down"></i></div>';
                        echo '<div class="skills-lang-options">';
                        echo '<a href="#">Esp</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
                
                <!-- Social icons on the right -->
                <div class="skills-social-icons-top">
                    <a href="https://www.facebook.com/SkillsForChicago/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.linkedin.com/company/skills-for-chicago/?viewAsMember=true" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="https://www.youtube.com/@skillsforchicago" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
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
                                <a href="https://sfcf.my.site.com/skillsportal/s/manage-my-account" class="skills-dropdown-item"><?php echo $manage_account_text; ?></a>
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
                                <a href="https://sfcf.my.site.com/skillsportal/s/manage-my-account"><?php echo $manage_account_text; ?></a>
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
                        // Use the text-based language switcher for mobile too
                        if (function_exists('icl_get_languages')) {
                            $languages = icl_get_languages('skip_missing=0&orderby=code');
                            if (!empty($languages)) {
                                $current_language = '';
                                foreach ($languages as $lang) {
                                    if ($lang['active']) {
                                        $current_language = $lang['language_code'];
                                        break;
                                    }
                                }
                                
                                // Default to English if no active language found
                                if (empty($current_language)) {
                                    $current_language = 'en';
                                }
                                
                                echo '<div class="skills-mobile-lang-selector">';
                                
                                foreach ($languages as $lang) {
                                    $display_name = ($lang['language_code'] == 'en') ? 'English' : 'Espa単ol';
                                    $active_class = $lang['active'] ? ' skills-lang-active' : '';
                                    echo '<a href="' . $lang['url'] . '" class="skills-mobile-lang-option' . $active_class . '">' . $display_name . '</a>';
                                }
                                
                                echo '</div>';
                            }
                        } else {
                            // Fallback
                            echo '<div class="skills-mobile-lang-selector">';
                            echo '<a href="#" class="skills-mobile-lang-option skills-lang-active">English</a>';
                            echo '<a href="#" class="skills-mobile-lang-option">Espa単ol</a>';
                            echo '</div>';
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
add_shortcode('skills_custom_header', 'skills_custom_header_shortcode');

// Register widget if needed
class Skills_Custom_Header_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'skills_custom_header_widget',
            'Skills Custom Header',
            array('description' => 'Displays the Skills for Chicago custom header')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo do_shortcode('[skills_custom_header]');
        echo $args['after_widget'];
    }

    public function form($instance) {
        echo '<p>This widget displays the Skills for Chicago custom header.</p>';
    }
}
function register_skills_header_widget() {
    register_widget('Skills_Custom_Header_Widget');
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
}
register_activation_hook(__FILE__, 'skills_header_activate');