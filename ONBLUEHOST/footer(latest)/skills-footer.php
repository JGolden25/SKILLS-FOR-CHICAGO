<?php
/*
Plugin Name: Skills Custom Footer
Description: Adds a shortcode to display the Skills for Chicago custom footer
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin URL constant for assets
define('SKILLS_FOOTER_PLUGIN_URL', plugin_dir_url(__FILE__));

// Add custom styles and scripts for footer
function skills_footer_custom_assets() {
    // Only load on pages with our shortcode
    global $post;
    if (is_a($post, 'WP_Post') && (has_shortcode($post->post_content, 'skills_custom_footer') || is_active_widget('', '', 'skills_custom_footer_widget'))) {
        // Enqueue Font Awesome if not already loaded
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
        
        // Enqueue jQuery
        wp_enqueue_script('jquery');
        
        // Enqueue custom CSS
        wp_enqueue_style(
            'skills-footer-style',
            SKILLS_FOOTER_PLUGIN_URL . 'skills-footer.css',
            array('font-awesome'),
            '1.0.0',
            'all'
        );
        
        // Enqueue custom JS
        wp_enqueue_script(
            'skills-footer-script',
            SKILLS_FOOTER_PLUGIN_URL . 'skills-footer.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'skills_footer_custom_assets');

// Create shortcode for skills footer
function skills_custom_footer_shortcode() {
    // Check if spanish language is requested
    $is_spanish = isset($_GET['lang']) && $_GET['lang'] == 'es';
    
    ob_start();
    ?>
    <!-- Footer Content -->
    <footer class="skills-modern-footer">
        <div class="skills-footer-container">
            <div class="skills-footer-top">
                <a href="https://skillsforchicago.org/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><div class="skills-footer-logo">
                    <img src="https://ibc.gzr.mybluehost.me/wp-content/uploads/2025/03/SFC_Logo_Assets_2025_v1-3_optimized.png" alt="Skills for Chicago Logo">
                </div></a>
                <div class="skills-footer-divider"></div>
                <div class="skills-footer-social">
                    <a href="https://www.facebook.com/SkillsForChicago/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.linkedin.com/company/skills-for-chicago/?viewAsMember=true" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="https://www.youtube.com/@skillsforchicago" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.instagram.com/skillsforchicago/" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <!-- Desktop Footer Navigation -->
            <div class="skills-footer-nav">
                <div class="skills-footer-nav-section">
                    <h3><?php echo $is_spanish ? 'Inicio' : 'Home'; ?></h3>
                    <div class="skills-footer-nav-links">
                        <a href="https://skillsforchicago.org/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Inicio' : 'Home'; ?></a>
                    </div>
                </div>
                <div class="skills-footer-nav-section">
                    <h3><?php echo $is_spanish ? 'Sobre Nosotros' : 'About Us'; ?></h3>
                    <div class="skills-footer-nav-links">
                        <a href="https://skillsforchicago.org/who-we-are/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Quiénes Somos' : 'Who We Are'; ?></a>
                        <a href="https://skillsforchicago.org/our-team/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Nuestro Equipo' : 'Our Team'; ?></a>
                        <a href="https://skillsforchicago.org/annual-reports/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Informes Anuales y Financieros' : 'Annual Reports & Financials'; ?></a>
                        <a href="https://skillsforchicago.org/our-work/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Nuestro Trabajo en Acción' : 'Our Work in Action'; ?></a>
                        <a href="https://skillsforchicago.org/careers/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Carreras en Skills' : 'Careers at Skills'; ?></a>
                    </div>
                </div>
                
                <div class="skills-footer-nav-section">
                    <h3><?php echo $is_spanish ? 'Buscadores de Empleo' : 'Job Seekers'; ?></h3>
                    <div class="skills-footer-nav-links">
                        <a href="https://skillsforchicago.org/find-a-job/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Encuentra un Trabajo' : 'Find A Job'; ?></a>
                        <a href="https://sfcf.my.site.com/skillsportal/s/manage-my-account<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Administrar Mi Cuenta' : 'Manage My Account'; ?></a>
                        <a href="https://skillsforchicago.org/career-resources/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Recursos Profesionales' : 'Career Resources'; ?></a>
                        <a href="https://skillsforchicago.org/faqs/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Preguntas Frecuentes' : 'FAQs for Job Seekers'; ?></a>
                    </div>
                </div>
                
                <div class="skills-footer-nav-section">
                    <h3><?php echo $is_spanish ? 'Empleadores' : 'Employers'; ?></h3>
                    <div class="skills-footer-nav-links">
                        <a href="https://skillsforchicago.org/partner/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Asóciate con Nosotros' : 'Partner With Us'; ?></a>
                        <a href="https://skillsforchicago.org/employer-partners/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Nuestros Socios Empleadores' : 'Our Employer Partners'; ?></a>
                    </div>
                </div>

                <div class="skills-footer-nav-section">
                    <h3><?php echo $is_spanish ? 'Socios Comunitarios' : 'Community Partners'; ?></h3>
                    <div class="skills-footer-nav-links">
                        <a href="https://skillsforchicago.org/collaborate/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Colabora con Nosotros' : 'Collaborate With Us'; ?></a>
                        <a href="https://skillsforchicago.org/community-impact/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Impacto Comunitario' : 'Community Impact'; ?></a>
                        <a href="https://skillsforchicago.org/community-partners/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Nuestros Socios Comunitarios' : 'Our Community Partners'; ?></a>
                    </div>
                </div>
                <div class="skills-footer-nav-section">
                    <h3><?php echo $is_spanish ? 'Donantes' : 'Donors'; ?></h3>
                    <div class="skills-footer-nav-links">
                        <a href="https://skillsforchicago.org/donors/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Donantes' : 'Donors'; ?></a>
                    </div>
                </div>
                <div class="skills-footer-nav-section">
                    <h3><?php echo $is_spanish ? 'Eventos' : 'Events'; ?></h3>
                    <div class="skills-footer-nav-links">
                        <a href="https://skillsforchicago.org/events/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Eventos y Destacados' : 'Events & Highlights'; ?></a>
                        <a href="https://skillsforchicago.org/ecb/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Desayuno de Campeones de Empleo' : 'Employment Champions Breakfast'; ?></a>
                    </div>
                </div>
                <div class="skills-footer-nav-section">
                    <h3><?php echo $is_spanish ? 'Expansión Nacional' : 'National Expansion'; ?></h3>
                    <div class="skills-footer-nav-links">
                        <a href="https://skillsforchicago.org/national-expansion/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Expansión Nacional' : 'National Expansion'; ?></a>
                    </div>
                </div>
                <div class="skills-footer-nav-section">
                    <h3><?php echo $is_spanish ? 'Contáctenos' : 'Contact Us'; ?></h3>
                    <div class="skills-footer-nav-links">
                        <a href="https://skillsforchicago.org/contact-us/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Contáctenos' : 'Contact Us'; ?></a>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Footer Accordion -->
            <div class="skills-mobile-footer">
                <!-- ABOUT US Section -->
                <div class="skills-footer-accordion">
                    <div class="skills-footer-accordion-header">
                        <h3 class="skills-footer-accordion-title"><?php echo $is_spanish ? 'SOBRE NOSOTROS' : 'ABOUT US'; ?></h3>
                        <span class="skills-footer-accordion-icon">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <div class="skills-footer-accordion-content">
                        <ul class="skills-footer-mobile-links">
                            <li><a href="https://skillsforchicago.org/who-we-are/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Quiénes Somos' : 'Who We Are'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/our-team/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Nuestro Equipo' : 'Our Team'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/annual-reports/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Informes Anuales y Financieros' : 'Annual Reports & Financials'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/our-work/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Nuestro Trabajo en Acción' : 'Our Work in Action'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/careers/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Carreras en Skills' : 'Careers at Skills'; ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- JOB SEEKERS Section -->
                <div class="skills-footer-accordion">
                    <div class="skills-footer-accordion-header">
                        <h3 class="skills-footer-accordion-title"><?php echo $is_spanish ? 'BUSCADORES DE EMPLEO' : 'JOB SEEKERS'; ?></h3>
                        <span class="skills-footer-accordion-icon">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <div class="skills-footer-accordion-content">
                        <ul class="skills-footer-mobile-links">
                            <li><a href="https://skillsforchicago.org/find-a-job/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Encuentra un Trabajo' : 'Find A Job'; ?></a></li>
                            <li><a href="https://sfcf.my.site.com/skillsportal/s/manage-my-account<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Administrar Mi Cuenta' : 'Manage My Account'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/career-resources/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Recursos Profesionales' : 'Career Resources'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/faqs/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Preguntas Frecuentes' : 'FAQs for Job Seekers'; ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- EMPLOYERS Section -->
                <div class="skills-footer-accordion">
                    <div class="skills-footer-accordion-header">
                        <h3 class="skills-footer-accordion-title"><?php echo $is_spanish ? 'EMPLEADORES' : 'EMPLOYERS'; ?></h3>
                        <span class="skills-footer-accordion-icon">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <div class="skills-footer-accordion-content">
                        <ul class="skills-footer-mobile-links">
                            <li><a href="https://skillsforchicago.org/partner/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Asóciate con Nosotros' : 'Partner With Us'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/employer-partners/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Nuestros Socios Empleadores' : 'Our Employer Partners'; ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- COMMUNITY PARTNERS Section -->
                <div class="skills-footer-accordion">
                    <div class="skills-footer-accordion-header">
                        <h3 class="skills-footer-accordion-title"><?php echo $is_spanish ? 'SOCIOS COMUNITARIOS' : 'COMMUNITY PARTNERS'; ?></h3>
                        <span class="skills-footer-accordion-icon">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <div class="skills-footer-accordion-content">
                        <ul class="skills-footer-mobile-links">
                            <li><a href="https://skillsforchicago.org/collaborate/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Colabora con Nosotros' : 'Collaborate With Us'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/community-impact/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Impacto Comunitario' : 'Community Impact'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/community-partners/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Nuestros Socios Comunitarios' : 'Our Community Partners'; ?></a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- DONORS Section -->
                <div class="skills-footer-accordion">
                    <div class="skills-footer-accordion-header">
                        <h3 class="skills-footer-accordion-title"><?php echo $is_spanish ? 'DONANTES' : 'DONORS'; ?></h3>
                        <span class="skills-footer-accordion-icon">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <div class="skills-footer-accordion-content">
                        <ul class="skills-footer-mobile-links">
                            <li><a href="https://skillsforchicago.org/donors/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Donantes' : 'Donors'; ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- EVENTS & HIGHLIGHTS Section -->
                <div class="skills-footer-accordion">
                    <div class="skills-footer-accordion-header">
                        <h3 class="skills-footer-accordion-title"><?php echo $is_spanish ? 'EVENTOS' : 'EVENTS'; ?></h3>
                        <span class="skills-footer-accordion-icon">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <div class="skills-footer-accordion-content">
                        <ul class="skills-footer-mobile-links">
                            <li><a href="https://skillsforchicago.org/events/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Eventos y Destacados' : 'Events & Highlights'; ?></a></li>
                            <li><a href="https://skillsforchicago.org/ecb/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Desayuno de Campeones de Empleo' : 'Employment Champions Breakfast'; ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- NATIONAL EXPANSION Section -->
                <div class="skills-footer-accordion">
                    <div class="skills-footer-accordion-header">
                        <h3 class="skills-footer-accordion-title"><?php echo $is_spanish ? 'EXPANSIÓN NACIONAL' : 'NATIONAL EXPANSION'; ?></h3>
                        <span class="skills-footer-accordion-icon">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <div class="skills-footer-accordion-content">
                        <ul class="skills-footer-mobile-links">
                            <li><a href="https://skillsforchicago.org/national-expansion/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Expansión Nacional' : 'National Expansion'; ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- CONTACT US Section -->
                <div class="skills-footer-accordion">
                    <div class="skills-footer-accordion-header">
                        <h3 class="skills-footer-accordion-title"><?php echo $is_spanish ? 'CONTÁCTENOS' : 'CONTACT US'; ?></h3>
                        <span class="skills-footer-accordion-icon">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                    <div class="skills-footer-accordion-content">
                        <ul class="skills-footer-mobile-links">
                            <li><a href="https://skillsforchicago.org/contact-us/<?php echo $is_spanish ? '?lang=es' : ''; ?>"><?php echo $is_spanish ? 'Contáctenos' : 'Contact Us'; ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="skills-footer-copyright">
            <div class="skills-footer-container">
                <div class="skills-footer-copyright-content">
                    <div class="skills-footer-links">
                        <a href="https://skillsforchicago.org/wp-content/uploads/2025/05/Privacy-Policy-Skills-for-Chicago.pdf" target="_blank"><?php echo $is_spanish ? 'Política de Privacidad' : 'Privacy Policy'; ?></a>
                        <a href="https://skillsforchicago.org/wp-content/uploads/2025/05/Equal-Opportunity-Employment-Policy.pdf" target="_blank"><?php echo $is_spanish ? 'Política de Igualdad de Oportunidades de Empleo' : 'Equal Opportunity Employment Policy'; ?></a>
                    </div>
                    <div class="skills-footer-copyright-text">
                        © <?php echo date('Y'); ?> Skills for Chicago. <?php echo $is_spanish ? 'Todos los Derechos Reservados.' : 'All Rights Reserved.'; ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
    $output = ob_get_clean();
    return $output;
}
add_shortcode('skills_custom_footer', 'skills_custom_footer_shortcode');

// Register widget if needed
class Skills_Custom_Footer_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'skills_custom_footer_widget',
            'Skills Custom Footer',
            array('description' => 'Displays the Skills for Chicago custom footer')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo do_shortcode('[skills_custom_footer]');
        echo $args['after_widget'];
    }

    public function form($instance) {
        echo '<p>This widget displays the Skills for Chicago custom footer.</p>';
    }
}
function register_skills_footer_widget() {
    register_widget('Skills_Custom_Footer_Widget');
}
add_action('widgets_init', 'register_skills_footer_widget');

// Activation hook to create necessary files and directories
function skills_footer_activate() {
    // Create necessary directories if they don't exist
    $plugin_dir = plugin_dir_path(__FILE__);
    
    // CSS file path
    $css_file = $plugin_dir . 'skills-footer.css';
    
    // JS file path
    $js_file = $plugin_dir . 'skills-footer.js';
    
    // Create CSS file if it doesn't exist
    if (!file_exists($css_file)) {
        $css_content = '/* Font Families */
