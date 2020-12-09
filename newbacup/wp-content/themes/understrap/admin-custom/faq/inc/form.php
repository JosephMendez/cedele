<?php
function faq_custom_validation($item)
{
    $messages = array();

    $validation = new FAQ_Validation();
    $validation->name('Question')->value($item['question'])->required();
    $validation->name('Answer')->value($item['answer'])->required();

    if ($validation->isSuccess()) {
        return true;
    } else {
        $messages = $validation->getErrors();
        return implode('<br/>', $messages);
    }
}

function faq_custom_form_faq_handle() {
    global $wpdb;
    $faq_custom_table = $wpdb->prefix . 'faq_custom';
    $faq_categories_custom_table = $wpdb->prefix . 'faq_categories_custom';
    $categories = db_get_list("SELECT * FROM $faq_categories_custom_table");

    $message = '';
    $notice = '';

    $default = array(
        'id'              => 0,
        'question'        => '',
        'answer'          => '',
        'faq_category_id' => '',
        'status'          => 1,
    );

    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        
        $item = shortcode_atts($default, $_REQUEST);
        $item = array_map('trim', $item);
        $item['status'] = !empty($item['status']) ? 1 : 0;
        $item['answer'] = stripslashes($item['answer']);

        $item_valid = faq_custom_validation($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                // insert
                $result = $wpdb->insert($faq_custom_table, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = 'Item was successfully saved';
                } else {
                    $notice = 'There was an error while saving item';
                }
                wp_redirect(sprintf('?page=faq-custom-form&id=%s', $item['id']));
            } else {
                // update
                $result = $wpdb->update($faq_custom_table, $item, array('id' => $item['id']));
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
            $item = db_get_row(db_prepare("SELECT * FROM $faq_custom_table WHERE id = %d", $_REQUEST['id']));
            if (!$item) {
                $item = $default;
                $notice = 'Item not found';
            }
        }
    }

    $data_meta_box = [
        'categories' => $categories,
        'faq_category_id' => $item['faq_category_id'],
    ];
    $meta_box_title = !empty($_REQUEST['id']) ? 'Edit FAQ' : 'Add new FAQ';
    add_meta_box('faq_custom_section_right', 'Select category', 'faq_custom_section_right', 'section_right', 'normal', 'default');
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit">
            <br>
        </div>
        <h2>
            <?php echo $meta_box_title; ?>
            <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=faq-custom');?>">back to list</a>
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

        <form id="form-faq-custom" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>
            <div class="metabox-holder">
                <div class="form-faq-custom__section-left">
                    <input type="text" name="question" value="<?php echo $item['question'] ?>" spellcheck="true" autocomplete="off" placeholder="Question" autofocus/>
                    <br>
                    <br>
                    <?php the_editor($item['answer'], 'answer'); ?>
                    <br>
                    <button type="submit" class="button-primary">Save</button>
                </div>
                <div class="form-faq-custom__section-right">
                    <?php do_meta_boxes('section_right', 'normal', $data_meta_box); ?>
                </div>
            </div>
        </form>
    </div>
<?php
}
function faq_custom_section_right($data_meta_box) {
    $categories = $data_meta_box['categories'];
    $faq_cat = $data_meta_box['faq_category_id'];
?>  
    <select name="faq_category_id">
        <option value="">-- Select category --</option>
        <?php foreach ($categories as $key => $cate): ?>
            <option value="<?php echo $cate['id'] ?>" <?php echo $cate['id'] == $faq_cat ? 'selected' : ''; ?>>
                <?php echo $cate['title'] ?>
            </option>
        <?php endforeach; ?>
    </select>
<?php
}
