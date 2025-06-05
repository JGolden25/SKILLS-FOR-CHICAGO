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
    
    // Check if we're on a job detail page (URL has job_id parameter)
    if (window.location.href.indexOf('job_id=') > -1) {
        // Add more specific selectors based on the HTML structure visible in DevTools
        var hideTeamCSS = `
            section.team-section, 
            div.team-container, 
            .team-container, 
            #div\\.team-container,
            div[class*="team"], 
            section[class*="team"],
            div[_grid="0"].team-container { 
                display: none !important; 
                visibility: hidden !important;
                height: 0 !important;
                overflow: hidden !important;
            } 
            .credential-jobs-wrapper { 
                width: 100% !important; 
            }
        `;
        
        // Apply the CSS immediately
        $('<style id="hide-team-styles">' + hideTeamCSS + '</style>').appendTo('head');
        
        // Also try direct removal for this specific element pattern
        setTimeout(function() {
            $('section.team-section').remove();
            $('.team-container').remove();
            $('div.team-container').remove();
            $('[class*="team"]').hide();
        }, 100);
    }
});