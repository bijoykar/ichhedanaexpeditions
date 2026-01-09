/**
 * Admin Panel JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Sidebar toggle for mobile
        $('#sidebarToggle').on('click', function() {
            $('.admin-sidebar').toggleClass('active');
        });
        
        // Close sidebar when clicking outside on mobile
        $(document).on('click', function(e) {
            if ($(window).width() <= 768) {
                if (!$(e.target).closest('.admin-sidebar, #sidebarToggle').length) {
                    $('.admin-sidebar').removeClass('active');
                }
            }
        });
        
        // Alert close
        $('.alert-close').on('click', function() {
            $(this).parent().fadeOut();
        });
        
    });
    
})(jQuery);
