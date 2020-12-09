<?php

function install_table()
{
	create_table_store_location();
	create_table_store_master_data();
	create_table_store_working_time();
	create_table_holiday();
	create_table_store_holiday();
	create_table_store_post();
}

function uninstall_table()
{
	// code ...
}

function create_table_store_location()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_location';
    $table_columns = "id int(11) NOT NULL AUTO_INCREMENT,
				      store_name VARCHAR (100) NOT NULL,
				      number_house VARCHAR (100) NOT NULL,
				      street_name VARCHAR (100) NOT NULL,
				      zipcode VARCHAR (100) NOT NULL,
				      floor_unit VARCHAR (100) NULL,
				      building VARCHAR (100) NULL,
				      longitude DOUBLE(50,14) NULL,
				      latitude DOUBLE(50,14) NULL,
				      area int(11) NOT NULL,
				      district int(11) NOT NULL,
				      outlet_type int(11) NOT NULL,
				      phone_number VARCHAR(20) NOT NULL,
				      email_address VARCHAR(100) NOT NULL,
				      image_id INT(11) null,
				      file_id INT(11) null,
				      status TINYINT(1) DEFAULT 1,
				      central_kitchen TINYINT(1) DEFAULT 0 COMMENT '1: true, 0: false'";
	$table_keys = "PRIMARY KEY (id)";

	create_table($table_name, $table_columns, $table_keys);
}

function create_table_store_master_data()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_master_data';
    $table_columns = "id int(11) NOT NULL AUTO_INCREMENT,
				      data_name VARCHAR(50) NOT NULL,
				      type VARCHAR (20) NOT NULL";
	$table_keys = "PRIMARY KEY (id)";

	create_table($table_name, $table_columns, $table_keys);
}

function create_table_store_working_time()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_working_time';
    $table_columns = "id int(11) NOT NULL AUTO_INCREMENT,
				      store_id int(11) NOT NULL,
				      start_working_time TIME NOT NULL,
				      end_working_time TIME NOT NULL,
				      working_day VARCHAR (10) NOT NULL";
	$table_keys = "PRIMARY KEY (id)";

	create_table($table_name, $table_columns, $table_keys);
}

/*
 * import style and scripts
 */
function create_table_holiday()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_holiday';
    $table_columns = "id int(11) NOT NULL AUTO_INCREMENT,
				      start_date DATE NOT NULL,
				      end_date DATE NOT NULL,
				      description VARCHAR(100) NOT NULL";
	$table_keys = "PRIMARY KEY (id)";

	create_table($table_name, $table_columns, $table_keys);
}

function create_table_store_holiday()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_holiday_related';
    $table_columns = "id int(11) NOT NULL AUTO_INCREMENT,
				      holiday_id INT(11) NOT NULL,
				      store_id INT(11) NOT NULL";
	$table_keys = "PRIMARY KEY (id)";

	create_table($table_name, $table_columns, $table_keys);
}

function create_table_store_post()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_location_post';
    $table_columns = "id int(11) NOT NULL AUTO_INCREMENT,
				      post_id INT(11) NOT NULL,
				      store_id INT(11) NOT NULL,
				      is_in_stock TINYINT(1) NOT NULL DEFAULT 1";
	$table_keys = "PRIMARY KEY (id)";

	create_table($table_name, $table_columns, $table_keys);
}

function create_table($table_name, $table_columns, $table_keys = null, $charset_collate = null) {
    global $wpdb;

    if($charset_collate == null)
        $charset_collate = $wpdb->get_charset_collate();
    $table_columns = strtolower($table_columns);

    if($table_keys)
        $table_keys =  ", $table_keys";

    $table_structure = "( $table_columns $table_keys )";

    $search_array = array();
    $replace_array = array();

    $search_array[] = "`";
    $replace_array[] = "";

    $table_structure = str_replace($search_array,$replace_array,$table_structure);

    $sql = "CREATE TABLE IF NOT EXISTS $table_name $table_structure $charset_collate;";

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    return dbDelta($sql);
}
