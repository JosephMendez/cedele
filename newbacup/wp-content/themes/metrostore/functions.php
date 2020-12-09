<?php
/**
 * MetroStore functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MetroStore
 */

if ( ! function_exists( 'metrostore_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function metrostore_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on MetroStore, use a find and replace
     * to change 'metrostore' to the name of your theme in all the template files.
     */
    load_theme_textdomain( 'metrostore', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    add_image_size('metrostore-cat-image', 270, 355, true);
    add_image_size('metrostore-blog-image', 375, 265, true);
    add_image_size('metrostore-banner-image', 1350, 520, true);

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary', 'metrostore' ),
    ) );

    // Support WooCommerce WordPress Plugins
    add_theme_support( 'woocommerce' );

    // Set up the WordPress Gallery Lightbox
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    /**
     * Editor style.
    */
    add_editor_style( 'assets/css/editor-style.css' );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
    */
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    // Set up the WordPress core custom background feature.
    add_theme_support( 'custom-background', apply_filters( 'metrostore_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ) ) );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    /*
     * Enable support for custom logo.
    */
    add_theme_support( 'custom-logo', array(
        'height'      => 350,
        'width'       => 175,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( '.site-title', '.site-description' ),
    ) );
}
endif;
add_action( 'after_setup_theme', 'metrostore_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function metrostore_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'metrostore_content_width', 640 );
}
add_action( 'after_setup_theme', 'metrostore_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function metrostore_widgets_init() {
    
    register_sidebar( array(
        'name'          => esc_html__( 'Right Sidebar Widget Area', 'metrostore' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'metrostore' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar( array(
        'name'          => esc_html__( 'Left Sidebar Widget Area', 'metrostore' ),
        'id'            => 'sidebar-2',
        'description'   => esc_html__( 'Add widgets here.', 'metrostore' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));


    if ( is_customize_preview() ) {
        $metrostore_description = sprintf( esc_html__( 'Displays widgets on home page main content area.%1$s Note : Please go to %2$s "Static Front Page"%3$s setting, Select "A static page" then "Front page" and "Posts page" to show added widgets', 'metrostore' ), '<br />','<b><a class="sparkle-customizer" data-section="static_front_page" style="cursor: pointer">','</a></b>' );
    }
    else{
        $metrostore_description = esc_html__( 'Displays widgets on Front/Home page. Note : First Create Page and Select "Page Attributes Template"( Home Page ) then Please go to Setting => Reading, Select "A static page" then "Front page" and add widgets to show on Home Page', 'metrostore' );
    }

    register_sidebar( array(
        'name'          => esc_html__( 'Metrostore HomePage Widget Area', 'metrostore' ),
        'id'            => 'metrostore_homepage',
        'description'   => $metrostore_description,
        'before_widget' => '',
        'after_widget'  => '',
    ));
    
}
add_action( 'widgets_init', 'metrostore_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
if ( ! function_exists( 'metrostore_scripts' ) ) {

    function metrostore_scripts() {

        $metrostore_theme = wp_get_theme();
        $theme_version = $metrostore_theme->get( 'Version' );

        /* Metro Store Google Font */
        //wp_enqueue_style( 'metrostore-googleapis', '//fonts.googleapis.com/css?family=Lato:400,700,300');
        $metrostore_font_args = array(
            'family' => 'Open+Sans+Condensed:300,700|Open+Sans:300,400,600,700,800|Karla:400,400italic,700,700italic|Dancing+Script:400,700|Source+Sans+Pro:200,200italic,300,300italic,400,400italic,600,600italic,700,700italic,900,900italic|Source+Code+Pro:400,500,600,700,300|Montserrat:400,500,600,700,800',
        );
        wp_enqueue_style('metrostore-google-fonts', add_query_arg( $metrostore_font_args, "//fonts.googleapis.com/css" ) );

        /* Metro Store Bootstrap */
        wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/library/bootstrap/css/bootstrap.min.css', esc_attr( $theme_version ) );

        /* Metro Store Font Awesome */
        wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/library/font-awesome/css/font-awesome.min.css', esc_attr( $theme_version ) );

        /*Metro Store Flexslider CSS*/
        wp_enqueue_style('jquery-flexslider', get_template_directory_uri() . '/assets/library/flexslider/css/flexslider.css', esc_attr( $theme_version ));

        /* Metro Store Owl Carousel CSS */
        wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/assets/library/carouselowl/css/owl.carousel.css' );

        wp_enqueue_style( 'owl-theme', get_template_directory_uri() . '/assets/library/carouselowl/css/owl.theme.css' );

        /* Metro Store Main Style */
        wp_enqueue_style( 'metrostore-style', get_stylesheet_uri() );

       if ( has_header_image() ) {
        $custom_css = '.site-header{ background-image: url("' . esc_url( get_header_image() ) . '"); background-repeat: no-repeat; background-position: center center; background-size: cover; }';
        wp_add_inline_style( 'metrostore-style', $custom_css );
       }
       
       /* Metro Store Jquery Section Start */
        wp_enqueue_script( 'metrostore-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), esc_attr( $theme_version ), true );
        wp_enqueue_script( 'metrostore-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), esc_attr( $theme_version ), true );

        /* Metro Store html5 */
        wp_enqueue_script('html5', get_template_directory_uri() . '/assets/library/html5shiv/html5shiv.min.js', array('jquery'), esc_attr( $theme_version ), false);
        wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

        /* Metro Store Respond */
        wp_enqueue_script('respond', get_template_directory_uri() . '/assets/library/respond/respond.min.js', array('jquery'), esc_attr( $theme_version ), false);
        wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

        /*Metore Store Flexslider*/
        wp_enqueue_script('jquery-flexslider', get_template_directory_uri() . '/assets/library/flexslider/js/jquery.flexslider-min.js', array('jquery'), esc_attr( $theme_version ), true);

        /* Metore Store Bootstrap */
        wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/library/bootstrap/js/bootstrap.min.js', array('jquery'), esc_attr( $theme_version ), false);

        /* Metore Store Carousel */
        wp_enqueue_script('owl-carousel-min', get_template_directory_uri() . '/assets/library/carouselowl/js/owl.carousel.min.js', array(), esc_attr( $theme_version ), false);

        /* Metore Store Responsive Mobile Menu */
        wp_enqueue_script('mobile-menu', get_template_directory_uri() . '/assets/js/mobile-menu.js', array(), esc_attr( $theme_version ), false);

        /* Metore Store Responsive Mobile Menu */
        wp_enqueue_script('waypoints', get_template_directory_uri() . '/assets/library/waypoints/jquery.waypoints.min.js', array(), esc_attr( $theme_version ), false);
        
        /* Metore Store Full Background Video js */
        wp_enqueue_script('youtubebackground', get_template_directory_uri() . '/assets/js/jquery.youtubebackground.js', array(), esc_attr( $theme_version ), false);

        /* Metrostore Sidebar Widget Ticker */
        wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/assets/library/theia-sticky-sidebar/js/theia-sticky-sidebar.min.js', array('jquery'), esc_attr( $theme_version ), true);

        /* Metore Store Theme Custom js */
        wp_enqueue_script('metrostore-main', get_template_directory_uri() . '/assets/js/metrostore-main.js', array('jquery'), esc_attr( $theme_version ), false);

        wp_localize_script( 'metrostore-main', 'metrostore_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php') ) );

        /* Metore Store Waypoints support js infographic */
        wp_enqueue_script('infographic', get_template_directory_uri() . '/assets/js/infographic.js', array(), esc_attr( $theme_version ), false);


        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'metrostore_scripts' );

/**
 * Admin Enqueue scripts and styles.
**/
if ( ! function_exists( 'metrostore_admin_scripts' ) ) {

    function metrostore_admin_scripts($hook) {

        if( 'widgets.php' != $hook )
        return;
        if (function_exists('wp_enqueue_media'))
          wp_enqueue_media();
          wp_enqueue_script('metrostore-media-uploader', get_template_directory_uri() . '/assets/js/metrostore-admin.js', array( 'jquery', 'customize-controls' ) );
          wp_localize_script('metrostore-media-uploader', 'metrostore_img_remove', array(
              'upload' => esc_html__('Upload', 'metrostore'),
              'remove' => esc_html__('Remove', 'metrostore')
          ));
        wp_enqueue_style( 'metrostore-style-admin', get_template_directory_uri() . '/assets/css/metrostore-admin.css');   
    }

}
add_action('admin_enqueue_scripts', 'metrostore_admin_scripts');

/**
 * Query WooCommerce activation
*/
if ( ! function_exists( 'metrostore_is_woocommerce_activated' ) ) {
  function metrostore_is_woocommerce_activated() {
    return class_exists( 'woocommerce' ) ? true : false;
  }
}

function additional_product_tabs_metabox()
{
    add_meta_box(
        'add_product_metabox_additional_tabs',
        '
            <div class="product-availability" style="display:flex">
                <div class="product-availability-title">
                    Product availability
                </div>
            </div>
        ',
        'additional_product_tabs_metabox_content',
        'product',
        'advanced',
        'default',
        null
    );
}

function additional_product_tabs_metabox_content($post) {
    global $post;

    $typeChoosen = 'daily-product';

    $listDay = [
        'monday' => __('Monday', 'woocommerce'),
        'tuesday' => __('Tuesday', 'woocommerce'),
        'wednesday' => __('Wednesday', 'woocommerce'),
        'thursday' => __('Thursday', 'woocommerce'),
        'friday' => __('Friday', 'woocommerce'),
        'saturday' => __('Saturday', 'woocommerce'),
        'sunday' => __('Sunday', 'woocommerce'),
    ];

    $checkedDate = get_post_custom($post->ID);

    $timeFrom = get_post_meta($post->ID, 'daily-product-available-time-from', true);
    $timeTo = get_post_meta($post->ID, 'daily-product-available-time-to', true);


    $oneDayTimeFrom = get_post_meta($post->ID, 'one-day-time-from', true);
    $oneDayTimeTo = get_post_meta($post->ID, 'one-day-time-to', true);

    $timeRangeFrom = get_post_meta($post->ID, 'time-range-from', true);
    $timeRangeTo = get_post_meta($post->ID, 'time-range-to', true);


    $oneDayDatepicker = get_post_meta($post->ID, 'one-day-date-picker');
    $dateRangeFrom = get_post_meta($post->ID, 'date-range-from');
    $dateRangeTo = get_post_meta($post->ID, 'date-range-to');
    $deliveryMethod = get_post_meta($post->ID, 'delivery_method');

    // product lead time
    $productLeadTime = get_post_meta($post->ID, 'product-lead-time-checkbox');
    $leadTimeMinutes = get_post_meta($post->ID, 'product-lead-time-minutes');
    $leadTimeDays = get_post_meta($post->ID, 'product-lead-time-days');

    // store location
    require_once metrostore_file_directory('store_location.php');
    $list_stores = get_list_stores($post->ID);
    $list_option_stores = get_list_option_stores();
    ?>
        <div class="wp-product-avaiable product-availability-type-product">
            <b>Product frequently:</b>
            <input id="daily-product" type="radio" checked value="daily-product" name="product-avalability-type" onclick="checkDailyProduct()"/>
            <label>Daily Product</label>
            <input id="season-product" type="radio" name="product-avalability-type" value="season-product" onclick="checkSeasonProduct()"/>
            <label>Seasonal Product</label>
        </div>
        <input type="hidden" id="type-choosen" name="type-choosen" value="<?php echo $typeChoosen ?>" />
        <div class="wp-product-avaiable daily-product-info">
            <div class="wp-product-avaiable-sub">
                <div class="daily-product-available">
                    <label>Available day(s):</label>
                    <div class="daily-product-list-date-available" style="display:flex; margin-top:10px">
                        <?php
                            foreach($listDay as $key => $value) {
                                if ($checkedDate[$key] == NULL) {
                                    echo '<div>
                                    <input type="checkbox" checked value="' .$key. '" name="' .$key. '"/>&nbsp;
                                        <label>' . $value . '</label>
                                    </div>&nbsp;&nbsp;';
                                } else {
                                    echo '<div>
                                    <input type="checkbox" value="' .$key. '" name="' .$key. '"';
                                    ?>
                                    <?php
                                    if(isset($checkedDate[$key])) checked($checkedDate[$key][0], 'yes');
                                echo'/>&nbsp;
                                    <label>' . $value . '</label>
                                </div>&nbsp;&nbsp;';
                                }
                            }
                        ?>
                    </div>
                </div>

                <div class="daily-product-available" style="margin-top:30px">
                    <label>Available time:</label>
                    <div class="daily-product-available-time" style="margin-top:15px; display:flex">
                        <div class="daily-product-available-time-from" style="display:flex;align-items:center">
                            <label>From: </label>
                            <input name="daily-product-available-time-from" class="product-availability-timedatepicker" type="text" value="<?php echo $timeFrom !== '' ? $timeFrom : '00:00' ?>"/>
                        </div>
                        <div class="daily-product-available-time-to" style="margin-left:30px">
                            <label>To:</label>
                            <input name="daily-product-available-time-to" class="product-availability-timedatepicker" type="text" value="<?php echo $timeTo !== '' ? $timeTo : '23:59' ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wp-product-avaiable season-product-info">
            <div class="wp-product-avaiable-sub">
                <div class="season-product-available" style="display:flex; align-items:center">
                    <label>Available date(s):</label>
                    <div class="season-product-list-date-available" style="display:flex">
                        <select onchange="handleChangeType(this)" id="season-product-list-date-available-select" name="season-product-list-date-available-select" style="margin-left:10px">
                            <option value="one-day-only" name="one-day-only"> One Day Only </option>
                            <option value="time-range" name="time-range"> Time Range </option>
                        </select>
                        <input id="one-day-date-picker" type="date" value="<?php echo $oneDayDatepicker[0] ?>" name="one-day-date-picker" style="margin-left:10px;" required>
                        <div class="date-range-from" style="display:flex;align-items: center;margin-left:20px">
                            <label for="date-range-from">From:</label>
                            <input type="date" id="date-range-from" value="<?php echo $dateRangeFrom[0] ?>" name="date-range-from" style="margin-left:10px" required>
                        </div>
                        <div class="date-range-to" style="display:flex;align-items: center;margin-left:20px">
                            <label for="date-range-to">To:</label>
                            <input type="date" id="date-range-to" value="<?php echo $dateRangeTo[0] ?>" name="date-range-to" style="margin-left:10px" required>
                        </div>
                    </div>
                </div>

                <div class="season-product-available-one-day" style="margin-top:30px;">
                    <label>Available time:</label>
                    <div class="season-product-available-time" style="margin-top:15px; display:flex">
                        <div class="season-product-available-time-from" style="display:flex;align-items:center">
                            <label>From: </label>
                            <input name="one-day-time-from" class="product-availability-timedatepicker" type="text" value="<?php echo $oneDayTimeFrom !== '' ? $oneDayTimeFrom : '00:00' ?>"/>
                        </div>
                        <div class="season-product-available-time-to" style="margin-left:30px">
                            <label>To:</label>
                            <input name="one-day-time-to" class="product-availability-timedatepicker" type="text" value="<?php echo $oneDayTimeTo !== '' ? $oneDayTimeTo : '23:59' ?>"/>
                        </div>
                    </div>
                </div>

                <div class="season-product-available-time-range" style="margin-top:30px">
                    <label>Available time:</label>
                    <div class="season-product-available-time" style="margin-top:15px; display:flex">
                        <div class="season-product-available-time-from" style="display:flex;align-items:center">
                            <label>From: </label>
                            <input name="time-range-from" class="product-availability-timedatepicker" type="text" value="<?php echo $timeRangeFrom !== '' ? $timeRangeFrom : '00:00' ?>"/>
                        </div>
                        <div class="season-product-available-time-to" style="margin-left:30px">
                            <label>To:</label>
                            <input name="time-range-to" class="product-availability-timedatepicker" type="text" value="<?php echo $timeRangeTo !== '' ? $timeRangeTo : '23:59' ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wp-product-avaiable">
            <b>Product lead time:</b>
            <div class="wp-product-lead-time">
                <div class="lead-time-row">
                    <input type="radio" name="product-lead-time-checkbox" value="same"
                    <?php echo $productLeadTime[0] !== 'advance' ? 'checked' : '' ?>> Same day product
                    <span>
                        Leadtime for order: <input type="number" name="product-lead-time-minutes" min="0" max="9999" value="<?php echo $leadTimeMinutes[0] ?>" placeholder="minutes"> minutes
                    </span>
                </div>
                <div class="lead-time-row">
                    <input type="radio" name="product-lead-time-checkbox" value="advance"
                    <?php echo $productLeadTime[0] === 'advance' ? 'checked' : '' ?>> Advance product
                    <span>
                        Leadtime for order: <input type="number" name="product-lead-time-days" min="0" max="9999" value="<?php echo $leadTimeDays[0] ?>" placeholder="day(s)"> day(s)
                    </span>
                </div>
            </div>
        </div>
        <div class="wp-product-avaiable">
            <b>Delivery method:</b>
            <div class="wp-product-delivery-method">
                <div>
                    <input type="radio" name="delivery_method" value="both" checked> Both Delivery and Self-collect
                </div>
                <div>
                    <input type="radio" name="delivery_method" value="delivery"
                    <?php echo $deliveryMethod[0] === 'delivery' ? 'checked' : '' ?>> Only Delivery
                </div>
                <div>
                    <input type="radio" name="delivery_method" value="self"
                    <?php echo $deliveryMethod[0] === 'self' ? 'checked' : '' ?>> Only Self-collect
                </div>
            </div>
        </div>
        <div class="wp-product-avaiable wp-product-store-location">
            <b>Product available for location:</b>
            <div class="wp-product-list-location">
                <?php
                    if(!empty($list_option_stores)):
                        $checkall = false;
                        if (empty($deliveryMethod[0]))
                            $checkall = true
                ?>
                <div class="wp-product-location">
                    <input type="checkbox" class="wp-product-checkbox-all" <?php echo $checkall ? 'checked' : '' ?>>
                    <span>all locations</span>
                </div>
                <?php foreach ($list_option_stores as $key => $store):
                    $exist = filter_array($list_stores, 'store_id', $store['id']);
                ?>
                    <div class="wp-product-location">
                        <input type="checkbox" class="wp-product-checkbox" name="product_stores[]"
                        <?php echo !empty($exist) || $checkall ? 'checked' : '' ?>
                        value="<?php echo $store['id']; ?>"
                        >
                        <span><?php echo $store['store_name']; ?></span>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php
}

add_action( 'add_meta_boxes', 'additional_product_tabs_metabox' );

function save_data_custom_meta_box( $post_id) {
    $daily_product_from_string = 'daily-product-available-time-from';
    $daily_product_to_string = 'daily-product-available-time-to';
    $date_range_from = 'date-range-from';
    $date_range_to = 'date-range-to';
    $time_range_from = 'time-range-from';
    $time_range_to = 'time-range-to';
    $type_choosen = 'type-choosen';

    $delivery_method = 'delivery_method';
    $product_stores = 'product_stores';

    $listDay = [
        'monday' => __('Monday', 'woocommerce'),
        'tuesday' => __('Tuesday', 'woocommerce'),
        'wednesday' => __('Wednesday', 'woocommerce'),
        'thursday' => __('Thursday', 'woocommerce'),
        'friday' => __('Friday', 'woocommerce'),
        'saturday' => __('Saturday', 'woocommerce'),
        'sunday' => __('Sunday', 'woocommerce'),
    ];

    $product_stores = isset($_POST[$product_stores]) ? $_POST[$product_stores] : [];
    update_post_meta($post_id, $delivery_method, $_POST[$delivery_method]);
    // update table holiday store
    require_once metrostore_file_directory('store_location.php');
    if ($_POST[$delivery_method] !== 'delivery') {
        delete_option_stores($post_id);
        multiple_insert_store_post($post_id, $product_stores);
    }

    // product lead time
    $leadTimeCheckbox = 'product-lead-time-checkbox';
    $leadTimeMinutes = 'product-lead-time-minutes';
    $leadTimeDays = 'product-lead-time-days';
    update_post_meta($post_id, $leadTimeCheckbox, $_POST[$leadTimeCheckbox]);
    update_post_meta($post_id, $leadTimeMinutes, intval($_POST[$leadTimeMinutes]));
    update_post_meta($post_id, $leadTimeDays, intval($_POST[$leadTimeDays]));

    if (array_key_exists($type_choosen, $_POST) && $_POST['type-choosen'] === 'daily-product') {
        // save
        foreach ($listDay as $key => $value) {
            if (isset($_POST[$key])) {
                update_post_meta( $post_id, $key, 'yes' );
            } else {
                update_post_meta( $post_id, $key, 'no' );
            }
        }

        if (array_key_exists($daily_product_from_string, $_POST)) {
            update_post_meta(
                $post_id,
                $daily_product_from_string,
                $_POST[$daily_product_from_string]
            );
        }
    
        if (array_key_exists($daily_product_to_string, $_POST)) {
            update_post_meta(
                $post_id,
                $daily_product_to_string,
                $_POST[$daily_product_to_string]
            );
        }

        // == remove

        delete_post_meta(
            $post_id,
            $date_range_from,
        );

        delete_post_meta(
            $post_id,
            $time_range_from,
        );

        delete_post_meta(
            $post_id,
            $time_range_to,
        );
    
        delete_post_meta(
            $post_id,
            $date_range_to,
        );
    
        delete_post_meta(
            $post_id,
            'one-day-date-picker',
        );
    
        delete_post_meta(
            $post_id,
            'one-day-time-to',
        );
    
        delete_post_meta(
            $post_id,
            'one-day-time-from',
        );
    };

    if (array_key_exists($type_choosen, $_POST) && $_POST['type-choosen'] === 'season-product-one-day-only') {
        if (array_key_exists('one-day-date-picker', $_POST)) {
            update_post_meta(
                $post_id,
                'one-day-date-picker',
                $_POST['one-day-date-picker']
            );
        }

        if (array_key_exists('one-day-time-to', $_POST)) {
            update_post_meta(
                $post_id,
                'one-day-time-to',
                $_POST['one-day-time-to']
            );
        }

        if (array_key_exists('one-day-time-from', $_POST)) {
            update_post_meta(
            $post_id,
                'one-day-time-from',
                $_POST['one-day-time-from']
            );
        }

        //remove

        foreach ($listDay as $key => $value) {
            update_post_meta( $post_id, $key, 'no' );
        }

        delete_post_meta(
            $post_id,
            $daily_product_from_string,
        );
    
        delete_post_meta(
            $post_id,
            $daily_product_to_string,
        );

        delete_post_meta(
            $post_id,
            $date_range_from,
        );

        delete_post_meta(
            $post_id,
            $date_range_to,
        );

        delete_post_meta(
            $post_id,
            $time_range_from,
        );

        delete_post_meta(
            $post_id,
            $time_range_to,
        );
    }

    if (array_key_exists($type_choosen, $_POST) && $_POST['type-choosen'] === 'season-product-date-range') {
        if (array_key_exists($date_range_from, $_POST)) {
            update_post_meta(
                $post_id,
                $date_range_from,
                $_POST[$date_range_from]
            );
        };

        if (array_key_exists($date_range_to, $_POST)) {
            update_post_meta(
                $post_id,
                $date_range_to,
                $_POST[$date_range_to]
            );
        };

        if (array_key_exists($time_range_from, $_POST)) {
            update_post_meta(
                $post_id,
                $time_range_from,
                $_POST[$time_range_from]
            );
        };

        if (array_key_exists($time_range_to, $_POST)) {
            update_post_meta(
                $post_id,
                $time_range_to,
                $_POST[$time_range_to]
            );
        };

        foreach ($listDay as $key => $value) {
            update_post_meta( $post_id, $key, 'no' );
        }

        delete_post_meta(
            $post_id,
            $daily_product_from_string,
        );
    
        delete_post_meta(
            $post_id,
            $daily_product_to_string,
        );

        delete_post_meta(
            $post_id,
            'one-day-date-picker',
        );

        delete_post_meta(
            $post_id,
            'one-day-time-to',
        );

        delete_post_meta(
            $post_id,
            'one-day-time-from',
        );
    }
}
add_action('save_post', 'save_data_custom_meta_box');

function custom_js_product_avalability( $hook ) {
    wp_enqueue_script('jquery');
    wp_enqueue_style('pa-timepicker-css', get_template_directory_uri() . '/assets/css/jquery.datetimepicker.css');
    wp_enqueue_script('pa-timepicker-js', get_template_directory_uri() . '/assets/js/jquery.datetimepicker.full.js');
    wp_enqueue_style('pa-storecss', get_template_directory_uri() . '/assets/css/product-availability.css');
    wp_enqueue_script('pa-storejs', get_template_directory_uri() . '/assets/js/product-availability.js');
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementsByClassName('season-product-info')[0]) {
                document.getElementsByClassName('season-product-info')[0].style.display = 'none';
            }
            document.getElementById('one-day-date-picker').removeAttribute('required');
            document.getElementById('date-range-from').removeAttribute('required');
            document.getElementById('date-range-to').removeAttribute('required');
        });
        function checkDailyProduct() {
            document.getElementById('one-day-date-picker').removeAttribute('required');
            document.getElementById('date-range-from').removeAttribute('required');
            document.getElementById('date-range-to').removeAttribute('required');
            document.getElementById('type-choosen').value = 'daily-product';
            document.getElementsByClassName('season-product-info')[0].style.display = 'none';
            document.getElementsByClassName('daily-product-info')[0].style.display = 'block';
        };

        function checkSeasonProduct() {
            document.getElementById('one-day-date-picker').setAttribute('required', 'required');
            document.getElementById('date-range-from').removeAttribute('required');
            document.getElementById('date-range-to').removeAttribute('required');
            document.getElementById('type-choosen').value = 'season-product-one-day-only';
            document.getElementsByClassName('season-product-info')[0].style.display = 'block';
            document.getElementsByClassName('daily-product-info')[0].style.display = 'none';

            const value = document.getElementById('season-product-list-date-available-select').value;
            if (value === 'one-day-only') {
                document.getElementById('one-day-date-picker').setAttribute('required', 'required');
                document.getElementById('date-range-from').removeAttribute('required');
                document.getElementById('date-range-to').removeAttribute('required');
                document.getElementById('type-choosen').value = 'season-product-one-day-only';
                document.getElementsByClassName('date-range-from')[0].style.display = 'none';
                document.getElementsByClassName('date-range-to')[0].style.display = 'none';
                document.getElementsByClassName('season-product-available-time-range')[0].style.display = 'none';
            } else {
                document.getElementById('one-day-date-picker').removeAttribute('required', 'required');
                document.getElementById('date-range-from').setAttribute('required');
                document.getElementById('date-range-to').setAttribute('required');
                document.getElementById('type-choosen').value = 'season-product-date-range';
                document.getElementsByClassName('season-product-available-one-day')[0].style.display = 'none';
                document.getElementsByClassName('date-range-from')[0].style.display = 'block';
                document.getElementsByClassName('date-range-to')[0].style.display = 'block';
            }
        };

        function handleChangeType(data) {
            if (data.value === 'time-range') {
                document.getElementById('one-day-date-picker').removeAttribute('required', 'required');
                document.getElementById('date-range-from').setAttribute('required', 'required');
                document.getElementById('date-range-to').setAttribute('required', 'required');
                document.getElementById('type-choosen').value = 'season-product-date-range';
                document.getElementById('one-day-date-picker').style.display = 'none';
                document.getElementsByClassName('season-product-available-time-range')[0].style.display = 'block';
                document.getElementsByClassName('season-product-available-one-day')[0].style.display = 'none';
                document.getElementsByClassName('date-range-from')[0].style.display = 'block';
                document.getElementsByClassName('date-range-to')[0].style.display = 'block';
            } else {
                document.getElementById('one-day-date-picker').setAttribute('required', 'required');
                document.getElementById('date-range-from').removeAttribute('required');
                document.getElementById('date-range-to').removeAttribute('required');
                document.getElementById('type-choosen').value = 'season-product-one-day-only';
                document.getElementById('one-day-date-picker').style.display = 'block';
                document.getElementsByClassName('season-product-available-time-range')[0].style.display = 'none';
                document.getElementsByClassName('season-product-available-one-day')[0].style.display = 'block';
                document.getElementsByClassName('date-range-from')[0].style.display = 'none';
                document.getElementsByClassName('date-range-to')[0].style.display = 'none';
            }
        }

        function checkDailyProductVariation(loop) {
            document.getElementById(`daily-product-info-variations[${loop}]`).style.display = 'block';
            document.getElementById(`season-product-info-variations[${loop}]`).style.display = 'none';
            document.getElementById(`type-choosen-variation[${loop}]`).value = 'daily-product-variation';
        }

        function checkSeasonProductVariation(loop) {
            document.getElementById(`daily-product-info-variations[${loop}]`).style.display = 'none';
            document.getElementById(`season-product-info-variations[${loop}]`).style.display = 'block';
            document.getElementsByClassName(`date-range-from-variations[${loop}]`)[0].style.display = 'none';
            document.getElementsByClassName(`date-range-to-variations[${loop}]`)[0].style.display = 'none';
            document.getElementById(`type-choosen-variation[${loop}]`).value = 'season-product-one-day-only-variation';
            const value = document.getElementById(`season-product-list-date-available-select-variations[${loop}]`).value;

            if (value === 'one-day-only-variations') {
                document.getElementsByClassName(`season-product-available-time-range-variations[${loop}]`)[0].style.display = 'none';
                document.getElementById(`one-day-date-picker-variations[${loop}]`).style.display = 'block';
                document.getElementsByClassName(`date-range-from-variations[${loop}]`)[0].style.display = 'none';
                document.getElementById(`type-choosen-variation[${loop}]`).value = 'season-product-one-day-only-variation';
                document.getElementsByClassName(`date-range-to-variations[${loop}]`)[0].style.display = 'none';
            } else {
                document.getElementById(`one-day-date-picker-variations[${loop}]`).style.display = 'none';
                document.getElementsByClassName(`date-range-from-variations[${loop}]`)[0].style.display = 'block';
                document.getElementsByClassName(`date-range-to-variations[${loop}]`)[0].style.display = 'block';
                document.getElementsByClassName(`season-product-available-time-range-variations[${loop}]`)[0].style.display = 'block';
                document.getElementById(`type-choosen-variation[${loop}]`).value = 'season-product-date-range-variation';
            }
        }

        function handleChangeTypeVariation(data, loop) {
            if (data.value == 'one-day-only-variations') {
                document.getElementById(`one-day-date-picker-variations[${loop}]`).style.display = 'block';
                document.getElementsByClassName(`date-range-from-variations[${loop}]`)[0].style.display = 'none';
                document.getElementsByClassName(`date-range-to-variations[${loop}]`)[0].style.display = 'none';
                document.getElementsByClassName(`season-product-available-one-day-variations[${loop}]`)[0].style.display = 'block';
                document.getElementsByClassName(`season-product-available-time-range-variations[${loop}]`)[0].style.display = 'none';
                document.getElementById(`type-choosen-variation[${loop}]`).value = 'season-product-one-day-only-variation';
            } else {
                document.getElementById(`type-choosen-variation[${loop}]`).value = 'season-product-date-range-variation';
                document.getElementById(`one-day-date-picker-variations[${loop}]`).style.display = 'none';
                document.getElementsByClassName(`date-range-from-variations[${loop}]`)[0].style.display = 'block';
                document.getElementsByClassName(`date-range-to-variations[${loop}]`)[0].style.display = 'block';
                document.getElementsByClassName(`season-product-available-one-day-variations[${loop}]`)[0].style.display = 'none';
                document.getElementsByClassName(`season-product-available-time-range-variations[${loop}]`)[0].style.display = 'block';
            }
        }
    </script>
    <?php
}

add_action('admin_enqueue_scripts', 'custom_js_product_avalability');

add_action( 'woocommerce_save_product_variation', 'add_variation_product_availability', 10, 2 );

function add_variation_product_availability( $variation_id, $i ) {
    $listDay = [
        'monday_variation' => __('Monday', 'woocommerce'),
        'tuesday_variation' => __('Tuesday', 'woocommerce'),
        'wednesday_variation' => __('Wednesday', 'woocommerce'),
        'thursday_variation' => __('Thursday', 'woocommerce'),
        'friday_variation' => __('Friday', 'woocommerce'),
        'saturday_variation' => __('Saturday', 'woocommerce'),
        'sunday_variation' => __('Sunday', 'woocommerce'),
    ];

    if ($_POST['type-choosen-variation'][$i] === 'season-product-date-range-variation') {
        if (array_key_exists('date-range-from-variations', $_POST)) {
            update_post_meta(
                $variation_id,
                '_date_range_from_variation',
                $_POST['date-range-from-variations'][$i]
            );
        };
        
        if (array_key_exists('date-range-to-variations', $_POST)) {
            update_post_meta(
                $variation_id,
                '_date_range_to_variation',
                $_POST['date-range-to-variations'][$i]
            );
        };

        if (array_key_exists('time-range-from-variations', $_POST)) {
            update_post_meta(
                $variation_id,
                '_time_range_from_variation',
                $_POST['time-range-from-variations'][$i]
            );
        };

        if (array_key_exists('time-range-to-variations', $_POST)) {
            update_post_meta(
                $variation_id,
                '_time_range_to_variation',
                $_POST['time-range-to-variations'][$i]
            );
        };
        
        foreach ($listDay as $key => $value) {
            update_post_meta( $variation_id, $key, 'no' );
        }

        delete_post_meta(
            $variation_id,
            '_daily_product_available_time_from_variation',
        );
    
        delete_post_meta(
            $variation_id,
            '_daily_product_available_time_to_variation',
        );

        delete_post_meta(
            $variation_id,
            '_one_day_date_picker_variation',
        );

        delete_post_meta(
            $variation_id,
            '_one_day_time_from_variation',
        );

        delete_post_meta(
            $variation_id,
            '_one_day_time_to_variation',
        );
    } else if ($_POST['type-choosen-variation'][$i] === 'season-product-one-day-only-variation') {
        if (array_key_exists('one-day-date-picker-variations', $_POST)) {
            update_post_meta(
                $variation_id,
                '_one_day_date_picker_variation',
                $_POST['one-day-date-picker-variations'][$i]
            );
        }

        if (array_key_exists('one-day-time-to-variations', $_POST)) {
            update_post_meta(
                $variation_id,
                '_one_day_time_to_variation',
                $_POST['one-day-time-to-variations'][$i]
            );
        }
        
        if (array_key_exists('one-day-time-from-variations', $_POST)) {
            update_post_meta(
                $variation_id,
                '_one_day_time_from_variation',
                $_POST['one-day-time-from-variations'][$i]
            );
        }

        //remove

        foreach ($listDay as $key => $value) {
            update_post_meta( $variation_id, $key, 'no' );
        }

        delete_post_meta(
            $variation_id,
            '_daily_product_available_time_from_variation',
        );
    
        delete_post_meta(
            $variation_id,
            '_daily_product_available_time_to_variation',
        );

        delete_post_meta(
            $variation_id,
            '_date_range_from_variation',
        );
        
        delete_post_meta(
            $variation_id,
            '_date_range_to_variation',
        );

        delete_post_meta(
            $variation_id,
            '_time_range_from_variation',
        );
        
        delete_post_meta(
            $variation_id,
            '_time_range_to_variation',
        );
    } else {
        foreach ($listDay as $key => $value) {
            if (isset($_POST[$key][$i])) {
                update_post_meta( $variation_id, $key, 'yes' );
            } else {
                update_post_meta( $variation_id, $key, 'no' );
            }
        }

        if (array_key_exists('daily-product-available-time-from-variation', $_POST)) {
            update_post_meta(
                $variation_id,
                '_daily_product_available_time_from_variation',
                $_POST['daily-product-available-time-from-variation'][$i]
            );
        }
    
        if (array_key_exists('daily-product-available-time-to-variation', $_POST)) {
            update_post_meta(
                $variation_id,
                '_daily_product_available_time_to_variation',
                $_POST['daily-product-available-time-to-variation'][$i]
            );
        }
        
        delete_post_meta(
            $variation_id,
            '_date_range_from_variation',
        );
        
        delete_post_meta(
            $variation_id,
            '_date_range_to_variation',
        );

        delete_post_meta(
            $variation_id,
            '_time_range_from_variation',
        );
        
        delete_post_meta(
            $variation_id,
            '_time_range_to_variation',
        );

        delete_post_meta(
            $variation_id,
            '_one_day_date_picker_variation',
        );

        delete_post_meta(
            $variation_id,
            '_one_day_time_from_variation',
        );

        delete_post_meta(
            $variation_id,
            '_one_day_time_to_variation',
        );
    }

    // foreach ($listDay as $key => $value) {
    //     if (isset($_POST[$key][$i])) {
    //         update_post_meta( $variation_id, $key, 'yes' );
    //     } else {
    //         update_post_meta( $variation_id, $key, 'no' );
    //     }
    // }

    // $daily_product_time_from_variation = $_POST['daily-product-available-time-from-variation'][$i];

    // if ( isset( $daily_product_time_from_variation ) ) update_post_meta( $variation_id, '_daily_product_available_time_from_variation', esc_attr( $daily_product_time_from_variation ) );

    // $daily_product_time_to_variation = $_POST['daily-product-available-time-to-variation'][$i];

    // if ( isset( $daily_product_time_to_variation ) ) update_post_meta( $variation_id, '_daily_product_available_time_to_variation', esc_attr( $daily_product_time_to_variation ) );
    
    // $one_day_date_picker_variation = $_POST['one-day-date-picker-variations'][$i];

    // if ( isset( $one_day_date_picker_variation ) ) update_post_meta( $variation_id, '_one_day_date_picker_variation', esc_attr( $one_day_date_picker_variation ) );

    // $date_range_from_variation = $_POST['date-range-from-variations'][$i];

    // if ( isset( $date_range_from_variation ) ) update_post_meta( $variation_id, '_date_range_from_variation', esc_attr( $date_range_from_variation ) );

    // $date_range_to_variation = $_POST['date-range-to-variations'][$i];

    // if ( isset( $date_range_to_variation ) ) update_post_meta( $variation_id, '_date_range_to_variation', esc_attr( $date_range_to_variation ) );

    // $one_day_time_from_variation = $_POST['one-day-time-from-variations'][$i];

    // if ( isset( $one_day_time_from_variation ) ) update_post_meta( $variation_id, '_one_day_time_from_variation', esc_attr( $one_day_time_from_variation ) );
    
    // $one_day_time_to_variation = $_POST['one-day-time-to-variations'][$i];

    // if ( isset( $one_day_time_to_variation ) ) update_post_meta( $variation_id, '_one_day_time_to_variation', esc_attr( $one_day_time_to_variation ) );
    
    // $time_range_from_variation = $_POST['time-range-from-variations'][$i];
    
    // if ( isset( $time_range_from_variation ) ) update_post_meta( $variation_id, '_time_range_from_variation', esc_attr( $time_range_from_variation ) );

    // $time_range_to_variation = $_POST['time-range-to-variations'][$i];
    
    // if ( isset( $time_range_to_variation ) ) update_post_meta( $variation_id, '_time_range_to_variation', esc_attr( $time_range_to_variation ) );
}

/**
 * Require init.
**/
require  trailingslashit( get_template_directory() ).'sparklethemes/init.php';