.skills-modern-footer h1, 
.skills-modern-footer h2, 
.skills-modern-footer h3, 
.skills-modern-footer h4, 
.skills-modern-footer h5, 
.skills-modern-footer span {
    font-family: Greycliff CF, sans-serif;
}

.skills-modern-footer p, 
.skills-modern-footer a {
    font-family: Greycliff CF, Light;
}

/* Footer Content */
.skills-modern-footer {
    background-color: #1F3707;
    color: white;
    padding: 60px 0 0 0;
    font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
    position: relative;
    overflow: hidden; /* Keep the background logo contained within the footer */
}

.wpml-ls-legacy-list-horizontal.wpml-ls-statics-footer {
    display: none;
}

.elementor-widget:not(:last-child) {
    margin-bottom: 0px !important;
}

/* Add the SC logo as a background on the left side */
.skills-modern-footer::before {
    content: "";
    position: absolute;
    top: 50%;
    left: -10%; /* Position on the left side instead of right */
    transform: translateY(-50%);
    width: 60%; /* Large size to match the image */
    height: 140%;
    background-image: url("https://ibc.gzr.mybluehost.me/wp-content/uploads/2025/04/Artboard-1-copy.png");
    background-repeat: no-repeat;
    background-position: center center;
    background-size: contain;
    z-index: 0;
    opacity: 0.20; /* Dark but subtle appearance */
}

