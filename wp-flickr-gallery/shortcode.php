<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!function_exists('awl_fg_resolve_photo_url')) {
	/**
	 * Resolve the best available Flickr photo URL for a preferred size.
	 *
	 * Steps down to the nearest smaller available size first (preventing broken
	 * links on small uploads or restricted Original/1600px/2048px sizes), then
	 * steps up if no smaller size exists.
	 *
	 * @param array  $photo          Flickr photo array from API response.
	 * @param string $preferred_size Preferred size key (e.g. 'url_n', 'url_l').
	 * @return string Resolved image URL, or empty string if none available.
	 */
	function awl_fg_resolve_photo_url($photo, $preferred_size)
	{
		if (!is_array($photo)) {
			return '';
		}

		if (!empty($preferred_size) && !empty($photo[$preferred_size])) {
			return $photo[$preferred_size];
		}

		// Ordered from largest to smallest Flickr size key.
		$ladder = array(
			'url_o',
			'url_k',
			'url_h',
			'url_l',
			'url_c',
			'url_z',
			'url_m',
			'url_w',
			'url_n',
			'url_s',
			'url_q',
			'url_t',
			'url_sq',
		);

		$idx = array_search($preferred_size, $ladder, true);
		if (false === $idx) {
			$idx = 0;
		}

		$count = count($ladder);
		// Step down to nearest smaller available size first.
		for ($i = $idx + 1; $i < $count; $i++) {
			$key = $ladder[$i];
			if (!empty($photo[$key])) {
				return $photo[$key];
			}
		}

		// If no smaller size exists, step up to nearest larger available size.
		for ($i = $idx - 1; $i >= 0; $i--) {
			$key = $ladder[$i];
			if (!empty($photo[$key])) {
				return $photo[$key];
			}
		}

		return '';
	}
}

/**
 * Flickr Gallery Shortcode
 */
add_shortcode('FGAL', 'awl_flickr_gallery_shortcode');
add_shortcode('fgal', 'awl_flickr_gallery_shortcode');
function awl_flickr_gallery_shortcode($post_id)
{
	ob_start();

	// Enqueue all required assets
	wp_enqueue_style('awl-fg-bootstrap-css');
	wp_enqueue_style('awl-fg-lightcase-css');
	wp_enqueue_style('awl-fg-shortcode-css');
	wp_enqueue_style('awl-fg-frontend-css');

	wp_enqueue_script('awl-fg-isotope-js');
	wp_enqueue_script('awl-fg-imagesloaded-js');
	wp_enqueue_script('awl-fg-lightcase-js');
	wp_enqueue_script('awl-fg-frontend-js');

	$flickr_gallery_id = 0;
	if ( is_array( $post_id ) ) {
		if ( isset( $post_id['id'] ) ) {
			$flickr_gallery_id = absint( $post_id['id'] );
		} elseif ( isset( $post_id[0] ) ) {
			$flickr_gallery_id = absint( $post_id[0] );
		}
	}
	if ( ! $flickr_gallery_id ) {
		return '';
	}

	// get Flickr API Settings
	$flickr_api_settings = get_option('flickr_api_settings');
	$flickr_api_key = is_array($flickr_api_settings) && isset($flickr_api_settings['flickr_api_key']) ? $flickr_api_settings['flickr_api_key'] : '';
	$flickr_user_id = is_array($flickr_api_settings) && isset($flickr_api_settings['flickr_user_id']) ? $flickr_api_settings['flickr_user_id'] : '';

	// load post settings
	$flickr_gallery_settings = get_post_meta($flickr_gallery_id, 'awl_fg_post_settings_' . $flickr_gallery_id, true);
	if (!is_array($flickr_gallery_settings)) {
		$flickr_gallery_settings = array();
	}

	$flickr_gallery_type = (isset($flickr_gallery_settings['flickr_gallery_type']) && 'album' === $flickr_gallery_settings['flickr_gallery_type']) ? 'album' : 'photostream';

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
	$col_desktops = (isset($flickr_gallery_settings['col_desktops']) && 'col-md-3' === $flickr_gallery_settings['col_desktops']) ? 'col-md-3' : 'col-md-4';

	$thumb_img_size = isset($flickr_gallery_settings['thumb_img_size']) ? $flickr_gallery_settings['thumb_img_size'] : 'url_q';
	if ($thumb_img_size && strpos($thumb_img_size, 'url_') !== 0) {
		$thumb_img_size = 'url_' . $thumb_img_size;
	}

	$lightbox_img_size = isset($flickr_gallery_settings['lightbox_img_size']) ? $flickr_gallery_settings['lightbox_img_size'] : 'url_l';
	if ('b' === $lightbox_img_size) {
		$lightbox_img_size = 'url_l';
	} elseif ($lightbox_img_size && strpos($lightbox_img_size, 'url_') !== 0) {
		$lightbox_img_size = 'url_' . $lightbox_img_size;
	}

	$photo_titlestyle = isset($flickr_gallery_settings['photo_titlestyle']) ? $flickr_gallery_settings['photo_titlestyle'] : 1;
	$apply_light_box = (isset($flickr_gallery_settings['apply_light_box']) && in_array($flickr_gallery_settings['apply_light_box'], array('false', 'no'), true)) ? 'false' : 'true';

	// gallery post title settings
	$fg_title = get_the_title($flickr_gallery_id);
	$fg_gallery_title = (isset($flickr_gallery_settings['fg_gallery_title']) && 'false' === $flickr_gallery_settings['fg_gallery_title']) ? 'false' : 'true';
	$fg_gallery_titlecolor = isset($flickr_gallery_settings['fg_gallery_titlecolor']) ? sanitize_hex_color($flickr_gallery_settings['fg_gallery_titlecolor']) : '#000000';
	if (!$fg_gallery_titlecolor) {
		$fg_gallery_titlecolor = '#000000';
	}
	$fg_gallery_titlesize = isset($flickr_gallery_settings['fg_gallery_titlesize']) ? absint($flickr_gallery_settings['fg_gallery_titlesize']) : 16;
	$fg_gallery_titlealighment = isset($flickr_gallery_settings['fg_gallery_titlealighment']) ? sanitize_text_field($flickr_gallery_settings['fg_gallery_titlealighment']) : 'left';

	echo '<div class="awp-flickr-gallery-wrapper flickr-gallery-wrapper-' . esc_attr($flickr_gallery_id) . '">';

	// gallery title
	if ($fg_gallery_title === 'true' && $fg_title) {
		?>
		<p class="awp-flickr-post-title"
			style="color:<?php echo esc_attr($fg_gallery_titlecolor); ?>; font-size: <?php echo esc_attr($fg_gallery_titlesize); ?>px; text-align:<?php echo esc_attr($fg_gallery_titlealighment); ?>;">
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
