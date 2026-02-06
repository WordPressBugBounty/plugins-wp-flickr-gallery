<?php
// get photo stream - https://www.flickr.com/services/api/flickr.people.getPhotos.html
// check if API Setting saved
if ($flickr_user_id && $flickr_api_key) {
	$params = array(
		'api_key' => $flickr_api_key,
		'user_id' => $flickr_user_id,
		'method' => $flickr_photostrem_method,
		'per_page' => 200,
		'format' => 'php_serial',
		'extras' => 'date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_q, url_t, url_s, url_n, url_m, url_z, url_c, url_l, url_o',
	);

	$encoded_params = array();
	foreach ($params as $k => $v) {
		$encoded_params[] = urlencode($k) . '=' . urlencode($v);
	}

	// call the API and decode the response
	$url = 'https://api.flickr.com/services/rest/?' . implode('&', $encoded_params);
	$rsp = wp_remote_get($url);
	$rsp_obj = unserialize($rsp['body']);
	$res = $rsp_obj['photos']['photo'];
	?>
	<style>
		/* --- FRONTEND PREMIUM ENVATO CARD STYLE --- */

		/* Grid Item Container */
		#awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?> .single-photostream-<?php echo esc_attr($flickr_gallery_id); ?> {
			margin-bottom: 30px !important;
			padding: 0 10px !important;
			/* Gutter compensation */
			background: transparent !important;
			border: none !important;
			box-shadow: none !important;
		}

		/* Inner Card Wrapper */
		.awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?> .awl-hm-card-inner {
			position: relative !important;
			border-radius: 12px !important;
			overflow: hidden !important;
			background: #fff !important;
			box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1) !important;
			transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
			transform: translateY(0);
		}

		/* Hover Lift */
		.awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?> .single-photostream-<?php echo esc_attr($flickr_gallery_id); ?>:hover .awl-hm-card-inner {
			transform: translateY(-8px) !important;
			box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.2) !important;
		}

		/* Image Force Full Width */
		.awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?> img,
		.awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?> .img-thumbnail {
			width: 100% !important;
			min-width: 100% !important;
			height: auto !important;
			display: block !important;
			margin: 0 !important;
			padding: 0 !important;
			border-radius: 0 !important;
			border: none !important;
			transition: transform 0.6s ease !important;
		}

		/* Image Zoom */
		.awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?> .single-photostream-<?php echo esc_attr($flickr_gallery_id); ?>:hover img {
			transform: scale(1.15) !important;
		}

		/* Overlay */
		.awl-hm-overlay {
			position: absolute !important;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.2) 60%, rgba(0, 0, 0, 0) 100%) !important;
			opacity: 0 !important;
			transition: all 0.3s ease !important;
			display: flex !important;
			flex-direction: column !important;
			justify-content: flex-end !important;
			padding: 20px !important;
			z-index: 10 !important;
			pointer-events: none !important;
			/* Allow clicks to pass through */
		}

		.awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?> .single-photostream-<?php echo esc_attr($flickr_gallery_id); ?>:hover .awl-hm-overlay {
			opacity: 1 !important;
		}

		/* Content Styling */
		.awl-hm-content {
			transform: translateY(20px) !important;
			transition: transform 0.4s ease !important;
		}

		.awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?> .single-photostream-<?php echo esc_attr($flickr_gallery_id); ?>:hover .awl-hm-content {
			transform: translateY(0) !important;
		}

		.awl-hm-title {
			color: #fff !important;
			font-size: 16px !important;
			font-weight: 600 !important;
			margin: 0 0 5px 0 !important;
			line-height: 1.3 !important;
			display: block !important;
			text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
		}

		.awl-hm-icon {
			color: rgba(255, 255, 255, 0.8) !important;
			font-size: 13px !important;
			font-weight: 400 !important;
			text-transform: uppercase !important;
			letter-spacing: 0.5px !important;
		}

		.awl-hm-plus {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%) scale(0);
			width: 50px;
			height: 50px;
			background: rgba(255, 255, 255, 0.2);
			border-radius: 50%;
			backdrop-filter: blur(5px);
			display: flex;
			align-items: center;
			justify-content: center;
			color: #fffbox;
			transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
		}

		.awl-hm-plus::before {
			content: '+';
			color: #fff;
			font-size: 28px;
			font-weight: 300;
		}

		.awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?> .single-photostream-<?php echo esc_attr($flickr_gallery_id); ?>:hover .awl-hm-plus {
			transform: translate(-50%, -50%) scale(1);
		}
	</style>
	<div id="awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?>"
		class="awp-flickr-photostream-<?php echo esc_attr($flickr_gallery_id); ?>">
		<?php
		foreach ($res as $sp) {
			$photostream_title_fetch = isset($sp['title']) ? esc_html($sp['title']) : '';
			$thumbnail_url = '';
			$lightboxl_url = '';

			// Thumbnail Image Size Logic - STRICT COMPLIANCE (User gets exactly what they asked for)
			$thumbnail_url = '';

			// 1. User wants SQUARE (url_sq - 75x75)
			if ($thumb_img_size == 'url_sq') {
				if (isset($sp['url_sq'])) {
					$thumbnail_url = $sp['url_sq'];
				}
			}
			// 2. User wants LARGE SQUARE (url_q - 150x150)
			elseif ($thumb_img_size == 'url_q') {
				if (isset($sp['url_q'])) {
					$thumbnail_url = $sp['url_q'];
				}
			}
			// 3. User wants THUMBNAIL (url_t - 100px longest side)
			elseif ($thumb_img_size == 'url_t') {
				if (isset($sp['url_t'])) {
					$thumbnail_url = $sp['url_t'];
				}
			}
			// 4. User wants SMALL (url_s - 240px longest side)
			elseif ($thumb_img_size == 'url_s') {
				if (isset($sp['url_s'])) {
					$thumbnail_url = $sp['url_s'];
				}
			}
			// 5. User wants SMALL 320 (url_n - 320px longest side)
			elseif ($thumb_img_size == 'url_n') {
				if (isset($sp['url_n'])) {
					$thumbnail_url = $sp['url_n'];
				}
			}

			// Fallbacks (If specific choice failed)
			// Priority: Square 150 -> Small 320 -> Medium 500
			if (empty($thumbnail_url)) {
				// Try finding ANY square if a square was requested
				if ($thumb_img_size == 'url_sq' || $thumb_img_size == 'url_q') {
					// Prefer Square fallbacks
					if (isset($sp['url_q'])) {
						$thumbnail_url = $sp['url_q'];
					} elseif (isset($sp['url_sq'])) {
						$thumbnail_url = $sp['url_sq'];
					}
				}
				// Otherwise try normal rectangular
				if (empty($thumbnail_url)) {
					if (isset($sp['url_s'])) {
						$thumbnail_url = $sp['url_s'];
					} elseif (isset($sp['url_t'])) {
						$thumbnail_url = $sp['url_t'];
					} elseif (isset($sp['url_n'])) {
						$thumbnail_url = $sp['url_n'];
					} // Fallback to 320 if smaller missing
					elseif (isset($sp['url_m'])) {
						$thumbnail_url = $sp['url_m'];
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

			// Robust Fallback for Lightbox (Prioritize Large -> Original -> Medium)
			if (empty($lightboxl_url)) {
				if (isset($sp['url_l'])) {
					$lightboxl_url = $sp['url_l'];
				} elseif (isset($sp['url_o'])) {
					$lightboxl_url = $sp['url_o'];
				} elseif (isset($sp['url_m'])) {
					$lightboxl_url = $sp['url_m'];
				} elseif (isset($sp['url_z'])) {
					$lightboxl_url = $sp['url_z'];
				} // Medium 640
			}


			$image_type = 'image';
			if ($apply_light_box == 'true') {
				// with lightbox
				// light gallery
				if ($image_type == 'image' && $thumbnail_url && $lightboxl_url) {
					?>
					<div
						class="img-responsive text-center single-photostream-<?php echo esc_attr($flickr_gallery_id); ?> <?php echo esc_attr($col_desktops); ?>">
						<a href="<?php echo esc_url($lightboxl_url); ?>" class="awl-hm-card-inner" style="display: block;"
							data-sub-html="<?php echo esc_attr($flickr_gallery_id); ?><?php echo esc_html($photostream_title_fetch); ?>"
							data-rel="lightcase-<?php echo esc_attr($flickr_gallery_id); ?>:myCollection:slideshow">

							<div class="awl-hm-overlay">
								<div class="awl-hm-plus"></div>
								<div class="awl-hm-content">
									<h4 class="awl-hm-title"><?php echo mb_strimwidth($photostream_title_fetch, 0, 30, "..."); ?></h4>
									<span class="awl-hm-icon">View Photo</span>
								</div>
							</div>

							<img class="img-responsive img-thumbnail photo loading" src="<?php echo esc_url($thumbnail_url); ?>"
								alt="<?php echo esc_html($photostream_title_fetch); ?>" width="100%" height="auto">
						</a>
					</div>
					<?php
				}
			} else {
				// without lightbox
				if ($image_type == 'image' && $thumbnail_url) {
					?>
					<div
						class="img-responsive text-center single-photostream-<?php echo esc_attr($flickr_gallery_id); ?> <?php echo esc_attr($col_desktops); ?>">
						<div class="awl-hm-card-inner">
							<div class="awl-hm-overlay">
								<div class="awl-hm-content">
									<h4 class="awl-hm-title"><?php echo mb_strimwidth($photostream_title_fetch, 0, 30, "..."); ?></h4>
								</div>
							</div>
							<img class="img-responsive img-thumbnail photo loading" src="<?php echo esc_url($thumbnail_url); ?>"
								alt="<?php echo esc_html($photostream_title_fetch); ?>" width="100%" height="auto">
						</div>
					</div>
					<?php
				}
			}
		}
		?>
	</div>
	<?php
} else {
	echo "<p class='alert'>Error! Check your Flickr API Settings. May be API Key or User ID incorrect or empty.</p>";
}
?>
<script>
	<?php if ($apply_light_box == 'true') { ?>
		jQuery(window).load(function () {
			jQuery(document).ready(function (jQuery) {
				jQuery('a[data-rel^=lightcase-<?php echo esc_js($flickr_gallery_id); ?>]').lightcase({});
			});
		});
	<?php } ?>

	// masonry effect
	jQuery(document).ready(function () {
		// isotope effect function
		// Method 1 - Initialize Isotope, then trigger layout after each image loads.
		var fg_isotope = jQuery('#awp-flickr-photostream-<?php echo esc_js($flickr_gallery_id); ?>').isotope({
			// options
			itemSelector: '.single-photostream-<?php echo esc_js($flickr_gallery_id); ?>',
		});
		// layout Isotope after each image loads
		fg_isotope.imagesLoaded().progress(function () {
			fg_isotope.isotope('layout');
		});
	});
</script>