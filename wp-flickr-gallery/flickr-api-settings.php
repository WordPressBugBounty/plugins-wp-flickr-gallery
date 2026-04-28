<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Load API Settings
$flickr_api_settings = get_option('flickr_api_settings');
?>

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
			<?php esc_html_e('Note: As per Flickr’s guidelines, API key creation is currently disabled for free accounts. API key creation is only available to all', 'wp-flickr-gallery'); ?>
			<a href="https://www.flickr.com/services/apps/create/apply/" target="_new"
				style="color: #8b5cf6; font-weight: 600;">
				<?php esc_html_e('Flickr', 'wp-flickr-gallery'); ?>
			</a>
			<?php esc_html_e('PRO subscribers.', 'wp-flickr-gallery'); ?>
		</p>

		<form id="flickr-setting-form">
			<?php
			$flickr_user_id = (isset($flickr_api_settings['flickr_user_id'])) ? $flickr_api_settings['flickr_user_id'] : '';
			$flickr_api_key = (isset($flickr_api_settings['flickr_api_key'])) ? $flickr_api_settings['flickr_api_key'] : '';
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
					<span style="margin-left: 10px;"><?php esc_html_e('Saving...', 'wp-flickr-gallery'); ?></span>
				</div>
			</div>
		</form>
	</div>
</div>