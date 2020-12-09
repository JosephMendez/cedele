<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class FAQ_Categories_Custom_WP_List_Table extends WP_List_Table
 {
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'item',
            'plural'   => 'items',
        ));
    }

    function column_default($item, $column_name)
    {
        return htmlentities($item[$column_name]);
    }

    function column_title($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=faq-c-custom-form&id=%s">%s</a>', $item['id'], 'Edit'),
            'delete' => sprintf('<a onclick="return confirm(\'Do you really want to delete this FAQ categories?\')" href="?page=faq-c-custom&action=delete&id=%s">%s</a>', $item['id'], 'Delete'),
        );
        $title = '<a href="?page=faq-c-custom-form&id='. $item['id'] . '">' . htmlentities($item['title']) . '</a>';

        return sprintf('%s %s',
            $title,
            $this->row_actions($actions)
        );
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns()
    {
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'title'      => 'Title',
        );

        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'title'      => array('title', true),
        );

        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );

        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $faq_categories_custom_table = $wpdb->prefix . 'faq_categories_custom';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $faq_categories_custom_table WHERE id IN ($ids)");
            }
        }
    }

    function prepare_items($search = '')
    {
        global $wpdb;
        $faq_categories_custom_table = $wpdb->prefix . 'faq_categories_custom';

        $per_page = 8;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $faq_categories_custom_table");
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'title';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $where_clause = " title like '%s' ";
        $query_get_list = "SELECT *
            FROM $faq_categories_custom_table
            HAVING $where_clause
            ORDER BY $orderby $order 
            LIMIT %d OFFSET %d";

        $search = '%' . $search . '%';
        $this->items = $wpdb->get_results($wpdb->prepare($query_get_list, $search, $per_page, $paged), ARRAY_A);
        $this->items = !empty($this->items) ? $this->items : [];

        $this->set_pagination_args(array(
            'total_items' => count($this->items),
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}

function faq_custom_categories_list_handle()
{
    global $wpdb;

    $table = new FAQ_Categories_Custom_WP_List_Table();
    $table->prepare_items(trim($_POST['s']));

    $message = '';
    if ('delete' === $table->current_action()) {
        $delete_total = !empty($_REQUEST['id']) ? is_array($_REQUEST['id']) ? count($_REQUEST['id']) : 1 : 0;

        if ($delete_total) {
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', $delete_total) . '</p></div>';
        }
    }
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2>
            FAQ
        </h2>
        <?php echo $message; ?>

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

        <form class="faq_custom_categories-table" method="POST">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php 
                $table->search_box('Search', 'search_id');
                $table->display();
            ?>
        </form>
    </div>
<?php } ?>