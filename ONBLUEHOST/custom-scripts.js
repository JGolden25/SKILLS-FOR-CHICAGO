document.addEventListener('DOMContentLoaded', function() {
    // Dropdown Navigation Functionality
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            const dropdown = parent.querySelector('.dropdown-menu');
            dropdown.classList.toggle('active');
        });
    });
    
    // Filter Dropdowns - FIXED to match PHP IDs
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Fix for the -filter-btn pattern
            const dropdownId = this.id.replace('-filter-btn', '');
            const dropdown = document.getElementById(dropdownId + '-dropdown');
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-content').forEach(d => {
                if (d.id !== dropdownId + '-dropdown') {
                    d.classList.remove('active');
                }
            });
            
            // Toggle the current dropdown if found
            if (dropdown) {
                dropdown.classList.toggle('active');
            } else {
                console.error('Dropdown not found for ID:', dropdownId + '-dropdown');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.filter-dropdown')) {
            document.querySelectorAll('.dropdown-content').forEach(d => {
                d.classList.remove('active');
            });
        }
    });
    
    // Reset Button
    const resetBtns = document.querySelectorAll('#reset-btn');
    
    resetBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Clear checkboxes
            document.querySelectorAll('input[type="checkbox"]').forEach(input => {
                input.checked = false;
            });
            
            // Redirect to base URL
            window.location.href = window.location.pathname;
        });
    });
    
    // Sidebar Apply Button - Added type="submit" check
    const sidebarApplyBtn = document.getElementById('sidebar-apply-btn');
    if (sidebarApplyBtn) {
        sidebarApplyBtn.addEventListener('click', function() {
            const form = document.getElementById('sidebar-filter-form');
            if (form) {
                form.submit();
            } else {
                console.error('Sidebar filter form not found');
            }
        });
    }
    
    // Main Apply Button - Added for completeness
    const mainApplyBtn = document.getElementById('main-apply-btn');
    if (mainApplyBtn) {
        mainApplyBtn.addEventListener('click', function() {
            const form = document.getElementById('main-filters-form');
            if (form) {
                form.submit();
            }
        });
    }
    
    // Add debugging to help troubleshoot
    console.log('Skills Job Listings scripts loaded');
    
    // Mobile Menu Toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const hamburger = document.querySelector('.hamburger');
    const mobileNav = document.querySelector('.mobile-nav');
    const overlay = document.querySelector('.overlay');
    const body = document.body;

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            hamburger.classList.toggle('open');
            mobileNav.classList.toggle('open');
            overlay.classList.toggle('active');
            
            // FIXED: Only toggle class, don't set overflow directly
            // This fixes the sticky header in mobile view
            if (mobileNav.classList.contains('open')) {
                body.classList.add('menu-open');
                // Removed: body.style.overflow = 'hidden';
            } else {
                body.classList.remove('menu-open');
                // Removed: body.style.overflow = '';
            }
        });
    }

    // Close mobile menu when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            hamburger.classList.remove('open');
            mobileNav.classList.remove('open');
            overlay.classList.remove('active');
            // FIXED: Don't set overflow directly
            // Removed: body.style.overflow = '';
            body.classList.remove('menu-open');
        });
    }

    // Mobile Dropdown Functionality
    const mobileDropdowns = document.querySelectorAll('.mobile-dropdown');
    
    mobileDropdowns.forEach(dropdown => {
        const dropdownToggle = dropdown.querySelector('.mobile-dropdown-toggle');
        
        if (dropdownToggle) {
            dropdownToggle.addEventListener('click', function() {
                dropdown.classList.toggle('active');
                
                // Close other dropdowns
                mobileDropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown && otherDropdown.classList.contains('active')) {
                        otherDropdown.classList.remove('active');
                    }
                });
            });
        }
    });

    // Footer Accordion Functionality
    const accordionHeaders = document.querySelectorAll('.footer-accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const accordion = this.parentElement;
            
            // Toggle active class
            accordion.classList.toggle('active');
            
            // Close all other accordions
            accordionHeaders.forEach(otherHeader => {
                if (otherHeader !== this) {
                    otherHeader.parentElement.classList.remove('active');
                }
            });
        });
    });
});