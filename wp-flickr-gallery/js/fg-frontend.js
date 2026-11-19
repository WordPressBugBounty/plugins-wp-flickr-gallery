jQuery(document).ready(function ($) {
    // Initialize Lightcase
    if (typeof $.fn.lightcase === 'function') {
        $('a[data-rel^=lightcase]').lightcase({
            transition: 'fade',
            showSequenceInfo: true,
            showCaption: true
        });
    }

    // Initialize Isotope for each gallery
    if (typeof $.fn.isotope === 'function') {
        $('.awp-flickr-gallery-container').each(function() {
            var $container = $(this);
            var $grid = $container.isotope({
                itemSelector: '.single-flickr-item',
                layoutMode: 'masonry',
                percentPosition: true
            });

            // Layout Isotope after each image loads
            if (typeof $.fn.imagesLoaded === 'function') {
                $grid.imagesLoaded().progress(function () {
                    $grid.isotope('layout');
                });
            }
        });
    }
});
