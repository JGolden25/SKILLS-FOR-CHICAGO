<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <!-- Any additional head elements needed -->
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="social-strip">
    <div class="social-strip-container">
        <div class="social-icons-top">
            <a href="https://www.facebook.com/SkillsForChicago/" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="https://www.linkedin.com/company/skills-for-chicago/?viewAsMember=true" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="https://www.youtube.com/@skillsforchicagoland" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            <a href="https://www.instagram.com/skillsforchicago/" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</div>

<!-- Header -->
<header class="header">
    <div class="header-container">
        <a href="https://skillsforchicago.org/" class="logo-link">
            <img src="https://ibc.gzr.mybluehost.me/wp-content/uploads/2025/03/SFC_Logo_Assets_2025_v1-2_optimized.png" alt="Skills for Chicago Logo" class="logo">
        </a>
        
        <!-- Desktop Navigation -->
        <nav class="skills-dropdown-nav">
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="https://skillsforchicago.org/" class="nav-link">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">About Us <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="https://skillsforchicago.org/who-we-are/" class="dropdown-item">Who We Are</a>
                        <a href="https://skillsforchicago.org/our-team/" class="dropdown-item">Our Team</a>
                        <a href="https://skillsforchicago.org/annual-reports/" class="dropdown-item">Annual Reports & Financials</a>
                        <a href="https://skillsforchicago.org/our-work/" class="dropdown-item">Our Work in Action</a>
                        <a href="https://skillsforchicago.org/careers/" class="dropdown-item">Careers at Skills</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">Job Seekers <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="https://skillsforchicago.org/find-a-job/" class="dropdown-item">Find A Job</a>
                        <a href="https://skillsforchicago.org/career-resources/" class="dropdown-item">Career Resources</a>
                        <a href="https://skillsforchicago.org/faqs/" class="dropdown-item">FAQs for Job Seekers</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">Employers <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="https://skillsforchicago.org/partner/" class="dropdown-item">Partner With Us</a>
                        <a href="https://skillsforchicago.org/employer-partners/" class="dropdown-item">Our Employer Partners</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">Community Partners <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="https://skillsforchicago.org/collaborate/" class="dropdown-item">Collaborate With Us</a>
                        <a href="https://skillsforchicago.org/community-impact/" class="dropdown-item">Community Impact</a>
                        <a href="https://skillsforchicago.org/community-partners/" class="dropdown-item">Our Community Partners</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="https://skillsforchicago.org/donors/" class="nav-link">Donors</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">Events <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="https://skillsforchicago.org/events/" class="dropdown-item">Events & Highlights</a>
                        <a href="https://skillsforchicago.org/ecb/" class="dropdown-item">Employment Champions Breakfast</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="https://skillsforchicago.org/national-expansion/" class="nav-link">National Expansion</a>
                </li>
                <li class="nav-item">
                    <a href="https://skillsforchicago.org/contact-us/" class="nav-link">Contact Us</a>
                </li>
            </ul>
        </nav>
        
        <a href="https://fundraise.givesmart.com/form/5f7YAQ?vid=1j85h3" class="donate-button">
            <span>Donate</span>
            <div class="circle-icon">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
        
        <!-- Mobile Menu Toggle Button -->
        <button class="mobile-menu-toggle" aria-label="Toggle Mobile Menu">
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>
        
        <!-- Mobile Navigation Panel -->
        <div class="mobile-nav">
            <div class="mobile-nav-links">
                <a href="https://skillsforchicago.org/">Home</a>
                
                <!-- About Us Dropdown -->
                <div class="mobile-dropdown">
                    <div class="mobile-dropdown-toggle">
                        <span>About Us</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="mobile-dropdown-menu">
                        <a href="https://skillsforchicago.org/who-we-are/">Who We Are</a>
                        <a href="https://skillsforchicago.org/our-team/">Our Team</a>
                        <a href="https://skillsforchicago.org/annual-reports/">Annual Reports & Financials</a>
                        <a href="https://skillsforchicago.org/our-work/">Our Work in Action</a>
                        <a href="https://skillsforchicago.org/careers/">Careers at Skills</a>
                    </div>
                </div>
                
                <!-- Job Seekers Dropdown -->
                <div class="mobile-dropdown">
                    <div class="mobile-dropdown-toggle">
                        <span>Job Seekers</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="mobile-dropdown-menu">
                        <a href="https://skillsforchicago.org/find-a-job/">Find A Job</a>
                        <a href="https://skillsforchicago.org/career-resources/">Career Resources</a>
                        <a href="https://skillsforchicago.org/faqs/">FAQs for Job Seekers</a>
                    </div>
                </div>
                
                <!-- Employers Dropdown -->
                <div class="mobile-dropdown">
                    <div class="mobile-dropdown-toggle">
                        <span>Employers</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="mobile-dropdown-menu">
                        <a href="https://skillsforchicago.org/partner/">Partner With Us</a>
                        <a href="https://skillsforchicago.org/employer-partners/">Our Employer Partners</a>
                    </div>
                </div>
                
                <!-- Community Partners Dropdown -->
                <div class="mobile-dropdown">
                    <div class="mobile-dropdown-toggle">
                        <span>Community Partners</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="mobile-dropdown-menu">
                        <a href="https://skillsforchicago.org/collaborate/">Collaborate With Us</a>
                        <a href="https://skillsforchicago.org/community-impact/">Community Impact</a>
                        <a href="https://skillsforchicago.org/community-partners/">Our Community Partners</a>
                    </div>
                </div>
                
                <a href="https://skillsforchicago.org/donors/">Donors</a>
                
                <!-- Events Dropdown -->
                <div class="mobile-dropdown">
                    <div class="mobile-dropdown-toggle">
                        <span>Events</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="mobile-dropdown-menu">
                        <a href="https://skillsforchicago.org/events/">Events & Highlights</a>
                        <a href="https://skillsforchicago.org/ecb/">Employment Champions Breakfast</a>
                    </div>
                </div>
                
                <a href="https://skillsforchicago.org/national-expansion/">National Expansion</a>
                <a href="https://skillsforchicago.org/contact-us/">Contact Us</a>
            </div>
            <a href="https://fundraise.givesmart.com/form/5f7YAQ?vid=1j85h3" class="mobile-donate-button">
                <span>Donate</span>
                <div class="circle-icon">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
        </div>
        
        <!-- Overlay for mobile menu -->
        <div class="overlay"></div>
    </div>
</header>

<div id="main-content">