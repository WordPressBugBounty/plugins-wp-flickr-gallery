<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
@package WP Flickr Gallery
Plugin Name: Album Photostream Profile For Flickr
Plugin URI: https://awplife.com/
Description: A Newly Amazing Different Most Powerful Responsive Easy To Use Flickr Plugin For WordPress
Version: 1.6.0
Author: A WP Life
Author URI: https://awplife.com/
Text Domain: wp-flickr-gallery
Domain Path: /languages
*/

if ( ! class_exists( 'Awl_Flickr_Gallery' ) ) {

	class Awl_Flickr_Gallery {

		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}

		protected function _constants() {

			// Plugin Text Domain
			define( 'AWL_FG_TEXT_DOMAIN', 'wp-flickr-gallery' );

			// Plugin Name
			define( 'AWL_FG_NAME', 'Flickr Gallery' );

			// Plugin Slug
			define( 'AWL_FG_SLUG', 'flickr_gallery' );

			// Plugin Directory Path
			define( 'AWL_FG_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin Driectory URL
			define( 'AWL_FG_URL', plugin_dir_url( __FILE__ ) );

			/**
			 * Create a key for the .htaccess secure download link.
			 *
			 * @uses    NONCE_KEY     Defined in the WP root config.php
			 */
			define( 'AWL_FG_SECURE_KEY', md5( NONCE_KEY ) );

		} // end of constructor function

		/**
		 * Setup the default filters and actions
		 */
		protected function _hooks() {

			// Load Text Domain
			add_action( 'init', array( $this, '_load_textdomain' ) );

			// add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, 'fg_gallery_menu' ), 101 );

			// Create Flicker Gallery Custom Post
			add_action( 'init', array( $this, '_Flickr_Gallery' ) );

			// Add Meta Box To Custom Post
			add_action( 'add_meta_boxes', array( $this, '_fg_admin_add_meta_box' ) );

			add_action( 'save_post', array( &$this, '_fg_save_post_settings' ) );

			// Shortcode Compatibility in Text Widegts
			add_filter( 'widget_text', 'do_shortcode' );

			// add pfg cpt shortcode column - manage_{$post_type}_posts_columns
			add_filter( 'manage_flickr_gallery_posts_columns', array( &$this, 'set_flickr_gallery_shortcode_column_name' ) );

			// add pfg cpt shortcode column data - manage_{$post_type}_posts_custom_column
			add_action( 'manage_flickr_gallery_posts_custom_column', array( &$this, 'custom_flickr_gallery_shodrcode_data' ), 10, 2 );

			add_action( 'wp_ajax_api_settings_action', array( &$this, 'save_fg_api_setting' ) );

			add_action( 'wp_enqueue_scripts', array( &$this, 'flickr_enqueue_scripts_in_header' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'fg_admin_enqueue_scripts' ) );

		} // end of hook function

		public function fg_admin_enqueue_scripts( $hook ) {
			global $post;

			// Common Admin Scripts
			wp_enqueue_script( 'awl-fg-admin-common', AWL_FG_URL . 'js/fg-admin-common.js', array( 'jquery' ), '1.0', true );

			// Settings Page Assets
			if ( 'flickr_gallery_page_fg-api-settings' === $hook ) {
				wp_enqueue_style( 'awl-fg-admin-css', AWL_FG_URL . 'css/fg-admin.css' );
				wp_enqueue_style( 'awl-fg-font-awesome', AWL_FG_URL . 'css/font-awesome.min.css' );
				wp_enqueue_script( 'awl-fg-api-settings-js', AWL_FG_URL . 'js/fg-api-settings.js', array( 'jquery' ), '1.0', true );
				wp_localize_script( 'awl-fg-api-settings-js', 'fg_api_vars', array(
					'nonce' => wp_create_nonce( 'fg_api_setting_nonce_key' ),
				) );
			}

			// Our Plugins & Themes Assets
			if ( 'flickr_gallery_page_awl-fg-our-plugins' === $hook || 'flickr_gallery_page_awl-fg-our-themes' === $hook ) {
				wp_enqueue_style( 'awl-fg-our-plugins-style', AWL_FG_URL . 'css/our-plugins-style.css' );
			}

			// Post Editor Assets
			if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && isset( $post->post_type ) && 'flickr_gallery' === $post->post_type ) {
				wp_enqueue_media();
				wp_enqueue_style( 'awl-fg-admin-css', AWL_FG_URL . 'css/fg-admin.css' );
				wp_enqueue_style( 'awl-fg-setting-bootstrap-css', AWL_FG_URL . 'css/setting-bootstrap.css' );
				wp_enqueue_style( 'awl-fg-toogle-button-css', AWL_FG_URL . 'css/toogle-button.css' );
				wp_enqueue_style( 'awl-fg-styles-css', AWL_FG_URL . 'css/styles.css' );
				wp_enqueue_style( 'awl-fg-font-awesome', AWL_FG_URL . 'css/font-awesome.min.css' );
				wp_enqueue_style( 'awl-fg-lightcase-css', AWL_FG_URL . 'css/lightcase.css' );
				
				wp_enqueue_script( 'awl-fg-bootstrap-js', AWL_FG_URL . 'js/bootstrap.js', array( 'jquery' ), '', true );
				wp_enqueue_script( 'awl-fg-lightcase-js', AWL_FG_URL . 'js/lightcase.js', array( 'jquery' ), '', true );
				wp_enqueue_script( 'awl-fg-post-settings-js', AWL_FG_URL . 'js/fg-post-settings.js', array( 'jquery' ), '1.0', true );
			}
		}

		public function flickr_enqueue_scripts_in_header() {
			wp_enqueue_script( 'jquery' );
			wp_register_script( 'awl-fg-isotope-js', AWL_FG_URL . 'js/isotope.pkgd.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'awl-fg-imagesloaded-js', AWL_FG_URL . 'js/imagesloaded.pkgd.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'awl-fg-lightcase-js', AWL_FG_URL . 'js/lightcase.js', array( 'jquery' ), '1.0', true );
			wp_register_script( 'awl-fg-frontend-js', AWL_FG_URL . 'js/fg-frontend.js', array( 'jquery', 'awl-fg-isotope-js', 'awl-fg-imagesloaded-js' ), '1.0', true );

			wp_register_style( 'awl-fg-bootstrap-css', AWL_FG_URL . 'css/bootstrap.css' );
			wp_register_style( 'awl-fg-lightcase-css', AWL_FG_URL . 'css/lightcase.css' );
			wp_register_style( 'awl-fg-shortcode-css', AWL_FG_URL . 'css/fg-shortcode.css' );
			wp_register_style( 'awl-fg-frontend-css', AWL_FG_URL . 'css/fg-frontend.css' );
		}

		// saving Flickr gallery api setting
		public function save_fg_api_setting() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( check_ajax_referer( 'fg_api_setting_nonce_key', 'fg_api_security' ) ) {

				$flickr_user_id = isset( $_POST['flickr_user_id'] ) ? sanitize_text_field( $_POST['flickr_user_id'] ) : '';
				$flickr_api_key = isset( $_POST['flickr_api_key'] ) ? sanitize_text_field( $_POST['flickr_api_key'] ) : '';

				$flickr_meta_api = array(
					'flickr_user_id' => $flickr_user_id,
					'flickr_api_key' => $flickr_api_key,
				);

				update_option( 'flickr_api_settings', $flickr_meta_api );
			}
		}
		// Flickr gallery cpt shortcode column before date columns
		public function set_flickr_gallery_shortcode_column_name( $defaults ) {
			$new       = array();
			
			foreach ( $defaults as $key => $value ) {
				if ( $key == 'date' ) {  // when we find the date column
					$new['flickr_gallery_shortcode'] = __( 'Shortcode', 'wp-flickr-gallery' );  // put the shortcode column before it
				}
				$new[ $key ] = $value;
			}
			return $new;
		}

		// Flickr gallery cpt shortcode column data
		public function custom_flickr_gallery_shodrcode_data( $column, $post_id ) {
			switch ( $column ) {
				case 'flickr_gallery_shortcode':
					echo "<input type='text' class='button button-primary fg-shortcode-input' id='flickr-shortcode-" . esc_attr( $post_id ) . "' value='[FGAL id=" . esc_attr( $post_id ) . "]' readonly />";
					echo "<input type='button' class='button button-primary' onclick='return FLICKRCopyShortcode(" . esc_attr( $post_id ) . ");' value='Copy' style='margin-left:4px;' />";
					echo "<span id='copy-msg-" . esc_attr( $post_id ) . "' class='button button-primary fg-copied-badge' style='display:none;'>copied</span>";
					break;
			}
		}

		public function _load_textdomain() {
			load_plugin_textdomain( 'wp-flickr-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/* Add Gallery menu*/
		public function fg_gallery_menu() {
			add_submenu_page( 'edit.php?post_type=' . AWL_FG_SLUG, __( 'Flickr API Settings', 'wp-flickr-gallery' ), __( 'Flickr API Settings', 'wp-flickr-gallery' ), 'administrator', 'fg-api-settings', array( $this, '_fg_api_settings' ) );
			add_submenu_page( 'edit.php?post_type=' . AWL_FG_SLUG, __( 'Our Plugins', 'wp-flickr-gallery' ), __( 'Our Plugins', 'wp-flickr-gallery' ), 'administrator', 'awl-fg-our-plugins', array( $this, '_fg_our_plugins' ) );
			add_submenu_page( 'edit.php?post_type=' . AWL_FG_SLUG, __( 'Our Themes', 'wp-flickr-gallery' ), __( 'Our Themes', 'wp-flickr-gallery' ), 'administrator', 'awl-fg-our-themes', array( $this, '_fg_our_themes' ) );
		}

		/**
		 * Flicker Gallery Custom Post
		 * Create gallery post type in admin dashboard.
		 */
		public function _Flickr_Gallery() {
			$labels = array(
				'name'               => _x( 'Flickr Gallery', 'post type general name', 'wp-flickr-gallery' ),
				'singular_name'      => _x( 'Flickr Gallery', 'post type singular name', 'wp-flickr-gallery' ),
				'menu_name'          => __( 'Flickr Gallery', 'wp-flickr-gallery' ),
				'name_admin_bar'     => __( 'Flickr Gallery', 'wp-flickr-gallery' ),
				'parent_item_colon'  => __( 'Parent Item:', 'wp-flickr-gallery' ),
				'all_items'          => __( 'All Flickr Gallery', 'wp-flickr-gallery' ),
				'add_new_item'       => __( 'Add Flickr Gallery', 'wp-flickr-gallery' ),
				'add_new'            => __( 'Add Flickr Gallery', 'wp-flickr-gallery' ),
				'new_item'           => __( 'Flickr Gallery', 'wp-flickr-gallery' ),
				'edit_item'          => __( 'Edit Flickr Gallery', 'wp-flickr-gallery' ),
				'update_item'        => __( 'Update Flickr Gallery', 'wp-flickr-gallery' ),
				'search_items'       => __( 'Search Flickr Gallery', 'wp-flickr-gallery' ),
				'not_found'          => __( 'Flickr Gallery Not found', 'wp-flickr-gallery' ),
				'not_found_in_trash' => __( 'Flickr Gallery Not found in Trash', 'wp-flickr-gallery' ),
			);

			$args = array(
				'label'               => __( 'Flickr Gallery', 'wp-flickr-gallery' ),
				'description'         => __( 'Custom Post Type For Flickr Gallery', 'wp-flickr-gallery' ),
				'labels'              => $labels,
				'supports'            => array( 'title' ),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-images-alt',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);

			register_post_type( 'flickr_gallery', $args );
		}//end _Flickr_Gallery()

		/**
		 * Adds Meta Boxes
		 */
		public function _fg_admin_add_meta_box() {
			// Syntax: add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
			add_meta_box( __( 'Flickr Gallery Shortcode', 'wp-flickr-gallery' ), __( 'Flickr Gallery Shortcode', 'wp-flickr-gallery' ), array( &$this, '_fg_shortcode_box' ), 'flickr_gallery', 'side', 'default' );
			add_meta_box( __( 'Flickr Settings', 'wp-flickr-gallery' ), __( 'Flickr Settings', 'wp-flickr-gallery' ), array( &$this, '_fg_post_settings' ), 'flickr_gallery', 'normal', 'default' );
			add_meta_box( __( 'Upgrade Flickr Gallery Pro', 'wp-flickr-gallery' ), __( 'Upgrade Flickr Gallery Pro', 'wp-flickr-gallery' ), array( &$this, 'fg_upgrade_pro' ), 'flickr_gallery', 'side', 'default' );
			add_meta_box( __( 'Rate Our Plugin', 'wp-flickr-gallery' ), __( 'Rate Our Plugin', 'wp-flickr-gallery' ), array( &$this, 'fg_rate_plugin' ), 'flickr_gallery', 'side', 'default' );
		}
		// meta upgrade pro
		public function fg_upgrade_pro() { ?>
			<img src="<?php echo esc_url( AWL_FG_URL . 'img/featured-Image.png' ); ?>" width="250" height="280">
			<a href="https://awplife.com/demo/flickr-gallery-premium/" target="_new" class="button button-primary button-large fg-meta-btn"><?php esc_html_e( 'Live Demo', 'wp-flickr-gallery' ); ?></a>
			<a href="https://awplife.com/account/signup/flickr-gallery-premium/" target="_new" class="button button-primary button-large fg-meta-btn"><?php esc_html_e( 'Upgrade Pro', 'wp-flickr-gallery' ); ?></a>
			<?php
		}
		// meta rate us
		public function fg_rate_plugin() {
			?>
		<div style="text-align:center">
			<p><?php esc_html_e( 'If you like our plugin then please', 'wp-flickr-gallery' ); ?> <b><?php esc_html_e( 'Rate us', 'wp-flickr-gallery' ); ?></b> <?php esc_html_e( 'on WordPress', 'wp-flickr-gallery' ); ?></p>
		</div>
		<div style="text-align:center">
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
		</div>
		<br>
		<div style="text-align:center">
			<a href="https://wordpress.org/support/plugin/wp-flickr-gallery/reviews/?filter=5" target="_new" class="button button-primary button-large fg-meta-btn"><?php esc_html_e( 'Please Rate Us', 'wp-flickr-gallery' ); ?></a>
		</div>	
			<?php
		}

		public function _fg_shortcode_box( $post ) {
			?>
			<p class="input-text-wrap">
				<p><?php esc_html_e( 'Copy & Embed shotcode into any Page/ Post / Text Widget to display your flickr gallery on your site.', 'wp-flickr-gallery' ); ?><br></p>
				<input type="text" name="FGALCopyShortcode" id="FGALCopyShortcode" value="<?php echo '[FGAL id=' . esc_attr( $post->ID ) . ']'; ?>" readonly style="height: 60px; text-align: center; width:100%;  font-size: 24px; border: 2px dashed;">
			<p id="fgal-copy-code" style="display:none;"><?php esc_html_e('Shortcode copied to clipboard!', 'wp-flickr-gallery' ); ?></p>
				<p style="margin-top: 10px"><?php esc_html_e('Copy & Embed shotcode into any Page/ Post / Text Widget to display gallery.', 'wp-flickr-gallery'); ?></p>
			</p>
			<span onclick="copyToClipboard('#FGALCopyShortcode')" class="igm-copy dashicons dashicons-clipboard"></span>
			<?php
		}
		// displaying post settings
		public function _fg_post_settings( $post ) {
			require_once 'flickr-post-settings.php';
		}

		public function _fg_save_post_settings( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( isset( $_POST['fg_save_nonce'] ) && wp_verify_nonce( $_POST['fg_save_nonce'], 'fg_save_settings' ) ) {

				$flickr_gallery_type       = isset( $_POST['flickr_gallery_type'] ) ? sanitize_text_field( $_POST['flickr_gallery_type'] ) : 'photostream';
				$flickr_album_id           = isset( $_POST['flickr_album_id'] ) ? sanitize_text_field( $_POST['flickr_album_id'] ) : '';
				$fg_gallery_title          = isset( $_POST['fg_gallery_title'] ) ? sanitize_text_field( $_POST['fg_gallery_title'] ) : '';
				$fg_gallery_titlecolor     = isset( $_POST['fg_gallery_titlecolor'] ) ? sanitize_hex_color( $_POST['fg_gallery_titlecolor'] ) : '#000000';
				$fg_gallery_titlesize      = isset( $_POST['fg_gallery_titlesize'] ) ? sanitize_text_field( $_POST['fg_gallery_titlesize'] ) : '20';
				$fg_gallery_titlealighment = isset( $_POST['fg_gallery_titlealighment'] ) ? sanitize_text_field( $_POST['fg_gallery_titlealighment'] ) : 'center';
				$col_desktops              = isset( $_POST['col_desktops'] ) ? sanitize_text_field( $_POST['col_desktops'] ) : 'col-md-3';
				$thumb_img_size            = isset( $_POST['thumb_img_size'] ) ? sanitize_text_field( $_POST['thumb_img_size'] ) : 'q';
				$lightbox_img_size         = isset( $_POST['lightbox_img_size'] ) ? sanitize_text_field( $_POST['lightbox_img_size'] ) : 'b';
				$apply_light_box           = isset( $_POST['apply_light_box'] ) ? sanitize_text_field( $_POST['apply_light_box'] ) : 'yes';

				$flickermeta_settings = array(
					'flickr_gallery_type'       => $flickr_gallery_type,
					'flickr_album_id'           => $flickr_album_id,
					'fg_gallery_title'          => $fg_gallery_title,
					'fg_gallery_titlecolor'     => $fg_gallery_titlecolor,
					'fg_gallery_titlesize'      => $fg_gallery_titlesize,
					'fg_gallery_titlealighment' => $fg_gallery_titlealighment,
					'col_desktops'              => $col_desktops,
					'thumb_img_size'            => $thumb_img_size,
					'lightbox_img_size'         => $lightbox_img_size,
					'apply_light_box'           => $apply_light_box,

				);

				$awl_fg_post_settings = 'awl_fg_post_settings_' . $post_id;
				update_post_meta( $post_id, $awl_fg_post_settings, $flickermeta_settings );
			}
		}//end _fg_save_post_settings()

		// displaying flickr api settings page
		public function _fg_api_settings() {
			require_once 'flickr-api-settings.php';
		}

		// displaying our plugins page
		public function _fg_our_plugins() {
			require_once 'our-plugins.php';
		}

		// displaying our themes page
		public function _fg_our_themes() {
			require_once 'our-themes.php';
		}
	}//end class

	$awl_fg_gallery = new Awl_Flickr_Gallery();
	require_once 'shortcode.php';
}

