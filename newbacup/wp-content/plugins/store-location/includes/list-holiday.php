<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class Custom_Table_Holiday extends WP_List_Table
 { 
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'holiday',
            'plural'   => 'holidays',
        ));
    }

    function column_default($item, $column_name)
    {
        return htmlentities($item[$column_name]);
    }

    function column_description($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=holidays_form&id=%s">%s</a>', $item['id'], 'Edit'),
            'delete' => sprintf('<a onclick="return confirm(\'Do you really want to delete the holiday?\')" href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], 'Delete'),
        );
        $description = sprintf('<a href="?page=holidays_form&id='. $item['id'] . '">' . htmlentities($item['description']) . '</a>');

        return sprintf('%s %s',
            $description,
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
            'cb'          => '<input type="checkbox" />',
            'description' => 'Description',
            'start_date'  => 'Start date',
            'end_date'    => 'End date',
        );

        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'description'     => array('description', true),
            'start_date'      => array('start_date', true),  
            'end_date'        => array('end_date', true),
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
        global $wpdb, $table_holiday, $table_store_holiday;

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_holiday WHERE id IN ($ids)");
                $wpdb->query("DELETE FROM $table_store_holiday WHERE holiday_id IN ($ids)");
            }
        }
    }

    function prepare_items($search = '')
    {  
        global $wpdb, $table_holiday, $table_store_holiday;

        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_holiday");
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'description';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';
        
        $where_clause = " description like '%s' ";
        $search = '%' . $search . '%';
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_holiday WHERE $where_clause ORDER BY $orderby $order LIMIT %d OFFSET %d", $search, $per_page, $paged), ARRAY_A);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}

function wpsl_list_holiday_page_handle()
{
    global $wpdb;

    $table = new Custom_Table_Holiday();
    $table->prepare_items(trim($_POST['s']));

    $message = '';
    if ('delete' === $table->current_action()) {
        $delete_total = !empty($_REQUEST['id']) ? is_array($_REQUEST['id']) ? count($_REQUEST['id']) : 1 : 0;

        if ($delete_total) {
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', $delete_total) . '</p></div>';
        }
    }
    $alert = '';
    if (!empty($_SESSION['wpsl_alert'])) {
        $alert = '<div class="updated below-h2" id="alert"><p>' . $_SESSION['wpsl_alert'] . '</p></div>';
        unset($_SESSION['wpsl_alert']);
    }
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2>
            HOLIDAYS
            <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=holidays_form');?>">
                Add new
            </a>
        </h2>
        <?php echo $message; ?>
        <?php echo $alert; ?>

        <form id="holidays-table" method="POST">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php 
                $table->search_box('Search', 'search_id');
                $table->display();
            ?>
        </form>
    </div>
<?php } ?>