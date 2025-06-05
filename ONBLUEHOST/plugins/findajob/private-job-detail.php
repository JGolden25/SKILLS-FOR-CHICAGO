<?php
/*
Plugin Name: Private Job Detail
Description: Displays individual job detail pages for private, unpublished jobs
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin URL constant for assets
define('PRIVATE_JOB_DETAIL_PLUGIN_URL', plugin_dir_url(__FILE__));

// Add custom styles and scripts for job detail page
function private_job_detail_custom_assets() {
    // Only load on pages with our shortcode
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'private_job_detail')) {
        // Enqueue jQuery if not already loaded
        wp_enqueue_script('jquery');
        
        // Enqueue Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
        
        // Enqueue custom CSS
        wp_enqueue_style(
            'private-job-detail-style',
            PRIVATE_JOB_DETAIL_PLUGIN_URL . 'private-job-detail.css',
            array('font-awesome'),
            '1.0.0',
            'all'
        );
    }
}
add_action('wp_enqueue_scripts', 'private_job_detail_custom_assets');

// Add shortcode function
function private_job_detail_shortcode($atts) {
    // Start output buffering
    ob_start();
    
    // Get job_id from URL parameter
    $job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
    
    echo '<div class="private-job-detail-wrapper">';
    
    if ($job_id > 0) {
        echo private_render_job_detail($job_id);
    } else {
        echo '<div class="job-not-found">
            <h2>Job Not Found</h2>
            <p>No job ID was provided. Please check the URL and try again.</p>
        </div>';
    }
    
    echo '</div>';
    
    // Get the contents of the output buffer
    $output = ob_get_clean();
    return $output;
}

// Function to determine if content contains HTML
function private_content_has_html($content) {
    return preg_match('/<[^>]*>/', $content);
}

// Function to sanitize output - modified to handle HTML content properly
function private_clean_detail_output($data, $allow_html = false) {
    if ($allow_html) {
        return wp_kses_post($data); // Allow safe HTML
    } else {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); // Escape HTML
    }
}

// Function to render a job detail for private unpublished jobs
function private_render_job_detail($job_id) {
    global $wpdb;
    
    // Get job details from database - ONLY private unpublished jobs
    $job_sql = $wpdb->prepare("SELECT * FROM JobDetails WHERE JN = %d AND Published = 0 AND Private = 1", $job_id);
    $job = $wpdb->get_row($job_sql);
    
    if (!$job) {
        // Job not found or not private/unpublished
        return '<div class="job-not-found">
            <h2>Job Not Found</h2>
            <p>The job you are looking for is not available, has been removed, or you do not have permission to view it.</p>
        </div>';
    }
    
    // Start output buffering
    ob_start();
    
    // Create a placeholder logo URL using company name
    $logo_url = !empty($job->{'Account Logo'}) ? 
        esc_url($job->{'Account Logo'}) : 
        "https://api.placeholder.com/120x60?text=" . urlencode($job->Employer);

    // Add the banner image section
    echo '<div class="job-banner-image"></div>';

    echo '<div class="job-detail-container">';
    
    // Job Title Header with Logo
    echo '<div class="job-title-header">';
    
    // Left - Company Logo
    echo '<div class="company-logo-container">';
    echo '<img src="' . $logo_url . '" alt="' . private_clean_detail_output($job->Employer) . ' logo" class="company-header-logo">';
    echo '</div>';
    
    // Middle - Job Title and Company
    echo '<div class="job-title-info">';
    echo '<h1 class="job-title-main">' . private_clean_detail_output($job->{'Job Title'}) . '</h1>';
    echo '<p class="job-company-name">' . private_clean_detail_output($job->Employer) . '</p>';
    echo '</div>';
    
    // Right - Apply Button
    echo '<div class="apply-button-container">';
    // Get parameters if they exist - DO NOT urlencode yet
    $source = isset($_GET['source']) ? $_GET['source'] : '';
    $tfa_49 = isset($_GET['tfa_49']) ? $_GET['tfa_49'] : '';
    $tfa_89 = isset($_GET['tfa_89']) ? $_GET['tfa_89'] : '';
    $cid = isset($_GET['CID']) ? $_GET['CID'] : '';

    // Build apply now URL with job_id
    $apply_now_url = "https://skillsforchicago.org/candidate-login/?tfa_3=" . urlencode($job->JN);
    
    // Add source parameter if it exists (as tfa_5)
    if (!empty($source)) {
        $apply_now_url .= "&tfa_5=" . urlencode($source);
    }

    // Add cred=credential parameter ONLY if this is a credential job
    if (isset($job->{'Credential Job'}) && $job->{'Credential Job'} == 1) {
        $apply_now_url .= "&cred=credential";
    }
    
    // Add additional parameters if they exist
    if (!empty($tfa_49)) {
        $apply_now_url .= "&tfa_49=" . urlencode($tfa_49);
    }
    if (!empty($tfa_89)) {
        $apply_now_url .= "&tfa_89=" . urlencode($tfa_89);
    }
    // Add CID parameter if it exists
    if (!empty($cid)) {
        $apply_now_url .= "&CID=" . urlencode($cid);
    }
    
    echo '<a href="' . esc_url($apply_now_url) . '" class="action-button" style="width: 150px;">
                <span>Apply Now</span>
                <div class="circle-icon">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>';
    echo '</div>';
    
    echo '</div>'; // End job-title-header
    
    // Job details and image - two column layout
    echo '<div class="job-metadata-section">';
    
    // Left column - job details
    echo '<div class="job-metadata-left">';
    
    // Employer Name
    echo '<div class="job-metadata-item">';
    echo '<h3 class="metadata-label">Employer Name:</h3>';
    echo '<p class="metadata-value"><span class="green-bullet"></span> ' . private_clean_detail_output($job->Employer) . '</p>';
    echo '</div>';
    
    // Job Title
    echo '<div class="job-metadata-item">';
    echo '<h3 class="metadata-label">Job Title</h3>';
    echo '<p class="metadata-value"><span class="green-bullet"></span> ' . private_clean_detail_output($job->{'Job Title'}) . '</p>';
    echo '</div>';
    
    // Job Type
    if (!empty($job->{'Job Type'})) {
        echo '<div class="job-metadata-item">';
        echo '<h3 class="metadata-label">Job Type</h3>';
        echo '<p class="metadata-value"><span class="green-bullet"></span> ' . private_clean_detail_output($job->{'Job Type'}) . '</p>';
        echo '</div>';
    }
    
    // Location
    echo '<div class="job-metadata-item">';
    echo '<h3 class="metadata-label">Location</h3>';
    echo '<p class="metadata-value"><span class="green-bullet"></span> ' . private_clean_detail_output($job->Location) . '</p>';
    echo '</div>';
    
    // Shift Details
    if (!empty($job->{'Shift Details'})) {
        echo '<div class="job-metadata-item">';
        echo '<h3 class="metadata-label">Shift Details</h3>';
        echo '<p class="metadata-value"><span class="green-bullet"></span> ' . private_clean_detail_output($job->{'Shift Details'}) . '</p>';
        echo '</div>';
    }
    
    // Compensation
    if (!empty($job->Compensation)) {
        echo '<div class="job-metadata-item">';
        echo '<h3 class="metadata-label">Compensation</h3>';
        echo '<p class="metadata-value"><span class="green-bullet"></span> ' . private_clean_detail_output($job->Compensation) . '</p>';
        echo '</div>';
    }
    
    echo '</div>'; // End job-metadata-left
    
    // Right column - job image
    echo '<div class="job-metadata-right">';
    $job_image_url = !empty($job->{'Job Image Link'}) ? 
        esc_url($job->{'Job Image Link'}) : 
        esc_url(PRIVATE_JOB_DETAIL_PLUGIN_URL . 'assets/job-image.jpg'); // Fallback to static image

    echo '<img src="' . $job_image_url . '" alt="Job preview" class="job-preview-image">';
    echo '</div>'; // End job-metadata-right
    
    echo '</div>'; // End job-metadata-section
    
    // Single column job content for the rest
    echo '<div class="job-detail-single-column">';
    
    // Job Description
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">Job Description</h2>';
    if (!empty($job->{'Job Description'})) {
        $description = $job->{'Job Description'};
        
        // Check if description already contains HTML
        if (private_content_has_html($description)) {
            // If it has HTML, just display it with safe HTML allowed
            echo '<div class="job-detail-content" style="font-size: 22pt !important;">' . private_clean_detail_output($description, true) . '</div>';
        } else {
            // If no HTML, format with bullets as before
            $description = nl2br(private_clean_detail_output($description));
            $description_lines = explode('<br />', $description);
            
            echo '<ul class="job-detail-list">';
            foreach ($description_lines as $line) {
                $line = trim($line);
                if (!empty($line)) {
                    echo '<li><span class="green-bullet"></span> <span>' . $line . '</span></li>';
                }
            }
            echo '</ul>';
        }
    } else {
        echo '<p>No detailed job description available.</p>';
    }
    echo '</div>';
    
    // Skills and Qualifications
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">Skills & Qualifications</h2>';
    if (!empty($job->{'Skills and Qualifications'})) {
        $skills = $job->{'Skills and Qualifications'};
        
        // Check if skills content already contains HTML
        if (private_content_has_html($skills)) {
            // If it has HTML, just display it with safe HTML allowed
            echo '<div class="job-detail-content" style="font-size: 22pt !important;">' . private_clean_detail_output($skills, true) . '</div>';
        } else {
            // If no HTML, format with bullets as before
            $skills = nl2br(private_clean_detail_output($skills));
            $skills_lines = explode('<br />', $skills);
            
            echo '<ul class="job-detail-list">';
            foreach ($skills_lines as $line) {
                $line = trim($line);
                if (!empty($line)) {
                    echo '<li><span class="green-bullet"></span> <span>' . $line . '</span></li>';
                }
            }
            echo '</ul>';
        }
    } else {
        echo '<p>No specific skills or qualifications listed.</p>';
    }
    echo '</div>';
    
    // Education Section
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">Education</h2>';
    if (!empty($job->Education)) {
        echo '<ul class="job-detail-list">';
        echo '<li><span class="green-bullet"></span> <span>' . private_clean_detail_output($job->Education) . '</span></li>';
        echo '</ul>';
    } else {
        echo '<p>No specific education requirements listed.</p>';
    }
    echo '</div>';
    
    // Benefits
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">Benefits</h2>';
    
    echo '<ul class="job-detail-list">';
    
    // Parse the Benefits field - updated to handle semicolon separation
    if (!empty($job->Benefits)) {
        // Split by commas, new lines, AND semicolons
        $benefits = preg_split('/[,;\n]+/', $job->Benefits);
        foreach ($benefits as $benefit) {
            $benefit = trim($benefit);
            if (!empty($benefit)) {
                echo '<li><span class="green-bullet"></span> <span>' . private_clean_detail_output($benefit) . '</span></li>';
            }
        }
    } else {
        echo '<li>No specific benefits listed.</li>';
    }
    echo '</ul>';
    echo '</div>';
    
    // About the Employer
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">About the Employer</h2>';
    if (!empty($job->{'About the Employer'})) {
        echo '<div class="employer-description">' . nl2br(private_clean_detail_output($job->{'About the Employer'})) . '</div>';
    } else {
        echo '<p>No detailed information about the employer available.</p>';
    }
    echo '</div>';
    
    echo '</div>'; // End job-detail-single-column
    
    echo '</div>'; // End job-detail-container
    
    // Get the contents of the output buffer and clean it
    $output = ob_get_clean();
    return $output;
}

// Register the shortcode
add_shortcode('private_job_detail', 'private_job_detail_shortcode');

// Flush rewrite rules on plugin activation
function private_job_detail_activate() {
    flush_rewrite_rules();
    
    // Create the necessary directories for assets
    $assets_dir = plugin_dir_path(__FILE__) . 'assets';
    
    if (!file_exists($assets_dir)) {
        wp_mkdir_p($assets_dir);
    }
}
register_activation_hook(__FILE__, 'private_job_detail_activate');

// Flush rewrite rules on plugin deactivation
function private_job_detail_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'private_job_detail_deactivate');