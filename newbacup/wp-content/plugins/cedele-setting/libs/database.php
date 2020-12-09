<?php
function cdls_multiple_insert($table_name, $arrayData = [])
{
    global $wpdb;
    if (!is_array($arrayData) || empty($arrayData))
        return false;

    $query = cdls_datas_to_multiple_insert($table_name, $arrayData);
    $result = $wpdb->query($query);
    
    return $result;
}

function cdls_multiple_update($table_name, $arrayData = [])
{
    global $wpdb;
    if (!is_array($arrayData) || empty($arrayData))
        return false;

    foreach ($arrayData as $key => $data) {
        if (!empty($data['id'])) {
            $result = $wpdb->update($table_name, $data, ['id' => $data['id']]);
        }
    }
    
    return $result;
}

function cdls_datas_to_multiple_insert($table_name, $arrayData = [])
{
    global $wpdb;
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

function cdls_multiple_delete($table_name, $delete_ids)
{
    global $wpdb;

    if (!is_array($delete_ids) || empty($delete_ids))
        return false;

    $delete_ids = implode(',', $delete_ids);
    $result = $wpdb->query("DELETE FROM $table_name WHERE id IN ($delete_ids)");

    return $result;
}