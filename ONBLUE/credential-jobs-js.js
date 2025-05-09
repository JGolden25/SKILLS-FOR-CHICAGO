jQuery(document).ready(function($) {
    // Make entire job card clickable
    $('.job-card').on('click', function(e) {
        var jobId = $(this).data('job-id');
        var currentUrl = window.location.href.split('?')[0];
        window.location.href = currentUrl + '?job_id=' + jobId;
    });
});