/**
 * Elementor Enhanced Related Products - Admin Scripts
 * For better UI in Elementor editor
 */

(function($) {
    'use strict';
    
    // Initialize when Elementor is ready
    $(window).on('elementor/frontend/init', function() {
        // Add custom UI enhancements if needed
        elementor.hooks.addAction('panel/open_editor/widget/enhanced_related_products', function(panel, model, view) {
            // Custom logic when widget panel opens
            console.log('Enhanced Related Products widget panel opened');
        });
    });
    
    // Helper for dynamic product search in manual selection
    if (typeof elementor !== 'undefined' && elementor.hasOwnProperty('elements')) {
        // This ensures manual product selection works smoothly
        $(document).on('select2:open', () => {
            document.querySelector('.select2-container--open .select2-search__field').focus();
        });
    }
    
})(jQuery);
