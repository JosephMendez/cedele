<?php
function db_prepare($sql, $arg){
    global $wpdb;

    return $wpdb->prepare($sql, $arg);
}

function db_get_list($sql){
    global $wpdb;
    $data = $wpdb->get_results($sql, ARRAY_A);

    return $data;
}

function db_get_row($sql){
    global $wpdb;
    $data = $wpdb->get_row($sql, ARRAY_A);
    
    return $data;
}

function db_multiple_insert($table_name, $arrayData = [])
{
    global $wpdb;
    if (count($arrayData) === 0)
        return false;

    $query = datas_to_multiple_insert($table_name, $arrayData);
    $result = $wpdb->query($query);
    
    return $result;
}

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
            $eachValue .= "'" . $value . "',";
        }
        $values .= "(" . trim($eachValue, ',') . "),";
    }

    $values = trim($values, ',');

    return $values;
}

function db_multiple_update($table_name, $arrayData = [])
{
    global $wpdb;
    if (count($arrayData) === 0)
        return false;
    $result = true;
    foreach ($arrayData as $key => $data) {
        $temp = $wpdb->update($table_name, $data, array('id' => $data['id']));

        if (!$temp)
            $result = $temp;
    }

    return $result;
}

function db_multiple_delete($table_name, $delete_ids)
{
    global $wpdb;
    $delete_ids = implode(',', $delete_ids);
    $result = $wpdb->query("DELETE FROM $table_name WHERE id IN ($delete_ids)");

    return $result;
}