/* Ensure footer content stays on top of the background image */
.skills-footer-container, .skills-footer-copyright {
    position: relative;
    z-index: 1;
}

.skills-footer-container {
    padding: 0 6%;
    font-weight: 700;
}

.skills-footer-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    position: relative;
}

.skills-footer-divider {
    flex: 1;
    height: 1px;
    background-color: #ffffff;
    margin: 0 40px;
}

.skills-footer-logo img {
    height: 80px !important;
}

.skills-footer-social {
    display: flex;
    gap: 20px;
}

.skills-footer-social a {
    color: white;
    font-size: 20px;
    transition: opacity 0.3s ease;
}

.skills-footer-social a:hover {
    opacity: 0.8;
}

/* Updated footer navigation styles to fix vertical alignment */
.skills-footer-nav {
    display: flex;
    justify-content: space-between;
    align-items: flex-start; /* Changed from center to flex-start */
    margin-bottom: 60px;
}

.skills-footer-nav-section {
    display: flex;
    flex-direction: column;
    min-height: 300px; /* Set a minimum height to ensure consistent spacing */
}

.skills-footer-nav-section h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 25px;
    text-transform: uppercase;
    height: 22px; /* Fixed height for the heading */
    line-height: 22px; /* Match the height to ensure consistent alignment */
}

