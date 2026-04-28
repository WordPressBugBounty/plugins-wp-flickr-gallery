<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Flickr Post Settings
$flickr_gallery_settings = get_post_meta($post->ID, 'awl_fg_post_settings_' . $post->ID, true);
?>

<div class="awl-hm-wrapper animate-in">
	<div class="awl-hm-header">
		<h1><?php esc_html_e('Flickr Gallery', 'wp-flickr-gallery'); ?></h1>
		<p><?php esc_html_e('Create Beautiful, Responsive Galleries', 'wp-flickr-gallery'); ?></p>
	</div>

	<div class="awl-hm-grid">

		<!-- LEFT COL -->
		<div class="awl-hm-col animate-in delay-1">

			<!-- Source -->
			<div class="awl-hm-card">
				<h2 class="awl-hm-card-title">
					<div class="awl-hm-icon-box"><i class="dashicons dashicons-images-alt2"></i></div>
					<?php esc_html_e('Gallery Source', 'wp-flickr-gallery'); ?>
				</h2>

				<?php $flickr_gallery_type = isset($flickr_gallery_settings['flickr_gallery_type']) ? $flickr_gallery_settings['flickr_gallery_type'] : 'album'; ?>

				<div class="awl-hm-group">
					<label class="awl-hm-label"><?php esc_html_e('Content Type', 'wp-flickr-gallery'); ?></label>
					<div class="awl-hm-radios">
						<label class="awl-hm-radio-item">
							<input type="radio" name="flickr_gallery_type" value="photostream" <?php checked($flickr_gallery_type, 'photostream'); ?>>
							<div class="awl-hm-radio-box">
								<i class="dashicons dashicons-format-gallery"></i>
								<span><?php esc_html_e('Photostream', 'wp-flickr-gallery'); ?></span>
							</div>
						</label>
						<label class="awl-hm-radio-item">
							<input type="radio" name="flickr_gallery_type" value="album" <?php checked($flickr_gallery_type, 'album'); ?>>
							<div class="awl-hm-radio-box">
								<i class="dashicons dashicons-album"></i>
								<span><?php esc_html_e('Album', 'wp-flickr-gallery'); ?></span>
							</div>
						</label>
					</div>
				</div>

				<div class="album_gallery awl-hm-group" style="display:none;">
					<label class="awl-hm-label"><?php esc_html_e('Select Album', 'wp-flickr-gallery'); ?></label>
					<?php
					$flickr_album_id = isset($flickr_gallery_settings['flickr_album_id']) ? $flickr_gallery_settings['flickr_album_id'] : '';
					$flickr_api_settings = get_option('flickr_api_settings');

					$flickr_api_key = is_array($flickr_api_settings) && isset($flickr_api_settings['flickr_api_key']) ? $flickr_api_settings['flickr_api_key'] : '';
					$flickr_user_id = is_array($flickr_api_settings) && isset($flickr_api_settings['flickr_user_id']) ? $flickr_api_settings['flickr_user_id'] : '';

					if ($flickr_user_id && $flickr_api_key) {
						echo '<select name="flickr_album_id" class="awl-hm-select">';

						$params = array(
							'api_key' => $flickr_api_key, 
							'user_id' => $flickr_user_id, 
							'method' => 'flickr.photosets.getList', 
							'format' => 'php_serial'
						);
						$encoded_params = [];
						foreach ($params as $k => $v) {
							$encoded_params[] = urlencode($k) . '=' . urlencode($v);
						}
						$url = 'https://api.flickr.com/services/rest/?' . implode('&', $encoded_params);
						$rsp = wp_remote_get($url);

						if (!is_wp_error($rsp)) {
							$rsp_obj = unserialize($rsp['body']);
							if (isset($rsp_obj['photosets']['photoset'])) {
								foreach ($rsp_obj['photosets']['photoset'] as $album) {
									echo '<option value="' . esc_attr($album['id']) . '" ' . selected($flickr_album_id, $album['id'], false) . '>' . esc_html($album['title']['_content']) . '</option>';
								}
							} else {
								echo '<option>' . esc_html__('No Albums Found', 'wp-flickr-gallery') . '</option>';
							}
						} else {
							echo '<option>' . esc_html__('API Error', 'wp-flickr-gallery') . '</option>';
						}
						echo '</select>';
					} else {
						echo '<div class="awl-hm-sub">' . esc_html__('Please configure API Keys first.', 'wp-flickr-gallery') . '</div>';
					}
					?>
				</div>

				<h2 class="awl-hm-card-title" style="margin-top: 40px;">
					<div class="awl-hm-icon-box" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);">
						<i class="dashicons dashicons-layout"></i>
					</div>
					<?php esc_html_e('Layout', 'wp-flickr-gallery'); ?>
				</h2>

				<div class="awl-hm-group">
					<label class="awl-hm-label"><?php esc_html_e('Columns', 'wp-flickr-gallery'); ?></label>
					<?php $col_desktops = isset($flickr_gallery_settings['col_desktops']) ? $flickr_gallery_settings['col_desktops'] : 'col-md-3'; ?>
					<select name="col_desktops" class="awl-hm-select">
						<option value="col-md-4" <?php selected($col_desktops, 'col-md-4'); ?>><?php esc_html_e('3 Columns (Wide)', 'wp-flickr-gallery'); ?></option>
						<option value="col-md-3" <?php selected($col_desktops, 'col-md-3'); ?>><?php esc_html_e('4 Columns (Standard)', 'wp-flickr-gallery'); ?></option>
					</select>
				</div>

				<div class="awl-hm-group">
					<label class="awl-hm-label"><?php esc_html_e('Thumbnail Size', 'wp-flickr-gallery'); ?></label>
					<?php $thumb_img_size = isset($flickr_gallery_settings['thumb_img_size']) ? $flickr_gallery_settings['thumb_img_size'] : 'url_q'; ?>
					<select name="thumb_img_size" class="awl-hm-select">
						<option value="url_sq" <?php selected($thumb_img_size, 'url_sq'); ?>><?php esc_html_e('Square 75px', 'wp-flickr-gallery'); ?></option>
						<option value="url_q" <?php selected($thumb_img_size, 'url_q'); ?>><?php esc_html_e('Large Square 150px', 'wp-flickr-gallery'); ?></option>
						<option value="url_t" <?php selected($thumb_img_size, 'url_t'); ?>><?php esc_html_e('Thumbnail 100px', 'wp-flickr-gallery'); ?></option>
						<option value="url_s" <?php selected($thumb_img_size, 'url_s'); ?>><?php esc_html_e('Small 240px', 'wp-flickr-gallery'); ?></option>
						<option value="url_n" <?php selected($thumb_img_size, 'url_n'); ?>><?php esc_html_e('Small 320px', 'wp-flickr-gallery'); ?></option>
					</select>
				</div>
			</div>
		</div>

		<!-- RIGHT COL -->
		<div class="awl-hm-col animate-in delay-2">

			<!-- Typography -->
			<div class="awl-hm-card">
				<h2 class="awl-hm-card-title">
					<div class="awl-hm-icon-box" style="background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);">
						<i class="dashicons dashicons-art"></i>
					</div>
					<?php esc_html_e('Typography', 'wp-flickr-gallery'); ?>
				</h2>

				<div class="awl-hm-group">
					<label class="awl-hm-label"><?php esc_html_e('Show Title', 'wp-flickr-gallery'); ?></label>
					<?php $fg_gallery_title = isset($flickr_gallery_settings['fg_gallery_title']) ? $flickr_gallery_settings['fg_gallery_title'] : 'true'; ?>
					<div class="awl-hm-switch-group">
						<label class="awl-hm-switch-item">
							<input type="radio" name="fg_gallery_title" value="true" <?php checked($fg_gallery_title, 'true'); ?>>
							<span class="awl-hm-switch-btn"><?php esc_html_e('Show', 'wp-flickr-gallery'); ?></span>
						</label>
						<label class="awl-hm-switch-item">
							<input type="radio" name="fg_gallery_title" value="false" <?php checked($fg_gallery_title, 'false'); ?>>
							<span class="awl-hm-switch-btn"><?php esc_html_e('Hide', 'wp-flickr-gallery'); ?></span>
						</label>
					</div>
				</div>

				<div class="gallery_post_title">
					<div class="awl-hm-group">
						<label class="awl-hm-label"><?php esc_html_e('Alignment', 'wp-flickr-gallery'); ?></label>
						<?php $fg_gallery_titlealighment = isset($flickr_gallery_settings['fg_gallery_titlealighment']) ? $flickr_gallery_settings['fg_gallery_titlealighment'] : 'left'; ?>
						<div class="awl-hm-switch-group">
							<label class="awl-hm-switch-item">
								<input type="radio" name="fg_gallery_titlealighment" value="left" <?php checked($fg_gallery_titlealighment, 'left'); ?>>
								<span class="awl-hm-switch-btn"><?php esc_html_e('Left', 'wp-flickr-gallery'); ?></span>
							</label>
							<label class="awl-hm-switch-item">
								<input type="radio" name="fg_gallery_titlealighment" value="center" <?php checked($fg_gallery_titlealighment, 'center'); ?>>
								<span class="awl-hm-switch-btn"><?php esc_html_e('Center', 'wp-flickr-gallery'); ?></span>
							</label>
							<label class="awl-hm-switch-item">
								<input type="radio" name="fg_gallery_titlealighment" value="right" <?php checked($fg_gallery_titlealighment, 'right'); ?>>
								<span class="awl-hm-switch-btn"><?php esc_html_e('Right', 'wp-flickr-gallery'); ?></span>
							</label>
						</div>
					</div>

					<div class="awl-hm-group">
						<label class="awl-hm-label"><?php esc_html_e('Font Size', 'wp-flickr-gallery'); ?></label>
						<?php $fg_gallery_titlesize = isset($flickr_gallery_settings['fg_gallery_titlesize']) ? $flickr_gallery_settings['fg_gallery_titlesize'] : 16; ?>
						<div class="awl-hm-range-wrap">
							<input type="range" name="fg_gallery_titlesize" class="awl-hm-range" min="10" max="60"
								value="<?php echo esc_attr($fg_gallery_titlesize); ?>">
							<span class="awl-hm-badge-val"
								id="size-display"><?php echo esc_attr($fg_gallery_titlesize); ?>px</span>
						</div>
					</div>

					<div class="awl-hm-group">
						<label class="awl-hm-label"><?php esc_html_e('Title Color', 'wp-flickr-gallery'); ?></label>
						<?php $fg_gallery_titlecolor = isset($flickr_gallery_settings['fg_gallery_titlecolor']) ? $flickr_gallery_settings['fg_gallery_titlecolor'] : '#000000'; ?>

						<div class="awl-hm-color-wrapper">
							<input type="color" class="awl-hm-color-swatch" id="color-picker-native"
								value="<?php echo esc_attr($fg_gallery_titlecolor); ?>">
							<input type="text" class="awl-hm-color-input" id="fg_gallery_titlecolor"
								name="fg_gallery_titlecolor" value="<?php echo esc_attr($fg_gallery_titlecolor); ?>">
						</div>
					</div>
				</div>

				<h2 class="awl-hm-card-title" style="margin-top: 40px;">
					<div class="awl-hm-icon-box" style="background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);">
						<i class="dashicons dashicons-welcome-view-site"></i>
					</div>
					<?php esc_html_e('Interaction', 'wp-flickr-gallery'); ?>
				</h2>

				<div class="awl-hm-group">
					<label class="awl-hm-label"><?php esc_html_e('Lightbox', 'wp-flickr-gallery'); ?></label>
					<?php $apply_light_box = isset($flickr_gallery_settings['apply_light_box']) ? $flickr_gallery_settings['apply_light_box'] : 'true'; ?>
					<div class="awl-hm-switch-group">
						<label class="awl-hm-switch-item">
							<input type="radio" name="apply_light_box" value="true" <?php checked($apply_light_box, 'true'); ?>>
							<span class="awl-hm-switch-btn"><?php esc_html_e('Enable', 'wp-flickr-gallery'); ?></span>
						</label>
						<label class="awl-hm-switch-item">
							<input type="radio" name="apply_light_box" value="false" <?php checked($apply_light_box, 'false'); ?>>
							<span class="awl-hm-switch-btn"><?php esc_html_e('Disable', 'wp-flickr-gallery'); ?></span>
						</label>
					</div>
					<p class="awl-hm-sub"><?php esc_html_e('Open images in a fullscreen popup.', 'wp-flickr-gallery'); ?></p>
				</div>

				<div class="awl-hm-group">
					<label class="awl-hm-label"><?php esc_html_e('Lightbox Quality', 'wp-flickr-gallery'); ?></label>
					<?php $lightbox_img_size = isset($flickr_gallery_settings['lightbox_img_size']) ? $flickr_gallery_settings['lightbox_img_size'] : 'url_m'; ?>
					<select name="lightbox_img_size" class="awl-hm-select">
						<option value="url_m" <?php selected($lightbox_img_size, 'url_m'); ?>><?php esc_html_e('Medium - 500px', 'wp-flickr-gallery'); ?></option>
						<option value="url_l" <?php selected($lightbox_img_size, 'url_l'); ?>><?php esc_html_e('Large - 1024px', 'wp-flickr-gallery'); ?></option>
						<option value="url_o" <?php selected($lightbox_img_size, 'url_o'); ?>><?php esc_html_e('Original - HD', 'wp-flickr-gallery'); ?></option>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>

<?php wp_nonce_field('fg_save_settings', 'fg_save_nonce'); ?>