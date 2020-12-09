<?php
function update_cdls_homes_etting_highlight($ids, $cdls_homes_etting_highlight)
{
    global $wpdb;
    foreach ($ids as $id) {
        if (!empty($id) && !empty($cdls_homes_etting_highlight[$id])) {
            update_term_meta($id, 'cdls_homes_etting_highlight', 1);
        } else if (!empty($id)) {
            delete_term_meta($id, 'cdls_homes_etting_highlight');
        }
    }
}
function update_cdls_homes_etting_featured_product($ids, $cdls_homes_etting_featured_product)
{
    global $wpdb;
    $table_term = $wpdb->prefix . 'termmeta';
    foreach ($cdls_homes_etting_featured_product as $id => $value) {
        $oldValue = get_term_meta($id, 'cdls_homes_etting_featured_product', true);
        if ($oldValue != $value) {
            $valueDelete = !empty($value) ? $value : $oldValue;
            $wpdb->delete($table_term, [
                'meta_key' => 'cdls_homes_etting_featured_product',
                'meta_value' => "$valueDelete",
            ]);
        }
    }
    foreach ($cdls_homes_etting_featured_product as $id => $value) {
        if (!empty($value)) {
            update_term_meta($id, 'cdls_homes_etting_featured_product', $value);
        }
    }
}
function cdls_form_highlight_categories() {
    global $wpdb, $table_occasion, $table_peak_hour;

    $message = '';
    $notice = '';

    $default = [
        'cdls_homes_etting_highlight' => [],
        'cdls_homes_etting_featured_product' => [],
        'ids' => [],
    ];
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);

        update_cdls_homes_etting_highlight($item['ids'], $item['cdls_homes_etting_highlight']);
        update_cdls_homes_etting_featured_product($item['ids'], $item['cdls_homes_etting_featured_product']);
        $message = 'Item was successfully saved';
        $_SESSION['cdls_alert'] = $message;

        if (!empty($_POST['cdls_save_and_redirect'])) {
            header('Location: ' . $_POST['cdls_save_and_redirect']);
            die;
        }
        header('Location: ' . $_SERVER['REQUEST_URI']);
        die;
    }

    $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
    $per_page = 10;
    $offset = ($paged - 1) * $per_page;

    $taxonomy = 'product_cat';
    $list_categories = get_terms($taxonomy, array(
        'hide_empty' => false,
        'number' => $per_page,
        'offset' => $offset,
        'orderby' => 'id',
    ));
    $total = wp_count_terms($taxonomy);
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
        <?php if (!empty($_SESSION['cdls_alert'])): ?>
            <div id="message" class="updated">
                <p><?php echo $_SESSION['cdls_alert'];unset($_SESSION['cdls_alert']); ?></p>
            </div>
        <?php endif;?>
        <?php require_once "tab.php" ?>
        <form class="cdls-form cdls-form-highlight" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <ul class="subsubsub">
                <li>
                    <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=home-setting&section=highlight'); ?>" class="current">Highlighted Categories</a> | 
                </li>
                <li>
                    <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=home-setting&section=placeholder'); ?>">Place holder for Delivery</a>
                </li>
            </ul>
            <br>
            <br>
            <table class="cdls-table-highlight wp-list-table widefat striped" cellpadding="8" border="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="20%">Category Id</th>
                        <th width="40%">Category Name</th>
                        <th width="15%">Highlight</th>
                        <th width="25%">Featured Product</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if (!empty($list_categories)):
                            foreach ($list_categories as $key => $category):
                            $highlight = get_term_meta($category->term_id, 'cdls_homes_etting_highlight', true);
                            $feature = get_term_meta($category->term_id, 'cdls_homes_etting_featured_product', true);
                            $term_id = $category->term_id;
                    ?>
                    <tr data-id="<?php echo $term_id ?>">
                        <input type="hidden" name="ids[]" value="<?php echo $term_id ?>">
                        <td>
                            <?php echo $category->term_id; ?>
                        </td>
                        <td>
                            <?php echo $category->name; ?>
                        </td>
                        <td>
                            <input type="checkbox" value="1" name="<?php echo 'cdls_homes_etting_highlight[' . $term_id . ']' ?>" <?php echo !empty($highlight) ? 'checked' : '' ?>>
                        </td>
                        <td>
                            <select name="<?php echo 'cdls_homes_etting_featured_product[' . $term_id . ']' ?>">
                                <option value="" selected>None</option>
                                <option value="1" <?php echo $feature == 1 ? 'selected' : '' ?>>1</option>
                                <option value="2" <?php echo $feature == 2 ? 'selected' : '' ?>>2</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php
            $args = array(
                'base'               => '%_%',
                'format'             => '?paged=%#%',
                'total'              => $total / $per_page + 1,
                'current'            => $paged,
                'prev_next'          => true,
                'prev_text'          => __('‹'),
                'next_text'          => __('›'),
                'type'               => 'plain',
            );
            echo '<div class="cdls-nagination">';
            echo paginate_links($args);
            echo '</div>';
            ?>
            <button type="button" class="button-primary cdls-button-submit-highlight">Save changes</button>
            <div class="clearfix"></div>
        </form>
    </div>
<?php
}