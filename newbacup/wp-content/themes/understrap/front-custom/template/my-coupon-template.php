<?php
defined( 'ABSPATH' ) || exit;

$max_num = $coupons->total_count;
$max_num_pages = $coupons->total_page;

$variant_coupon = [
	['variant_class' => '', 'variant_img' => get_template_directory_uri() . '/images/rewards/fruit1.png'],
	['variant_class' => 'coupon2', 'variant_img' => get_template_directory_uri() . '/images/rewards/fruit2.png'],
	['variant_class' => 'coupon3', 'variant_img' => get_template_directory_uri() . '/images/rewards/fruit3.png'],
];
?>
	<h4 class="frmyCoupon__section--header-title">Coupon</h4>
	<div class="frmyCoupon__section--list-coupons">
		<?php foreach ($coupons->results as $coupon_key => $coupon) {
			$variant_key = $coupon_key % 3;
			$expired_date = new DateTime($coupon->expired_date);

			$is_expired_date = (new DateTime()) > $expired_date;
		?>
		<div class="frmyCoupon__section--coupon-item <?php echo $is_expired_date ? 'expired_date' : '' ?> <?php echo $variant_coupon[$variant_key]['variant_class']; ?>">
			<p class="frmyCoupon__section--coupon-name"><?php esc_html_e($coupon->coupon_name) ?></p>
            <div class="frmyCoupon__section--coupon-ticket">
            	<div class="frmyCoupon__section--coupon-ticketsub">
                    <div class="frmyCoupon__section--coupon-icon">
                        <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0)">
                            <path d="M14.1716 9.92893L15.5858 11.3431" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.4142 14.1716L19.8284 15.5858" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22.6569 18.4142L24.0711 19.8284" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.1005 17L17 7.10051C17.3751 6.72543 17.8838 6.51472 18.4142 6.51472C18.9446 6.51472 19.4533 6.72543 19.8284 7.10051L21.9497 9.22183C21.5747 9.5969 21.364 10.1056 21.364 10.636C21.364 11.1665 21.5747 11.6752 21.9497 12.0503C22.3248 12.4253 22.8335 12.636 23.364 12.636C23.8944 12.636 24.4031 12.4253 24.7782 12.0503L26.8995 14.1716C27.2746 14.5466 27.4853 15.0554 27.4853 15.5858C27.4853 16.1162 27.2746 16.6249 26.8995 17L17 26.8995C16.6249 27.2746 16.1162 27.4853 15.5858 27.4853C15.0553 27.4853 14.5466 27.2746 14.1716 26.8995L12.0502 24.7782C12.4253 24.4031 12.636 23.8944 12.636 23.364C12.636 22.8335 12.4253 22.3248 12.0502 21.9497C11.6752 21.5747 11.1665 21.364 10.636 21.364C10.1056 21.364 9.59689 21.5747 9.22182 21.9497L7.1005 19.8284C6.72543 19.4534 6.51471 18.9446 6.51471 18.4142C6.51471 17.8838 6.72543 17.3751 7.1005 17" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                            <defs>
                            <clipPath id="clip0">
                            <rect width="24" height="24" fill="white" transform="translate(0.0294342 17) rotate(-45)"/>
                            </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div class="frmyCoupon__section--coupon-text"><?php esc_html_e($coupon->coupon_code); ?></div>
                </div>
				<p class="frmyCoupon__section--coupon-item-expired-date">Valid until <?php echo date('Y-m-d', strtotime($coupon->expired_date)); ?></p>
            </div>
			<div class="frmyCoupon__section--layer-decorate">
				<img src="<?php echo $variant_coupon[$variant_key]['variant_img']; ?>" alt="">
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="frmyCoupon__section--pagination">
	    <?php if ( 1 < $max_num_pages) : ?>
	        <?php
	        $args = array(
	            'base'          => 'page%_%',
	            'format'        => '%#%',
	            'total'         => $max_num_pages,
	            'current'       => $current_page,
	            'show_all'      => false,
	            'end_size'      => 3,
	            'mid_size'      => 3,
	            'prev_next'     => true,
	            'prev_text'     => 'Pre',
	            'next_text'     => 'Next',
	            'type'          => 'list',
	            'add_args'      => false,
	            'add_fragment'  => ''
	        );
	        echo str_replace( "<ul class='page-numbers'>", '<ul class="front-custom-pagination">', paginate_links( $args ));
	        ?>
	    <?php endif; ?>
	    </div>
	<div class="frmyCoupon__section--border"></div>
<?php
