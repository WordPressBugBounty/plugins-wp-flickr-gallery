jQuery(document).ready(function ($) {
    function refreshUI() {
        var galleryType = $('input[name="flickr_gallery_type"]:checked').val();
        if (galleryType === 'album') {
            $('.album_gallery').stop(true, true).slideDown(300);
        } else {
            $('.album_gallery').stop(true, true).slideUp(200);
        }

        var showTitle = $('input[name="fg_gallery_title"]:checked').val();
        if (showTitle === 'true') {
            $('.gallery_post_title').stop(true, true).slideDown(300);
        } else {
            $('.gallery_post_title').stop(true, true).slideUp(200);
        }
    }

    $('input[name="flickr_gallery_type"], input[name="fg_gallery_title"]').on('change', refreshUI);

    // Range Slider Live
    $('.awl-hm-range').on('input change', function () {
        $('#size-display').text($(this).val() + 'px');
    });

    // Sync Native Color Picker
    $('#color-picker-native').on('input change', function () {
        $('#fg_gallery_titlecolor').val($(this).val());
    });
    $('#fg_gallery_titlecolor').on('input change', function () {
        $('#color-picker-native').val($(this).val());
    });
});
