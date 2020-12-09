<?php
function faq_category_custom_validation($item)
{
    $messages = array();

    $validation = new FAQ_Validation();
    $validation->name('Title')->value($item['title'])->required();

    if ($validation->isSuccess()) {
        return true;
    } else {
        $messages = $validation->getErrors();
        return implode('<br/>', $messages);
    }
}

function faq_custom_categories_form_handle() {
    global $wpdb;
    $faq_categories_custom_table = $wpdb->prefix . 'faq_categories_custom';

    $message = '';
    $notice = '';

    $default = array(
        'id'    => 0,
        'title' => '',
    );

    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        
        $item = shortcode_atts($default, $_REQUEST);
        $item = array_map('trim', $item);

        $item_valid = faq_category_custom_validation($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                // insert
                $result = $wpdb->insert($faq_categories_custom_table, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = 'Item was successfully saved';
                } else {
                    $notice = 'There was an error while saving item';
                }
                wp_redirect(sprintf('?page=faq-c-custom-form&id=%s', $item['id']));
            } else {
                // update
                $result = $wpdb->update($faq_categories_custom_table, $item, array('id' => $item['id']));
                if ($result || $result === 0) {
                    $message = 'Item was successfully updated';
                } else {
                    $notice = 'There was an error while updating item';
                }
            }
        } else {
            $notice = $item_valid;
        }
    } else {
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = db_get_row(db_prepare("SELECT * FROM $faq_categories_custom_table WHERE id = %d", $_REQUEST['id']));
            if (!$item) {
                $item = $default;
                $notice = 'Item not found';
            }
        }
    }

    $meta_box_title = !empty($_REQUEST['id']) ? 'Edit FAQ Categories' : 'Add new FAQ Categories';
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit">
            <br>
        </div>
        <h2>
            <?php echo $meta_box_title; ?>
            <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=faq-c-custom');?>">back to list</a>
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
        <div class="faq_custom_categories-form" style="margin-top: 0px;">
            <form method="post">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
                <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>
                <div class="form-field form-required">
                    <label for="tag-name">Title</label>
                    <input name="title" type="text" value="<?php echo $item['title'] ?>" autocomplete="off" placeholder="Title" autofocus>
                    <p>The title is how it appears on your site.</p>
                </div>
                <p class="submit">
                    <button type="submit" class="button button-primary">Save</button>
                </p>
            </form>
        </div>
    </div>
<?php
}