.skills-footer-nav-links {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.skills-footer-nav-links a {
    color: white;
    text-decoration: none;
    font-size: 12px;
    transition: opacity 0.3s ease;
    font-weight: 300;
    font-family: Greycliff CF, Light;
}

.skills-footer-nav-links a:hover {
    opacity: 0.8;
    text-decoration: underline;
}

.skills-footer-copyright {
    background-color: #1F3707;
    padding: 20px 0;
    font-size: 14px;
    border-top: 1px solid #DCDCDC;
}

.skills-footer-copyright-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.skills-footer-links {
    display: flex;
    gap: 30px;
}

.skills-footer-links a {
    color: white;
    text-decoration: none;
    font-size: 14px;
    transition: opacity 0.3s ease;
    font-weight: 300;
}

.skills-footer-links a:hover {
    opacity: 0.8;
    text-decoration: underline;
}

.skills-footer-copyright-text {
    text-align: right;
}

/* NEW STYLES FOR MOBILE FOOTER ACCORDION */
.skills-mobile-footer {
    display: none; /* Hide on desktop */
}

.skills-footer-accordion {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.skills-footer-accordion-header {
    padding: 15px 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.skills-footer-accordion-title {
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: white;
    margin: 0;
}

.skills-footer-accordion-icon {
    transition: transform 0.3s ease;
    color: white;
}

.skills-footer-accordion-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    padding: 0 20px;
}

.skills-footer-accordion.active .skills-footer-accordion-icon {
    transform: rotate(180deg);
}

.skills-footer-accordion.active .skills-footer-accordion-content {
    max-height: 300px; /* Adjust as needed */
    padding-bottom: 15px;
}

/* Mobile Footer Links */
.skills-footer-mobile-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.skills-footer-mobile-links li {
    margin-bottom: 12px;
}

.skills-footer-mobile-links a {
    color: white;
    text-decoration: none;
    font-size: 15px;
    display: block;
    opacity: 0.9;
    text-align: left;
    font-weight: 300;
}

.skills-footer-mobile-links a:hover {
    opacity: 1;
    text-decoration: underline;
}

/* Responsive adjustments */
@media (max-width: 1354px) {
    .skills-footer-nav {
        flex-wrap: wrap;
        gap: 30px;
    }
    
    .skills-footer-nav-section {
        flex: 1 0 30%;
        min-height: auto;
    }
}

@media (max-width: 976px) {
    .skills-modern-footer::before {
        width: 100%;
        left: -20%;
    }
    
    .skills-footer-top {
        flex-direction: column;
        gap: 0px;
        text-align: center;
        margin-bottom: 0px;
    }

    /* Hide desktop footer navigation and show accordion */
    .skills-footer-nav {
        display: none;
    }

    .skills-mobile-footer {
        display: block;
    }
    
    .skills-footer-copyright-content {
        flex-direction: column;
        gap: 15px;
    }
    
    .skills-footer-links {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .skills-footer-copyright-text {
        text-align: center;
    }
}

@media (max-width: 576px) {
    .skills-footer-nav {
        grid-template-columns: 1fr;
        text-align: center;
    }
}';
        
        file_put_contents($css_file, $css_content);
    }
    
    // Create JS file if it doesn't exist
    if (!file_exists($js_file)) {
        $js_content = 'jQuery(document).ready(function($) {
    // Footer Accordion Functionality
    $(".skills-footer-accordion-header").on("click", function() {
        var accordion = $(this).parent();
        
        // Toggle active class
        accordion.toggleClass("active");
        
        // Close all other accordions (optional - comment out if you want multiple open)
        $(".skills-footer-accordion").not(accordion).removeClass("active");
    });
});';
        
        file_put_contents($js_file, $js_content);
    }
}
register_activation_hook(__FILE__, 'skills_footer_activate');