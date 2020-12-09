<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Xoo_Ml_Admin_Settings{

	protected static $_instance = null;

	public static $callbacks;
	public $all_options_array = array();
	public $tabs = array();


	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){

		self::$callbacks = include (XOO_ML_PATH.'admin/includes/class-xoo-ml-callbacks.php');

		$this->set_tabs(); // Set tabs

		add_action( 'admin_init', array( $this, 'set_default_options' ) );

		add_action('admin_menu',array($this,'add_menu_page'));
		add_action('admin_enqueue_scripts',array($this,'enqueue_scripts'));

		add_action('admin_init',array($this,'display_all_settings'));
		add_filter( 'plugin_action_links_' . XOO_ML_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
		add_filter( 'xoo_ml_setting_args', array( $this, 'phone_operator_setting' ) );

		add_action( 'wp_ajax_download_operator_sdk', array( $this, 'download_operator_sdk' ) );
		add_action( 'plugins_loaded', array( $this, 'on_version_update' ), 20 );

		add_action( 'xoo_ml_admin_settings_start', array( $this, 'popup_trial_notice' ) );
	}


	public function on_version_update(){

		$version_option = 'xoo-ml-version';
		$db_version 	= get_option( $version_option );

		if( version_compare( $db_version, '1.0', '=' ) ){
			$this->fetch_sdk( 'aws', true );
		}
	}


	public function set_tabs(){

		if( !empty( $this->tabs ) ){
			return $this->tabs;
		}

		$this->tabs = array(
			'phone' 	=> __( 'Phone','mobile-login-woocommerce' ),
			'services' 	=> __( 'Services','mobile-login-woocommerce' ),
			'pro' 		=> __( 'PRO', 'mobile-login-woocommerce' )
		);

	}


	public function set_default_options(){

		$default_options = $this->get_all_options_array();
		if( empty( $default_options ) ) return;

		foreach ($default_options as $option_name => $settings ) {

			//Return current option value from the database
			$option_value = (array) get_option($option_name) ;

			foreach ($settings as $setting) {	
				if( $setting['type'] === 'setting' && isset( $setting['default'] ) && isset( $setting['id'] ) && !isset( $option_value[$setting['id']]) ){
					$option_value[$setting['id']] = $setting['default'];
				}
			}



			update_option( $option_name, $option_value );
			
		}
	}


	public function get_all_options_array(){

		if( !empty( $this->all_options_array ) ){
			return $this->all_options_array;
		}

		foreach ($this->tabs as $key => $title) {

			$path = XOO_ML_PATH.'admin/includes/options/'.$key.'-options.php'; 

			if( file_exists( $path ) ){
				$this->all_options_array[ 'xoo-ml-'.$key.'-options' ] = include $path;
			}
		}

		return $this->all_options_array;
	}


	public function enqueue_scripts($hook) {

		//Enqueue Styles only on plugin settings page
		if( $hook !== 'toplevel_page_xoo-ml' && $hook !== 'login-signup-popup_page_xoo-ml' ){
			return;
		}
		
		wp_enqueue_media(); // media gallery
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style( 'xoo-ml-admin-style', XOO_ML_URL . '/admin/assets/css/xoo-ml-admin-style.css', array(), XOO_ML_VERSION, 'all' );
		wp_enqueue_script( 'xoo-ml-admin-js', XOO_ML_URL . '/admin/assets/js/xoo-ml-admin-js.js', array( 'jquery','wp-color-picker'), XOO_ML_VERSION, false );

		wp_localize_script('xoo-ml-admin-js','xoo_ml_admin_localize',array(
			'adminurl'  => admin_url().'admin-ajax.php',
		));

	}


	public function add_menu_page(){

		if( defined( 'XOO_EL_PATH' ) ){
			add_submenu_page(
				'xoo-el',
				'Mobile Login Settings',
				'Mobile Login',
				'manage_options',
				'xoo-ml',
				array($this,'menu_page_callback')
			);
		}else{
			add_menu_page( 
				'Mobile Login/Signup Settings', //Page Title
				'Mobile Login/Signup', // Menu Titlle
				'manage_options',// capability
				'xoo-ml', // Menu Slug
				array($this,'menu_page_callback') // callback
			);
		}
	}

	public function menu_page_callback(){
		$args = array(
			'tabs' 		=> $this->tabs
		);
		xoo_get_template( "xoo-ml-admin-display.php", XOO_ML_PATH.'/admin/templates/', $args );
	}


	public function display_all_settings(){

		$default_options = $this->get_all_options_array();

		foreach ( $default_options as $option_name => $settings ) {
			$this->generate_settings( $settings, $option_name, $option_name, $option_name);
		}
	}


	public function generate_settings( $setting_fields, $page, $group, $option_name ){

		if(empty($setting_fields)){
			return;
		}

		foreach ($setting_fields as $field) {

			//Arguments for add_settings_field
			$args = $field;

			if( !isset($field['id']) || !isset($field['type']) || !isset($field['callback']) ) {
				continue;
			}

			//Check for callback functions
			if( is_callable( array( self::$callbacks, $field['callback'] ) ) ){
				$callback = array( self::$callbacks, $field['callback'] );
			}
			elseif ( is_callable( $field['callback'] ) ) {
				$callback = $field['callback'];
			}
			else{
				continue;
			}

			$title = isset($field['title']) ? $field['title'] : null;

			//Add a section
			if( $field['type'] === 'section' ){

				$section_args = array(
					'id' 		=> $field['id'],
					'title' 	=> $title,
					'callback' 	=> $callback,
					'page' 		=>$page
				);

				$section_args = apply_filters( 'xoo_ml_section_args', $section_args );
				call_user_func_array( 'add_settings_section', array_values( $section_args ) );

			}

			//Add a setting field
			elseif( $field['type'] === 'setting' ){

				$setting_args = array(
					'id' 		=> $field['id'],
					'title' 	=> $title,
					'callback' 	=> $callback,
					'page' 		=> $page,
					'section' 	=> $field['section'],
					'args' 		=> $args
				);

				$setting_args = apply_filters( 'xoo_ml_setting_args', $setting_args );
				
				call_user_func_array( 'add_settings_field', array_values( $setting_args ) );

			}

		}

		register_setting( $group, $option_name);

	}


	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=xoo-ml' ) . '" target="_blank">' . __('Settings', 'mobile-login-woocommerce' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}


	public function phone_operator_setting( $args ){
		if( $args['id'] === 'm-operator' ){
			$args['callback'] = array( $this, 'phone_operator_setting_output' );
		}
		return $args;
	}

	//Modify  output for phone operator setting
	public function phone_operator_setting_output( $args ){

		$html = call_user_func( array( self::$callbacks, $args['callback'] ), $args );
		$operator_data = xoo_ml_operator_data();
		ob_start();

		?>
		<ul class="xoo-ml-opt-links">
		
			<?php foreach( $operator_data as $operator => $data ): ?>
				<li data-operator="<?php echo $operator; ?>" style="display: none;">
					<a class="xoo-ml-sdk-dwnld" href="#">Download <?php echo $data['title']; ?> SDK</a>
					<a href="<?php echo $data['doc']; ?>" target="_blank">Documentation</a>
				</li>
			<?php endforeach; ?>

		</ul>
		<span class="xoo-ml-notice"></span>
		<?php
		$html .= ob_get_clean();
		echo $html;
	}


	protected function fetch_sdk( $operator, $force_download = false ){
		$base_dir = wp_get_upload_dir()['basedir'];

		//Check if SDK folder exists
		if( !is_dir( $base_dir.'/xootix-sms-sdks' ) ){
			mkdir( $base_dir.'/xootix-sms-sdks' );
		}
		$upload_dir = $base_dir.'/xootix-sms-sdks';

		//Check if SDK already installed
		if( is_dir( $upload_dir.'/'.$operator ) && !$force_download ){
			return new WP_Error( 'exists', 'You already have this SDK. <a class="xoo-ml-sdk-dwnld-again" href="#">Download again</a> ' );
		}

		// If the function it's not available, require it.
		if ( ! function_exists( 'download_url' ) ) {
		    require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		//Download
		$operator_data 	= xoo_ml_operator_data()[ $operator ];
		$permfile 		= $upload_dir.'/'.$operator.'.zip';
		$tmpfile 		= download_url( $operator_data['download'], $timeout = 300 );

		//Check if download was succesfull
		if( is_wp_error( $tmpfile ) ){
			return $tmpfile;
		}
		copy( $tmpfile, $permfile );
		unlink( $tmpfile ); // must unlink afterwards

		//Unzip
		WP_Filesystem();
		$unzipfile = unzip_file( $permfile, $upload_dir );

		return $unzipfile;
	}


	public function download_operator_sdk(){

		if( !isset( $_POST['operator'] ) ) return;

		try {

			$fetchSdk = $this->fetch_sdk( $_POST['operator'], isset( $_POST['download_again'] ) && $_POST['download_again'] === "yes" );

			if( is_wp_error( $fetchSdk ) ){
				throw new Xoo_Exception( $fetchSdk );	
			}

			//All good
			wp_send_json( array(
				'error' 	=> 0,
				'notice' 	=> 'Downloaded succesfully'
			) );

		} catch ( Xoo_Exception $e) {
			wp_send_json( array(
				'error' 	=> 1,
				'notice' 	=> $e->getMessage()
			) );
		}
		

	}


	public function popup_trial_notice(){
		if( !class_exists( 'Xoo_El_Core' ) || defined( 'XOO_ML_PRO' ) ) return;
		?>
		<div class="xoo-ml-trial-notice">
			<span>OTP Login is a free add-on for woocommerce users. However using with our Popup design is a part of <a href="https://xootix.com/plugins/mobile-login-for-woocommerce">pro add-on</a>.</span>
			<span> <?php echo !Xoo_Ml::hasTrialExpired() ? "You have a 15 days trial period to test with our popup design." : "OTP login add-on trial period has expired." ?></span>
			<span>You can continue to use this plugin with woocommerce login/signup form after trial period is over</span>
		</div>
		<?php
	}


}

function xoo_ml_admin_settings(){
	return Xoo_Ml_Admin_Settings::get_instance();
}

xoo_ml_admin_settings();

?>