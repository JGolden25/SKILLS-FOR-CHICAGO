<?php
/*
Plugin Name: Skills Custom Header Spanish
Description: Shows Spanish version of Skills header when ?lang=es is in URL
Version: 1.2
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Spanish translations for menu items
function skills_get_spanish_translations() {
    return array(
        'Home' => 'Inicio',
        'About Us' => 'Sobre Nosotros',
        'Who We Are' => 'Quiénes Somos',
        'Our Team' => 'Nuestro Equipo',
        'Annual Reports & Financials' => 'Informes Anuales y Finanzas',
        'Our Work in Action' => 'Nuestro Trabajo en Acción',
        'Careers at Skills' => 'Carreras en Skills',
        'Job Seekers' => 'Buscadores de Empleo',
        'Find A Job' => 'Encuentra un Trabajo',
        'Career Resources' => 'Recursos Profesionales',
        'FAQs for Job Seekers' => 'Preguntas Frecuentes',
        'Employers' => 'Empleadores',
        'Partner With Us' => 'Asóciate Con Nosotros',
        'Our Employer Partners' => 'Nuestros Socios Empleadores',
        'Community Partners' => 'Socios Comunitarios',
        'Collaborate With Us' => 'Colabora Con Nosotros',
        'Community Impact' => 'Impacto Comunitario',
        'Our Community Partners' => 'Nuestros Socios Comunitarios',
        'Donors' => 'Donantes',
        'Events' => 'Eventos',
        'Events & Highlights' => 'Eventos y Destacados',
        'Employment Champions Breakfast' => 'Desayuno de Campeones de Empleo',
        'National Expansion' => 'Expansión Nacional',
        'Contact Us' => 'Contáctenos',
        'Donate' => 'Donar'
    );
}

// Function to process HTML and translate dropdown menu items
function skills_translate_dropdown_items($html) {
    $translations = skills_get_spanish_translations();
    
    // Regular expression to find dropdown toggle items with their text and icon
    $pattern = '/<a href="#" class="skills-nav-link skills-dropdown-toggle">(.*?) <i class="fas fa-chevron-down"><\/i><\/a>/';
    
    $html = preg_replace_callback($pattern, function($matches) use ($translations) {
        $text = $matches[1];
        
        // Check if this text has a translation
        foreach ($translations as $english => $spanish) {
            if ($text === $english) {
                // Replace the text but keep the HTML structure
                return '<a href="#" class="skills-nav-link skills-dropdown-toggle">' . $spanish . ' <i class="fas fa-chevron-down"></i></a>';
            }
        }
        
        // If no translation found, return unchanged
        return $matches[0];
    }, $html);
    
    // Also handle mobile dropdown toggles
    $mobile_pattern = '/<div class="skills-mobile-dropdown-toggle"><span>(.*?)<\/span><i class="fas fa-chevron-down"><\/i><\/div>/';
    
    $html = preg_replace_callback($mobile_pattern, function($matches) use ($translations) {
        $text = $matches[1];
        
        // Check if this text has a translation
        foreach ($translations as $english => $spanish) {
            if ($text === $english) {
                // Replace the text but keep the HTML structure
                return '<div class="skills-mobile-dropdown-toggle"><span>' . $spanish . '</span><i class="fas fa-chevron-down"></i></div>';
            }
        }
        
        // If no translation found, return unchanged
        return $matches[0];
    }, $html);
    
    return $html;
}

// Function to process HTML and add lang=es to links, except for language switcher
function skills_process_html_for_spanish($html) {
    $dom = new DOMDocument();
    
    // Use @ to suppress warnings from potentially malformed HTML
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    // Get all links
    $links = $dom->getElementsByTagName('a');
    
    // Process each link
    foreach ($links as $link) {
        $href = $link->getAttribute('href');
        
        // Skip if link is empty
        if (empty($href)) continue;
        
        // Check if this is part of language switcher (parent classes)
        $is_language_switcher = false;
        $parent = $link->parentNode;
        
        while ($parent && $parent->nodeType === XML_ELEMENT_NODE) {
            if ($parent->hasAttribute('class')) {
                $classes = $parent->getAttribute('class');
                if (strpos($classes, 'skills-language-switcher') !== false || 
                    strpos($classes, 'wpml-ls') !== false) {
                    $is_language_switcher = true;
                    break;
                }
            }
            $parent = $parent->parentNode;
        }
        
        // If link is a language switcher, skip it
        if ($is_language_switcher) {
            continue;
        }
        
        // If link points to Skills for Chicago website and doesn't have lang parameter
        if (strpos($href, 'skillsforchicago.org') !== false && strpos($href, 'lang=') === false) {
            // Add lang=es parameter
            $sep = (strpos($href, '?') !== false) ? '&' : '?';
            $link->setAttribute('href', $href . $sep . 'lang=es');
        }
    }
    
    // Get the HTML back
    $processedHtml = $dom->saveHTML();
    
    // If DOMDocument failed, fall back to regex method but exclude language switcher sections
    if (!$processedHtml) {
        // Simple regex that attempts to avoid language switcher sections
        $processedHtml = preg_replace_callback('/<a\s+([^>]*?)href="(https?:\/\/skillsforchicago\.org\/[^"]*?)(?:\?|")([^>]*?)>/i', 
            function($matches) use ($html) {
                // Check if this link is within a language switcher section
                $before_link = substr($html, 0, strpos($html, $matches[0]));
                
                // If we find language switcher class before the closest div, skip this link
                $last_div_pos = strrpos($before_link, '<div');
                if ($last_div_pos !== false) {
                    $div_section = substr($before_link, $last_div_pos);
                    if (strpos($div_section, 'skills-language-switcher') !== false || 
                        strpos($div_section, 'wpml-ls') !== false) {
                        return $matches[0]; // Return unchanged
                    }
                }
                
                // Not in language switcher, add lang parameter
                $sep = (strpos($matches[2], '?') !== false) ? '&' : '?';
                return "<a {$matches[1]}href=\"{$matches[2]}{$sep}lang=es\"{$matches[3]}>";
            }, 
            $html
        );
    }
    
    return $processedHtml;
}

// Process content and translate text
function skills_translate_content($content) {
    // First, translate dropdown menu items specifically
    $content = skills_translate_dropdown_items($content);
    
    // Then do general text translations
    $translations = skills_get_spanish_translations();
    foreach ($translations as $english => $spanish) {
        $content = str_replace('>' . $english . '<', '>' . $spanish . '<', $content);
    }
    
    // Process links - add lang=es except to language switcher
    $content = skills_process_html_for_spanish($content);
    
    return $content;
}

// Create shortcode for Spanish skills header
function skills_custom_header_span_shortcode($atts) {
    // Get the content from the original shortcode
    $original_content = do_shortcode('[skills_custom_header]');
    
    // Process and translate the content
    $translated_content = skills_translate_content($original_content);
    
    return $translated_content;
}
add_shortcode('skills_custom_header_span', 'skills_custom_header_span_shortcode');

// Function to check if URL has lang=es and swap the header
function skills_check_url_language() {
    // Only if ?lang=es is in the URL
    if (isset($_GET['lang']) && $_GET['lang'] === 'es') {
        // Filter the output of the original shortcode
        add_filter('the_content', 'skills_replace_header_shortcode');
        add_filter('widget_text', 'skills_replace_header_shortcode');
        add_filter('do_shortcode_tag', 'skills_filter_shortcode_output', 10, 3);
    }
}
add_action('wp', 'skills_check_url_language');

// Filter function to replace content with Spanish version
function skills_replace_header_shortcode($content) {
    // Replace original shortcode with Spanish shortcode
    return str_replace('[skills_custom_header]', '[skills_custom_header_span]', $content);
}

// Filter function to capture the original shortcode output
function skills_filter_shortcode_output($output, $tag, $attr) {
    if ($tag === 'skills_custom_header') {
        // Process and translate the content
        $output = skills_translate_content($output);
    }
    return $output;
}

// Add a simple widget
class Skills_Spanish_Header_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'skills_spanish_header_widget',
            'Skills Spanish Header',
            array('description' => 'Spanish version of Skills for Chicago header')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo do_shortcode('[skills_custom_header_span]');
        echo $args['after_widget'];
    }

    public function form($instance) {
        echo '<p>Displays the Spanish version of the Skills for Chicago header.</p>';
    }
}

function register_skills_spanish_header_widget() {
    register_widget('Skills_Spanish_Header_Widget');
}
add_action('widgets_init', 'register_skills_spanish_header_widget');