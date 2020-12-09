<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

update_option( WOO_DYNAMIC_GALLERY_PREFIX . 'thumb_show_type', 'slider' );
update_option( WOO_DYNAMIC_GALLERY_PREFIX . 'thumb_columns', 3 );
update_option( WOO_DYNAMIC_GALLERY_PREFIX . 'thumb_border_color', 'transparent' );
update_option( WOO_DYNAMIC_GALLERY_PREFIX . 'thumb_current_border_color', 'transparent' );

// Build sass
$GLOBALS[WOO_DYNAMIC_GALLERY_PREFIX.'less']->plugin_build_sass();