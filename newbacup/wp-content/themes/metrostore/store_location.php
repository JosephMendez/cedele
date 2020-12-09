<?php

function get_list_stores($post_id)
{
	if (empty($post_id))
		return [];
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_location_post';

	$result = $wpdb->get_results("SELECT * FROM $table_name WHERE post_id = $post_id", ARRAY_A);

	return $result;
}

function get_list_option_stores()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_location';

	$result = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1 ORDER BY store_name ASC", ARRAY_A);

	return $result;
}

function delete_option_stores($post_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_location_post';

	if (!empty($post_id)) {
	    $wpdb->delete($table_name, ['post_id' => $post_id]);
	}
}


function convert_store_post_to_insert($post_id = 0, $stores) {
    $new_data = [];
    foreach ($stores as $key => $s) {
        $data = [
            'store_id' => $s,
            'post_id' => $post_id,
        ];

        $new_data[] = $data;
    }

    return $new_data;
}

function multiple_insert_store_post($post_id, $arrayData)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'store_location_post';

    if (count($arrayData) === 0)
        return false;

	if (!empty($post_id)) {
	    $arrayData = convert_store_post_to_insert($post_id, $arrayData);

	    db_multiple_insert($table_name, $arrayData);
	}
}

if (!function_exists('db_multiple_insert')) {
	function db_multiple_insert($table_name, $arrayData = [])
	{
	    global $wpdb;
	    if (count($arrayData) === 0)
	        return false;

	    $query = datas_to_multiple_insert($table_name, $arrayData);
	    $result = $wpdb->query($query);
	    
	    return $result;
	}
}

if (!function_exists('datas_to_multiple_insert')) {
	function datas_to_multiple_insert($table_name, $arrayData = [])
	{
	    global $wpdb;
	    if (count($arrayData) === 0)
	        return false;

	    $array_keys = array_keys($arrayData[0]);
	    $values = implode(',', $array_keys);
	    $values = "INSERT INTO $table_name ($values) VALUES ";

	    foreach ($arrayData as $key => $data){
	        $eachValue = '';
	        foreach ($data as $k => $value) {
	            $eachValue .= "'" . addslashes($value). "',";
	        }
	        $values .= "(" . trim($eachValue, ',') . "),";
	    }

	    $values = trim($values, ',');

	    return $values;
	}
}