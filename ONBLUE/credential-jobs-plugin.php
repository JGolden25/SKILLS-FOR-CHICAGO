<?php
/*
Plugin Name: Credential Jobs Listings
Description: Creates a shortcode to display credential job listings from database
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin URL constant for assets
define('CREDENTIAL_JOBS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Add custom styles and scripts for job listings page
function credential_jobs_custom_assets() {
    // Only load on pages with our shortcode
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'credential_jobs')) {
        // Enqueue jQuery if not already loaded
        wp_enqueue_script('jquery');
        
        // Enqueue Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
        
        // Enqueue custom CSS
        wp_enqueue_style(
            'credential-jobs-style',
            CREDENTIAL_JOBS_PLUGIN_URL . 'credential-jobs.css',
            array('font-awesome'),
            '1.0.0',
            'all'
        );
        
        // Enqueue custom JS (minimal now, but keeping it for future functionality)
        wp_enqueue_script(
            'credential-jobs-scripts',
            CREDENTIAL_JOBS_PLUGIN_URL . 'credential-jobs.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'credential_jobs_custom_assets');

// Add shortcode function
function credential_jobs_shortcode($atts) {
    // Start output buffering
    ob_start();
    
    // Check if we're displaying a job detail page
    $job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
    
    echo '<div class="credential-jobs-wrapper">';
    if ($job_id > 0) {
        echo credential_render_job_detail($job_id);
    } else {
        echo credential_render_job_listings();
    }
    echo '</div>';
    
    // Get the contents of the output buffer
    $output = ob_get_clean();
    return $output;
}

// Function to render job listings - only credential jobs
function credential_render_job_listings() {
    // Start output buffering to capture all output
    ob_start();
    
    // Function to sanitize output
    function credential_clean_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    // Get current page
    $current_page = isset($_GET['job_page']) ? max(1, intval($_GET['job_page'])) : 1;
    $jobs_per_page = 20;
    $offset = ($current_page - 1) * $jobs_per_page;
    
    // Get WordPress database connection
    global $wpdb;
    
    // Build the SQL query to get only credential jobs
    $sql = "SELECT * FROM JobDetails WHERE Published = 1 AND `Credential Job` = 1";
    $count_sql = "SELECT COUNT(*) FROM JobDetails WHERE Published = 1 AND `Credential Job` = 1";
    
    // Add ordering
    $sql .= " ORDER BY JN DESC";
    
    // Add pagination
    $sql .= " LIMIT %d OFFSET %d";
    $sql = $wpdb->prepare($sql, $jobs_per_page, $offset);
    
    // Get total job count
    $total_jobs = $wpdb->get_var($count_sql);
    
    // Get results for current page
    $results = $wpdb->get_results($sql);
    $job_count = count($results);
    
    // Calculate pagination info
    $total_pages = ceil($total_jobs / $jobs_per_page);
    $showing_from = min($total_jobs, $offset + 1);
    $showing_to = min($total_jobs, $offset + $jobs_per_page);
    
    // Main Content HTML
    echo '<div class="credential-jobs-container">';
    echo '<div class="page-title-container">';
    echo '<h1 class="page-title">Credential Jobs</h1>';
    echo '<h2 class="page-subtitle">Find Your Next Credential Job. Build Your Future.</h2>';
    echo '</div>';
    
    // Results Count
    echo '<div class="results-count">' . $total_jobs . ' Credential Jobs Available</div>';
    
    // Job Listings Grid
    echo '<div class="job-listings">';
    
    if ($job_count > 0) {
        foreach ($results as $row) {
            // Create a placeholder logo URL using company name
            $logo_url = !empty($row->{'Account Logo'}) ? 
                esc_url($row->{'Account Logo'}) : 
                "https://api.placeholder.com/100x40?text=" . urlencode($row->Employer);
            
            // Create job detail URL
            $current_page_url = get_permalink();
            $current_query_params = $_GET;
            $current_query_params['job_id'] = $row->JN;
            $job_detail_url = add_query_arg($current_query_params, $current_page_url);
            
            echo '<div class="job-card" data-job-id="' . $row->JN . '">';
            
            // Make the entire card clickable
            echo '<a href="' . esc_url($job_detail_url) . '" style="display:block; text-decoration:none; color:inherit;">';
            
            echo '<img src="' . $logo_url . '" alt="' . credential_clean_output($row->Employer) . ' logo" class="company-logo">';
            echo '<h3 class="job-title">' . credential_clean_output($row->{'Job Title'}) . '</h3>';
            echo '<p class="company-name">' . credential_clean_output($row->Employer) . '</p>';
            echo '<p class="job-location">' . credential_clean_output($row->Location) . '</p>';
            
            // Education requirement
            if (!empty($row->Education)) {
                echo '<p class="job-education">' . credential_clean_output($row->Education) . '</p>';
            }
            
            // Shift information
            if (!empty($row->{'Shift Details'})) {
                echo '<p class="job-shift">' . credential_clean_output($row->{'Shift Details'}) . '</p>';
            }
            
            echo '</a>';
            echo '</div>';
        }
    } else {
        echo '<div class="no-results">No credential jobs found.</div>';
    }
    
    echo '</div>'; // End job-listings
    
    // Pagination
    if ($total_pages > 1) {
        echo '<div class="pagination">';
        
        // Build a base URL that preserves all current filters
        $current_url = strtok($_SERVER["REQUEST_URI"], '?');
        $query_params = $_GET;
        
        // Previous page button
        if ($current_page > 1) {
            $prev_query = $query_params;
            $prev_query['job_page'] = $current_page - 1;
            unset($prev_query['page']);
            $prev_url = $current_url . '?' . http_build_query($prev_query);
            echo '<a href="' . esc_url($prev_url) . '" class="pagination-btn prev-page"><i class="fas fa-chevron-left"></i> Previous</a>';
        } else {
            echo '<span class="pagination-btn prev-page disabled"><i class="fas fa-chevron-left"></i> Previous</span>';
        }
        
        // Page number text
        echo '<span class="pagination-info">Page ' . $current_page . ' of ' . $total_pages . '</span>';
        
        // Next page button
        if ($current_page < $total_pages) {
            $next_query = $query_params;
            $next_query['job_page'] = $current_page + 1;
            unset($next_query['page']);
            $next_url = $current_url . '?' . http_build_query($next_query);
            echo '<a href="' . esc_url($next_url) . '" class="pagination-btn next-page">Next <i class="fas fa-chevron-right"></i></a>';
        } else {
            echo '<span class="pagination-btn next-page disabled">Next <i class="fas fa-chevron-right"></i></span>';
        }
        
        echo '</div>'; // End pagination
    }
    
    echo '</div>'; // End credential-jobs-container
    
    // Get the contents of the output buffer and clean it
    $output = ob_get_clean();
    return $output;
}

// Function to render a job detail - only for credential jobs
function credential_render_job_detail($job_id) {
    global $wpdb;
    
    // Get job details from database - only credential jobs
    $job_sql = $wpdb->prepare("SELECT * FROM JobDetails WHERE JN = %d AND Published = 1 AND `Credential Job` = 1", $job_id);
    $job = $wpdb->get_row($job_sql);
    
    if (!$job) {
        // Job not found or not published or not a credential job
        return '<div class="job-not-found">
            <h2>Job Not Found</h2>
            <p>The credential job you are looking for is not available or has been removed.</p>
            <a href="' . esc_url(remove_query_arg('job_id')) . '" class="back-btn">Back to Credential Job Listings</a>
        </div>';
    }
    
    // Start output buffering
    ob_start();
    
    // Function to sanitize output
    function credential_clean_detail_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    // Create a placeholder logo URL using company name
    $logo_url = !empty($job->{'Account Logo'}) ? 
        esc_url($job->{'Account Logo'}) : 
        "https://api.placeholder.com/120x60?text=" . urlencode($job->Employer);
    
    // Back to listings link
    echo '<a href="' . esc_url(remove_query_arg('job_id')) . '" class="back-link"><i class="fas fa-arrow-left"></i> Back to all credential jobs</a>';

    echo '<div class="job-detail-container">';
    
    // Job Title Header with Logo
    echo '<div class="job-title-header">';
    
    // Left - Company Logo
    echo '<div class="company-logo-container">';
    echo '<img src="' . $logo_url . '" alt="' . credential_clean_detail_output($job->Employer) . ' logo" class="company-header-logo">';
    echo '</div>';
    
    // Middle - Job Title and Company
    echo '<div class="job-title-info">';
    echo '<h1 class="job-title-main">' . credential_clean_detail_output($job->{'Job Title'}) . '</h1>';
    echo '<p class="job-company-name">' . credential_clean_detail_output($job->Employer) . '</p>';
    echo '</div>';
    
    // Right - Apply Button
    echo '<div class="apply-button-container">';
    // Get source parameter if it exists
    $source = isset($_GET['source']) ? urlencode($_GET['source']) : '';
    // Build apply now URL with job_id and optional source
    $apply_now_url = "https://skillsforchicago.org/candidate-login/?tfa_3={$job->JN}&tfa_5={$source}";
    echo '<a href="' . esc_url($apply_now_url) . '" class="action-button">
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
    echo '<p class="metadata-value"><span class="green-bullet"></span> ' . credential_clean_detail_output($job->Employer) . '</p>';
    echo '</div>';
    
    // Job Title
    echo '<div class="job-metadata-item">';
    echo '<h3 class="metadata-label">Job Title</h3>';
    echo '<p class="metadata-value"><span class="green-bullet"></span> ' . credential_clean_detail_output($job->{'Job Title'}) . '</p>';
    echo '</div>';
    
    // Job Type
    if (!empty($job->{'Job Type'})) {
        echo '<div class="job-metadata-item">';
        echo '<h3 class="metadata-label">Job Type</h3>';
        echo '<p class="metadata-value"><span class="green-bullet"></span> ' . credential_clean_detail_output($job->{'Job Type'}) . '</p>';
        echo '</div>';
    }
    
    // Location
    echo '<div class="job-metadata-item">';
    echo '<h3 class="metadata-label">Location</h3>';
    echo '<p class="metadata-value"><span class="green-bullet"></span> ' . credential_clean_detail_output($job->Location) . '</p>';
    echo '</div>';
    
    // Shift Details
    if (!empty($job->{'Shift Details'})) {
        echo '<div class="job-metadata-item">';
        echo '<h3 class="metadata-label">Shift Details</h3>';
        echo '<p class="metadata-value"><span class="green-bullet"></span> ' . credential_clean_detail_output($job->{'Shift Details'}) . '</p>';
        echo '</div>';
    }
    
    // Compensation
    if (!empty($job->Compensation)) {
        echo '<div class="job-metadata-item">';
        echo '<h3 class="metadata-label">Compensation</h3>';
        echo '<p class="metadata-value"><span class="green-bullet"></span> ' . credential_clean_detail_output($job->Compensation) . '</p>';
        echo '</div>';
    }
    
    echo '</div>'; // End job-metadata-left
    
    // Right column - job image
    echo '<div class="job-metadata-right">';
    $job_image_url = !empty($job->{'Job Image Link'}) ? 
        esc_url($job->{'Job Image Link'}) : 
        esc_url(CREDENTIAL_JOBS_PLUGIN_URL . 'assets/job-image.jpg'); // Fallback to static image

    echo '<img src="' . $job_image_url . '" alt="Job preview" class="job-preview-image">';
    echo '</div>'; // End job-metadata-right
    
    echo '</div>'; // End job-metadata-section
    
    // Single column job content for the rest
    echo '<div class="job-detail-single-column">';
    
    // Job Description
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">Job Description</h2>';
    if (!empty($job->{'Job Description'})) {
        // Parse job description to create a list with bullet points
        $description = nl2br(credential_clean_detail_output($job->{'Job Description'}));
        $description_lines = explode('<br />', $description);
        
        echo '<ul class="job-detail-list">';
        foreach ($description_lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                echo '<li><span class="green-bullet"></span> <span>' . $line . '</span></li>';
            }
        }
        echo '</ul>';
    } else {
        echo '<p>No detailed job description available.</p>';
    }
    echo '</div>';
    
    // Skills and Qualifications
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">Skills & Qualifications</h2>';
    if (!empty($job->{'Skills and Qualifications'})) {
        // Parse skills to create a list with bullet points
        $skills = nl2br(credential_clean_detail_output($job->{'Skills and Qualifications'}));
        $skills_lines = explode('<br />', $skills);
        
        echo '<ul class="job-detail-list">';
        foreach ($skills_lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                echo '<li><span class="green-bullet"></span> <span>' . $line . '</span></li>';
            }
        }
        echo '</ul>';
    } else {
        echo '<p>No specific skills or qualifications listed.</p>';
    }
    echo '</div>';
    
    // Education Section
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">Education</h2>';
    if (!empty($job->Education)) {
        echo '<ul class="job-detail-list">';
        echo '<li><span class="green-bullet"></span> <span>' . credential_clean_detail_output($job->Education) . '</span></li>';
        echo '</ul>';
    } else {
        echo '<p>No specific education requirements listed.</p>';
    }
    echo '</div>';
    
    // Benefits
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">Benefits</h2>';
    
    echo '<ul class="job-detail-list">';
    
    // Parse the Benefits field
    if (!empty($job->Benefits)) {
        $benefits = preg_split('/[,\n]+/', $job->Benefits);
        foreach ($benefits as $benefit) {
            $benefit = trim($benefit);
            if (!empty($benefit)) {
                echo '<li><span class="green-bullet"></span> <span>' . credential_clean_detail_output($benefit) . '</span></li>';
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
        echo '<div class="employer-description">' . nl2br(credential_clean_detail_output($job->{'About the Employer'})) . '</div>';
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
add_shortcode('credential_jobs', 'credential_jobs_shortcode');

// Add credential_jobs_activate function for plugin activation
function credential_jobs_activate() {
    // Create the necessary directories
    $assets_dir = plugin_dir_path(__FILE__) . 'assets';
    
    if (!file_exists($assets_dir)) {
        wp_mkdir_p($assets_dir);
    }
    
    // Copy default image if needed
    $default_image = $assets_dir . '/job-image.jpg';
    if (!file_exists($default_image)) {
        // If you have a default image to copy, do it here
    }
}
register_activation_hook(__FILE__, 'credential_jobs_activate');