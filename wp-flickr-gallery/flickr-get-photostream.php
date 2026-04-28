<?php
if (!defined('ABSPATH')) {
	exit;
}
// get photo stream - https://www.flickr.com/services/api/flickr.people.getPhotos.html
// check if API Setting saved
if ($flickr_user_id && $flickr_api_key) {
	$params = array(
		'api_key' => $flickr_api_key,
		'user_id' => $flickr_user_id,
		'method' => $flickr_photostrem_method,
		'per_page' => 200,
		'format' => 'json',
        'nojsoncallback' => 1,
		'extras' => 'date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_q, url_t, url_s, url_n, url_m, url_z, url_c, url_l, url_o',
	);

	$encoded_params = array();
	foreach ($params as $k => $v) {
		$encoded_params[] = urlencode($k) . '=' . urlencode($v);
	}

	// call the API and decode the response
	$url = 'https://api.flickr.com/services/rest/?' . implode('&', $encoded_params);
	$rsp = wp_remote_get($url);
    if (is_wp_error($rsp)) {
        echo '<p>' . esc_html__('API Error', 'wp-flickr-gallery') . '</p>';
        return;
    }
	$rsp_obj = json_decode($rsp['body'], true);
	$res = isset($rsp_obj['photos']['photo']) ? $rsp_obj['photos']['photo'] : array();

    if (empty($res)) {
        echo '<p>' . esc_html__('No photos found in this photostream.', 'wp-flickr-gallery') . '</p>';
        return;
    }

    wp_enqueue_style('awl-fg-frontend-css');
    wp_enqueue_script('awl-fg-frontend-js');
	?>
	<div id="awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?>"
		class="awp-flickr-gallery-container awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?>"
        data-gallery-id="<?php echo esc_attr($flickr_gallery_id); ?>">
		<?php
		foreach ($res as $sp) {
			$photostream_title_fetch = isset($sp['title']) ? $sp['title'] : '';
			$thumbnail_url = '';
			$lightboxl_url = '';

			// Thumbnail Image Size Logic
			if ($thumb_img_size == 'url_sq' && isset($sp['url_sq'])) {
                $thumbnail_url = $sp['url_sq'];
			} elseif ($thumb_img_size == 'url_q' && isset($sp['url_q'])) {
                $thumbnail_url = $sp['url_q'];
			} elseif ($thumb_img_size == 'url_t' && isset($sp['url_t'])) {
                $thumbnail_url = $sp['url_t'];
			} elseif ($thumb_img_size == 'url_s' && isset($sp['url_s'])) {
                $thumbnail_url = $sp['url_s'];
			} elseif ($thumb_img_size == 'url_n' && isset($sp['url_n'])) {
                $thumbnail_url = $sp['url_n'];
			}

			// Fallbacks
			if (empty($thumbnail_url)) {
                $fallback_order = array('url_q', 'url_sq', 'url_s', 'url_t', 'url_n', 'url_m');
                foreach ($fallback_order as $size) {
                    if (isset($sp[$size])) {
                        $thumbnail_url = $sp[$size];
                        break;
                    }
                }
			}

			// Lightbox Image Size Logic
			if ($lightbox_img_size == 'url_m' && isset($sp['url_m'])) {
				$lightboxl_url = $sp['url_m'];
			} elseif ($lightbox_img_size == 'url_l' && isset($sp['url_l'])) {
				$lightboxl_url = $sp['url_l'];
			} elseif ($lightbox_img_size == 'url_o' && isset($sp['url_o'])) {
				$lightboxl_url = $sp['url_o'];
			}

			// Lightbox Fallback
			if (empty($lightboxl_url)) {
                $fallback_order = array('url_l', 'url_o', 'url_m', 'url_z');
                foreach ($fallback_order as $size) {
                    if (isset($sp[$size])) {
                        $lightboxl_url = $sp[$size];
                        break;
                    }
                }
			}

			if ($thumbnail_url) {
                ?>
                <div class="img-responsive text-center single-flickr-item single-photostream-<?php echo esc_attr($flickr_gallery_id); ?> <?php echo esc_attr($col_desktops); ?>">
                    <?php if ($apply_light_box == 'true' && $lightboxl_url) : ?>
                        <a href="<?php echo esc_url($lightboxl_url); ?>" class="awl-hm-card-inner" style="display: block;"
                            data-sub-html="<?php echo esc_attr($photostream_title_fetch); ?>"
                            data-rel="lightcase-<?php echo esc_attr($flickr_gallery_id); ?>:myCollection:slideshow">
                    <?php else : ?>
                        <div class="awl-hm-card-inner">
                    <?php endif; ?>

                        <div class="awl-hm-overlay">
                            <div class="awl-hm-plus"></div>
                            <div class="awl-hm-content">
                                <h4 class="awl-hm-title"><?php echo esc_html(mb_strimwidth($photostream_title_fetch, 0, 30, "...")); ?></h4>
                                <span class="awl-hm-icon"><?php esc_html_e('View Photo', 'wp-flickr-gallery'); ?></span>
                            </div>
                        </div>

                        <img class="img-responsive img-thumbnail photo loading" src="<?php echo esc_url($thumbnail_url); ?>"
                            alt="<?php echo esc_attr($photostream_title_fetch); ?>" width="100%" height="auto">

                    <?php if ($apply_light_box == 'true' && $lightboxl_url) : ?>
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
	<?php
} else {
	echo "<p class='alert'>" . esc_html__('Error! Check your Flickr API Settings. Maybe API Key or User ID is incorrect or empty.', 'wp-flickr-gallery') . "</p>";
}