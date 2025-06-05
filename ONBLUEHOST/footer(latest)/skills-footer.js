jQuery(document).ready(function($) {
    // Footer Accordion Functionality
    $(".skills-footer-accordion-header").on("click", function() {
        var accordion = $(this).parent();
        
        // Toggle active class
        accordion.toggleClass("active");
        
        // Close all other accordions (optional - comment out if you want multiple open)
        $(".skills-footer-accordion").not(accordion).removeClass("active");
    });
});