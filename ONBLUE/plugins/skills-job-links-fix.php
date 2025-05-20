<?php
/*
Plugin Name: Skills Job Listings Link Fix
Description: Fixes job detail links when using ?lang=es parameter
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * This plugin specifically fixes the issue with job detail links not working when using 
 * the Spanish language parameter (?lang=es) on the job listings page.
 */

// Fix job detail links when in Spanish mode
function skills_job_spanish_fix_job_links() {
    if (isset($_GET['lang']) && $_GET['lang'] === 'es') {
        // Add JS to fix the links on the page
        add_action('wp_footer', 'skills_job_spanish_add_link_fix_script');
    }
}
add_action('wp', 'skills_job_spanish_fix_job_links');

// Add JavaScript to fix job links
function skills_job_spanish_add_link_fix_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fix job card links
        document.querySelectorAll('.job-card a').forEach(function(link) {
            // Get the current href
            let url = link.href;
            
            // Check if it has job_id parameter but is missing lang parameter
            if (url.includes('job_id=') && !url.includes('lang=es')) {
                // Add lang=es parameter
                url = url.includes('?') ? 
                    url.replace('?', '?lang=es&') : 
                    url + '?lang=es';
                
                // Update the link
                link.href = url;
            }
        });
        
        // Make the entire job card clickable and correctly linked
        document.querySelectorAll('.job-card').forEach(function(card) {
            card.addEventListener('click', function(e) {
                // If this is a direct click on the card (not on a child link)
                if (e.target === this || !e.target.closest('a')) {
                    const jobId = this.getAttribute('data-job-id');
                    if (jobId) {
                        // Construct proper URL with both language and job ID
                        const baseUrl = window.location.pathname;
                        window.location.href = baseUrl + '?lang=es&job_id=' + jobId;
                    }
                }
            });
        });
    });
    </script>
    <?php
}

// Fix the actual URL structure when processing job detail requests
function skills_job_spanish_fix_url_structure() {
    // Check if we're on a job listings page with both lang and job_id parameters
    if (isset($_GET['lang']) && $_GET['lang'] === 'es' && isset($_GET['job_id'])) {
        // Ensure job_id is correctly processed
        add_filter('skills_render_job_detail', 'skills_job_spanish_process_job_detail', 5, 1);
    }
}
add_action('wp', 'skills_job_spanish_fix_url_structure');

// Make sure job detail rendering works with Spanish parameter
function skills_job_spanish_process_job_detail($job_detail) {
    // Ensure any "Back to listings" links maintain the Spanish parameter
    $job_detail = preg_replace(
        '/href="([^"]*?)\?job_id=([^"]*)"/', 
        'href="$1?lang=es"', 
        $job_detail
    );
    
    // Fix Apply Now links to include Spanish parameter
    $job_detail = preg_replace(
        '/href="(https:\/\/skillsforchicago\.org\/candidate-login\/\?tfa_3=[^"]*)"/', 
        'href="$1&lang=es"', 
        $job_detail
    );
    
    return $job_detail;
}

// Priority fix for the job_id parameter in URL generation
function skills_job_spanish_fix_query_args($url, $path, $args) {
    // Only process if we're in Spanish mode and have job_id
    if (isset($args['job_id']) && isset($args['lang']) && $args['lang'] === 'es') {
        // Make sure lang parameter is at the front
        unset($args['lang']);
        $url = add_query_arg('lang', 'es', $url);
        
        // Re-add job_id 
        $url = add_query_arg('job_id', $args['job_id'], $url);
        
        return $url;
    }
    
    return $url;
}
add_filter('add_query_arg', 'skills_job_spanish_fix_query_args', 10, 3);

// Direct fix for job cards to ensure proper URL structure with Spanish parameter
function skills_job_spanish_fix_job_card_urls() {
    if (isset($_GET['lang']) && $_GET['lang'] === 'es') {
        add_filter('the_content', 'skills_job_spanish_modify_job_cards', 99);
    }
}
add_action('wp', 'skills_job_spanish_fix_job_card_urls');

// Modify job card links in the content
function skills_job_spanish_modify_job_cards($content) {
    if (has_shortcode($content, 'skills_job_listings')) {
        // Use this only if other methods fail - it's a more aggressive approach
        $content = preg_replace(
            '/<a href="([^"]*?)job_id=([^"&]*)"/', 
            '<a href="$1lang=es&job_id=$2"', 
            $content
        );
    }
    
    return $content;
}
