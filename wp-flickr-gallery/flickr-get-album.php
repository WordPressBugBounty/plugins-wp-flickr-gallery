<?php
if (!defined('ABSPATH')) {
	exit;
}
// get album by id = https://www.flickr.com/services/api/flickr.photosets.getPhotos.html
if (empty($flickr_user_id) || empty($flickr_api_key)) {
	echo "<p class='alert'>" . esc_html__('Error! Check your Flickr API Settings. Maybe API Key or User ID is incorrect or empty.', 'wp-flickr-gallery') . "</p>";
	return;
}

if (empty($flickr_album_id)) {
	echo "<p class='alert'>" . esc_html__('Please select a Flickr album in the gallery settings.', 'wp-flickr-gallery') . "</p>";
	return;
}

$transient_key = 'awl_fg_album_v2_' . $flickr_gallery_id;
$res = get_transient($transient_key);

if (false === $res) {
	$params = array(
		'api_key' => $flickr_api_key,
		'user_id' => $flickr_user_id,
		'photoset_id' => $flickr_album_id,
		'method' => $flickr_album_method,
		'per_page' => 200,
		'format' => 'json',
		'nojsoncallback' => 1,
		'extras' => 'date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_q, url_t, url_s, url_n, url_w, url_m, url_z, url_c, url_l, url_h, url_k, url_o',
	);
	$encoded_params = array();
	foreach ($params as $k => $v) {
		$encoded_params[] = urlencode($k) . '=' . urlencode($v);
	}

	// call the API and decode the response
	$url = 'https://api.flickr.com/services/rest/?' . implode('&', $encoded_params);
	$rsp = wp_remote_get($url, array('timeout' => 15));
	if (is_wp_error($rsp)) {
		echo '<p class="alert alert-warning">' . esc_html__('API Error: Could not connect to Flickr.', 'wp-flickr-gallery') . '</p>';
		return;
	}
	$rsp_obj = json_decode($rsp['body'], true);

	if (isset($rsp_obj['stat']) && 'fail' === $rsp_obj['stat']) {
		$err_msg = isset($rsp_obj['message']) ? $rsp_obj['message'] : __('Flickr API error encountered.', 'wp-flickr-gallery');
		echo '<p class="alert alert-warning">' . esc_html($err_msg) . '</p>';
		return;
	}

	$res = isset($rsp_obj['photoset']['photo']) ? $rsp_obj['photoset']['photo'] : array();

	if (!empty($res)) {
		set_transient($transient_key, $res, HOUR_IN_SECONDS);
	}
}

if (empty($res)) {
	echo '<p class="alert alert-warning">' . esc_html__('No photos found in this album.', 'wp-flickr-gallery') . '</p>';
	return;
}
?>
<div id="awp-flickr-album-<?php echo esc_attr($flickr_gallery_id); ?>"
	class="awp-flickr-gallery-container awp-flickr-album-<?php echo esc_attr($flickr_gallery_id); ?>"
	data-gallery-id="<?php echo esc_attr($flickr_gallery_id); ?>">
	<?php
	foreach ($res as $sp) {
		$album_title_fetch = isset($sp['title']) ? $sp['title'] : '';
		$thumbnail_url = awl_fg_resolve_photo_url($sp, $thumb_img_size);
		$lightboxl_url = awl_fg_resolve_photo_url($sp, $lightbox_img_size);

		if ($thumbnail_url) {
			?>
			<div class="img-responsive text-center single-flickr-item single-album-<?php echo esc_attr($flickr_gallery_id); ?> <?php echo esc_attr($col_desktops); ?>">
				<?php if ($apply_light_box === 'true' && $lightboxl_url) : ?>
					<a href="<?php echo esc_url($lightboxl_url); ?>" class="awl-hm-card-inner" style="display: block;"
						data-sub-html="<?php echo esc_attr($album_title_fetch); ?>"
						data-rel="lightcase-<?php echo esc_attr($flickr_gallery_id); ?>:myCollection:slideshow">
				<?php else : ?>
					<div class="awl-hm-card-inner">
				<?php endif; ?>

					<div class="awl-hm-overlay">
						<div class="awl-hm-plus"></div>
						<div class="awl-hm-content">
							<?php
							$display_album_photo_title = function_exists('mb_strimwidth') ? mb_strimwidth($album_title_fetch, 0, 30, '...') : wp_html_excerpt($album_title_fetch, 30, '...');
							?>
							<h4 class="awl-hm-title"><?php echo esc_html($display_album_photo_title); ?></h4>
							<span class="awl-hm-icon"><?php esc_html_e('View Photo', 'wp-flickr-gallery'); ?></span>
						</div>
					</div>

					<img class="img-responsive img-thumbnail photo loading" src="<?php echo esc_url($thumbnail_url); ?>"
						alt="<?php echo esc_attr($album_title_fetch); ?>" width="100%" height="auto">

				<?php if ($apply_light_box === 'true' && $lightboxl_url) : ?>
					</a>
				<?php else : ?>
					</div>
				<?php endif; ?>
			</div>
			<?php
		}
	}
	?>
</div>
