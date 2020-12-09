<?php
function cdls_form_config_image_screen() {
    $message = '';
    $notice = '';

    $default = array(
        'id'               => 0,
        'sdls_login_image' => '',
        'sdls_store_image' => '',
        'sdls_blog_image'  => '',
        'sdls_product_list_image'  => '',
        'sdls_out_story_image1' => '',
        'sdls_out_story_image2' => '',
        'sdls_signup_form_image'  => '',
        'sdls_login_form_image' => '',
        'sdls_forgotpw_form_image' => '',
        'sdls_checkout_as_guest_image' => '',
        'sdls_banner_contact_page' => '',
        'sdls_banner_rewards_page' => '',
        'sdls_banner_career_page' => '',
        'sdls_banner_ads' => '',
    );

    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item = array_map('trim', $item);

        update_option('sdls_login_image', $item['sdls_login_image']);
        update_option('sdls_store_image', $item['sdls_store_image']);
        update_option('sdls_blog_image', $item['sdls_blog_image']);
        update_option('sdls_product_list_image', $item['sdls_product_list_image']);
        update_option('sdls_out_story_image1', $item['sdls_out_story_image1']);
        update_option('sdls_out_story_image2', $item['sdls_out_story_image2']);
        update_option('sdls_signup_form_image', $item['sdls_signup_form_image']);
        update_option('sdls_login_form_image', $item['sdls_login_form_image']);
        update_option('sdls_forgotpw_form_image', $item['sdls_forgotpw_form_image']);
        update_option('sdls_checkout_as_guest_image', $item['sdls_checkout_as_guest_image']);
        update_option('sdls_banner_contact_page', $item['sdls_banner_contact_page']);
        update_option('sdls_banner_rewards_page', $item['sdls_banner_rewards_page']);
        update_option('sdls_banner_career_page', $item['sdls_banner_career_page']);
        update_option('sdls_banner_ads', $item['sdls_banner_ads']);
        $message = 'Item was successfully saved';
    } else {
        $item = $default;
        $item['sdls_login_image'] = get_option('sdls_login_image', $item['sdls_login_image']);
        $item['sdls_store_image'] = get_option('sdls_store_image', $item['sdls_store_image']);
        $item['sdls_blog_image'] = get_option('sdls_blog_image', $item['sdls_blog_image']);
        $item['sdls_product_list_image'] = get_option('sdls_product_list_image', $item['sdls_product_list_image']);
        $item['sdls_out_story_image1'] = get_option('sdls_out_story_image1', $item['sdls_out_story_image1']);
        $item['sdls_out_story_image2'] = get_option('sdls_out_story_image2', $item['sdls_out_story_image2']);
        $item['sdls_signup_form_image'] = get_option('sdls_signup_form_image', $item['sdls_signup_form_image']);
        $item['sdls_login_form_image'] = get_option('sdls_login_form_image', $item['sdls_login_form_image']);
        $item['sdls_forgotpw_form_image'] = get_option('sdls_forgotpw_form_image', $item['sdls_forgotpw_form_image']);
        $item['sdls_checkout_as_guest_image'] = get_option('sdls_checkout_as_guest_image', $item['sdls_checkout_as_guest_image']);
        $item['sdls_banner_contact_page'] = get_option('sdls_banner_contact_page', $item['sdls_banner_contact_page']);
        $item['sdls_banner_rewards_page'] = get_option('sdls_banner_rewards_page', $item['sdls_banner_rewards_page']);
        $item['sdls_banner_career_page'] = get_option('sdls_banner_career_page', $item['sdls_banner_career_page']);
        $item['sdls_banner_ads'] = get_option('sdls_banner_ads', $item['sdls_banner_ads']);
    }

    $sdls_login_image = wp_get_attachment_image_src($item['sdls_login_image'], 'full');
    $sdls_store_image = wp_get_attachment_image_src($item['sdls_store_image'], 'full');
    $sdls_blog_image = wp_get_attachment_image_src($item['sdls_blog_image'], 'full');
    $sdls_product_list_image = wp_get_attachment_image_src($item['sdls_product_list_image'], 'full');
    $sdls_out_story_image1 = wp_get_attachment_image_src($item['sdls_out_story_image1'], 'full');
    $sdls_out_story_image2 = wp_get_attachment_image_src($item['sdls_out_story_image2'], 'full');
    $sdls_signup_form_image = wp_get_attachment_image_src($item['sdls_signup_form_image'], 'full');
    $sdls_login_form_image = wp_get_attachment_image_src($item['sdls_login_form_image'], 'full');
    $sdls_forgotpw_form_image = wp_get_attachment_image_src($item['sdls_forgotpw_form_image'], 'full');
    $sdls_checkout_as_guest_image = wp_get_attachment_image_src($item['sdls_checkout_as_guest_image'], 'full');
    $sdls_banner_contact_page = wp_get_attachment_image_src($item['sdls_banner_contact_page'], 'full');
    $sdls_banner_rewards_page = wp_get_attachment_image_src($item['sdls_banner_rewards_page'], 'full');
    $sdls_banner_career_page = wp_get_attachment_image_src($item['sdls_banner_career_page'], 'full');
    $sdls_banner_ads = wp_get_attachment_image_src($item['sdls_banner_ads'], 'full');

