<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class FAQ_Custom_WP_List_Table extends WP_List_Table
 {
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'faq',
            'plural'   => 'faqs',
        ));
    }

    function column_default($item, $column_name)
    {
        return htmlentities($item[$column_name]);
    }

    function column_status($item)
    {
        return empty($item['status']) ? 'Disabled' : 'Enable';
    }

    function column_question($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=faq-custom-form&id=%s">%s</a>', $item['id'], 'Edit'),
            'delete' => sprintf('<a onclick="return confirm(\'Do you really want to delete this FAQ?\')" href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], 'Delete'),
        );
        $question = '<a href="?page=faq-custom-form&id='. $item['id'] . '">' . htmlentities($item['question']) . '</a>';

        return sprintf('%s %s',
            $question,
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
            'question'      => 'Question',
            'answer'        => 'Answer',
            'category_name' => 'Category',
            'updated_at'    => 'Updated At',
            // 'status'        => 'Status',
        );

        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'question'      => array('question', true),
            'answer'        => array('answer', true),
            'category_name' => array('category_name', true),
            'updated_at'    => array('updated_at', true),
            // 'status'        => array('status', true)
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
        $faq_custom_table = $wpdb->prefix . 'faq_custom';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $faq_custom_table WHERE id IN ($ids)");
            }
        }
    }

    function extra_tablenav( $which ) {
        global $wpdb, $tablet;
        $faq_categories_custom_table = $wpdb->prefix . 'faq_categories_custom';
        if ( $which == "top" ){
            ?>
            <div class="alignleft actions bulkactions">
            <?php
            $list_categories = $wpdb->get_results("SELECT * FROM $faq_categories_custom_table", ARRAY_A);
            $current_cat = isset($_GET['faq-custom-cat-filter']) ? $_GET['faq-custom-cat-filter'] : '';
            ?>
                <select name="faq-custom-cat-filter" class="ewc-filter-cat">
                    <option value="">Filter by Category</option>
                    <?php
                    if ($list_categories):
                    foreach ($list_categories as $category):
                        $selected = '';
                        if($current_cat == $category['id']){
                            $selected = ' selected ';   
                        }
                    ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo $selected; ?>><?php echo $category['title']; ?></option>
                    <?php endforeach;endif; ?>
                </select>
                <input type="submit" id="search-submit" class="button" value="Filter">
            </div>
            <?php
        }
    }

    function prepare_items($search = '', $cat_filter = '')
    {
        global $wpdb;
        $faq_custom_table = $wpdb->prefix . 'faq_custom';
        $faq_categories_custom_table = $wpdb->prefix . 'faq_categories_custom';

        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $faq_custom_table");
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $where_clause = " question like '%s' ";
        if ($cat_filter) {
            $where_clause .= " and faq_category_id = $cat_filter ";
        }
        $query_get_list = "SELECT *, faq_cat_table.title as category_name
            FROM $faq_custom_table
            LEFT JOIN
            (
                SELECT id as c_id, title
                FROM $faq_categories_custom_table
            ) as faq_cat_table
            ON
                $faq_custom_table.faq_category_id = faq_cat_table.c_id
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

function faq_custom_list_faq_handle()
{
    global $wpdb;

    $table = new FAQ_Custom_WP_List_Table();
    $table->prepare_items(trim($_POST['s']), trim($_POST['faq-custom-cat-filter']));

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
            <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=faq-custom-form');?>">
                Add new
            </a>
        </h2>
        <?php echo $message; ?>

        <form id="faq_custom-table" method="POST">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php 
                $table->search_box('Search', 'search_id');
                $table->display();
            ?>
        </form>
    </div>
<?php } ?>