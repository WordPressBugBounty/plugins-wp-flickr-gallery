function FLICKRCopyShortcode(id) {
    var copyText = document.getElementById('flickr-shortcode-' + id);
    if (!copyText) return;
    
    copyText.select();
    document.execCommand('copy');
    
    // Fade in and out copied message
    jQuery('#copy-msg-' + id).fadeIn(1000, 'linear', function() {
        jQuery(this).fadeOut(2500, 'swing');
    });
}

function copyToClipboard(element) {
    var $temp = jQuery("<input>");
    jQuery("body").append($temp);
    $temp.val(jQuery(element).val()).select();
    document.execCommand("copy");
    $temp.remove();
    jQuery(element).select();
    jQuery("#fgal-copy-code").fadeIn();
}

jQuery(document).ready(function($) {
    $( "#fgal-copy-code" ).hide();
});