//    print_r($item['sdls_out_story_image1']);
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit">
            <br>
        </div>
        <h2>
            CEDELE SETTING
        </h2>

        <?php if (!empty($notice)): ?>
            <div id="notice" class="error">
                <p><?php echo $notice ?></p>
            </div>
        <?php endif;?>

        <?php if (!empty($message)): ?>
            <div id="message" class="updated">
                <p><?php echo $message ?></p>
            </div>
        <?php endif;?>
        <?php require_once "tab.php" ?>
        <form class="cdls-form" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <div class="cdls-config-images">
                <div class="config-image">
                    <p>
                        <b>Blog banner</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1920x700); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_blog_image[0]) ? $sdls_blog_image[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_blog_image" value="<?php echo $item['sdls_blog_image'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Image displayed in Product List screen</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1920x700); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_product_list_image[0]) ? $sdls_product_list_image[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_product_list_image" value="<?php echo $item['sdls_product_list_image'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Store Location banner</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1920x700); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_store_image[0]) ? $sdls_store_image[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_store_image" value="<?php echo $item['sdls_store_image'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Login banner</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1040x980); file type should be .jpg, .png.
                    </p>

                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_login_image[0]) ? $sdls_login_image[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_login_image" value="<?php echo $item['sdls_login_image'] ?>">
                </div>
<!--                 //Add config   -->
                <div class="config-image">
                    <p>
                        <b>Our story banner 1</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1920x700); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_out_story_image1[0]) ? $sdls_out_story_image1[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_out_story_image1" value="<?php echo $item['sdls_out_story_image1'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Our story banner 2</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1920x700); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_out_story_image2[0]) ? $sdls_out_story_image2[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_out_story_image2" value="<?php echo $item['sdls_out_story_image2'] ?>">
                </div>


                <div class="config-image">
                    <p>
                        <b>Sign up form</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1040 x 980); file type should be .jpg, .png.
                    </p>

                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_signup_form_image[0]) ? $sdls_signup_form_image[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_signup_form_image" value="<?php echo $item['sdls_signup_form_image'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Login form to checkout</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (332 x 609); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_login_form_image[0]) ? $sdls_login_form_image[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_login_form_image" value="<?php echo $item['sdls_login_form_image'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Upload file to "Check out as guess" pop-up</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (332x609); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_checkout_as_guest_image[0]) ? $sdls_checkout_as_guest_image[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_checkout_as_guest_image" value="<?php echo $item['sdls_checkout_as_guest_image'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Upload file to "banner contact page" </b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1920x700); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_banner_contact_page[0]) ? $sdls_banner_contact_page[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_banner_contact_page" value="<?php echo $item['sdls_banner_contact_page'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Upload file to "banner rewards page" </b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1920x700); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_banner_rewards_page[0]) ? $sdls_banner_rewards_page[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_banner_rewards_page" value="<?php echo $item['sdls_banner_rewards_page'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Upload file to "Banner Career page" </b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1920x700); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_banner_career_page[0]) ? $sdls_banner_career_page[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_banner_career_page" value="<?php echo $item['sdls_banner_career_page'] ?>">
                </div>
                <div class="config-image">
                    <p>
                        <b>Forgot password form</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (1040 x 980); file type should be .jpg, .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_forgotpw_form_image[0]) ? $sdls_forgotpw_form_image[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_forgotpw_form_image" value="<?php echo $item['sdls_forgotpw_form_image'] ?>">
                </div>

                <div class="config-image">
                    <p>
                        <b>Upload images banner ads blog list</b>:
                        <span class="text-red cdls-button-clear-attachment">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-trash" fill="#FF0000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>
                        <br>
                        Select a file with dimension (350 x 500); file type should be .png.
                    </p>
                    <div class="cdls-upload-div">
                        <svg width="20" height="20" viewBox="0 0 16 16" class="bi bi-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8zM5 4.854a.5.5 0 0 0 .707 0L8 2.56l2.293 2.293A.5.5 0 1 0 11 4.146L8.354 1.5a.5.5 0 0 0-.708 0L5 4.146a.5.5 0 0 0 0 .708z"/>
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 2z"/>
                        </svg>
                        <img class="cdls-setting-img" src="<?php echo !empty($sdls_banner_ads[0]) ? $sdls_banner_ads[0] : '' ?>" alt="">
                    </div>
                    <input type="hidden" name="sdls_banner_ads" value="<?php echo $item['sdls_banner_ads'] ?>">
                </div>


            </div>
            <button type="submit" class="button-primary">Save changes</button>
        </form>
    </div>
<?php
}