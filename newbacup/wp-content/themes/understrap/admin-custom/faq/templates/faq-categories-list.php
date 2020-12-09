<?php
$table->prepare_items(trim($_POST['s']));
?>
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>
    FAQ
</h2>
<div class="updated below-h2" id="message"><p>Item was successfully saved</p></div>

<div class="faq_custom_categories-form">
    <h2>Add New FAQ Category</h2>
    <form method="post">
        <div class="form-field form-required">
            <label for="tag-name">Title</label>
            <input name="title" class="faq-category-title" type="text">
            <p>The title is how it appears on your site.</p>
        </div>
        <p class="submit">
            <button type="button" class="button button-primary btn-add-faq-category">Add New Category</button>
            <span class="spinner"></span>
        </p>
    </form>
</div>

<form class="faq_custom_categories-table">
    <input type="hidden" name="page" value="faq-c-custom"/>
    <?php 
        $table->search_box('Search', 'search_id');
        $table->display();
    ?>
</form>