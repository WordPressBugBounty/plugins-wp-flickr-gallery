var fgIsSaving = false;

function FlickrAPISaveSettings() {
    if (fgIsSaving) {
        return;
    }
    fgIsSaving = true;

    var $ = jQuery;
    var $msg = $("#fg_api_message");
    $msg.hide().empty();
    $("#fg_setting_load").css('display', 'inline-flex');
    $("#save_flickr_api_setting").prop('disabled', true).css('opacity', '0.7');

    var ajaxEndpoint = (typeof fg_api_vars !== 'undefined' && fg_api_vars.ajax_url) ? fg_api_vars.ajax_url : (typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php');
    var postData = $('#flickr-setting-form').serialize() + '&action=api_settings_action';
    if (typeof fg_api_vars !== 'undefined' && fg_api_vars.nonce && postData.indexOf('fg_api_security') === -1) {
        postData += '&fg_api_security=' + fg_api_vars.nonce;
    }

    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: ajaxEndpoint,
        cache: false,
        data: postData,
        success: function (response) {
            fgIsSaving = false;
            $("#fg_setting_load").hide();
            $("#save_flickr_api_setting").prop('disabled', false).css('opacity', '1');

            if (response && response.success) {
                var successMsg = (response.data && response.data.message) ? response.data.message : 'Settings saved successfully!';
                $msg.css({
                    'display': 'block',
                    'background-color': '#ecfdf5',
                    'color': '#065f46',
                    'border': '1px solid #a7f3d0'
                }).text(successMsg);
            } else {
                var errorMsg = (response && response.data && response.data.message) ? response.data.message : 'Error saving settings. Please try again.';
                $msg.css({
                    'display': 'block',
                    'background-color': '#fef2f2',
                    'color': '#991b1b',
                    'border': '1px solid #fecaca'
                }).text(errorMsg);
            }
        },
        error: function (xhr, status, error) {
            fgIsSaving = false;
            $("#fg_setting_load").hide();
            $("#save_flickr_api_setting").prop('disabled', false).css('opacity', '1');
            $msg.css({
                'display': 'block',
                'background-color': '#fef2f2',
                'color': '#991b1b',
                'border': '1px solid #fecaca'
            }).text('An unexpected error occurred. Please try again.');
        }
    });
}

jQuery(document).ready(function ($) {
    $('#flickr-setting-form').on('submit', function (e) {
        e.preventDefault();
        FlickrAPISaveSettings();
    });
});
