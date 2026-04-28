jQuery(document).ready(function ($) {
    function refreshUI() {
        if ($('input[name="flickr_gallery_type"]:checked').val() == 'album') {
            $('.album_gallery').slideDown(300);
        } else {
            $('.album_gallery').slideUp(200);
        }

        if ($('input[name="fg_gallery_title"]:checked').val() == 'true') {
            $('.gallery_post_title').slideDown(300);
        } else {
            $('.gallery_post_title').slideUp(200);
        }
    }

    refreshUI();
    $('input[name="flickr_gallery_type"], input[name="fg_gallery_title"]').change(refreshUI);

    // Range Slider Live
    $('.awl-hm-range').on('input', function () {
        $('#size-display').text($(this).val() + 'px');
    });

    // Sync Native Color Picker
    $('#color-picker-native').on('input', function () {
        $('#fg_gallery_titlecolor').val($(this).val());
    });
    $('#fg_gallery_titlecolor').on('input', function () {
        $('#color-picker-native').val($(this).val());
    });
});
