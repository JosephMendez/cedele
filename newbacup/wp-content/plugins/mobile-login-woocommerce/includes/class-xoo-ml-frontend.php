<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_Ml_Phone_Frontend{

	protected static $_instance = null;
	public $settings;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		$this->settings = get_option( 'xoo-ml-phone-options', true );
		$this->hooks();
	}

	public function hooks(){

		if( $this->settings['l-enable-login-with-otp'] === "yes" ){
			add_action( 'woocommerce_login_form_end', array( $this, 'wc_login_with_otp_form' ) );
			add_filter( 'gettext', array( $this, 'wc_login_username_field_i8n' ), 999, 3 );
		}

		if( $this->settings['r-enable-phone'] === "yes" ){
			add_action( 'woocommerce_register_form_start', array( $this, 'wc_register_phone_input' ) );
			add_action( 'woocommerce_edit_account_form_start', array( $this, 'wc_myaccount_edit_phone_input' ) );
			add_filter(  'xoo_ml_user_register_phone_forms', array( $this, 'add_wc_register_form_for_phone' ) );
		}
		
		//add_filter(	'wc_get_template', array( $this, 'override_myaccount_template' ), 9999999, 5 );
		add_action( 'wp_enqueue_scripts' ,array( $this,'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts' , array( $this,'enqueue_scripts' ), 0 );
		
	}

	public function add_wc_register_form_for_phone( $register_forms ){
		if( class_exists( 'woocommerce' ) ){
			$register_forms['register'] 	= 'Register'; // wc registration
			$register_forms['action'] 		= 'save_account_details'; //wc edit account
		}
		return $register_forms;
	}



	public function wc_login_with_otp_form(){
		$args = apply_filters( 'xoo_ml_wc_otp_login_btn', self::wc_register_phone_input_args() );
		return xoo_ml_get_login_with_otp_form( $args );

	}


	//Enqueue stylesheets
	public function enqueue_styles(){
		wp_enqueue_style( 'xoo-ml-style', XOO_ML_URL.'/assets/css/xoo-ml-style.css', array(), XOO_ML_VERSION );
		$settings = get_option( 'xoo-ml-phone-options', true );
		$style = '';
		if( $settings[ 'l-login-display' ] === "yes" ){
			$style = "
				.xoo-el-form-login{
					display: none;
				}
			";
		}
		wp_add_inline_style('xoo-ml-style', $style );
	}

	//Enqueue javascript
	public function enqueue_scripts(){
		wp_enqueue_script( 'xoo-ml-phone-js', XOO_ML_URL.'/assets/js/xoo-ml-phone-js.js', array('jquery'), XOO_ML_VERSION, true ); // Main JS

		$settings = get_option( 'xoo-ml-phone-options', true );

		wp_localize_script('xoo-ml-phone-js','xoo_ml_phone_localize',array(
			'adminurl'  			=> admin_url().'admin-ajax.php',
			'resend_wait' 			=> $settings['otp-resend-wait'],
			'phone_form_classes'	=> json_encode( self::phone_form_classes() ),
			'auto_submit_reg' 		=> $settings['r-auto-submit'],
			'show_phone' 			=> $settings['r-phone-field'],
			'notices' 				=> array(
				'empty_phone' 	=> xoo_ml_add_notice( __( 'Please enter a phone number', 'mobile-login-woocommerce' ), 'error' ),
				'empty_email' 	=> xoo_ml_add_notice( __( 'Email address cannot be empty.', 'mobile-login-woocommerce' ), 'error' ),
				'empty_password'=> xoo_ml_add_notice( __( 'Please enter a password.', 'mobile-login-woocommerce' ), 'error' ),
				'invalid_phone' => xoo_ml_add_notice( __( 'Please enter a valid phone number without any special characters & country code.', 'mobile-login-woocommerce' ), 'error' ),
			),
			'login_first' 	=> $settings['l-login-display'],
			//'phone_first' 			=> $settings['r-phone-first'],
		));
	}


	public function override_myaccount_template( $template, $template_name, $args, $template_path, $default_path ){

		if( $template_name === 'myaccount/form-login.php' ){
			$template = xoo_locate_template( 'xoo-ml-form-login.php', XOO_ML_PATH.'/templates/' );
		}
		return $template;
	}

	public static function wc_register_phone_input_args( $args = array() ){
		$default_args = array(
			'label' 		=> __('Phone', 'mobile-login-woocommerce'),
			'cont_class' 	=> array(
				'woocommerce-form-row',
				'woocommerce-form-row--wide',
				'form-row form-row-wide'
			),
			'input_class' 	=> array(
				'woocommerce-Input',
				'input-text',
				'woocommerce-Input--text'
			)
		);
		return wp_parse_args( $args, $default_args );
	}

	public function wc_myaccount_edit_phone_input(){
		return xoo_ml_get_phone_input_field( self::wc_register_phone_input_args(
			array(
				'form_type' 	=> 'update_user',
				'default_phone' => xoo_ml_get_user_phone( get_current_user_id(), 'number' ),
				'default_cc'	=> xoo_ml_get_user_phone( get_current_user_id(), 'code' ),
			)
		) );
	}

	public function wc_register_phone_input(){
		return xoo_ml_get_phone_input_field( self::wc_register_phone_input_args() );
	}

	public function wc_register_phone_form(){
		return xoo_ml_phone_input_form( self::wc_register_phone_input_args() );
	}


	public static function phone_form_classes(){
		return apply_filters( 'xoo_ml_phone_form_classes', array(
			'woocommerce-form-register'
		) );
	}


	public function wc_login_username_field_i8n( $translation, $text, $domain ){
		if( $domain === 'woocommerce' && strcmp( $translation, 'Username or email address' ) === 0 ){
			return __( 'Phone or Email address', 'mobile-login-woocommerce' );
		}
		return $translation;
	}

}

function xoo_ml_phone_frontend(){
	return Xoo_Ml_Phone_Frontend::get_instance();
}
xoo_ml_phone_frontend();
