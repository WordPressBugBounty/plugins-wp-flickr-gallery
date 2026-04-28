jQuery(document).ready(function ($) {
    // Initialize Lightcase
    if (typeof $.fn.lightcase === 'function') {
        $('a[data-rel^=lightcase]').lightcase({});
    }

    // Initialize Isotope for each gallery
    $('.awp-flickr-gallery-container').each(function() {
        var $container = $(this);
        var galleryId = $container.data('gallery-id');
        
        var $grid = $container.isotope({
            itemSelector: '.single-flickr-item',
            layoutMode: 'masonry'
        });

        // Layout Isotope after each image loads
        if (typeof $.fn.imagesLoaded === 'function') {
            $grid.imagesLoaded().progress(function () {
                $grid.isotope('layout');
            });
        }
    });
});
