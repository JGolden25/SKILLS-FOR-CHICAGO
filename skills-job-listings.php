<?php
/*
Plugin Name: Skills Job Listings
Description: Creates a shortcode to display job listings from database
Version: 1.0
Author: Skills for Chicago
*/

// Don't allow direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Add shortcode function
function skills_job_listings_shortcode() {
    // Start output buffering to capture all output
    ob_start();
    
    // Function to sanitize output
    function skills_clean_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    // Get filter parameters
    $employer_filter = isset($_GET['employer']) ? sanitize_text_field($_GET['employer']) : '';
    $job_type_filter = isset($_GET['job_type']) ? sanitize_text_field($_GET['job_type']) : '';
    $location_filter = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
    
    // Get WordPress database connection
    global $wpdb;
    
    // Debug SQL
    // echo "<!-- Using table: JobDetails -->";
    
    // Build the SQL query with possible filters - using your actual column names
    $sql = "SELECT * FROM JobDetails WHERE Published = 1";
    $params = array();
    
    if (!empty($employer_filter)) {
        $sql .= " AND Employer = %s";
        $params[] = $employer_filter;
    }
    
    if (!empty($job_type_filter)) {
        $sql .= " AND `Job Type` = %s";
        $params[] = $job_type_filter;
    }
    
    if (!empty($location_filter)) {
        $sql .= " AND Location LIKE %s";
        $params[] = '%' . $wpdb->esc_like($location_filter) . '%';
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
    
    // For simplicity, let's extract city names for location filter
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
    
    // CSS Styles
    echo '<style>
        /* Filter Section */
        .filter-section {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .filter-dropdown {
            position: relative;
        }
        
        .filter-btn {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 30px;
            padding: 10px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            min-width: 120px;
            justify-content: space-between;
        }
        
        .filter-btn i {
            color: #999;
        }
        
        .all-filters-btn {
            background-color: #f5f5f5;
        }
        
        /* Results Count */
        .results-count {
            color: #4F6B3A;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        /* Job Listings Grid */
        .job-listings {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .job-card {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            position: relative;
            transition: box-shadow 0.3s ease;
        }
        
        .job-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .company-logo {
            width: 100px;
            height: 40px;
            object-fit: contain;
            margin-bottom: 15px;
        }
        
        .job-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        
        .company-name {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .job-location {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .job-details {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .days-posted {
            position: absolute;
            bottom: 20px;
            right: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .bookmark-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: transparent;
            border: none;
            cursor: pointer;
            color: #ddd;
            font-size: 18px;
        }
        
        .bookmark-btn.active {
            color: #4F6B3A;
        }
        
        /* Filter Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 900;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
        }
        
        .overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .filter-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 380px;
            height: 100%;
            background-color: white;
            z-index: 1000;
            overflow-y: auto;
            transition: right 0.3s ease;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        }
        
        .filter-sidebar.active {
            right: 0;
        }
        
        .filter-header {
            background-color: #1E3707;
            color: white;
            padding: 20px;
            font-size: 18px;
            font-weight: 600;
        }
        
        .filter-content {
            padding: 20px;
        }
        
        .filter-group {
            margin-bottom: 30px;
        }
        
        .filter-group-title {
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .filter-option {
            margin-bottom: 12px;
        }
        
        .filter-option label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .filter-option input[type="radio"] {
            cursor: pointer;
            width: 16px;
            height: 16px;
        }
        
        .filter-actions {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
        }
        
        .reset-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            font-size: 14px;
        }
        
        .apply-btn {
            background-color: #8DC63F;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 10px 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .apply-btn i {
            font-size: 12px;
        }
        
        .job-listings-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .page-title-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .page-subtitle {
            font-size: 18px;
            color: #4F6B3A;
            margin-bottom: 20px;
        }
        
        .page-description {
            max-width: 800px;
            margin: 0 auto 30px;
            text-align: center;
            font-size: 16px;
            line-height: 1.6;
        }
        
        /* Responsive styles */
        @media (max-width: 992px) {
            .job-listings {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .filter-section {
                flex-wrap: wrap;
            }
            
            .filter-sidebar {
                width: 300px;
            }
        }
    </style>';
    
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
    
    // Filter Section
    echo '<div class="filter-section">';
    echo '<div class="filter-dropdown">';
    echo '<button class="filter-btn" id="employer-filter-btn">Employer <i class="fas fa-chevron-down"></i></button>';
    echo '</div>';
    echo '<div class="filter-dropdown">';
    echo '<button class="filter-btn" id="job-type-filter-btn">Job Type <i class="fas fa-chevron-down"></i></button>';
    echo '</div>';
    echo '<div class="filter-dropdown">';
    echo '<button class="filter-btn" id="location-filter-btn">Location <i class="fas fa-chevron-down"></i></button>';
    echo '</div>';
    echo '<div class="filter-dropdown">';
    echo '<button class="filter-btn all-filters-btn" id="all-filters-btn">All Filters <i class="fas fa-sliders-h"></i></button>';
    echo '</div>';
    echo '</div>';
    
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
            
            echo '<div class="job-card">';
            echo '<img src="' . $logo_url . '" alt="' . skills_clean_output($row->Employer) . ' logo" class="company-logo">';
            echo '<h3 class="job-title">' . skills_clean_output($row->{'Job Title'}) . '</h3>';
            echo '<p class="company-name">' . skills_clean_output($row->Employer) . '</p>';
            echo '<p class="job-location">' . skills_clean_output($row->Location) . '</p>';
            
            if (!empty($row->Education)) {
                echo '<p class="job-details">' . skills_clean_output($row->Education) . '</p>';
            }
            
            if (!empty($row->{'Shift Details'})) {
                echo '<p class="job-details">' . skills_clean_output($row->{'Shift Details'}) . '</p>';
            }
            
            echo '<div class="days-posted">' . $days_display . '</div>';
            echo '<button class="bookmark-btn" data-id="' . $row->JN . '"><i class="far fa-bookmark"></i></button>';
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
    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="GET" id="filter-form">';
    
    // Employer Filter
    echo '<div class="filter-group">';
    echo '<h3 class="filter-group-title">Employer Name</h3>';
    foreach ($employers as $employer) {
        echo '<div class="filter-option">';
        echo '<label>';
        echo '<input type="radio" name="employer" value="' . skills_clean_output($employer) . '"';
        if ($employer_filter === $employer) echo ' checked';
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
        echo '<input type="radio" name="job_type" value="' . skills_clean_output($job_type) . '"';
        if ($job_type_filter === $job_type) echo ' checked';
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
        echo '<input type="radio" name="location" value="' . skills_clean_output($city) . '"';
        if ($location_filter === $city) echo ' checked';
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
    echo '<button class="apply-btn" id="apply-btn">Apply <i class="fas fa-arrow-right"></i></button>';
    echo '</div>';
    
    echo '</div>'; // End filter-sidebar
    
    // Add JavaScript
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        // All Filters button
        const allFiltersBtn = document.getElementById("all-filters-btn");
        const overlay = document.getElementById("overlay");
        const filterSidebar = document.getElementById("filter-sidebar");
        const applyBtn = document.getElementById("apply-btn");
        const resetBtn = document.getElementById("reset-btn");
        
        // Bookmark buttons
        const bookmarkBtns = document.querySelectorAll(".bookmark-btn");
        
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
        
        // Apply filters
        if (applyBtn) {
            applyBtn.addEventListener("click", function() {
                document.getElementById("filter-form").submit();
            });
        }
        
        // Reset filters
        if (resetBtn) {
            resetBtn.addEventListener("click", function() {
                const radioInputs = document.querySelectorAll("input[type=\"radio\"]");
                radioInputs.forEach(input => {
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
                const jobId = this.getAttribute("data-id");
                
                // Toggle active class
                this.classList.toggle("active");
                
                // Update icon
                if (this.classList.contains("active")) {
                    this.innerHTML = "<i class=\"fas fa-bookmark\"></i>";
                    // You could save to localStorage or make an AJAX request to save in database
                    console.log("Job bookmarked:", jobId);
                } else {
                    this.innerHTML = "<i class=\"far fa-bookmark\"></i>";
                    console.log("Job bookmark removed:", jobId);
                }
            });
        });
    });
    </script>';
    
    // Get the contents of the output buffer and clean it
    $output = ob_get_clean();
    return $output;
}

// Register the shortcode
add_shortcode('skills_job_listings', 'skills_job_listings_shortcode');

// Add Font Awesome to the site
function skills_job_listings_enqueue_scripts() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'skills_job_listings_enqueue_scripts');
?>