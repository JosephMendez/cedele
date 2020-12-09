<?php
function cdls_form_self_colection_inventory() {
    global $wpdb, $current_user;
    get_currentuserinfo();

    $email = $current_user->user_email;

    $is_admin = current_user_can('administrator');

    // $stores = $wpdb->get_results (
    //     " SELECT * FROM `wp_store_location` WHERE id in (SELECT store_id from wp_store_location_post WHERE post_id IN (SELECT post_id from wp_postmeta where meta_key = 'delivery_method' and (meta_value = 'both' or meta_value='self') )) ORDER BY store_name  "
    // );
    if ($is_admin) {
        $stores = $wpdb->get_results (
            " SELECT * FROM `wp_store_location`
                WHERE id in (
                    SELECT store_id from wp_store_location_post
                    WHERE post_id IN (
                        SELECT post_id from wp_postmeta where meta_key = 'delivery_method'
                        AND (meta_value = 'both' or meta_value='self')))
                        ORDER BY store_name 
            "
        );
    } else {
        $stores = $wpdb->get_results (
            " SELECT * FROM `wp_store_location`
                WHERE id in (
                    SELECT store_id from wp_store_location_post
                    WHERE post_id IN (
                        SELECT post_id from wp_postmeta where meta_key = 'delivery_method'
                        AND (meta_value = 'both' or meta_value='self')))
                        AND email_address = '${email}'
                        ORDER BY store_name 
            "
        );
    }

    $message = '';
    $notice = '';
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
        <div class="self-collection-content">
            <label for="_stores">Store Location:</label>
            <select id="_stores" name="stores">
                <option value="" selected>No Selected</option>
                <?php if (!empty($stores)) 
                    foreach ($stores as $key => $value) {
                        echo '<option value="'. $value->id .'">'.$value->store_name.'</option>';
                    }
                ?>
            </select> 
            <div class="self_collection_search_product">
                <input id="_self-collection-content-search-input" type="text" placeholder="Search product" />
                <img src="../../../../wp-includes/images/media/search.svg" width="18px"/>
            </div>
        </div>
        <table id="self-collection-content-list-product">
            <thead>
                <tr id="self-collection-content-list-product-title">
                    <th>
                        Product ID
                    </th>
                    <th>
                        Product Name
                    </th>
                    <th>
                        In Stock
                    </th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
<?php
}