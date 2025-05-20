<?php
/*
Plugin Name: Skills Job Listings Bullet Fix
Description: Fixes the double bullet issue in Spanish job listings
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Only run this on Spanish pages
function skills_job_bullet_fix() {
    // Only apply on pages with the Spanish parameter
    if (!isset($_GET['lang']) || $_GET['lang'] !== 'es') {
        return;
    }
    
    // Add CSS to fix the double bullet issue
    ?>
    <style>
    /* Remove list-style bullets to avoid double bullets */
    .skills-job-listings-wrapper li,
    .job-detail-list li {
        list-style-type: none !important;
        padding-left: 25px !important;
        position: relative !important;
    }
    
    /* Add a single bullet via CSS */
    .skills-job-listings-wrapper li:before,
    .job-detail-list li:before {
        content: "" !important;
        position: absolute !important;
        left: 0 !important;
        top: 8px !important;
        width: 8px !important;
        height: 8px !important;
        background-color: #4F6B3A !important;
        border-radius: 50% !important;
        display: block !important;
    }
    
    /* Hide any duplicate bullet spans */
    .skills-job-listings-wrapper li .green-bullet,
    .job-detail-list li .green-bullet {
        display: none !important;
    }
    
    /* Fix specific bullet spacing on some templates */
    .job-detail-list li span.green-bullet {
        display: none !important;
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Run immediately and after a delay to catch all elements
        fixDoubleBullets();
        setTimeout(fixDoubleBullets, 1000);
        
        function fixDoubleBullets() {
            // Remove any bullet characters from the beginning of list items
            document.querySelectorAll('.skills-job-listings-wrapper li, .job-detail-list li').forEach(function(li) {
                // Get the HTML content
                const html = li.innerHTML;
                
                // Remove any green-bullet spans at the beginning of the content
                if (html.trim().startsWith('<span class="green-bullet"></span>')) {
                    li.innerHTML = html.replace('<span class="green-bullet"></span>', '');
                }
                
                // Remove bullet characters at the beginning of text content
                const text = li.textContent;
                if (text.startsWith('•') || text.startsWith('​•') || text.startsWith(' •')) {
                    // Get all child nodes
                    const childNodes = Array.from(li.childNodes);
                    
                    // Find the text node with the bullet
                    for (let i = 0; i < childNodes.length; i++) {
                        if (childNodes[i].nodeType === Node.TEXT_NODE) {
                            const nodeText = childNodes[i].textContent;
                            if (nodeText.startsWith('•') || nodeText.startsWith('​•') || nodeText.startsWith(' •')) {
                                // Remove the bullet character
                                childNodes[i].textContent = nodeText.replace(/^[•​ ]+/, '').trim();
                                break;
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'skills_job_bullet_fix', 99999); // Run with highest priority
