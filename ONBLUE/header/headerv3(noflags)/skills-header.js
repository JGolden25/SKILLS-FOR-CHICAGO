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
    
    // Close menu when clicking a mobile navigation link
    const mobileNavLinks = $('.skills-mobile-nav-links a');
    mobileNavLinks.each(function() {
        $(this).on('click', function() {
            toggleMobileMenu();
        });
    });
    
    // Close menu on window resize if screen becomes larger than mobile breakpoint
    $(window).on('resize', function() {
        if (window.innerWidth > 1024 && mobileNav.hasClass('open')) {
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
            // Optionally close the dropdown when a link is clicked
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
});