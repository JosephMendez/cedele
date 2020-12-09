<?php

if ( ! function_exists( 'metrostore_footer_before' ) ) {
	function metrostore_footer_before(){ ?>
		<footer id="colophon" class="site-footer" role="contentinfo">
	<?php
	}
}
add_action( 'metrostore_footer_before', 'metrostore_footer_before', 5 );


if ( ! function_exists( 'metrostore_footer_widget_area' ) ) {
	function metrostore_footer_widget_area(){ ?>
		<div class="container">
	      <div class="row">
			
			<?php if ( is_active_sidebar('footer_one') ) { ?>
				<div class="col-sm-12 col-xs-12 col-lg-4">
				  	<?php dynamic_sidebar('footer_one'); ?>
				</div>
			<?php } ?>

			<?php if ( is_active_sidebar('footer_two') ) { ?>
				<div class="col-sm-6 col-md-3 col-xs-12 col-lg-2 collapsed-block">
				  	<?php dynamic_sidebar('footer_two'); ?>
				</div>
			<?php } ?>

			<?php if ( is_active_sidebar('footer_three') ) { ?>
				<div class="col-sm-6 col-md-3 col-xs-12 col-lg-2 collapsed-block">
				  	<?php dynamic_sidebar('footer_three'); ?>
				</div>
			<?php } ?>

			<?php if ( is_active_sidebar('footer_four') ) { ?>
				<div class="col-sm-6 col-md-3 col-xs-12 col-lg-2 collapsed-block">
				  	<?php dynamic_sidebar('footer_four'); ?>
				</div>
			<?php } ?>

			<?php if ( is_active_sidebar('footer_five') ) { ?>
				<div class="col-sm-6 col-md-3 col-xs-12 col-lg-2 collapsed-block">
				  	<?php dynamic_sidebar('footer_five'); ?>
				</div>
			<?php } ?>

	      </div>
	    </div>
	<?php
	}
}
add_action( 'metrostore_footer_widget', 'metrostore_footer_widget_area', 10 );


if ( ! function_exists( 'metrostore_footer_after' ) ) {
	function metrostore_footer_after(){ ?>
		</footer>
	<?php
	}
}
add_action( 'metrostore_footer_after', 'metrostore_footer_after', 15 );


/**
 * Sub Footer Section
**/

if ( ! function_exists( 'metrostore_sub_footer_before' ) ) {
	function metrostore_sub_footer_before(){ ?>
		<div class="footer-coppyright">
	      <div class="container">
	        <div class="row">
	<?php
	}
}
add_action( 'metrostore_sub_footer_before', 'metrostore_sub_footer_before', 5 );

/**
 * Footer Copyright Information
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'metrostore_footer_credit' ) ){

    function metrostore_footer_credit() {

    	echo '<div class="col-sm-6 col-xs-12 coppyright">';

	        $copyright = get_theme_mod( 'metrostore_footer_copyright_setting' ); 

	        if( !empty( $copyright ) ) { 

	            echo apply_filters( 'metrostore_copyright_text', $copyright .' - ' ); 

	        } else { 

	            echo esc_html( apply_filters( 'metrostore_copyright_text', $content = esc_html__('Copyright  &copy; ','metrostore') . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) .' - ' ) );
	        }

	        printf( ' WordPress Theme : By %1$s', '<a href=" ' . esc_url('https://sparklewpthemes.com/') . ' " rel="designer" target="_blank">'.esc_html__('Sparkle Themes','metrostore').'</a>' );

	    echo '</div>';
    }
}
add_action( 'metrostore_sub_footer', 'metrostore_footer_credit', 5 );




if ( ! function_exists( 'metrostore_social_media' ) ) {
	function metrostore_social_media() { ?>
			<div class="social col-sm-6 col-xs-12">
				<ul class="inline-mode">
					
					<?php if ( esc_url( get_theme_mod('metrostore_social_facebook') ) ) { ?>
                    <li class="social-network fb"> <a title="Connect us on Facebook" href="<?php echo esc_url(get_theme_mod('metrostore_social_facebook'));?>" <?php if ( esc_attr( get_theme_mod('metrostore_social_facebook_checkbox', 0 ) ) == 1 ) {  echo "target=_blank"; } ?>><i class="fa fa-facebook"></i></a> </li>
	                <?php } ?>

	                <?php if ( esc_url( get_theme_mod('metrostore_social_twitter') ) ) { ?>
	                    <li class="social-network tw"> <a title="Connect us on Twitter" href="<?php echo esc_url( get_theme_mod('metrostore_social_twitter') ); ?>" <?php if (esc_attr( get_theme_mod('metrostore_social_twitter_checkbox', 0 ) ) == 1 ){ echo "target=_blank"; } ?>><i class="fa fa-twitter"></i></a> </li>
	                <?php } ?>

	                <?php if ( esc_url( get_theme_mod('metrostore_social_googleplus') ) ) { ?>
	                    <li class="social-network googleplus"> <a href="<?php echo esc_url( get_theme_mod('metrostore_social_googleplus') ); ?>" <?php if (esc_attr(get_theme_mod('metrostore_social_googleplus_checkbox', 0 ) ) == 1) { echo "target=_blank"; } ?>><i class="fa fa-google-plus"></i></a> </li>
	                <?php } ?>

	                <?php if ( esc_url( get_theme_mod('metrostore_social_youtube') ) ) { ?>
	                    <li class="social-network youtube"> <a href="<?php echo esc_url( get_theme_mod('metrostore_social_youtube') ); ?>" <?php if (esc_attr(get_theme_mod('metrostore_social_youtube_checkbox', 0 ) ) == 1) { echo "target=_blank"; } ?>><i class="fa fa-youtube"></i></a> </li>
	                <?php } ?>

	                <?php if (esc_url(get_theme_mod('metrostore_social_instagram'))) { ?>
	                    <li class="social-network instagram"> <a title="Connect us on Instagram" href="<?php echo esc_url( get_theme_mod('metrostore_social_instagram') ) ; ?>" <?php if ( esc_attr( get_theme_mod('metrostore_social_instagram_checkbox', 0 ) ) == 1) { echo "target=_blank"; } ?>><i class="fa fa-instagram"></i></a> </li>
	                <?php } ?>

	                <?php if ( esc_url( get_theme_mod('metrostore_social_linkedin') ) ) { ?>
	                    <li> <a href="<?php echo esc_url(get_theme_mod('metrostore_social_linkedin')); ?>" <?php if ( esc_attr( get_theme_mod('metrostore_social_linkedin_checkbox', 0 ) ) == 1 ){ echo "target=_blank"; } ?>><i class="fa fa-linkedin"></i></a> </li>
	                <?php } ?>

	                <?php if ( esc_url( get_theme_mod('metrostore_social_pinterest') ) ) { ?>
	                    <li> <a href="<?php echo esc_url(get_theme_mod('metrostore_social_pinterest')); ?>" <?php if ( esc_attr( get_theme_mod('metrostore_social_pinterest_checkbox', 0 ) ) == 1 ){ echo "target=_blank"; } ?>><i class="fa fa-pinterest"></i></a> </li>
	                <?php } ?>
					
				</ul>
			</div>
		<?php
	}
}
add_action( 'metrostore_sub_footer', 'metrostore_social_media', 15 );


if ( ! function_exists( 'metrostore_sub_footer_after' ) ) {
	function metrostore_sub_footer_after(){ ?>
			</div>
	      </div>
	    </div>
	<?php
	}
}
add_action( 'metrostore_sub_footer_after', 'metrostore_sub_footer_after', 20 );