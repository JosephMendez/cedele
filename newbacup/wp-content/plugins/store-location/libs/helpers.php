<?php
function filter_array($array = [], $key, $value)
{
    return !empty($array) && is_array($array) ? array_filter(
        $array,
        function ($v) use ($key, $value) {
            return $v[$key] === $value;
        }
    ) : [];
}

function convert_json_to_array($json_str) {
    $arr_result = json_decode(stripslashes($json_str), true);
    if (json_last_error())
    	return [];

    return $arr_result;
}

function get_link_file($url) {
    $array = explode('/', $url);

    return array_pop($array);
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function formatDate($date, $format = 'm/d/Y', $targetFormat = 'Y-m-d')
{
    if (empty($date))
        return '';
    
    $d = DateTime::createFromFormat($format, $date);

    return $d->format($targetFormat);
}