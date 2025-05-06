<?php
/*
Plugin Name: Skills Job Listings
Description: Creates a shortcode to display job listings from database with individual job detail pages
Version: 1.2
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin URL constant for assets
define('SKILLS_JOB_LISTINGS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Add shortcode function
function skills_job_listings_shortcode($atts) {
    // Check if we're displaying a job detail page
    $job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
    
    if ($job_id > 0) {
        return '<div class="skills-job-listings-wrapper">' . skills_render_job_detail($job_id) . '</div>';
    } else {
        return '<div class="skills-job-listings-wrapper">' . skills_render_job_listings() . '</div>';
    }
}

// Function to render job listings
function skills_render_job_listings() {
    // Start output buffering to capture all output
    ob_start();
    
    // Function to sanitize output
    function skills_clean_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    // Get filter parameters - updated to handle arrays
    $employer_filters = isset($_GET['employer']) ? (array)$_GET['employer'] : array();
    $job_type_filters = isset($_GET['job_type']) ? (array)$_GET['job_type'] : array();
    $location_filters = isset($_GET['location']) ? (array)$_GET['location'] : array();
    
    // Clean input for security
    $employer_filters = array_map('sanitize_text_field', $employer_filters);
    $job_type_filters = array_map('sanitize_text_field', $job_type_filters);
    $location_filters = array_map('sanitize_text_field', $location_filters);
    
    // Get WordPress database connection
    global $wpdb;
    
    // Build the SQL query with possible filters - using  actual column names
    $sql = "SELECT * FROM JobDetails WHERE Published = 1";
    $params = array();
    
    // Handle multiple employer filters
    if (!empty($employer_filters)) {
        $placeholders = array_fill(0, count($employer_filters), '%s');
        $sql .= " AND Employer IN (" . implode(', ', $placeholders) . ")";
        $params = array_merge($params, $employer_filters);
    }
    
    // Handle multiple job type filters
    if (!empty($job_type_filters)) {
        $placeholders = array_fill(0, count($job_type_filters), '%s');
        $sql .= " AND `Job Type` IN (" . implode(', ', $placeholders) . ")";
        $params = array_merge($params, $job_type_filters);
    }
    
    // Handle multiple location filters
    if (!empty($location_filters)) {
        $location_clauses = [];
        foreach ($location_filters as $location) {
            $location_clauses[] = "Location LIKE %s";
            $params[] = '%' . $wpdb->esc_like($location) . '%';
        }
        $sql .= " AND (" . implode(' OR ', $location_clauses) . ")";
    }
    
    // Add ordering
    $sql .= " ORDER BY JN DESC";
    
    // Prepare the query with WordPress functions
    if (!empty($params)) {
        $sql = $wpdb->prepare($sql, $params);
    }
    
    // Get results
    $results = $wpdb->get_results($sql);
    $job_count = count($results);
    
    // Get unique values for filter options
    function skills_get_filter_options($column) {
        global $wpdb;
        // Escape column name with backticks for special column names with spaces
        $sql = "SELECT DISTINCT `{$column}` FROM JobDetails WHERE Published = 1 AND `{$column}` IS NOT NULL AND `{$column}` != '' ORDER BY `{$column}`";
        return $wpdb->get_col($sql);
    }
    
    $employers = skills_get_filter_options('Employer');
    $job_types = skills_get_filter_options('Job Type');
    $locations = skills_get_filter_options('Location');
    
    // For simplicity, extract city names for location filter
    function skills_extract_city($location) {
        $parts = explode(',', $location);
        return trim($parts[0]);
    }
    
    $cities = array();
    foreach ($locations as $location) {
        $city = skills_extract_city($location);
        if (!in_array($city, $cities) && !empty($city)) {
            $cities[] = $city;
        }
    }
    
    // Main Content HTML
    echo '<div class="job-listings-container">';
    echo '<div class="page-title-container">';
    echo '<h1 class="page-title">Find a Job</h1>';
    echo '<h2 class="page-subtitle">Find Your Next Job. Build Your Future.</h2>';
    echo '<p class="page-description">';
    echo 'At Skills for Chicago, we connect job seekers with real job opportunities by partnering with employers ready to hire. ';
    echo 'Whether you\'re looking for a new job, a better opportunity, or career support, we provide the resources, guidance, ';
    echo 'and employer connections to help you succeed.';
    echo '</p>';
    echo '</div>';
    
    // Create main filters form
    echo '<form id="main-filters-form" action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="GET">';
    
    // Filter Section
    echo '<div class="filter-section">';
    
    // Employer Dropdown
    echo '<div class="filter-dropdown">';
    echo '<button type="button" class="filter-btn" id="employer-filter-btn">Employer <i class="fas fa-chevron-down"></i></button>';
    echo '<div class="dropdown-content" id="employer-dropdown">';
    
    foreach ($employers as $employer) {
        echo '<div class="dropdown-option">';
        echo '<label>';
        echo '<input type="checkbox" name="employer[]" value="' . skills_clean_output($employer) . '"';
        if (in_array($employer, $employer_filters)) echo ' checked';
        echo '>';
        echo skills_clean_output($employer);
        echo '</label>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    
    // Job Type Dropdown
    echo '<div class="filter-dropdown">';
    echo '<button type="button" class="filter-btn" id="job-type-filter-btn">Job Type <i class="fas fa-chevron-down"></i></button>';
    echo '<div class="dropdown-content" id="job-type-dropdown">';
    
    foreach ($job_types as $job_type) {
        echo '<div class="dropdown-option">';
        echo '<label>';
        echo '<input type="checkbox" name="job_type[]" value="' . skills_clean_output($job_type) . '"';
        if (in_array($job_type, $job_type_filters)) echo ' checked';
        echo '>';
        echo skills_clean_output($job_type);
        echo '</label>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    
    // Location Dropdown
    echo '<div class="filter-dropdown">';
    echo '<button type="button" class="filter-btn" id="location-filter-btn">Location <i class="fas fa-chevron-down"></i></button>';
    echo '<div class="dropdown-content" id="location-dropdown">';
    
    foreach ($cities as $city) {
        echo '<div class="dropdown-option">';
        echo '<label>';
        echo '<input type="checkbox" name="location[]" value="' . skills_clean_output($city) . '"';
        if (in_array($city, $location_filters)) echo ' checked';
        echo '>';
        echo skills_clean_output($city) . (strpos($city, 'IL') === false ? ', IL' : '');
        echo '</label>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';

    // New Apply Button
    echo '<button type="submit" class="filter-apply-btn" id="main-apply-btn">Search <i class="fas fa-search"></i></button>';
    
    // All Filters Button
    echo '<div class="filter-dropdown">';
    echo '<button type="button" class="filter-btn all-filters-btn" id="all-filters-btn">Reset<i class="fa-solid fa-rotate-left"></i></button>';
    echo '</div>';
    
    
    echo '</div>'; // End filter-section
    echo '</form>'; // End main filters form
    
    // Results Count
    echo '<div class="results-count">' . $job_count . ' Results</div>';
    
    // Job Listings Grid
    echo '<div class="job-listings">';
    
    if ($job_count > 0) {
        foreach ($results as $row) {
            // Use a default day count since we don't know when it was posted
            $days_posted = 5;
            $days_display = $days_posted . "d";
            
            // Create a placeholder logo URL using company name
            $logo_url = !empty($row->{'Account Logo'}) ? 
                esc_url($row->{'Account Logo'}) : 
                "https://api.placeholder.com/100x40?text=" . urlencode($row->Employer);
            
            // Create job detail URL
            $current_page_url = get_permalink();
            $job_detail_url = add_query_arg(array('job_id' => $row->JN), $current_page_url);
            
            echo '<div class="job-card" data-job-id="' . $row->JN . '">';
            // Make the entire card clickable except for the bookmark button
            echo '<a href="' . esc_url($job_detail_url) . '" style="display:block; text-decoration:none; color:inherit;">';
            echo '<img src="' . $logo_url . '" alt="' . skills_clean_output($row->Employer) . ' logo" class="company-logo">';
            echo '<h3 class="job-title">' . skills_clean_output($row->{'Job Title'}) . '</h3>';
            echo '<p class="company-name">' . skills_clean_output($row->Employer) . '</p>';
            echo '<p class="job-location"><i class="fas fa-map-marker-alt"></i> ' . skills_clean_output($row->Location) . '</p>';
            
            if (!empty($row->Education)) {
                echo '<p class="job-details"><i class="fas fa-graduation-cap"></i> ' . skills_clean_output($row->Education) . '</p>';
            }
            
            if (!empty($row->{'Shift Details'})) {
                echo '<p class="job-details"><i class="far fa-clock"></i> ' . skills_clean_output($row->{'Shift Details'}) . '</p>';
            }
            
            echo '<div class="days-posted">' . $days_display . '</div>';
            echo '</a>';
            echo '</div>';
        }
    } else {
        echo '<div class="no-results">No jobs found matching your criteria.</div>';
    }
    
    echo '</div>'; // End job-listings
    echo '</div>'; // End job-listings-container
    
    // Filter Overlay
    echo '<div class="overlay" id="overlay"></div>';
    echo '<div class="filter-sidebar" id="filter-sidebar">';
    echo '<div class="filter-header">All Filters</div>';
    echo '<div class="filter-content">';
    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="GET" id="sidebar-filter-form">';
    
    // Employer Filter
    echo '<div class="filter-group">';
    echo '<h3 class="filter-group-title">Employer Name</h3>';
    foreach ($employers as $employer) {
        echo '<div class="filter-option">';
        echo '<label>';
        echo '<input type="checkbox" name="employer[]" value="' . skills_clean_output($employer) . '"';
        if (in_array($employer, $employer_filters)) echo ' checked';
        echo '>';
        echo skills_clean_output($employer);
        echo '</label>';
        echo '</div>';
    }
    echo '</div>';
    
    // Job Type Filter
    echo '<div class="filter-group">';
    echo '<h3 class="filter-group-title">Job Type</h3>';
    foreach ($job_types as $job_type) {
        echo '<div class="filter-option">';
        echo '<label>';
        echo '<input type="checkbox" name="job_type[]" value="' . skills_clean_output($job_type) . '"';
        if (in_array($job_type, $job_type_filters)) echo ' checked';
        echo '>';
        echo skills_clean_output($job_type);
        echo '</label>';
        echo '</div>';
    }
    echo '</div>';
    
    // Location Filter
    echo '<div class="filter-group">';
    echo '<h3 class="filter-group-title">Location</h3>';
    foreach ($cities as $city) {
        echo '<div class="filter-option">';
        echo '<label>';
        echo '<input type="checkbox" name="location[]" value="' . skills_clean_output($city) . '"';
        if (in_array($city, $location_filters)) echo ' checked';
        echo '>';
        echo skills_clean_output($city) . (strpos($city, 'IL') === false ? ', IL' : '');
        echo '</label>';
        echo '</div>';
    }
    echo '</div>';
    
    echo '</form>';
    echo '</div>'; // End filter-content
    
    // Filter Actions
    echo '<div class="filter-actions">';
    echo '<button class="reset-btn" id="reset-btn">Reset</button>';
    echo '<button class="apply-btn" id="sidebar-apply-btn">Apply <i class="fas fa-arrow-right"></i></button>';
    echo '</div>';
    
    echo '</div>'; // End filter-sidebar
    
    // Add JavaScript
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        // All Filters button
        const allFiltersBtn = document.getElementById("all-filters-btn");
        const overlay = document.getElementById("overlay");
        const filterSidebar = document.getElementById("filter-sidebar");
        const sidebarApplyBtn = document.getElementById("sidebar-apply-btn");
        const resetBtn = document.getElementById("reset-btn");
        
        // Filter dropdown buttons
        const employerFilterBtn = document.getElementById("employer-filter-btn");
        const jobTypeFilterBtn = document.getElementById("job-type-filter-btn");
        const locationFilterBtn = document.getElementById("location-filter-btn");
        
        // Filter dropdown content
        const employerDropdown = document.getElementById("employer-dropdown");
        const jobTypeDropdown = document.getElementById("job-type-dropdown");
        const locationDropdown = document.getElementById("location-dropdown");
        
        // Forms
        const mainFiltersForm = document.getElementById("main-filters-form");
        const sidebarFilterForm = document.getElementById("sidebar-filter-form");
        
        // Bookmark buttons
        const bookmarkBtns = document.querySelectorAll(".bookmark-btn");
        
        // Toggle dropdowns
        function toggleDropdown(dropdown) {
            // Close all other dropdowns first
            document.querySelectorAll(".dropdown-content").forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove("active");
                }
            });
            
            dropdown.classList.toggle("active");
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener("click", function(e) {
            if (!e.target.closest(".filter-dropdown")) {
                document.querySelectorAll(".dropdown-content").forEach(d => {
                    d.classList.remove("active");
                });
            }
        });
        
        // Prevent form submission on button click (we want to toggle the dropdown instead)
        employerFilterBtn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        
        jobTypeFilterBtn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        
        locationFilterBtn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        
        // Prevent immediate closing of dropdown when clicking checkboxes
        const filterCheckboxes = document.querySelectorAll(".dropdown-content input[type=\'checkbox\']");
        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("click", function(e) {
                e.stopPropagation(); // Prevent the click from bubbling up
            });
        });
        
        // Setup dropdown toggles
        if (employerFilterBtn) {
            employerFilterBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                toggleDropdown(employerDropdown);
            });
        }
        
        if (jobTypeFilterBtn) {
            jobTypeFilterBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                toggleDropdown(jobTypeDropdown);
            });
        }
        
        if (locationFilterBtn) {
            locationFilterBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                toggleDropdown(locationDropdown);
            });
        }
        
        // Open filter sidebar
        if (allFiltersBtn) {
            allFiltersBtn.addEventListener("click", function() {
                overlay.classList.add("active");
                filterSidebar.classList.add("active");
                document.body.style.overflow = "hidden"; // Prevent scrolling
            });
        }
        
        // Close filter sidebar on overlay click
        if (overlay) {
            overlay.addEventListener("click", function() {
                overlay.classList.remove("active");
                filterSidebar.classList.remove("active");
                document.body.style.overflow = "";
            });
        }
        
        // Sidebar Apply button
        if (sidebarApplyBtn) {
            sidebarApplyBtn.addEventListener("click", function() {
                sidebarFilterForm.submit();
            });
        }
        
        // Reset filters
        if (resetBtn) {
            resetBtn.addEventListener("click", function() {
                const checkboxInputs = document.querySelectorAll("#sidebar-filter-form input[type=\"checkbox\"]");
                checkboxInputs.forEach(input => {
                    input.checked = false;
                });
                
                // Clear current URL parameters and reload
                window.location.href = window.location.pathname;
            });
        }
        
        // Bookmark functionality
        bookmarkBtns.forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                const jobId = this.getAttribute("data-id");
                
                // Toggle active class
                this.classList.toggle("active");
                
                // Update icon
                if (this.classList.contains("active")) {
                    this.innerHTML = "<i class=\"fas fa-bookmark\"></i>";
                    // You could save to localStorage or make an AJAX request to save in database
                    localStorage.setItem("bookmark_" + jobId, "saved");
                    console.log("Job bookmarked:", jobId);
                } else {
                    this.innerHTML = "<i class=\"far fa-bookmark\"></i>";
                    localStorage.removeItem("bookmark_" + jobId);
                    console.log("Job bookmark removed:", jobId);
                }
            });
            
            // Check if job is already bookmarked on page load
            const jobId = btn.getAttribute("data-id");
            if (localStorage.getItem("bookmark_" + jobId) === "saved") {
                btn.classList.add("active");
                btn.innerHTML = "<i class=\"fas fa-bookmark\"></i>";
            }
        });
    });
    </script>';
    
    // Get the contents of the output buffer and clean it
    $output = ob_get_clean();
    return $output;
}

// Function to render a job detail
function skills_render_job_detail($job_id) {
    global $wpdb;
    
    // Get job details from database
    $job_sql = $wpdb->prepare("SELECT * FROM JobDetails WHERE JN = %d AND Published = 1", $job_id);
    $job = $wpdb->get_row($job_sql);
    
    if (!$job) {
        // Job not found or not published
        return '<div class="job-not-found">
            <h2>Job Not Found</h2>
            <p>The job you are looking for is not available or has been removed.</p>
            <a href="' . esc_url(remove_query_arg('job_id')) . '" class="back-btn">Back to Job Listings</a>
        </div>';
    }
    
    // Start output buffering
    ob_start();
    
    // Function to sanitize output
    function skills_clean_detail_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    // Create a placeholder logo URL using company name
    $logo_url = !empty($job->{'Account Logo'}) ? 
        esc_url($job->{'Account Logo'}) : 
        "https://api.placeholder.com/120x60?text=" . urlencode($job->Employer);
    
    // Back to listings link
    echo '<a href="' . esc_url(remove_query_arg('job_id')) . '" class="back-link"><i class="fas fa-arrow-left"></i> Back to all jobs</a>';
    
    echo '<div class="job-detail-container">';
    
    // Job Title Header with Logo - NEW LAYOUT
    echo '<div class="job-title-header">';
    
    // Left - Company Logo
    echo '<div class="company-logo-container">';
    echo '<img src="' . $logo_url . '" alt="' . skills_clean_detail_output($job->Employer) . ' logo" class="company-header-logo">';
    echo '</div>';
    
    // Middle - Job Title and Company
    echo '<div class="job-title-info">';
    echo '<h1 class="job-title-main">' . skills_clean_detail_output($job->{'Job Title'}) . '</h1>';
    echo '<p class="job-company-name">' . skills_clean_detail_output($job->Employer) . '</p>';
    echo '</div>';
    
    // Right - Apply Button
    echo '<div class="apply-button-container">';
    echo '<a href="#" class="apply-now-button">Apply Now <i class="fas fa-arrow-right"></i></a>';
    echo '</div>';
    
    echo '</div>'; // End job-title-header
    
    // Job details and image - two column layout like in the image
    echo '<div class="job-metadata-section">';
    
    // Left column - job details
    echo '<div class="job-metadata-left">';
    
    // Employer Name
    echo '<div class="job-metadata-item">';
    echo '<h3 class="metadata-label">Employer Name:</h3>';
    echo '<p class="metadata-value"><span class="green-bullet"></span> ' . skills_clean_detail_output($job->Employer) . '</p>';
    echo '</div>';
    
    // Job Title
    echo '<div class="job-metadata-item">';
    echo '<h3 class="metadata-label">Job Title</h3>';
    echo '<p class="metadata-value"><span class="green-bullet"></span> ' . skills_clean_detail_output($job->{'Job Title'}) . '</p>';
    echo '</div>';
    
    // Job Type
    if (!empty($job->{'Job Type'})) {
        echo '<div class="job-metadata-item">';
        echo '<h3 class="metadata-label">Job Type</h3>';
        echo '<p class="metadata-value"><span class="green-bullet"></span> ' . skills_clean_detail_output($job->{'Job Type'}) . '</p>';
        echo '</div>';
    }
    
    // Location
    echo '<div class="job-metadata-item">';
    echo '<h3 class="metadata-label">Location</h3>';
    echo '<p class="metadata-value"><span class="green-bullet"></span> ' . skills_clean_detail_output($job->Location) . '</p>';
    echo '</div>';
    
    // Shift Details
    if (!empty($job->{'Shift Details'})) {
        echo '<div class="job-metadata-item">';
        echo '<h3 class="metadata-label">Shift Details</h3>';
        echo '<p class="metadata-value"><span class="green-bullet"></span> ' . skills_clean_detail_output($job->{'Shift Details'}) . '</p>';
        echo '</div>';
    }
    
    // Compensation
    if (!empty($job->Compensation)) {
        echo '<div class="job-metadata-item">';
        echo '<h3 class="metadata-label">Compensation</h3>';
        echo '<p class="metadata-value"><span class="green-bullet"></span> ' . skills_clean_detail_output($job->Compensation) . '</p>';
        echo '</div>';
    }
    
    echo '</div>'; // End job-metadata-left
    
    // Right column - job image
    echo '<div class="job-metadata-right">';
    $job_image_url = !empty($job->{'Job Image Link'}) ? 
    esc_url($job->{'Job Image Link'}) : 
    esc_url(SKILLS_JOB_LISTINGS_PLUGIN_URL . 'assets/job-image.jpg'); // Fallback to static image

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
        $description = nl2br(skills_clean_detail_output($job->{'Job Description'}));
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
        $skills = nl2br(skills_clean_detail_output($job->{'Skills and Qualifications'}));
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
        echo '<li><span class="green-bullet"></span> <span>' . skills_clean_detail_output($job->Education) . '</span></li>';
        echo '</ul>';
    } else {
        echo '<p>No specific education requirements listed.</p>';
    }
    echo '</div>';
    
    // Benefits
    echo '<div class="job-detail-section">';
    echo '<h2 class="job-detail-heading">Benefits</h2>';
    
    echo '<ul class="job-detail-list">';
    
    // Parse the Benefits field - assuming it contains a list of benefits separated by commas or new lines
    if (!empty($job->Benefits)) {
        $benefits = preg_split('/[,\n]+/', $job->Benefits);
        foreach ($benefits as $benefit) {
            $benefit = trim($benefit);
            if (!empty($benefit)) {
                echo '<li><span class="green-bullet"></span> <span>' . skills_clean_detail_output($benefit) . '</span></li>';
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
        echo '<div class="employer-description">' . nl2br(skills_clean_detail_output($job->{'About the Employer'})) . '</div>';
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
add_shortcode('skills_job_listings', 'skills_job_listings_shortcode');

// Add Font Awesome and our custom CSS to the site
function skills_job_listings_enqueue_scripts() {
    // Try an alternative CDN for Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
    
    // Enqueue  custom CSS file
    wp_enqueue_style(
        'skills-job-listings-style',
        SKILLS_JOB_LISTINGS_PLUGIN_URL . 'skills-job-listings.css',
        array('font-awesome'),
        '1.0.2', // Increase version number to bust cache
        'all'
    );
}
add_action('wp_enqueue_scripts', 'skills_job_listings_enqueue_scripts');

// Add a rewrite rule to handle job detail pages
function skills_job_listings_rewrite_rules() {
    add_rewrite_rule(
        'jobs/([0-9]+)/?$',
        'index.php?pagename=jobs&job_id=$matches[1]',
        'top'
    );
}
add_action('init', 'skills_job_listings_rewrite_rules');

// Add job_id as a query var
function skills_job_listings_query_vars($vars) {
    $vars[] = 'job_id';
    return $vars;
}
add_filter('query_vars', 'skills_job_listings_query_vars');

// Flush rewrite rules on plugin activation
function skills_job_listings_activate() {
    skills_job_listings_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'skills_job_listings_activate');