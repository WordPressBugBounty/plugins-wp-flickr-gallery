<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * video Gallery Premium Shortcode
 */
add_shortcode( 'FGAL', 'awl_flickr_gallery_shortcode' );
function awl_flickr_gallery_shortcode( $post_id ) {
	ob_start();

	// css
	wp_enqueue_style( 'awl-fg-bootstrap-css' );
	wp_enqueue_style( 'awl-fg-lightcase-css' );

	// js
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'awl-fg-isotope-js' );
	wp_enqueue_script( 'awl-fg-imagesloaded-js' );
	wp_enqueue_script( 'awl-fg-lightcase-js' );

	$flickr_gallery_id = $post_id['id'];

	// get Flickr API Settings
	$flickr_api_settings = get_option( 'flickr_api_settings' );
	// print_r($flickr_api_settings);
	$flickr_api_key = $flickr_api_settings['flickr_api_key'];
	$flickr_user_id = $flickr_api_settings['flickr_user_id'];

	// load post settings
	$flickr_gallery_settings = get_post_meta( $flickr_gallery_id, 'awl_fg_post_settings_' . $flickr_gallery_id, true );
	if ( isset( $flickr_gallery_settings['flickr_gallery_type'] ) ) {
		$flickr_gallery_type = $flickr_gallery_settings['flickr_gallery_type'];
	} else {
		$flickr_gallery_type = 'photostream';
	}

	// print_r($flickr_gallery_settings);

	// photostream settings
	if ( $flickr_gallery_type == 'photostream' ) {
		$flickr_photostrem_method = 'flickr.people.getPublicPhotos';
	}

	// album settings
	if ( $flickr_gallery_type == 'album' ) {
		$flickr_album_method = 'flickr.photosets.getPhotos';
		if ( isset( $flickr_gallery_settings['flickr_album_id'] ) ) {
			$flickr_album_id = $flickr_gallery_settings['flickr_album_id'];
		} else {
			$flickr_album_id = '';
		}
	}

	// common settings
	if ( isset( $flickr_gallery_settings['col_desktops'] ) ) {
		$col_desktops = $flickr_gallery_settings['col_desktops'];
	} else {
		$col_desktops = 'col-md-4';
	}
	if ( isset( $flickr_gallery_settings['thumb_img_size'] ) ) {
		$thumb_img_size = $flickr_gallery_settings['thumb_img_size'];
	} else {
		$thumb_img_size = 'url_q';
	}
	if ( isset( $flickr_gallery_settings['lightbox_img_size'] ) ) {
		$lightbox_img_size = $flickr_gallery_settings['lightbox_img_size'];
	} else {
		$lightbox_img_size = 'url_m';
	}
	if ( isset( $flickr_gallery_settings['photo_titlestyle'] ) ) {
		$photo_titlestyle = $flickr_gallery_settings['photo_titlestyle'];
	} else {
		$photo_titlestyle = 1;
	}

	// light box settings
	if ( isset( $flickr_gallery_settings['apply_light_box'] ) ) {
		$apply_light_box = $flickr_gallery_settings['apply_light_box'];
	} else {
		$apply_light_box = 'true';
	}

	// gallery post title settings
	if ( isset( $flickr_gallery_settings['post_title'] ) ) {
		$fg_title = $flickr_gallery_settings['post_title'];
	} else {
		$fg_title = '';
	}
	if ( isset( $flickr_gallery_settings['fg_gallery_title'] ) ) {
		$fg_gallery_title = $flickr_gallery_settings['fg_gallery_title'];
	} else {
		$fg_gallery_title = 'true';
	}
	if ( isset( $flickr_gallery_settings['fg_gallery_titlecolor'] ) ) {
		$fg_gallery_titlecolor = $flickr_gallery_settings['fg_gallery_titlecolor'];
	} else {
		$fg_gallery_titlecolor = '#000000';
	}
	if ( isset( $flickr_gallery_settings['fg_gallery_titlesize'] ) ) {
		$fg_gallery_titlesize = $flickr_gallery_settings['fg_gallery_titlesize'];
	} else {
		$fg_gallery_titlesize = 16;
	}
	if ( isset( $flickr_gallery_settings['fg_gallery_titlealighment'] ) ) {
		$fg_gallery_titlealighment = $flickr_gallery_settings['fg_gallery_titlealighment'];
	} else {
		$fg_gallery_titlealighment = 'text-left';
	}
	?>
	<style>
		.entry-content a, .entry-summary a, .taxonomy-description a, 
		.logged-in-as a, .comment-content a, .pingback .comment-body > a, 
		.textwidget a, .entry-footer a:hover, .site-info a:hover {
			box-shadow: none !important;
		}
	
		.single-photostream-<?php echo esc_attr( $flickr_gallery_id ); ?> {
			padding-bottom: 15px;
		}
	</style>
	<?php

	// gallery tile
	if ( $fg_gallery_title == 'true' ) {
		?>
		<p class="awp-flickr-post-title" style="margin-right: 15px; margin-left: 10px; color:<?php echo esc_attr( $fg_gallery_titlecolor ); ?>; font-size: <?php echo esc_attr( $fg_gallery_titlesize ); ?>px ; text-align:<?php echo esc_attr( $fg_gallery_titlealighment ); ?>;"><?php echo esc_html( $fg_title ); ?></p>
		<?php
	}

	// photostream
	if ( $flickr_gallery_type == 'photostream' ) {
		require 'flickr-get-photostream.php';
	}

	// album
	if ( $flickr_gallery_type == 'album' ) {
		require 'flickr-get-album.php';
	}
	return ob_get_clean();
}
?>
