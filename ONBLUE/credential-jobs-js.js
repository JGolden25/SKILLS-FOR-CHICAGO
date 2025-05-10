jQuery(document).ready(function($) {
    // Make entire job card clickable
    $('.job-card').on('click', function(e) {
        // Get the link URL from the card's link element
        var url = $(this).find('a.job-card-link').attr('href');
        
        // If the click was on the card but not on the link directly, navigate to the URL
        if (!$(e.target).closest('a.job-card-link').length) {
            window.location.href = url;
        }
    });
});