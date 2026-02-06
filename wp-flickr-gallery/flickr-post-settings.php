<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Flickr Post Settings
$flickr_gallery_settings = get_post_meta($post->ID, 'awl_fg_post_settings_' . $post->ID, true);


// css
// css
wp_enqueue_style('awl-fg-setting-bootstrap-css', plugin_dir_url(__FILE__) . 'css/setting-bootstrap.css');
wp_enqueue_style('awl-fg-toogle-button-css', plugin_dir_url(__FILE__) . 'css/toogle-button.css');
wp_enqueue_style('awl-fg-styles-css', plugin_dir_url(__FILE__) . 'css/styles.css');
wp_enqueue_style('awl-fg-font-awesome-min-css', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css');

// js
wp_enqueue_script('jquery');
wp_enqueue_script('awl-fg-bootstrap-js', plugin_dir_url(__FILE__) . 'js/bootstrap.js', array('jquery'), '', true);

// uploader
wp_enqueue_media();
wp_enqueue_script('thickbox');
wp_enqueue_script('em-image-upload');
wp_enqueue_style('thickbox');
?>
<style>
	/* --- HYPER-MODERN UI VARIABLES --- */
	:root {
		--hm-primary: #8b5cf6;
		/* Violet */
		--hm-secondary: #ec4899;
		/* Pink */
		--hm-accent: #06b6d4;
		/* Cyan */
		--hm-gradient: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
		--hm-gradient-hover: linear-gradient(135deg, #7c3aed 0%, #db2777 100%);
		--hm-bg-mesh: radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 1) 0, transparent 50%), radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 1) 0, transparent 50%), radial-gradient(at 100% 0%, hsla(339, 49%, 30%, 1) 0, transparent 50%);
		--hm-glass-bg: rgba(255, 255, 255, 0.65);
		/* Increased opacity for better contrast */
		--hm-glass-border: 1px solid rgba(255, 255, 255, 0.8);
		--hm-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
		--hm-card-radius: 20px;
		--hm-text-dark: #1f2937;
		--hm-text-light: #6b7280;
	}

	.awl-hm-wrapper * {
		box-sizing: border-box;
		outline: none;
	}

	.awl-hm-wrapper {
		font-family: 'Inter', 'Outfit', system-ui, sans-serif;
		background-color: #f3f4f6;
		background-image:
			radial-gradient(at 10% 10%, rgba(139, 92, 246, 0.15) 0px, transparent 50%),
			radial-gradient(at 90% 10%, rgba(236, 72, 153, 0.15) 0px, transparent 50%),
			radial-gradient(at 90% 90%, rgba(6, 182, 212, 0.1) 0px, transparent 50%),
			radial-gradient(at 10% 90%, rgba(244, 63, 94, 0.1) 0px, transparent 50%);
		padding: 50px;
		max-width: 1280px;
		margin: 20px auto;
		border-radius: var(--hm-card-radius);
		box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
		min-height: 80vh;
	}

	/* --- HEADER --- */
	.awl-hm-header {
		text-align: center;
		margin-bottom: 50px;
	}

	.awl-hm-header h1 {
		font-size: 3rem;
		font-weight: 800;
		margin: 0;
		background: var(--hm-gradient);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		letter-spacing: -0.02em;
	}

	.awl-hm-header p {
		font-size: 1.1rem;
		color: var(--hm-text-light);
		margin-top: 10px;
	}

	.awl-hm-badge {
		background: var(--hm-gradient);
		color: #fff;
		padding: 4px 10px;
		border-radius: 12px;
		font-size: 0.7rem;
		font-weight: 700;
		text-transform: uppercase;
		vertical-align: top;
		margin-left: 8px;
		box-shadow: 0 2px 4px rgba(236, 72, 153, 0.4);
	}

	/* --- GRID --- */
	.awl-hm-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
		gap: 30px;
		align-items: start;
	}

	/* --- CARDS --- */
	.awl-hm-card {
		background: var(--hm-glass-bg);
		backdrop-filter: blur(16px);
		-webkit-backdrop-filter: blur(16px);
		border: var(--hm-glass-border);
		border-radius: var(--hm-card-radius);
		padding: 30px;
		box-shadow: var(--hm-shadow);
		transition: transform 0.3s ease;
		margin-bottom: 30px;
		position: relative;
		/* overflow: hidden; */
		z-index: 1;
	}

	.awl-hm-card:hover {
		transform: translateY(-2px);
		z-index: 10;
		box-shadow:
			0 15px 25px -5px rgba(0, 0, 0, 0.1),
			inset 0 0 20px rgba(255, 255, 255, 0.5);
		/* Inner Glow */
	}

	.awl-hm-card-title {
		font-size: 1.3rem;
		font-weight: 700;
		color: var(--hm-text-dark);
		margin: 0 0 25px 0;
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.awl-hm-icon-box {
		width: 40px;
		height: 40px;
		background: var(--hm-gradient);
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		font-size: 1.1rem;
		box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
	}

	/* --- CONTROLS --- */
	.awl-hm-group {
		margin-bottom: 25px;
	}

	.awl-hm-label {
		display: block;
		font-weight: 600;
		margin-bottom: 10px;
		color: #374151;
		font-size: 1.4rem;
	}

	.awl-hm-sub {
		font-size: 0.85rem;
		color: var(--hm-text-light);
		display: block;
		margin-top: 5px;
	}

	/* INPUTS / SELECTS */
	.awl-hm-select,
	.awl-hm-input {
		width: 100%;
		padding: 10px 15px;
		border: 1px solid rgba(0, 0, 0, 0.1);
		background: #fff;
		border-radius: 10px;
		font-size: 0.95rem;
		color: var(--hm-text-dark);
		transition: all 0.2s;
	}

	.awl-hm-select:focus,
	.awl-hm-input:focus {
		border-color: #8b5cf6;
		box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
	}

	/* RADIO CARDS (Source) */
	.awl-hm-radios {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 15px;
	}

	.awl-hm-radio-item {
		cursor: pointer;
		position: relative;
	}

	.awl-hm-radio-item input {
		position: absolute;
		opacity: 0;
		width: 0;
		height: 0;
	}

	.awl-hm-radio-box {
		display: flex;
		flex-direction: column;
		align-items: center;
		padding: 20px;
		background: white;
		border: 2px solid transparent;
		border-radius: 14px;
		transition: all 0.2s;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
	}

	.awl-hm-radio-box i {
		font-size: 1.8rem;
		margin-bottom: 8px;
		color: #9ca3af;
	}

	.awl-hm-radio-box span {
		font-weight: 600;
		font-size: 0.9rem;
	}

	/* Active Radio Card */
	.awl-hm-radio-item input:checked+.awl-hm-radio-box {
		border-color: #d946ef;
		background: #fff;
		box-shadow: 0 4px 12px rgba(217, 70, 239, 0.25);
		transform: translateY(-2px);
	}

	.awl-hm-radio-item input:checked+.awl-hm-radio-box i {
		color: #d946ef;
	}

	.awl-hm-radio-item input:checked+.awl-hm-radio-box span {
		color: #8b5cf6;
	}

	/* PILL SWITCHES */
	.awl-hm-switch-group {
		display: inline-flex;
		background: #e2e8f0;
		padding: 4px;
		border-radius: 99px;
		position: relative;
	}

	.awl-hm-switch-item {
		position: relative;
		cursor: pointer;
	}

	.awl-hm-switch-item input {
		position: absolute;
		opacity: 0;
		width: 0;
		height: 0;
	}

	.awl-hm-switch-btn {
		display: inline-block;
		padding: 8px 24px;
		border-radius: 99px;
		font-size: 0.9rem;
		font-weight: 600;
		color: #64748b;
		transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
		z-index: 1;
		position: relative;
	}

	/* Active Switch Button */
	.awl-hm-switch-item input:checked+.awl-hm-switch-btn {
		background: var(--hm-gradient);
		color: #fff;
		box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
	}

	.awl-hm-switch-item:hover .awl-hm-switch-btn {
		color: #334155;
	}

	.awl-hm-switch-item input:checked+.awl-hm-switch-btn:hover {
		color: #fff;
	}

	/* RANGE SLIDER */
	.awl-hm-range-wrap {
		display: flex;
		align-items: center;
		gap: 15px;
	}

	.awl-hm-range {
		flex: 1;
		-webkit-appearance: none;
		height: 6px;
		border-radius: 99px;
		background: #e2e8f0;
	}

	.awl-hm-range::-webkit-slider-thumb {
		-webkit-appearance: none;
		width: 20px;
		height: 20px;
		border-radius: 50%;
		background: var(--hm-primary);
		cursor: pointer;
		box-shadow: 0 0 0 4px rgba(255, 255, 255, 1), 0 2px 5px rgba(0, 0, 0, 0.1);
		transition: transform 0.1s;
	}

	.awl-hm-range::-webkit-slider-thumb:hover {
		transform: scale(1.1);
	}

	.awl-hm-badge-val {
		background: var(--hm-secondary);
		color: #fff;
		font-weight: 700;
		padding: 2px 8px;
		border-radius: 6px;
		font-size: 0.85rem;
		min-width: 40px;
		text-align: center;
	}

	/* NATIVE COLOR INPUT */
	.awl-hm-color-wrapper {
		display: flex;
		align-items: center;
		gap: 15px;
		background: #fff;
		padding: 10px;
		border-radius: 12px;
		border: 1px solid rgba(0, 0, 0, 0.1);
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
		width: fit-content;
	}

	.awl-hm-color-swatch {
		width: 45px;
		height: 45px;
		padding: 0;
		border: none;
		border-radius: 8px;
		cursor: pointer;
		overflow: hidden;
		background: transparent;
	}

	.awl-hm-color-swatch::-webkit-color-swatch-wrapper {
		padding: 0;
	}

	.awl-hm-color-swatch::-webkit-color-swatch {
		border: none;
		border-radius: 8px;
		box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.1);
	}

	.awl-hm-color-input {
		border: none;
		background: transparent;
		font-family: monospace;
		font-size: 1rem;
		color: #374151;
		width: 100px;
		text-transform: uppercase;
		font-weight: 600;
	}

	.awl-hm-color-input:focus {
		outline: none;
	}

	/* Animations */
	@keyframes slideUpFade {
		from {
			opacity: 0;
			transform: translateY(20px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.animate-in {
		animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
	}

	.delay-1 {
		animation-delay: 0.1s;
	}

	.delay-2 {
		animation-delay: 0.2s;
	}
</style>

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
					<label class="awl-hm-label">Content Type</label>
					<div class="awl-hm-radios">
						<label class="awl-hm-radio-item">
							<input type="radio" name="flickr_gallery_type" value="photostream" <?php checked($flickr_gallery_type, 'photostream'); ?>>
							<div class="awl-hm-radio-box">
								<i class="dashicons dashicons-format-gallery"></i>
								<span>Photostream</span>
							</div>
						</label>
						<label class="awl-hm-radio-item">
							<input type="radio" name="flickr_gallery_type" value="album" <?php checked($flickr_gallery_type, 'album'); ?>>
							<div class="awl-hm-radio-box">
								<i class="dashicons dashicons-album"></i>
								<span>Album</span>
							</div>
						</label>
					</div>
				</div>

				<div class="album_gallery awl-hm-group" style="display:none;">
					<label class="awl-hm-label">Select Album</label>
					<?php
					$flickr_album_id = isset($flickr_gallery_settings['flickr_album_id']) ? $flickr_gallery_settings['flickr_album_id'] : '';
					$flickr_api_settings = get_option('flickr_api_settings');

					// Safe Access
					$flickr_api_key = is_array($flickr_api_settings) && isset($flickr_api_settings['flickr_api_key']) ? $flickr_api_settings['flickr_api_key'] : '';
					$flickr_user_id = is_array($flickr_api_settings) && isset($flickr_api_settings['flickr_user_id']) ? $flickr_api_settings['flickr_user_id'] : '';

					if ($flickr_user_id && $flickr_api_key) {
						echo '<select name="flickr_album_id" class="awl-hm-select">';

						// Fetch logic (Simplified for view)
						$params = array('api_key' => $flickr_api_key, 'user_id' => $flickr_user_id, 'method' => 'flickr.photosets.getList', 'format' => 'php_serial');
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
								echo '<option>No Albums Found</option>';
							}
						} else {
							echo '<option>API Error</option>';
						}
						echo '</select>';
					} else {
						echo '<div class="awl-hm-sub">Please configure API Keys first.</div>';
					}
					?>
				</div>

				<h2 class="awl-hm-card-title" style="margin-top: 40px;">
					<div class="awl-hm-icon-box" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);">
						<i class="dashicons dashicons-layout"></i>
					</div>
					Layout
				</h2>

				<div class="awl-hm-group">
					<label class="awl-hm-label">Columns</label>
					<?php $col_desktops = isset($flickr_gallery_settings['col_desktops']) ? $flickr_gallery_settings['col_desktops'] : 'col-md-3'; ?>
					<select name="col_desktops" class="awl-hm-select">
						<option value="col-md-4" <?php selected($col_desktops, 'col-md-4'); ?>>3 Columns (Wide)</option>
						<option value="col-md-3" <?php selected($col_desktops, 'col-md-3'); ?>>4 Columns (Standard)
						</option>
					</select>
				</div>

				<div class="awl-hm-group">
					<label class="awl-hm-label">Thumbnail Size</label>
					<?php $thumb_img_size = isset($flickr_gallery_settings['thumb_img_size']) ? $flickr_gallery_settings['thumb_img_size'] : 'url_q'; ?>
					<select name="thumb_img_size" class="awl-hm-select">
						<option value="url_sq" <?php selected($thumb_img_size, 'url_sq'); ?>>Square 75px</option>
						<option value="url_q" <?php selected($thumb_img_size, 'url_q'); ?>>Large Square 150px</option>
						<option value="url_t" <?php selected($thumb_img_size, 'url_t'); ?>>Thumbnail 100px</option>
						<option value="url_s" <?php selected($thumb_img_size, 'url_s'); ?>>Small 240px</option>
						<option value="url_n" <?php selected($thumb_img_size, 'url_n'); ?>>Small 320px</option>

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
					Typography
				</h2>

				<div class="awl-hm-group">
					<label class="awl-hm-label">Show Title</label>
					<?php $fg_gallery_title = isset($flickr_gallery_settings['fg_gallery_title']) ? $flickr_gallery_settings['fg_gallery_title'] : 'true'; ?>
					<div class="awl-hm-switch-group">
						<label class="awl-hm-switch-item">
							<input type="radio" name="fg_gallery_title" value="true" <?php checked($fg_gallery_title, 'true'); ?>>
							<span class="awl-hm-switch-btn">Show</span>
						</label>
						<label class="awl-hm-switch-item">
							<input type="radio" name="fg_gallery_title" value="false" <?php checked($fg_gallery_title, 'false'); ?>>
							<span class="awl-hm-switch-btn">Hide</span>
						</label>
					</div>
				</div>

				<div class="gallery_post_title">
					<div class="awl-hm-group">
						<label class="awl-hm-label">Alignment</label>
						<?php $fg_gallery_titlealighment = isset($flickr_gallery_settings['fg_gallery_titlealighment']) ? $flickr_gallery_settings['fg_gallery_titlealighment'] : 'left'; ?>
						<div class="awl-hm-switch-group">
							<label class="awl-hm-switch-item">
								<input type="radio" name="fg_gallery_titlealighment" value="left" <?php checked($fg_gallery_titlealighment, 'left'); ?>>
								<span class="awl-hm-switch-btn">Left</span>
							</label>
							<label class="awl-hm-switch-item">
								<input type="radio" name="fg_gallery_titlealighment" value="center" <?php checked($fg_gallery_titlealighment, 'center'); ?>>
								<span class="awl-hm-switch-btn">Center</span>
							</label>
							<label class="awl-hm-switch-item">
								<input type="radio" name="fg_gallery_titlealighment" value="right" <?php checked($fg_gallery_titlealighment, 'right'); ?>>
								<span class="awl-hm-switch-btn">Right</span>
							</label>
						</div>
					</div>

					<div class="awl-hm-group">
						<label class="awl-hm-label">Font Size</label>
						<?php $fg_gallery_titlesize = isset($flickr_gallery_settings['fg_gallery_titlesize']) ? $flickr_gallery_settings['fg_gallery_titlesize'] : 16; ?>
						<div class="awl-hm-range-wrap">
							<input type="range" name="fg_gallery_titlesize" class="awl-hm-range" min="10" max="60"
								value="<?php echo esc_attr($fg_gallery_titlesize); ?>">
							<span class="awl-hm-badge-val"
								id="size-display"><?php echo esc_attr($fg_gallery_titlesize); ?>px</span>
						</div>
					</div>

					<div class="awl-hm-group">
						<label class="awl-hm-label">Title Color</label>
						<?php $fg_gallery_titlecolor = isset($flickr_gallery_settings['fg_gallery_titlecolor']) ? $flickr_gallery_settings['fg_gallery_titlecolor'] : '#000000'; ?>

						<!-- NATIVE HTML5 COLOR INPUT -->
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
					Interaction
				</h2>

				<div class="awl-hm-group">
					<label class="awl-hm-label">Lightbox</label>
					<?php $apply_light_box = isset($flickr_gallery_settings['apply_light_box']) ? $flickr_gallery_settings['apply_light_box'] : 'true'; ?>
					<div class="awl-hm-switch-group">
						<label class="awl-hm-switch-item">
							<input type="radio" name="apply_light_box" value="true" <?php checked($apply_light_box, 'true'); ?>>
							<span class="awl-hm-switch-btn">Enable</span>
						</label>
						<label class="awl-hm-switch-item">
							<input type="radio" name="apply_light_box" value="false" <?php checked($apply_light_box, 'false'); ?>>
							<span class="awl-hm-switch-btn">Disable</span>
						</label>
					</div>
					<p class="awl-hm-sub">Open images in a fullscreen popup.</p>
				</div>

				<div class="awl-hm-group">
					<label class="awl-hm-label">Lightbox Quality</label>
					<?php $lightbox_img_size = isset($flickr_gallery_settings['lightbox_img_size']) ? $flickr_gallery_settings['lightbox_img_size'] : 'url_m'; ?>
					<select name="lightbox_img_size" class="awl-hm-select">
						<option value="url_m" <?php selected($lightbox_img_size, 'url_m'); ?>>Medium - 500px</option>
						<option value="url_l" <?php selected($lightbox_img_size, 'url_l'); ?>>Large - 1024px</option>
						<option value="url_o" <?php selected($lightbox_img_size, 'url_o'); ?>>Original - HD</option>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>

<?php wp_nonce_field('fg_save_settings', 'fg_save_nonce'); ?>

<script>
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
</script>