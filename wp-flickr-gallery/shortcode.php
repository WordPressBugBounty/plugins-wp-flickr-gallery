<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Flickr Gallery Shortcode
 */
add_shortcode('FGAL', 'awl_flickr_gallery_shortcode');
function awl_flickr_gallery_shortcode($post_id)
{
	ob_start();

	// Assets are enqueued via wp_enqueue_scripts hook in main class for consistency
	wp_enqueue_style('awl-fg-bootstrap-css');
	wp_enqueue_style('awl-fg-lightcase-css');
	wp_enqueue_style('awl-fg-shortcode-css');

	wp_enqueue_script('awl-fg-isotope-js');
	wp_enqueue_script('awl-fg-imagesloaded-js');
	wp_enqueue_script('awl-fg-lightcase-js');

	$flickr_gallery_id = isset($post_id['id']) ? $post_id['id'] : 0;
	if (!$flickr_gallery_id) return '';

	// get Flickr API Settings
	$flickr_api_settings = get_option('flickr_api_settings');
	$flickr_api_key = isset($flickr_api_settings['flickr_api_key']) ? $flickr_api_settings['flickr_api_key'] : '';
	$flickr_user_id = isset($flickr_api_settings['flickr_user_id']) ? $flickr_api_settings['flickr_user_id'] : '';

	// load post settings
	$flickr_gallery_settings = get_post_meta($flickr_gallery_id, 'awl_fg_post_settings_' . $flickr_gallery_id, true);
	$flickr_gallery_type = isset($flickr_gallery_settings['flickr_gallery_type']) ? $flickr_gallery_settings['flickr_gallery_type'] : 'photostream';

	// photostream settings
	if ($flickr_gallery_type == 'photostream') {
		$flickr_photostrem_method = 'flickr.people.getPublicPhotos';
	}

	// album settings
	if ($flickr_gallery_type == 'album') {
		$flickr_album_method = 'flickr.photosets.getPhotos';
		$flickr_album_id = isset($flickr_gallery_settings['flickr_album_id']) ? $flickr_gallery_settings['flickr_album_id'] : '';
	}

	// common settings
	$col_desktops = isset($flickr_gallery_settings['col_desktops']) ? $flickr_gallery_settings['col_desktops'] : 'col-md-4';
	$thumb_img_size = isset($flickr_gallery_settings['thumb_img_size']) ? $flickr_gallery_settings['thumb_img_size'] : 'url_q';
	$lightbox_img_size = isset($flickr_gallery_settings['lightbox_img_size']) ? $flickr_gallery_settings['lightbox_img_size'] : 'url_m';
	$photo_titlestyle = isset($flickr_gallery_settings['photo_titlestyle']) ? $flickr_gallery_settings['photo_titlestyle'] : 1;
	$apply_light_box = isset($flickr_gallery_settings['apply_light_box']) ? $flickr_gallery_settings['apply_light_box'] : 'true';

	// gallery post title settings
	$fg_title = get_the_title($flickr_gallery_id);
	$fg_gallery_title = isset($flickr_gallery_settings['fg_gallery_title']) ? $flickr_gallery_settings['fg_gallery_title'] : 'true';
	$fg_gallery_titlecolor = isset($flickr_gallery_settings['fg_gallery_titlecolor']) ? $flickr_gallery_settings['fg_gallery_titlecolor'] : '#000000';
	$fg_gallery_titlesize = isset($flickr_gallery_settings['fg_gallery_titlesize']) ? $flickr_gallery_settings['fg_gallery_titlesize'] : 16;
	$fg_gallery_titlealighment = isset($flickr_gallery_settings['fg_gallery_titlealighment']) ? $flickr_gallery_settings['fg_gallery_titlealighment'] : 'left';

	echo '<div class="awp-flickr-gallery-container single-photostream-' . esc_attr($flickr_gallery_id) . '">';

	// gallery title
	if ($fg_gallery_title == 'true') {
		?>
		<p class="awp-flickr-post-title"
			style="color:<?php echo esc_attr($fg_gallery_titlecolor); ?>; font-size: <?php echo esc_attr($fg_gallery_titlesize); ?>px ; text-align:<?php echo esc_attr($fg_gallery_titlealighment); ?>;">
			<?php echo esc_html($fg_title); ?>
		</p>
		<?php
	}

	// photostream
	if ($flickr_gallery_type == 'photostream') {
		require 'flickr-get-photostream.php';
	}

	// album
	if ($flickr_gallery_type == 'album') {
		require 'flickr-get-album.php';
	}
	
	echo '</div>';

	return ob_get_clean();
}