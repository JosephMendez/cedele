<?php

namespace A3Rev\WCDynamicGallery;

class Shortcodes 
{
	public static function parse_shortcode_product_dynamic_gallery( $attributes ) {
		// Don't show content for shortcode on Dashboard, still support for admin ajax
		if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) return;

		if ( ! is_array( $attributes ) ) {
			$attributes = array();
		}

		extract( array_merge(array(
			'product_id'  => '',
        ), $attributes ) );

        if ( empty( $product_id ) && is_product() ) {
        	global $post;

        	$product_id = $post->ID;
        }

        if ( empty( $product_id ) ) {
        	return '';
        }

        $gallery_html = '';

		$global_wc_dgallery_activate = get_option( WOO_DYNAMIC_GALLERY_PREFIX . 'activate' );
		$actived_d_gallery           = get_post_meta( $product_id, '_actived_d_gallery',true );

		if ( '' == $actived_d_gallery && 'no' != $global_wc_dgallery_activate ) {
			$actived_d_gallery = 1;
		}

		if ( 1 == $actived_d_gallery ) {
			// Include google fonts into header
			add_action( 'wp_enqueue_scripts', array( '\A3Rev\WCDynamicGallery\Functions', 'add_google_fonts'), 9 );

			wp_enqueue_style( 'a3-dgallery-style' );
			wp_enqueue_script( 'a3-dgallery-script' );

			$popup_gallery = get_option( WOO_DYNAMIC_GALLERY_PREFIX.'popup_gallery' );
			if ($popup_gallery == 'fb') {
				wp_enqueue_style( 'woocommerce_fancybox_styles' );
				wp_enqueue_script( 'fancybox' );
			} elseif ($popup_gallery == 'colorbox') {
				wp_enqueue_style( 'a3_colorbox_style' );
				wp_enqueue_script( 'colorbox_script' );
			}

			ob_start();
			Main::wc_dynamic_gallery_display( $product_id );

			$gallery_html = ob_get_clean();
		}

        return $gallery_html;
	}
}
