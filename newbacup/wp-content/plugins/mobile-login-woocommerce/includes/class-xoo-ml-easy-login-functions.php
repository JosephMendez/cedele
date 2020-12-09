<?php

class Xoo_Ml_Easy_Login_Functions{
	protected static $_instance = null;
	public static $hasPhoneReg, $hasPhoneLogin;
	public $settings, $easyLoginSettings;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){

		if( !defined( 'XOO_EL_VERSION' ) ){
			return;
		}

		$this->settings = get_option( 'xoo-ml-phone-options', true );
		$this->easyLoginSettings = get_option( 'xoo-el-general-options', true );

		$phone_field = xoo_el()->aff->fields->get_field_data( 'xoo-ml-reg-phone' );
		self::$hasPhoneReg = $phone_field['settings']['active'] === "yes"  ? true : false;
		self::$hasPhoneLogin = $this->settings['l-enable-login-with-otp'] === "yes" ? true : false;
		
		if( self::$hasPhoneReg ){
			$this->registration_hooks();
		}

		if( self::$hasPhoneLogin ){
			$this->login_hooks();
		}
	}


	public function registration_hooks(){

		add_filter( 'xoo_el_myaccount_fields', array( $this, 'remove_phone_field' ) );

		add_filter( 'xoo_aff_easy-login-woocommerce_input_html', array( $this, 'popup_phone_input_addition' ), 10, 2 );

		//Add form to validation forms
		add_filter( 'xoo_ml_user_register_phone_forms', function( $register_forms ){
			$register_forms['_xoo_el_form'] = 'register';
			return $register_forms;
		} );
		add_filter( 'xoo_aff_easy-login-woocommerce_field_args', array( $this, 'setting_phone_field_in_login_popup' ) );
	}

	public function login_hooks(){
		add_action( 'xoo_el_login_form_end', array( $this, 'easy_login_login_with_otp_form' ), 5 );
	}


	public function setting_phone_field_in_login_popup( $args ){
		if( $args['unique_id'] === 'xoo-ml-reg-phone-cc' ){

			$phone_settings = get_option( 'xoo-ml-phone-options', true );

			if( $phone_settings['r-default-country-code-type'] === 'geolocation' ){
				$default_cc = Xoo_Ml_Geolocation::get_phone_code();
			}else{
				$default_cc = $phone_settings['r-default-country-code'];
			}

			$args['value'] = $default_cc;

			if( $phone_settings['r-show-country-code-as'] === 'input' ){
				$args['input_type'] = 'text';
			}

			$args['class'][] = 'xoo-ml-phone-cc';
		}

		if( $args['unique_id'] === 'xoo-ml-reg-phone' ){
			$args['class'][] = 'xoo-ml-phone-input';
		}
		return $args;
	}


	public function easy_login_login_with_otp_form(){
		$args = array(
			'is_login_popup' => true,
			'button_class' => array(
				'button', 'btn', 'xoo-el-action-btn'
			),
			'label' => ''
		);

		$args = apply_filters( 'xoo_ml_easy_login_otp_login_btn', $args );

		return xoo_ml_get_login_with_otp_form( $args );

	}


	public function remove_phone_field( $fields ){
		if( isset( $fields['xoo-ml-reg-phone'] ) ){
			unset( $fields['xoo-ml-reg-phone'] );
		}

		if( isset( $fields['xoo-ml-reg-phone-cc'] ) ){
			unset( $fields['xoo-ml-reg-phone-cc'] );
		}

		return $fields;
	}


	//Login/Signup popup input phone addition
	public function popup_phone_input_addition( $field_html, $args ){
		if( !isset( $args['unique_id'] ) || $args['unique_id'] !== 'xoo-ml-reg-phone' ) return $field_html;
		ob_start();
		?>
		<span class="xoo-ml-reg-phone-change"><?php _e( 'Change?', 'mobile-login-woocommerce' ); ?></span>
		<input type="hidden" name="xoo-ml-form-token" value="<?php echo mt_rand( 1000, 9999 ); ?>"/>
		<?php
		$field_html .= ob_get_clean();
		return $field_html;
	}
}


add_action( 'init', function(){
	Xoo_Ml_Easy_Login_Functions::get_instance();
}, 0 );

