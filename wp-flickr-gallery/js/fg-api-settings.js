function FlickrAPISaveSettings() {
    jQuery("#fg_setting_load").css('display', 'inline-flex');
    jQuery("#save_flickr_api_setting").prop('disabled', true).css('opacity', '0.7');

    jQuery.ajax({
        dataType: 'html',
        type: 'POST',
        url: ajaxurl,
        cache: false,
        data: jQuery('#flickr-setting-form').serialize() + '&action=api_settings_action' + '&fg_api_security=' + fg_api_vars.nonce,
        success: function (data) {
            jQuery("#fg_setting_load").hide();
            jQuery("#save_flickr_api_setting").prop('disabled', false).css('opacity', '1');
            // Success feedback could be added here
        }
    });
}
