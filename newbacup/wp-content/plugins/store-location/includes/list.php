<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class Custom_Table_Example_List_Table extends WP_List_Table
 {
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'location',
            'plural'   => 'locations',
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

    function column_central_kitchen($item)
    {
        return empty($item['central_kitchen']) ? 'no' : 'yes';
    }

    function column_store_name($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=locations_form&id=%s">%s</a>', $item['id'], 'Edit'),
        );
        $store_name = '<a href="?page=locations_form&id='. $item['id'] . '">' . htmlentities($item['store_name']) . '</a>';

        return sprintf('%s %s',
            $store_name,
            $this->row_actions($actions)
        );
    }

    function column_area($item)
    {
        $area = $this->find_master_data($item['area']);
        return $area ? $area : '';
    }

    function column_outlet_type($item)
    {
        $outlet_type = $this->find_master_data($item['outlet_type']);
        return $outlet_type ? $outlet_type : '';
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
            'cb'              => '<input type="checkbox" />',
            'store_name'      => 'Store Name',
            'address'         => 'Address',
            'area'            => 'Area',
            'outlet_type'     => 'Outlet type',
            'phone_number'    => 'Phone',
            'email_address'   => 'Email',
            'status'          => 'Status',
            'central_kitchen' => 'Central Kitchen',
        );

        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'store_name'      => array('store_name', true),
            'address'         => array('address', true),
            'area'            => array('area', true),
            'outlet_type'     => array('outlet', true),
            'phone_number'    => array('phone_number', true),
            'email_address'   => array('email_address', true),
            'status'          => array('status', true),
            'central_kitchen' => array('central_kitchen', true),
        );

        return $sortable_columns;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'store_location';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN ($ids)");
            }
        }
    }

    function find_master_data($id)
    {
        global $wpdb, $table_master_data;
        $result = $wpdb->get_row("SELECT * FROM $table_master_data WHERE id = $id");
        if ($result) {
            return $result->data_name;
        }

        return '';
    }

    function prepare_items($search = '')
    {
        global $wpdb, $table_store, $table_master_data;

        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_store");

        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $where_clause = " store_name like '%s' OR
                    address like '%s' OR
                    outlet_type in (
                        SELECT id FROM $table_master_data 
                        WHERE data_name like '%s' and type='outlet'
                    )";
        $query_get_list = "SELECT *, CONCAT_WS(' ', number_house, street_name, zipcode, tmd.data_name) as address
            FROM $table_store
            LEFT JOIN
            (
                SELECT id as tmd_id, data_name
                FROM $table_master_data
            ) as tmd
            ON
                $table_store.district = tmd.tmd_id
            HAVING $where_clause
            ORDER BY $orderby $order 
            LIMIT %d OFFSET %d";

        $search = '%' . $search . '%';

        $this->items = $wpdb->get_results($wpdb->prepare($query_get_list, $search, $search, $search, $per_page, $paged*$per_page), ARRAY_A);

        $this->items = !empty($this->items) ? $this->items : [];

        $this->set_pagination_args(array(
            'total_items' => count($this->items),
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}

function wpsl_list_page_handle()
{
    global $wpdb;

    $table = new Custom_Table_Example_List_Table();
    $table->prepare_items(trim($_POST['s']));

    $alert = '';
    if (!empty($_SESSION['wpsl_alert'])) {
        $alert = '<div class="updated below-h2" id="alert"><p>' . $_SESSION['wpsl_alert'] . '</p></div>';
        unset($_SESSION['wpsl_alert']);
    }
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2>
            STORE LOCATIONS
            <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=locations_form');?>">
                Add new
            </a>
        </h2>
        <?php echo $alert; ?>

        <form id="contacts-table" method="POST">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php 
                $table->search_box('Search', 'search_id');
                $table->display();
            ?>
        </form>
    </div>
<?php } ?>