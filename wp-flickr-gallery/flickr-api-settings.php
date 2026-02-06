<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
// css
wp_enqueue_style('awl-fg-font-awesome-min-css', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css');

// load API Settings
$flickr_api_settings = get_option('flickr_api_settings');

// Set Your Nonce
$fg_ajax_nonce = wp_create_nonce('fg_api_setting_nonce_key');
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
		max-width: 900px;
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
		font-size: 1rem;
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

	/* --- CARDS --- */
	.awl-hm-card {
		background: var(--hm-glass-bg);
		backdrop-filter: blur(16px);
		-webkit-backdrop-filter: blur(16px);
		border: var(--hm-glass-border);
		border-radius: var(--hm-card-radius);
		padding: 40px;
		box-shadow: var(--hm-shadow);
		transition: transform 0.3s ease;
		margin-bottom: 30px;
		position: relative;
		z-index: 1;
	}

	.awl-hm-card:hover {
		transform: translateY(-2px);
		z-index: 10;
		box-shadow:
			0 15px 25px -5px rgba(0, 0, 0, 0.1),
			inset 0 0 20px rgba(255, 255, 255, 0.5);
	}

	.awl-hm-card-title {
		font-size: 1.5rem;
		font-weight: 700;
		color: var(--hm-text-dark);
		margin: 0 0 30px 0;
		display: flex;
		align-items: center;
		gap: 15px;
	}

	.awl-hm-icon-box {
		width: 45px;
		height: 45px;
		background: var(--hm-gradient);
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		font-size: 1.2rem;
		box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
	}

	/* --- FORMS --- */
	.awl-hm-group {
		margin-bottom: 25px;
	}

	.awl-hm-label {
		display: block;
		font-weight: 600;
		margin-bottom: 10px;
		color: #374151;
		font-size: 1.5rem;
	}

	.awl-hm-input {
		width: 100%;
		padding: 12px 18px;
		border: 1px solid rgba(0, 0, 0, 0.1);
		background: #fff;
		border-radius: 12px;
		font-size: 1.2rem;
		color: var(--hm-text-dark);
		transition: all 0.2s;
	}

	.awl-hm-input:focus {
		border-color: #8b5cf6;
		box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
	}

	.awl-hm-helper {
		font-size: 1rem;
		color: var(--hm-text-light);
		margin-top: 8px;
		display: block;
	}

	.awl-hm-helper a {
		color: #8b5cf6;
		text-decoration: none;
		font-weight: 600;
	}

	.awl-hm-helper a:hover {
		text-decoration: underline;
	}

	/* --- BUTTONS --- */
	.awl-hm-btn {
		background: var(--hm-gradient);
		color: white;
		border: none;
		padding: 14px 32px;
		border-radius: 12px;
		font-size: 1rem;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.3s ease;
		box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
		display: inline-flex;
		align-items: center;
		gap: 10px;
	}

	.awl-hm-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
		color: white;
	}

	.awl-hm-btn:active {
		transform: translateY(0);
	}

	#fg_setting_load {
		display: inline-flex;
		align-items: center;
		color: var(--hm-primary);
		font-weight: 600;
		margin-left: 15px;
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
</style>

<div class="awl-hm-wrapper animate-in">
	<div class="awl-hm-header">
		<h1><?php esc_html_e('API Settings', 'wp-flickr-gallery'); ?></h1>
		<p><?php esc_html_e('Configure your Flickr API credentials to connect your galleries.', 'wp-flickr-gallery'); ?>
		</p>
	</div>

	<div class="awl-hm-card animate-in delay-1">
		<h2 class="awl-hm-card-title">
			<div class="awl-hm-icon-box"><i class="dashicons dashicons-admin-network"></i></div>
			<?php esc_html_e('Credentials', 'wp-flickr-gallery'); ?>
		</h2>

		<p style="margin-bottom: 25px; color: var(--hm-text-light); line-height: 1.6;">
			Note: As per Flickr’s guidelines, API key creation is currently disabled for free accounts. API key creation
			is only available to all
			<a href="https://www.flickr.com/services/apps/create/apply/" target="_new"
				style="color: #8b5cf6; font-weight: 600;">
				<?php esc_html_e('Flickr', 'wp-flickr-gallery'); ?>
			</a> PRO subscribers.
		</p>

		<form id="flickr-setting-form">
			<?php
			if (isset($flickr_api_settings['flickr_user_id'])) {
				$flickr_user_id = $flickr_api_settings['flickr_user_id'];
			} else {
				$flickr_user_id = '148536781@N05';
			}

			if (isset($flickr_api_settings['flickr_api_key'])) {
				$flickr_api_key = $flickr_api_settings['flickr_api_key'];
			} else {
				$flickr_api_key = '18abaa5173d6110a5389e3aba05f94e7';
			}
			?>

			<div class="awl-hm-group">
				<label for="flickr_user_id"
					class="awl-hm-label"><?php esc_html_e('Flickr User ID', 'wp-flickr-gallery'); ?></label>
				<input type="text" name="flickr_user_id" id="flickr_user_id" class="awl-hm-input"
					value="<?php echo esc_attr($flickr_user_id); ?>" placeholder="e.g. 148536781@N05">
				<span class="awl-hm-helper">
					<a href="https://awplife.com/how-to-get-your-user-id-of-flickr/" target="_new">
						<?php esc_html_e('How To Get Your User ID?', 'wp-flickr-gallery'); ?> <i
							class="fa fa-external-link" aria-hidden="true"></i>
					</a>
				</span>
			</div>

			<div class="awl-hm-group">
				<label for="flickr_api_key"
					class="awl-hm-label"><?php esc_html_e('Flickr API Key', 'wp-flickr-gallery'); ?></label>
				<input type="text" name="flickr_api_key" id="flickr_api_key" class="awl-hm-input"
					value="<?php echo esc_attr($flickr_api_key); ?>"
					placeholder="e.g. 18abaa5173d6110a5389e3aba05f94e7">
				<span class="awl-hm-helper">
					<a href="https://awplife.com/how-to-get-your-api-key-of-flickr/" target="_new">
						<?php esc_html_e('How To Get Your API Key?', 'wp-flickr-gallery'); ?> <i
							class="fa fa-external-link" aria-hidden="true"></i>
					</a>
				</span>
			</div>

			<div style="margin-top: 40px; display: flex; align-items: center;">
				<button type="button" id="save_flickr_api_setting" class="awl-hm-btn"
					onclick="FlickrAPISaveSettings();">
					<i class="fa fa-save"></i> <?php esc_html_e('Save Settings', 'wp-flickr-gallery'); ?>
				</button>

				<div id="fg_setting_load" name="fg_setting_load" style="display:none;">
					<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>
					<span style="margin-left: 10px;">Saving...</span>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	function FlickrAPISaveSettings() {
		jQuery("#fg_setting_load").css('display', 'inline-flex');
		jQuery("#save_flickr_api_setting").prop('disabled', true).css('opacity', '0.7');

		jQuery.ajax({
			dataType: 'html',
			type: 'POST',
			url: ajaxurl,
			cache: false,
			data: jQuery('#flickr-setting-form').serialize() + '&action=api_settings_action' + '&fg_api_security=' + '<?php echo esc_js($fg_ajax_nonce); ?>',
			complete: function () { },
			success: function (data) {
				jQuery("#fg_setting_load").hide();
				jQuery("#save_flickr_api_setting").prop('disabled', false).css('opacity', '1');

				// Optional: Show a success message toast or alert
				// alert('Settings Saved Successfully!'); 
			}
		});
	}
</script>