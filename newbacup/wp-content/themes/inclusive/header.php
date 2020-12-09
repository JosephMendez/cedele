<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Inclusive
 * @since 1.0.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta name="cedele-hash" value="bde463508a8105385969143aba5ff48958d4f093" />
	<meta name="cedele-version" value="1.11.28" />
	<meta name="cedele-build" value="Wed Dec  9 02:16:36 UTC 2020" />
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'inclusive' ); ?></a>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<?php
	Inclusive\Styles::get_template_part( 'header', 'navigation', 'assets/css/min/primary-menu.min.css' );
	get_template_part( 'template-parts/header/custom-header', get_post_type() );
