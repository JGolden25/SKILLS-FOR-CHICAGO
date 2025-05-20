<?php
/*
Plugin Name: Skills Job Links Emergency Fix
Description: Emergency fix for job detail links when using ?lang=es parameter
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Add JS to fix the links on the page
function skills_job_emergency_fix_script() {
    // Only run when the Spanish parameter is present
    if (!isset($_GET['lang']) || $_GET['lang'] !== 'es') {
        return;
    }
    
    ?>
    <script>
    // Execute immediately when the script loads, and then again when DOM is fully loaded
    // This ensures we catch both initial and dynamically loaded content
    fixJobLinks();
    
    document.addEventListener('DOMContentLoaded', function() {
        fixJobLinks();
        // Run it again after a short delay to catch any elements loaded by JS
        setTimeout(fixJobLinks, 500);
        // And again after a longer delay
        setTimeout(fixJobLinks, 1500);
    });
    
    function fixJobLinks() {
        console.log('Emergency job links fix running');
        
        // Fix all job cards - both links and click behavior
        document.querySelectorAll('.job-card').forEach(function(card) {
            // Store the job ID for debugging
            const jobId = card.getAttribute('data-job-id');
            console.log('Processing job card with ID:', jobId);
            
            // Find the anchor tag within the job card
            const anchor = card.querySelector('a');
            if (anchor) {
                // Get current URL and create a new corrected URL
                const currentUrl = window.location.href;
                const baseUrl = window.location.pathname;
                const newUrl = baseUrl + '?lang=es&job_id=' + jobId;
                
                console.log('Fixing job link. Old:', anchor.href, 'New:', newUrl);
                
                // Update the href attribute
                anchor.href = newUrl;
                
                // Add debugging click handler
                anchor.addEventListener('click', function(e) {
                    console.log('Job link clicked with job_id:', jobId);
                    console.log('Navigating to:', this.href);
                });
            }
            
            // Also make the entire card clickable
            card.style.cursor = 'pointer';
            card.addEventListener('click', function(e) {
                // Ignore clicks on the anchor itself to prevent double navigation
                if (e.target.tagName !== 'A' && !e.target.closest('a')) {
                    console.log('Job card clicked, job_id:', jobId);
                    const url = baseUrl + '?lang=es&job_id=' + jobId;
                    console.log('Redirecting to:', url);
                    window.location.href = url;
                }
            });
        });
    }
    </script>
    <?php
}
add_action('wp_footer', 'skills_job_emergency_fix_script', 999); // Very high priority

// Add direct fix for the skills_render_job_listings function output
function skills_emergency_output_fix($content) {
    // Only apply when the Spanish parameter is present
    if (!isset($_GET['lang']) || $_GET['lang'] !== 'es') {
        return $content;
    }
    
    // If we're on a job listings page
    if (strpos($content, 'job-listings-container') !== false) {
        // Find all job card links and fix them
        $content = preg_replace_callback(
            '/<div class="job-card" data-job-id="(\d+)">(.*?)<\/div>/s',
            function($matches) {
                $job_id = $matches[1];
                $inner_content = $matches[2];
                
                // First, try to fix any existing links
                $inner_content = preg_replace(
                    '/<a href="[^"]*"/',
                    '<a href="?lang=es&job_id=' . $job_id . '"',
                    $inner_content
                );
                
                return '<div class="job-card" data-job-id="' . $job_id . '">' . $inner_content . '</div>';
            },
            $content
        );
    }
    
    return $content;
}
add_filter('the_content', 'skills_emergency_output_fix', 999); // Very high priority

// Direct fix for shortcode output
function skills_emergency_shortcode_fix($output, $tag, $attr) {
    // Only apply to the skills_job_listings shortcode and when Spanish is active
    if ($tag !== 'skills_job_listings' || !isset($_GET['lang']) || $_GET['lang'] !== 'es') {
        return $output;
    }
    
    // Find all job card links and fix them
    $output = preg_replace_callback(
        '/<div class="job-card" data-job-id="(\d+)">(.*?)<\/div>/s',
        function($matches) {
            $job_id = $matches[1];
            $inner_content = $matches[2];
            
            // Replace any existing links
            $inner_content = preg_replace(
                '/<a href="[^"]*"/',
                '<a href="?lang=es&job_id=' . $job_id . '"',
                $inner_content
            );
            
            return '<div class="job-card" data-job-id="' . $job_id . '">' . $inner_content . '</div>';
        },
        $output
    );
    
    return $output;
}
add_filter('do_shortcode_tag', 'skills_emergency_shortcode_fix', 999, 3); // Very high priority

// Job navigation fix - ensure back button works properly
function skills_emergency_job_detail_fix($job_detail) {
    // Only apply when Spanish is active
    if (!isset($_GET['lang']) || $_GET['lang'] !== 'es') {
        return $job_detail;
    }
    
    // Fix back to listings link
    $job_detail = preg_replace(
        '/<a href="([^"]*)" class="back-link">/',
        '<a href="$1?lang=es" class="back-link">',
        $job_detail
    );
    
    return $job_detail;
}
add_filter('skills_render_job_detail', 'skills_emergency_job_detail_fix', 5);
