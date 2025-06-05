jQuery(document).ready(function($) {
    // Mobile Menu Functionality
    const mobileMenuToggle = $('.skills-mobile-menu-toggle');
    const hamburger = $('.skills-hamburger');
    const mobileNav = $('.skills-mobile-nav');
    const overlay = $('.skills-overlay');
    const body = $('body');
    
    // Function to toggle mobile menu
    function toggleMobileMenu() {
        hamburger.toggleClass('open');
        mobileNav.toggleClass('open');
        overlay.toggleClass('active');
        
        // Prevent body scrolling when menu is open
        if (mobileNav.hasClass('open')) {
            body.css('overflow', 'hidden');
        } else {
            body.css('overflow', '');
        }
    }
    
    // Event listeners for menu toggling
    if (mobileMenuToggle.length) {
        mobileMenuToggle.on('click', toggleMobileMenu);
    }
    
    // Close menu when clicking overlay
    if (overlay.length) {
        overlay.on('click', toggleMobileMenu);
    }
    
    // Close menu when clicking a mobile navigation link (but not dropdown toggles)
    const mobileNavLinks = $('.skills-mobile-nav-links > a');
    mobileNavLinks.each(function() {
        $(this).on('click', function() {
            toggleMobileMenu();
        });
    });
    
    // Close menu when clicking mobile buttons
    const mobileButtons = $('.skills-mobile-find-job-button, .skills-mobile-donate-button');
    mobileButtons.each(function() {
        $(this).on('click', function() {
            // Small delay to allow the click to register before closing
            setTimeout(function() {
                if (mobileNav.hasClass('open')) {
                    toggleMobileMenu();
                }
            }, 100);
        });
    });
    
    // Close menu on window resize if screen becomes larger than mobile breakpoint
    $(window).on('resize', function() {
        if (window.innerWidth > 1111 && mobileNav.hasClass('open')) {
            toggleMobileMenu();
        }
    });
    
    // Mobile Dropdown Functionality
    const mobileDropdowns = $('.skills-mobile-dropdown');
    
    mobileDropdowns.each(function() {
        const dropdownToggle = $(this).find('.skills-mobile-dropdown-toggle');
        
        dropdownToggle.on('click', function() {
            // Toggle active class on clicked dropdown
            $(this).parent().toggleClass('active');
            
            // Optionally close other dropdowns when one is opened
            mobileDropdowns.not($(this).parent()).removeClass('active');
        });
    });
    
    // Close dropdowns when a link inside is clicked
    const dropdownLinks = $('.skills-mobile-dropdown-menu a');
    dropdownLinks.each(function() {
        $(this).on('click', function() {
            // Close the dropdown when a link is clicked
            const parentDropdown = $(this).closest('.skills-mobile-dropdown');
            setTimeout(function() {
                parentDropdown.removeClass('active');
                
                // Also close the mobile menu
                hamburger.removeClass('open');
                mobileNav.removeClass('open');
                overlay.removeClass('active');
                body.css('overflow', '');
            }, 100); // Small delay to allow the click to register
        });
    });
    
    // Make sure mobile dropdowns are closed when menu is closed
    if (mobileMenuToggle.length) {
        mobileMenuToggle.on('click', function() {
            // If closing the mobile menu, close all dropdowns
            if (mobileNav.hasClass('open')) {
                setTimeout(function() {
                    mobileDropdowns.removeClass('active');
                }, 300); // Delay to match the mobile menu closing animation
            }
        });
    }
    
    // Language switcher functionality (if needed)
    const langDropdown = $('.skills-lang-dropdown');
    if (langDropdown.length) {
        // Additional hover functionality can be added here if needed
        // Currently using CSS :hover, but JS can be added for mobile touch support
        
        langDropdown.on('click', function(e) {
            // For mobile devices, toggle the dropdown on click
            if (window.innerWidth <= 768) {
                e.preventDefault();
                $(this).find('.skills-lang-options').toggle();
            }
        });
        
        // Close language dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!langDropdown.is(e.target) && langDropdown.has(e.target).length === 0) {
                $('.skills-lang-options').hide();
            }
        });
    }
    
    // Smooth scroll for anchor links (if any are added in the future)
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100 // Offset for fixed header
            }, 1000);
        }
    });
    
    // Add active states for desktop navigation (if needed)
    const currentPage = window.location.pathname;
    $('.skills-nav-link, .skills-dropdown-item').each(function() {
        const link = $(this);
        const href = link.attr('href');
        
        if (href && (currentPage.includes(href) || window.location.href === href)) {
            link.addClass('active');
        }
    });
});